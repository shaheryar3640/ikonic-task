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
        Category::truncate();
        $data = [
            ["name" =>"bug report"],
            ["name" =>"feature request"],
            ["name" =>"improvement"],
        ];
        Category::insert($data);
    }
}
