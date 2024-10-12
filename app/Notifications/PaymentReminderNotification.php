<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class PaymentReminderNotification extends Notification
{
    private $duePaymentsCount;

    public function __construct($duePaymentsCount)
    {
        $this->duePaymentsCount = $duePaymentsCount;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "There are {$this->duePaymentsCount} payments due soon.",
            'url' => route('filament.resources.payment-reminders.index'),
        ];
    }
}
