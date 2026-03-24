<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Trường học', 'color' => '#2E7D32', 'icon' => '🎓', 'sort_order' => 1],
            ['name' => 'Cơ quan',   'color' => '#1565C0', 'icon' => '🏛️', 'sort_order' => 2],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(['name' => $data['name']], $data);
        }

        echo "✓ Đã tạo " . \count($categories) . " danh mục.\n";
    }
}
