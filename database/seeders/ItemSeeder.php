<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\Item_condition;
use App\Models\User;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $items = [
            [
                'seller_id' => $user->id,
                'condition_id' => 1,
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolex',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_url' => 'img/sample_img/wrist_watch.jpg',
                'category_ids' => [1, 4, 5],
            ],
            [
                'seller_id' => $user->id,
                'condition_id' => 2,
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_url' => 'img/sample_img/HardDisk.jpg',
                'category_ids' => [2, 3],
            ],
            [
                'seller_id' => $user->id,
                'condition_id' => 3,
                'name' => '玉ねぎ３束',
                'price' => 300,
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ３束のセット',
                'image_url' => 'img/sample_img/onion.jpg',
                'category_ids' => [6],
            ],
            [
                'seller_id' => $user->id,
                'condition_id' => 4,
                'name' => '革靴',
                'price' => 4000,
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'image_url' => 'img/sample_img/LeatherShoes.jpg',
                'category_ids' => [1, 4],
            ],
            [
                'seller_id' => $user->id,
                'condition_id' => 1,
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'image_url' => 'img/sample_img/Laptop_PC.jpg',
                'category_ids' => [2],
            ],
            [
                'seller_id' => $user->id,
                'condition_id' => 2,
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image_url' => 'img/sample_img/Mic.jpg',
                'category_ids' => [2, 3],
            ],
            [
                'seller_id' => $user->id,
                'condition_id' => 3,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'image_url' => 'img/sample_img/red_bag.jpg',
                'category_ids' => [1, 4],
            ],
            [
                'seller_id' => $user->id,
                'condition_id' => 4,
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'image_url' => 'img/sample_img/black_tumbler.jpg',
                'category_ids' => [5],
            ],
            [
                'seller_id' => $user->id,
                'condition_id' => 1,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_url' => 'img/sample_img/Coffee_mill.jpg',
                'category_ids' => [5],
            ],
            [
                'seller_id' => $user->id,
                'condition_id' => 2,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'image_url' => 'img/sample_img/makeup_set.jpg',
                'category_ids' => [1, 4],
            ],
        ];

        foreach ($items as $itemData) {
            $categoryIds = $itemData['category_ids'];
            unset($itemData['category_ids']);

            $item = Item::create($itemData);
            $item->categories()->attach($categoryIds);
        }
    }
}
