<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class FormCreatedNotification extends Notification
{
    use Queueable;

    protected $form;

    public function __construct($form)
    {
        $this->form = $form;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'form_id' => $this->form->id,
            'form_title' => $this->form->titre,
            // Add any other data you want to pass to the notification
        ];
    }
}
