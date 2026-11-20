<?php

namespace Tests\Feature;

use App\Models\Shop\Application;
use App\Models\Shop\Category;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function customer(): User
    {
        return User::factory()->create(['role' => 'user']);
    }

    private function category(): Category
    {
        return Category::create([
            'name' => 'Корма',
            'slug' => 'korma',
            'description' => 'Корма для животных',
        ]);
    }

    private function product(array $attributes = []): Product
    {
        return Product::create(array_merge([
            'category_id' => $this->category()->id,
            'name' => 'Корм для собак',
            'slug' => 'korm-dlya-sobak',
            'description' => 'Описание товара',
            'price' => 450,
            'stock' => 10,
            'is_active' => true,
        ], $attributes));
    }

    public function test_guest_can_view_home_catalog_and_product(): void
    {
        $product = $this->product();

        $this->get('/')->assertOk();
        $this->get('/catalog')->assertOk()->assertSee($product->name);
        $this->get('/products/' . $product->slug)->assertOk()->assertSee($product->name);
    }

    public function test_inactive_product_is_hidden_from_guest_and_visible_to_admin(): void
    {
        $product = $this->product(['is_active' => false]);

        $this->get('/products/' . $product->slug)->assertNotFound();

        $this->actingAs($this->admin())
            ->get('/products/' . $product->slug)
            ->assertOk();
    }

    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Иван Иванов',
            'email' => 'ivan@example.com',
            'phone' => '+79990000000',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'ivan@example.com',
            'role' => 'user',
            'phone' => '+79990000000',
        ]);
    }

    public function test_user_can_login(): void
    {
        $user = $this->customer();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_is_redirected_to_dashboard_after_login(): void
    {
        $admin = $this->admin();

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_non_admin_cannot_open_admin_panel(): void
    {
        $this->actingAs($this->customer())
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_admin_can_create_product_with_unique_slug(): void
    {
        $category = $this->category();
        $admin = $this->admin();

        $payload = [
            'name' => 'Новый товар',
            'description' => 'Описание',
            'price' => 100,
            'stock' => 5,
            'category_id' => $category->id,
            'is_active' => 1,
        ];

        $this->actingAs($admin)->post('/admin/products', $payload)
            ->assertRedirect(route('admin.products.index'));

        $this->actingAs($admin)->post('/admin/products', $payload)
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseCount('products', 2);
        $this->assertDatabaseHas('products', ['slug' => 'novyi-tovar']);
        $this->assertDatabaseHas('products', ['slug' => 'novyi-tovar-2']);
    }

    public function test_admin_can_update_and_soft_delete_product(): void
    {
        $product = $this->product();
        $admin = $this->admin();

        $this->actingAs($admin)->put('/admin/products/' . $product->id, [
            'name' => 'Обновлённый корм',
            'description' => 'Новое описание',
            'price' => 500,
            'stock' => 8,
            'category_id' => $product->category_id,
            'is_active' => 1,
        ])->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Обновлённый корм',
            'slug' => 'obnovlennyi-korm',
        ]);

        $this->actingAs($admin)->delete('/admin/products/' . $product->id)
            ->assertRedirect(route('admin.products.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_admin_can_view_soft_deleted_product(): void
    {
        $product = $this->product();
        $product->delete();

        $this->actingAs($this->admin())
            ->get('/admin/products/' . $product->id)
            ->assertOk()
            ->assertSee($product->name);
    }

    public function test_user_can_checkout_and_stock_is_decremented(): void
    {
        $user = $this->customer();
        $product = $this->product(['stock' => 10]);

        $this->actingAs($user)
            ->withSession(['cart' => [$product->id => ['quantity' => 2]]])
            ->post('/checkout', ['address' => 'Москва, ул. Ленина, 1'])
            ->assertRedirect(route('profile.orders'));

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 450,
        ]);
        $this->assertSame(8, $product->fresh()->stock);
    }

    public function test_checkout_with_deleted_product_returns_error_instead_of_exception(): void
    {
        $user = $this->customer();
        $product = $this->product();
        $product->delete();

        $this->actingAs($user)
            ->withSession(['cart' => [$product->id => ['quantity' => 1]]])
            ->post('/checkout', ['address' => 'Москва'])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_user_can_submit_application(): void
    {
        $this->post('/applications', [
            'fullname' => 'Пётр Петров',
            'phone' => '+79991112233',
            'email' => 'petr@example.com',
            'wishes' => 'Хочу кота',
        ])->assertRedirect(route('home'));

        $this->assertDatabaseHas('applications', [
            'email' => 'petr@example.com',
            'status' => 'new',
        ]);
    }

    public function test_admin_can_update_order_status(): void
    {
        $user = $this->customer();
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 900,
            'status' => 'pending',
            'address' => 'Москва',
        ]);

        $this->actingAs($this->admin())
            ->patch('/admin/orders/' . $order->id . '/status', ['status' => 'processing'])
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);
    }

    public function test_user_cannot_view_another_users_order(): void
    {
        $owner = $this->customer();
        $other = $this->customer();

        $order = Order::create([
            'user_id' => $owner->id,
            'total_price' => 450,
            'status' => 'pending',
            'address' => 'Москва',
        ]);

        $this->actingAs($other)
            ->get('/profile/orders/' . $order->id)
            ->assertNotFound();
    }

    public function test_application_can_be_moderated_by_admin(): void
    {
        $application = Application::create([
            'fullname' => 'Пётр Петров',
            'phone' => '+79991112233',
            'email' => 'petr@example.com',
        ]);

        $this->actingAs($this->admin())
            ->patch('/admin/applications/' . $application->id . '/status', ['status' => 'in_progress'])
            ->assertRedirect(route('admin.applications.show', $application));

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'in_progress',
        ]);
    }
}
