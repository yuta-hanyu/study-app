# mysql接続コマンド
./vendor/bin/sail mysq

# migration
## migration作成
php artisan make:migration create_users_table
php artisan make:migration create_todos_table
php artisan make:migration create_inquiries_table
php artisan make:migration create_email_verification_table
php artisan make:migration create_maxims_table
php artisan make:migration create_identity_providers_table

# カラム追加
php artisan make:migration add_sort_order_to_todos_table --table=todos

# カラム変更(nulable)
php artisan make:migration change_email_password_to_nullable --table=users

## migraton実行
php artisan migrate
## rollback
php artisan migrate:rollback --step=2

# model作成
php artisan make:model BookMarkFolder
php artisan make:model BookMark
php artisan make:model Inquiry
php artisan make:model EmailVerification
php artisan make:model Maxim
php artisan make:model IdentityProvider


# middleware作成
php artisan make:middleware GetUserInfoMiddleware

# seed
## seedファイル作成
php artisan make:seeder TodosTableSeeder
php artisan make:seeder BookMarkFolderTableSeeder
php artisan make:seeder BookMarkTableSeeder
php artisan make:seeder MaximsTableSeeder

## seed実行
### 全部実行
php artisan db:seed
### classごと実行
php artisan db:seed --class=TodosTableSeeder
php artisan db:seed --class=BookMarkFolderTableSeeder
php artisan db:seed --class=BookMarkTableSeeder
php artisan db:seed --class=MaximsTableSeeder


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
php artisan make:controller TopController


# カスタムバリデーションのルール作成
php artisan make:rule CustomPasswordComparisonValidation
php artisan make:rule CustomGuestUserValidation
php artisan make:rule CustomReminderValidation

# バッチ処理
php artisan make:command TodoReminderBatch
# cron設定
00 21 * * * /var/www/html/study-app/artisan command:TodoReminderBatch
# cronテスト用
*/1 * * * * /var/www/html/study-app/artisan command:TodoReminderBatch

# クラス作成
php artisan make:mail InquiryMail
php artisan make:mail TodoReminderMail


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

# google認証ローカル
## クライアントID
742026765055-hs22jspvddoosuespq3brdpb80npvgp5.apps.googleusercontent.com
## クライアントシークレット
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
