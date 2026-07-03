<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SparepartRecommendationNotification extends Notification
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
            'title' => 'Rekomendasi Sparepart',
            'message' => 'Mekanik mengirim rekomendasi perbaikan/sparepart untuk ' . $this->booking->plat_nomor . ' (' . $nomorAntrean . '). Silakan konfirmasi.',
            'url' => route('booking.mine', ['id' => $this->booking->id])
        ];
    }
}
