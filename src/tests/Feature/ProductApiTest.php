<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Season;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品一覧を取得できる()
    {
        $user = User::factory()->create();

        Product::create([
            'user_id' => $user->id,
            'name' => 'テストりんご',
            'price' => 300,
            'description' => 'テスト用の商品です',
            'image' => 'images/products/apple.png',
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => 'テストりんご',
            'price' => 300,
        ]);
    }

    /** @test */
    public function 商品詳細を取得できる()
    {
        $user = User::factory()->create();

        $product = Product::create([
            'user_id' => $user->id,
            'name' => 'テストぶどう',
            'price' => 500,
            'description' => '商品詳細テスト用の商品です',
            'image' => 'images/products/grapes.png',
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => 'テストぶどう',
            'price' => 500,
            'description' => '商品詳細テスト用の商品です',
        ]);
    }

    /** @test */
    public function 未認証ユーザーは商品登録できない()
    {
        $response = $this->postJson('/api/products', [
            'name' => '未認証の商品',
            'price' => 1000,
            'description' => '未認証ユーザーによる登録テスト',
            'image' => 'images/products/test.png',
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('products', [
            'name' => '未認証の商品',
        ]);
    }

    /** @test */
    public function 認証済みユーザーは商品登録できる()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $image = UploadedFile::fake()->create(
            'test-orange.jpg',
            100,
            'image/jpeg'
        );

        $response = $this->actingAs($user, 'sanctum')
            ->post('/api/products', [
                'name' => 'テストみかん',
                'price' => 350,
                'description' => '商品登録テスト用の商品です',
                'image' => $image,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'user_id' => $user->id,
            'name' => 'テストみかん',
            'price' => 350,
        ]);
    }

    /** @test */
    public function 他人の商品は編集できない()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $season = Season::create([
            'name' => '夏',
        ]);

        $product = Product::create([
            'user_id' => $owner->id,
            'name' => '所有者の商品',
            'price' => 500,
            'description' => '所有者の商品です',
            'image' => 'images/products/grapes.png',
        ]);

        $response = $this->actingAs($otherUser, 'sanctum')
            ->putJson("/api/products/{$product->id}", [
                'name' => '勝手に変更',
                'price' => 999,
                'description' => '他人が編集しようとしています',
                'seasons' => [$season->id],
            ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => '所有者の商品',
            'price' => 500,
        ]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
            'name' => '勝手に変更',
        ]);
    }

    /** @test */
    public function 自分の商品は編集できる()
    {
        $user = User::factory()->create();

        $season = Season::create([
            'name' => '秋',
        ]);

        $product = Product::create([
            'user_id' => $user->id,
            'name' => '編集前の商品',
            'price' => 500,
            'description' => '編集前の商品説明です',
            'image' => 'images/products/grapes.png',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/products/{$product->id}", [
                'name' => '編集後の商品',
                'price' => 800,
                'description' => '編集後の商品説明です',
                'seasons' => [$season->id],
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'user_id' => $user->id,
            'name' => '編集後の商品',
            'price' => 800,
            'description' => '編集後の商品説明です',
        ]);

        $this->assertDatabaseHas('product_season', [
            'product_id' => $product->id,
            'season_id' => $season->id,
        ]);
    }

    /** @test */
    public function 自分の商品を削除できる()
    {
        $user = User::factory()->create();

        $product = Product::create([
            'user_id' => $user->id,
            'name' => '削除対象の商品',
            'price' => 500,
            'description' => '削除テスト用の商品です',
            'image' => 'images/products/grapes.png',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
