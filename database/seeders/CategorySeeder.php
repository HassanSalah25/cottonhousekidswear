<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed categories
        $categories = [
            [
                'parent_id' => null,
                'level' => 1,
                'name' => 'Category 1',
                'order_level' => 1,
                'featured' => true,
                'top' => true,
                'slug' => 'category-1',
                'meta_title' => 'Category 1 Meta Title',
                'meta_description' => 'Category 1 Meta Description',
                'sales_amount' => 1000,
            ],
            [
                'parent_id' => null,
                'level' => 1,
                'name' => 'Category 2',
                'order_level' => 2,
                'featured' => false,
                'top' => false,
                'slug' => 'category-2',
                'meta_title' => 'Category 2 Meta Title',
                'meta_description' => 'Category 2 Meta Description',
                'sales_amount' => 800,
            ],
            [
                'parent_id' => 1,
                'level' => 2,
                'name' => 'Subcategory 1-1',
                'order_level' => 1,
                'featured' => true,
                'top' => false,
                'slug' => 'subcategory-1-1',
                'meta_title' => 'Subcategory 1-1 Meta Title',
                'meta_description' => 'Subcategory 1-1 Meta Description',
                'sales_amount' => 500,
            ],
            // Add more categories as needed
        ];


        foreach ($categories as $categoryData) {

            $category = Category::create($categoryData);
            $category_translation = CategoryTranslation::firstOrNew(['lang' => 'en', 'category_id' => $category->id]);
            $category_translation->name = $category->name;
            $category_translation->save();

        }
    }
}
