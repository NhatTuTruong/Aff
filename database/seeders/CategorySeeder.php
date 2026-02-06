<?php

namespace Database\Seeders;

use App\Models\Category;
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
                'name' => 'Thời trang nam',
                'slug' => 'thoi-trang-nam',
                'description' => 'Các sản phẩm thời trang dành cho nam giới',
                'is_active' => true,
            ],
            [
                'name' => 'Làm đẹp',
                'slug' => 'lam-dep',
                'description' => 'Mỹ phẩm và sản phẩm chăm sóc sắc đẹp',
                'is_active' => true,
            ],
            [
                'name' => 'Tool AI',
                'slug' => 'tool-ai',
                'description' => 'Các công cụ và phần mềm trí tuệ nhân tạo',
                'is_active' => true,
            ],
            [
                'name' => 'Thời trang nữ',
                'slug' => 'thoi-trang-nu',
                'description' => 'Các sản phẩm thời trang dành cho nữ giới',
                'is_active' => true,
            ],
            [
                'name' => 'Điện tử',
                'slug' => 'dien-tu',
                'description' => 'Thiết bị điện tử và công nghệ',
                'is_active' => true,
            ],
            [
                'name' => 'Sức khỏe',
                'slug' => 'suc-khoe',
                'description' => 'Sản phẩm chăm sóc sức khỏe và thể thao',
                'is_active' => true,
            ],
            [
                'name' => 'Nhà cửa & Đời sống',
                'slug' => 'nha-cua-doi-song',
                'description' => 'Đồ dùng gia đình và nội thất',
                'is_active' => true,
            ],
            [
                'name' => 'Thực phẩm & Đồ uống',
                'slug' => 'thuc-pham-do-uong',
                'description' => 'Thực phẩm và đồ uống',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
