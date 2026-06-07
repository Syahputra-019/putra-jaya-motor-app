<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\DetailTransaksi;
use App\Models\Mekanik;
use App\Models\Pelanggan;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_transaction_draft_from_completed_booking(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$booking, $service, $sparepart] = $this->makeBookingFixture();

        $booking->update([
            'kategori_servis' => [$service->nama_service],
            'sparepart_diminta' => [$sparepart->nama_sparepart],
            'rekomendasi_sparepart' => [
                [
                    'id' => null,
                    'nama' => 'Seal Shock Custom',
                    'harga' => 70000,
                    'jumlah' => 1,
                    'tipe' => 'custom',
                ],
            ],
            'status_konfirmasi' => 'approved',
        ]);

        $response = $this->actingAs($admin)->get(route('transaksi.create', ['booking_id' => $booking->id]));

        $response->assertOk();
        $response->assertSee('Booking referensi terhubung');
        $response->assertSee($booking->pelanggan->nama_pelanggan);
        $response->assertSee($service->nama_service);
        $response->assertSee($sparepart->nama_sparepart);
        $response->assertSee('Seal Shock Custom');
    }

    public function test_admin_can_store_transaction_with_multiple_services_and_items(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$booking, $serviceA, $sparepartA, $mekanik, $pelanggan] = $this->makeBookingFixture();

        $serviceB = Service::create([
            'nama_service' => 'Pembersihan CVT',
            'harga' => 30000,
        ]);

        $sparepartB = Sparepart::create([
            'nama_sparepart' => 'Kampas Rem Belakang',
            'harga' => 50000,
            'stok' => 10,
        ]);

        $response = $this->actingAs($admin)->post(route('transaksi.store'), [
            'booking_id' => $booking->id,
            'kode_transaksi' => 'TRX-TEST-001',
            'tanggal' => now()->format('Y-m-d'),
            'pelanggan_id' => $pelanggan->id,
            'mekanik_id' => $mekanik->id,
            'keluhan' => 'Mesin kasar dan rem mulai tipis',
            'service_id' => [$serviceA->id, $serviceB->id],
            'service_qty' => [1, 1],
            'sparepart_id' => [$sparepartA->id, $sparepartB->id],
            'jumlah' => [2, 1],
            'custom_item_type' => ['part'],
            'custom_item_name' => ['Seal Shock Custom'],
            'custom_item_price' => [70000],
            'custom_item_qty' => [1],
        ]);

        $transaksi = Transaksi::firstOrFail()->fresh(['detailTransaksis', 'booking']);

        $response->assertRedirect(route('transaksi.bayar', $transaksi->id));
        $this->assertSame($booking->id, $transaksi->booking_id);
        $this->assertCount(3, $transaksi->detail_items);
        $this->assertDatabaseCount('detail_transaksis', 2);
        $this->assertSame(280000, (int) $transaksi->total_biaya);
        $this->assertSame('belum lunas', $transaksi->booking->status_pembayaran);

        $detailSparepart = DetailTransaksi::orderBy('sparepart_id')->get();
        $this->assertSame([2, 1], $detailSparepart->pluck('jumlah')->all());
        $this->assertSame(
            ['Servis Ringan / Tune Up', 'Pembersihan CVT', 'Seal Shock Custom'],
            collect($transaksi->detail_items)->pluck('nama')->all(),
        );
    }

    public function test_customer_can_access_own_payment_page_but_not_other_customer_transaction(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$booking, $service, $sparepart, $mekanik, $pelanggan, $customerUser] = $this->makeBookingFixture(includeCustomerUser: true);
        $otherCustomer = User::factory()->create(['role' => 'pelanggan']);

        $this->actingAs($admin)->post(route('transaksi.store'), [
            'booking_id' => $booking->id,
            'kode_transaksi' => 'TRX-TEST-002',
            'tanggal' => now()->format('Y-m-d'),
            'pelanggan_id' => $pelanggan->id,
            'mekanik_id' => $mekanik->id,
            'keluhan' => 'Tes akses pembayaran pelanggan',
            'service_id' => [$service->id],
            'service_qty' => [1],
            'sparepart_id' => [$sparepart->id],
            'jumlah' => [1],
        ]);

        $transaksi = Transaksi::firstOrFail();

        $this->actingAs($customerUser)
            ->get(route('transaksi.bayar', $transaksi->id))
            ->assertOk()
            ->assertSee($transaksi->kode_transaksi);

        $this->actingAs($otherCustomer)
            ->get(route('transaksi.bayar', $transaksi->id))
            ->assertForbidden();
    }

    public function test_payment_page_loads_midtrans_snap_script_when_configured(): void
    {
        config()->set('services.midtrans.server_key', 'SB-Mid-server-test');
        config()->set('services.midtrans.client_key', 'SB-Mid-client-test');
        config()->set('services.midtrans.is_production', false);

        $admin = User::factory()->create(['role' => 'admin']);
        [$booking, $service, $sparepart, $mekanik, $pelanggan] = $this->makeBookingFixture();

        $transaksi = Transaksi::create([
            'booking_id' => $booking->id,
            'kode_transaksi' => 'TRX-SNAP-001',
            'tanggal' => now()->format('Y-m-d'),
            'pelanggan_id' => $pelanggan->id,
            'mekanik_id' => $mekanik->id,
            'service_id' => $service->id,
            'keluhan' => 'Tes render script Midtrans',
            'status' => 'selesai',
            'total_biaya' => $service->harga + $sparepart->harga,
        ]);

        $this->actingAs($admin)
            ->get(route('transaksi.bayar', $transaksi->id))
            ->assertOk()
            ->assertSee('https://app.sandbox.midtrans.com/snap/snap.js', false)
            ->assertSee('SB-Mid-client-test', false)
            ->assertSee(route('transaksi.midtransToken', $transaksi->id), false);
    }

    private function makeBookingFixture(bool $includeCustomerUser = false): array
    {
        $customerUser = User::factory()->create(['role' => 'pelanggan']);
        $mekanikUser = User::factory()->create(['role' => 'mekanik']);

        $pelanggan = Pelanggan::create([
            'user_id' => $customerUser->id,
            'nama_pelanggan' => 'Budi Pelanggan',
            'no_telp' => '081234567890',
            'alamat' => 'Jl. Merdeka',
        ]);

        $mekanik = Mekanik::create([
            'user_id' => $mekanikUser->id,
            'nama_mekanik' => 'Andi Mekanik',
            'no_telp' => '081298765432',
            'spesialisasi' => 'Servis Rutin',
        ]);

        $service = Service::create([
            'nama_service' => 'Servis Ringan / Tune Up',
            'harga' => 50000,
        ]);

        $sparepart = Sparepart::create([
            'nama_sparepart' => 'Oli Mesin AHM MPX-2 0.8L',
            'harga' => 40000,
            'stok' => 20,
        ]);

        $booking = Booking::create([
            'user_id' => $customerUser->id,
            'pelanggan_id' => $pelanggan->id,
            'mekanik_id' => $mekanik->id,
            'plat_nomor' => 'B 1234 CD',
            'tipe_motor' => 'Honda Beat',
            'kategori_servis' => [$service->nama_service],
            'sparepart_diminta' => [$sparepart->nama_sparepart],
            'keluhan' => 'Motor terasa berat',
            'jadwal_booking' => now()->addDay(),
            'status' => 'selesai',
            'status_pembayaran' => 'belum lunas',
        ]);

        $data = [$booking, $service, $sparepart, $mekanik, $pelanggan];

        if ($includeCustomerUser) {
            $data[] = $customerUser;
        }

        return $data;
    }
}
