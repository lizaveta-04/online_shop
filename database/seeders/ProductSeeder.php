<?php

namespace Database\Seeders;

use App\Models\Shop\Product;
use App\Models\Shop\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        $products = [
            [
                'category_id' => $categories->firstWhere('slug', 'korma')->id,
                'name' => 'Корм для собак',
                'slug' => 'korm-dlya-sobak',
                'description' => 'Вес упаковки: 600г (идеально для ежедневного рациона средних пород). Состав: Натуральные ингредиенты — мясо, злаки, витамины и минералы.',
                'price' => 450.00,
                'stock' => 120,
                'image' => 'images/product1.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories->firstWhere('slug', 'igryushki')->id,
                'name' => 'Игрушка кость для собак',
                'slug' => 'igryushka-kost',
                'description' => 'Прочная игрушка в форме кости. Подходит для чистки зубов.',
                'price' => 300.00,
                'stock' => 85,
                'image' => 'images/product2.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories->firstWhere('slug', 'korma')->id,
                'name' => 'Корм для кошек',
                'slug' => 'korm-dlya-koshek',
                'description' => 'Сбалансированный корм для домашних кошек.',
                'price' => 450.00,
                'stock' => 0,
                'image' => 'images/product3.png',
                'is_active' => false,
            ],
            [
                'category_id' => $categories->firstWhere('slug', 'aksessuary')->id,
                'name' => 'Когтеточка',
                'slug' => 'kogtetochka',
                'description' => 'Высокая когтеточка с домиком. Идеально для кошек средних и крупных пород.',
                'price' => 2000.00,
                'stock' => 35,
                'image' => 'images/product4.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories->firstWhere('slug', 'aksessuary')->id,
                'name' => 'Миска для животных',
                'slug' => 'miska',
                'description' => 'Нержавеющая миска. Не скользит по полу.',
                'price' => 700.00,
                'stock' => 50,
                'image' => 'images/product5.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories->firstWhere('slug', 'igryushki')->id,
                'name' => 'Игрушка мяч',
                'slug' => 'igryushka-myach',
                'description' => 'Резиновый мяч для активных игр.',
                'price' => 150.00,
                'stock' => 200,
                'image' => 'images/product6.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories->firstWhere('slug', 'gidyena')->id,
                'name' => 'Расческа для животных',
                'slug' => 'raschjotka',
                'description' => 'Профессиональная расческа для вычёсывания шерсти.',
                'price' => 280.00,
                'stock' => 90,
                'image' => 'images/product7.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories->firstWhere('slug', 'dlya-gryzunov')->id,
                'name' => 'Корм для хомяков',
                'slug' => 'korm-dlya-homyakov',
                'description' => 'Смесь зерен и овощей для хомяков и морских свинок.',
                'price' => 350.00,
                'stock' => 60,
                'image' => 'images/product8.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories->firstWhere('slug', 'dlya-gryzunov')->id,
                'name' => 'Клетка для хомяка',
                'slug' => 'kletka-dlya-homyaka',
                'description' => 'Просторная клетка с несколькими уровнями.',
                'price' => 10000.00,
                'stock' => 12,
                'image' => 'images/product9.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories->firstWhere('slug', 'lezhanki')->id,
                'name' => 'Лежанка для животных',
                'slug' => 'lezhanka',
                'description' => 'Мягкая лежанка с бортиками. Подходит для кошек и маленьких собак.',
                'price' => 4000.00,
                'stock' => 25,
                'image' => 'images/product10.png',
                'is_active' => true,
            ],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }
    }
}
