<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
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
        $nomorAntrean = $this->booking->kode_antrean ?? ('#' . str_pad((string) $this->booking->nomor_antrean, 3, '0', STR_PAD_LEFT));

        return [
            'booking_id' => $this->booking->id,
            'title' => 'Booking Baru Masuk',
            'message' => 'Ada booking servis motor baru dengan plat nomor ' . $this->booking->plat_nomor . ' dan nomor antrean ' . $nomorAntrean,
            'url' => route('dashboard')
        ];
    }
}
