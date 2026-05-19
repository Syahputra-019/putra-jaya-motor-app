<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RekomendasiDijawabNotification extends Notification
{
    use Queueable;

    protected $booking;
    protected $status;

    public function __construct($booking, $status)
    {
        $this->booking = $booking;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $statusTeks = $this->status === 'approved' ? 'DISETUJUI' : 'DITOLAK';
        $url = $notifiable->role === 'mekanik' ? route('mekanik.jadwal') : route('booking.index');

        return [
            'booking_id' => $this->booking->id,
            'title' => 'Respon Rekomendasi (' . $statusTeks . ')',
            'message' => 'Pelanggan kendaraan ' . $this->booking->plat_nomor . ' telah ' . strtolower($statusTeks) . ' rekomendasi perbaikan.',
            'url' => $url
        ];
    }
}
