# fruit-furima-spa（バックエンド）

# 作成した目的

Laravel（Blade）での従来のCRUDから、  Next.jsを用いたSPA構成へ移行し、  API設計・認証・フロント連携の理解を深めるために作成。

# アプリケーションURL
ローカル環境
http://localhost

# 機能一覧

・認証機能（ログイン）

・商品一覧表示

・商品検索機能

・商品CRUD機能

・ソート機能（価格昇順・降順）

・ページネーション機能

・商品詳細表示

・画像アップロード機能

・複数カテゴリー機能

・ソート機能

・プロフィール機能

##  認証・認可

- Laravel Sanctum を使用したSPA認証
- Cookieベース認証（credentials: include）
- **他ユーザーの商品編集・削除を禁止（403制御）**
- フロント側でも編集画面遷移時にユーザー判定

# 使用技術
・Laravel 8

・nginx 1.21.1

・php 8.0

・html

・css(Tailwind CSS)

・mysql 8.0.26

・storage（シンボリックリンク）

・Api/Sanctum

・formrequest

# テーブル設計

<img width="751" height="784" alt="Image" src="https://github.com/user-attachments/assets/be8a03fd-82bf-487c-bc48-0d3f49017ac5" />

<img width="753" height="714" alt="Image" src="https://github.com/user-attachments/assets/7bee000f-150e-4129-9f29-be045c6b19a9" />

<img width="841" height="355" alt="Image" src="https://github.com/user-attachments/assets/5802f4cd-f923-4138-8bce-1a264ac37581" />

# ER図

<img width="1536" height="1024" alt="Image" src="https://github.com/user-attachments/assets/028a180a-1ad5-4e52-88f9-25abb085823e" />

# 環境構築
## 1 Gitファイルをクローンする

git clone https://github.com/shoyama1010/fruit-furima-spa.git

## 2 Dockerコンテナを作成する

docker-compose up -d --build

## 3 Laravelパッケージをインストールする

docker-compose exec php bash
でPHPコンテナにログインし

composer install

## 4 .envファイルを作成する

PHPコンテナにログインした状態で

cp .env.example .env

作成した.envファイルの該当欄を下記のように変更

DB_HOST=mysql

DB_DATABASE=laravel_db

DB_USERNAME=laravel_user

DB_PASSWORD=laravel_pass

MAIL_MAILER=smtp

MAIL_HOST=mailhog

MAIL_PORT=1025

MAIL_USERNAME=null

MAIL_PASSWORD=null

MAIL_ENCRYPTION=null

MAIL_FROM_ADDRESS=noreply@example.com 

MAIL_FROM_NAME="laravel"

## 5 テーブルの作成

docker-compose exec php bash

でPHPコンテナにログインし(ログインしたままであれば上記コマンドは実行しなくて良いです。)

php artisan migrate

## 6 ダミーデータ作成

PHPコンテナにログインした状態で

php artisan db:seed

## 7 アプリケーション起動キーの作成

PHPコンテナにログインした状態で

php artisan key:generate

## 8 シンボリックリンクの作成

PHPコンテナにログインした状態で

php artisan storage:link

# テスト

---

##  工夫した点

- Laravel + Next.js によるSPA構成を採用
- APIを中心とした設計に変更（MVC → API分離）
- 検索・ソートをクエリパラメータで動的処理
- 編集画面での**所有者チェック（セキュリティ対策）**
- 画像アップロード機能（storage連携）
