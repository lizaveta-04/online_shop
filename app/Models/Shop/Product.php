<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Товар интернет-магазина.
 *
 * @property int $id
 * @property int|null $category_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string $price
 * @property int $stock
 * @property string|null $image
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderItem> $orderItems
 * @property-read string|null $image_url
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Публичный URL изображения товара.
     *
     * Поддерживает внешние ссылки, файлы в public/ и файлы
     * на диске public (storage/app/public).
     */
    public function getImageUrlAttribute(): ?string
    {
        if (blank($this->image)) {
            return null;
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        // Файлы, сохранённые в public/
        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        // Файлы, сохранённые через Storage::disk('public') — лежат в
        // storage/app/public, которые маппятся на public/storage
        $storagePath = 'storage/' . $this->image;
        if (file_exists(public_path($storagePath))) {
            return asset($storagePath);
        }

        // На случай, если путь уже хранится как storage/...
        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('images/no-image.svg');
    }

    /**
     * Категория товара.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Позиции в заказах.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
