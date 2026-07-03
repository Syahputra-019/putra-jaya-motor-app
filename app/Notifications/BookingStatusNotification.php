<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification
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
            'title' => 'Status Servis Diperbarui',
            'message' => 'Status motor ' . $this->booking->plat_nomor . ' (' . $nomorAntrean . ') kamu sekarang: ' . strtoupper($this->booking->status),
            'url' => route('booking.mine', ['id' => $this->booking->id])
        ];
    }
}
