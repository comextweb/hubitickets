<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('type', 'admin')->first();
        $categoryArr = [
            [
                'name' => 'Bug',
                'color' => '#e5593b',
                'slug' => 'bug',
                'parent' => 0,
                'created_by' => 1,
                'subcategories' => [
                    [
                        'name' => 'Critical Bug',
                        'color' => '#f73208',
                        'slug' => 'critical-bug',
                        'parent' => 1,
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'Minor Bug',
                        'color' => '#ac290d',
                        'slug' => 'minor-bug',
                        'parent' => 1,
                        'created_by' => 1,
                    ]
                ],
            ],
            [
                'name' => 'Support',
                'color' => '#28a2e3',
                'slug' => 'support',
                'parent' => 0,
                'created_by' => 1,
                'subcategories' => [
                    [
                        'name' => 'Hardware',
                        'color' => '#1bad60',
                        'slug' => 'hardware',
                        'parent' => 1,
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'Update',
                        'color' => '#a51589',
                        'slug' => 'update',
                        'parent' => 1,
                        'created_by' => 1,
                    ]
                ],
            ]
        ];


        foreach ($categoryArr as $category) {
            $parentCategory = Category::where('slug', $category['slug'])->first();
            if (!$parentCategory) {
                $parentCategory = Category::create([
                    'name' => $category['name'],
                    'color' => $category['color'],
                    'slug' => $category['slug'],
                    'created_by' => $admin->id,
                    'parent_id' => 0,
                ]);
            }

            if ($category['subcategories'] && is_array($category['subcategories'])) {
                foreach ($category['subcategories'] as $subCategory) {
                    $childCategory = Category::where('slug', $subCategory['slug'])->first();
                    if (!$childCategory) {
                        $childCategory = Category::create([
                            'name' => $subCategory['name'],
                            'color' => $subCategory['color'],
                            'slug' => $subCategory['slug'],
                            'created_by' => $admin->id,
                            'parent_id' => $parentCategory->id,
                        ]);
                    }
                }
            }
        }
    }
}
