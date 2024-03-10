<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductNotification extends Notification
{
    use Queueable;
    public $user;
    public $product;
    public $status;
    public $notificationMethod;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($product,$user,$status,$notificationMethod)
    {
        $this->product = $product;
        $this->user = $user;
        $this->status = $status;
        $this->notificationMethod = $notificationMethod;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->notificationMethod === 'database') {
            return ['database'];
        } elseif ($this->notificationMethod === 'email') {
            return ['mail'];
        } else {
            return [];
        }
    }


    public function toDatabase(){
        return [
            'name product' =>$this->product,
            'user that add product is: ' => $this->user
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->mailer('smtp')
            ->subject('status product')
            ->greeting('Hello '.$notifiable->name)
            ->line('product '.$this->product .' that add it is '.$this->status);
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
