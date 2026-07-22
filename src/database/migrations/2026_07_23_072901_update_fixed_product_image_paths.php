<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateFixedProductImagePaths extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $images = [
            'banana.png',
            'grapes.png',
            'kiwi.png',
            'melon.png',
            'muscat.png',
            'orange.png',
            'peach.png',
            'pineapple.png',
            'strawberry.png',
            'watermelon.png',
        ];

        foreach ($images as $image) {
            DB::table('products')
                ->where('image', 'products/' . $image)
                ->update([
                    'image' => 'images/products/' . $image,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $images = [
            'banana.png',
            'grapes.png',
            'kiwi.png',
            'melon.png',
            'muscat.png',
            'orange.png',
            'peach.png',
            'pineapple.png',
            'strawberry.png',
            'watermelon.png',
        ];

        foreach ($images as $image) {
            DB::table('products')
                ->where('image', 'images/products/' . $image)
                ->update([
                    'image' => 'products/' . $image,
                ]);
        }
    }
}
