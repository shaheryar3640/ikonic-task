<?php

namespace Database\Seeders;

use App\Models\Feedback;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Feedback::truncate();
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            DB::table('feedback')->insert([
                'title' => $faker->title,
                'description' => $faker->text,
                'user_id' => \App\Models\User::get()->random()->id,
                'category_id' => \App\Models\Category::get()->random()->id,
            ]);
        }
    }
}
