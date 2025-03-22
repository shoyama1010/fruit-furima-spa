<?php

namespace Database\Seeders;
use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Models\Season;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // 1. 季節データを登録
        $seasonNames = ['春', '夏', '秋', '冬'];
        $seasons = [];
        foreach ($seasonNames as $name) {
            $seasons[$name] = Season::create(['name' => $name]);
        }

        // 2. 商品データ（画像ファイルは public/storage/products に配置されている前提）
        $fruits = [
            ['name' => 'バナナ',      'price' => 120, 'image' => 'banana.png',     'description' => '栄養たっぷりのバナナです',       'seasons' => ['夏', '秋']],
            ['name' => 'ぶどう',      'price' => 250, 'image' => 'grapes.png',     'description' => '甘くてジューシーなぶどうです',   'seasons' => ['秋']],
            ['name' => 'キウイ',      'price' => 180, 'image' => 'kiwi.png',       'description' => 'ビタミン豊富なキウイです',       'seasons' => ['冬', '春']],
            ['name' => 'メロン',      'price' => 600, 'image' => 'melon.png',      'description' => '贅沢な味わいのメロンです',       'seasons' => ['夏']],
            ['name' => 'マスカット',  'price' => 400, 'image' => 'muscat.png',     'description' => '爽やかな香りのマスカットです',   'seasons' => ['秋']],
            ['name' => 'オレンジ',    'price' => 200, 'image' => 'orange.png',     'description' => 'ビタミンCたっぷりのオレンジです','seasons' => ['冬']],
            ['name' => 'もも',        'price' => 350, 'image' => 'peach.png',      'description' => '柔らかくて甘い桃です',           'seasons' => ['夏']],
            ['name' => 'パイナップル','price' => 300, 'image' => 'pineapple.png',  'description' => '南国の味、パイナップルです',     'seasons' => ['夏']],
            ['name' => 'いちご',      'price' => 280, 'image' => 'strawberry.png', 'description' => '春の定番いちごです',             'seasons' => ['春', '冬']],
            ['name' => 'すいか',      'price' => 500, 'image' => 'watermelon.png', 'description' => '夏といえばスイカ！',             'seasons' => ['夏']],
        ];

        foreach ($fruits as $fruit) {
            $product = Product::create([
                'name' => $fruit['name'],
                'price' => $fruit['price'],
                'image' => 'products/' . $fruit['image'],
                'description' => $fruit['description'],
            ]);

            // 季節を中間テーブルで紐付け
            $seasonIds = array_map(fn($season) => $seasons[$season]->id, $fruit['seasons']);
            $product->seasons()->sync($seasonIds);
        }
    }
}
