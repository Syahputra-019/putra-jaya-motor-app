<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\User;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil maksimal 5 user dengan role pelanggan secara acak
        $pelanggans = User::where('role', 'pelanggan')->inRandomOrder()->take(5)->get();

        $ulasan = [
            "Pelayanan sangat memuaskan, mekaniknya ramah dan pengerjaan motor cepat selesai!",
            "Servis rutin selalu di Putra Jaya Motor, harga transparan dan mekanik sangat handal.",
            "Mantap! Motor saya yang tadinya sering brebet sekarang jadi ngacir lagi. Sukses terus!",
            "Tempat nunggunya cukup nyaman, sistem antrean juga jelas lewat WA. Recommended banget!",
            "Sparepart lengkap dan dijamin asli. Nggak pernah nyesel percayain servis motor di sini."
        ];

        foreach ($pelanggans as $index => $pelanggan) {
            Testimonial::create([
                'user_id'         => $pelanggan->id,
                'rating'          => rand(4, 5), // Rating acak antara 4 atau 5 bintang
                'isi_testimonial' => $ulasan[$index] ?? "Pelayanan yang sangat baik, terima kasih Putra Jaya Motor!",
                'status'          => 'approved', // Langsung di-approve agar tampil di Landing Page
            ]);
        }
    }
}
