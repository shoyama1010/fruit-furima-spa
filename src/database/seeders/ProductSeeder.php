<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'user_id' => 1, // UserSeederで作ったユーザーに紐付け
            'name' => 'バナナ',
            'price' => 120,
            'image' => 'products/banana.png',
            'description' => '栄養たっぷりのバナナです',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
