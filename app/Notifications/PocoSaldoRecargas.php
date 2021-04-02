<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PocoSaldoRecargas extends Notification 
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */


    public $saldo;

    public function __construct($saldo)
    {
        $this->saldo = $saldo;
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
        return (new MailMessage)

                    ->greeting('Hola!')
                    ->subject('Saldo de recargas')
                    ->line('Tu saldo para recargas esta por agotarse!')
                    // ->action('Notification Action', url('/'))
                    ->line('Saldo restante  $'.$this->saldo);
    }

    /**
     * Get the array representation of the notification.
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
