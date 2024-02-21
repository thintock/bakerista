<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public $token; // トークンをプロパティとして追加

    // コンストラクタを更新してトークンを受け取る
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/reset-password/'.$this->token); // パスワードリセットURLの生成

        return (new MailMessage)
            ->subject('パスワードリセットのお知らせ') // メールの件名
            ->line('あなたのアカウントのパスワードリセットを受け付けました。下のボタンをクリックして続行してください。') // メールの本文
            ->action('パスワードをリセット', $url) // アクションボタン
            ->line('もしパスワードリセットを要求していない場合は、このメールを無視してください。'); // メールのフッター
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
