<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Todo;
use Illuminate\Support\Carbon;

class TodoReminderBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:TodoReminderBatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'タスク管理_リマインダーメール通知_バッチ処理';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      Log::info('タスク管理_リマインダーメール通知_バッチ処理開始');

      // 通知対象データ取得条件整形
      $startToday = Carbon::tomorrow();
      $lastToday = str_replace('00:00:00', '23:59:59', $startToday);

      // 通知対象データ取得
      $todo = new Todo();
      $targetTodos = $todo->getRemaindTodos($startToday, $lastToday);

      // リマインドの時間を削除
      foreach($targetTodos as $targetTodo) {
        $targetTodo->todos_reminder = substr($targetTodo->todos_reminder, 0, 10);
      };
      $grouped = [];
      $grouped = $targetTodos->groupBy('email');
      $grouped->toArray();
      // メールを送信
      $mail = app()->make('App\Http\Controllers\MailController');
      foreach($grouped as $group) {
        $mail->todoReminderSend($group);
      }
      Log::info('タスク管理_リマインダーメール通知_バッチ処理終了');
    }
}
