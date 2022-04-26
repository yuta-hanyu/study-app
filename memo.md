# mysql接続コマンド
./vendor/bin/sail mysq

# migration
## migration作成
php artisan make:migration create_users_table
php artisan make:migration create_todos_table
php artisan make:migration create_inquiries_table
php artisan make:migration create_email_verification_table

# カラム追加
php artisan make:migration add_sort_order_to_todos_table --table=todos

## migraton実行
php artisan migrate
## rollback
php artisan migrate:rollback --step=2

# model作成
php artisan make:model BookMarkFolder
php artisan make:model BookMark
php artisan make:model Inquiry
php artisan make:model EmailVerification

# middleware作成
php artisan make:middleware GetUserInfoMiddleware

# seed
## seedファイル作成
php artisan make:seeder TodosTableSeeder
php artisan make:seeder BookMarkFolderTableSeeder
php artisan make:seeder BookMarkTableSeeder

## seed実行
### 全部実行
php artisan db:seed
### classごと実行
php artisan db:seed --class=TodosTableSeeder
php artisan db:seed --class=BookMarkFolderTableSeeder
php artisan db:seed --class=BookMarkTableSeeder

# コマンドラインからデータ投入
php artisan tinker
## users
\App\Models\User::factory()->create(['name' => 'ゲストユーザー', 'email' => 'guest@gmail.com', 'password' => bcrypt('guest')]);
\App\Models\User::factory()->create(['name' => 'ccc', 'email' => 'ccc@gmail.com', 'password' => bcrypt('ccc')]);
\App\Models\User::factory()->create(['name' => 'aaa', 'email' => 'aaa@gmail.com', 'password' => bcrypt('aaa')]);

# コントローラー作成
php artisan make:controller BookMarkController
php artisan make:controller InquiryController
php artisan make:controller MailController
php artisan make:controller UserController
php artisan make:controller RegisterController

# カスタムバリデーションのルール作成
php artisan make:rule CustomPasswordComparisonValidation
php artisan make:rule CustomGuestUserValidation
php artisan make:rule CustomReminderValidation

# バッチ処理
php artisan make:command TodoReminderBatch

# クラス作成
php artisan make:mail InquiryMail

# トランケイト
mysql> set foreign_key_checks = 0;
mysql> truncate table hoge;
mysql> set foreign_key_checks = 1;

#  ブックマーク全消しsql
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE `study_app`.`book_mark_folders`;
TRUNCATE `study_app`.`book_marks`;
SET FOREIGN_KEY_CHECKS=1;

SELECT * FROM study_app.book_mark_folders;

# docker 
docker-compose up -d
