<?php

namespace Database\Seeders;

use App\Models\Shop\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Корма',
                'slug' => 'korma',
                'description' => 'Качественные корма для собак, кошек и других животных',
            ],
            [
                'name' => 'Игрушки',
                'slug' => 'igryushki',
                'description' => 'Игрушки для собак, кошек и грызунов',
            ],
            [
                'name' => 'Аксессуары',
                'slug' => 'aksessuary',
                'description' => 'Когтеточки, миски, ошейники и другие аксессуары',
            ],
            [
                'name' => 'Для грызунов',
                'slug' => 'dlya-gryzunov',
                'description' => 'Корма, клетки и аксессуары для грызунов',
            ],
            [
                'name' => 'Лежанки',
                'slug' => 'lezhanki',
                'description' => 'Мягкие лежанки и домики для питомцев',
            ],
            [
                'name' => 'Гигиена',
                'slug' => 'gidyena',
                'description' => 'Шампуни, расчески и средства по уходу',
            ],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
