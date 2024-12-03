<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseCategory::insert([
            ['name' => 'Desarrollo Web'],
            ['name' => 'Marketing Digital'],
            ['name' => 'Data Science'],
            ['name' => 'Diseño Gráfico'],
        ]);
    }
}
