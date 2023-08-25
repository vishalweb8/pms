<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PasswordReset extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $user;
    public $token;
    public function __construct($token)
    {
        $this->token = $token;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $link = route('password.reset',$this->token);// .'?email='.$notifiable->email;
        $link .= '?email='.$notifiable->email;
        return (new MailMessage)
                    ->subject('Reset Password Notification')
                    ->view('emails.forgot-password',['user' => $notifiable , 'link' => $link ]);
    }

    /**
     * Get the array representation of the notification.
     *
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
           //
        ];
    }
}
