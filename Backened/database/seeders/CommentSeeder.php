<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     

        Comment::truncate();
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            DB::table('comments')->insert([
                'comment' => $faker->text(100),
                'user_id' => \App\Models\User::get()->random()->id,
                'feedback_id' => \App\Models\Feedback::get()->random()->id,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }
    }
}
