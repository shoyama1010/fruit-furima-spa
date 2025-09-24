<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     // 'id' => 1,
        //     'name' => 'テストユーザー',
        //     'email' => 'test@example.com',
        //     'password' => Hash::make('password123'),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        
        DB::table('users')->updateOrInsert(
            ['email' => 'test@example.com'], // ユニークキー条件
            [
                'name' => 'テストユーザー',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

    }
}
