<?php

namespace Database\Seeders;

use App\Models\VideoCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VideoCategory::insert([
            ['name' => 'Matemáticas'],
            ['name' => 'Programación'],
            ['name' => 'Ciencias'],
            ['name' => 'Arte'],
        ]);
    }
}
