<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Заявка на питомца / обратная связь.
 *
 * @property int $id
 * @property string $fullname
 * @property string $phone
 * @property string $email
 * @property string|null $wishes
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Application extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'phone',
        'email',
        'wishes',
        'status',
    ];
}
