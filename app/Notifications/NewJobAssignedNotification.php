<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewJobAssignedNotification extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'title' => 'Tugas Baru Diberikan',
            'message' => 'Anda ditugaskan untuk servis motor dengan plat ' . $this->booking->plat_nomor,
            'url' => route('mekanik.jadwal')
        ];
    }
}
