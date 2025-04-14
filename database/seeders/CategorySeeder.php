<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'name' => 'Smartphone',
                'description' => '',
            ], [
                'name' => 'Tablet',
                'description' => '',
            ], [
                'name' => 'Smartwatch',
                'description' => '',
            ], [
                'name' => 'Headphones & Earbuds',
                'description' => '',
            ], [
                'name' => 'Power Bank',
                'description' => '',
            ],
        ];

        collect($categories)->each(function ($category) {
            Category::firstOrCreate($category);
        });
    }
}
