<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class UpdateCategorySlugSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Category::all() as $category) {
            $category->slug = Str::slug($category->name);
            $category->save();
        }
    }
}
