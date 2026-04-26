<x-layout>
    <div class="container mx-auto px-4 py-8 md:max-w-3xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Status Servis Motor Anda</h2>
            <p class="text-sm text-gray-500">Pantau progres pengerjaan kendaraan Anda secara real-time.</p>
        </div>

        @if ($booking)
            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white p-6 shadow-lg md:p-8">

                <div class="mb-8 flex items-center justify-between border-b border-gray-200 pb-4">
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-400">Kendaraan</p>
                        <h3 class="text-lg font-bold text-gray-800">{{ $booking->tipe_motor }}</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold uppercase text-gray-400">Plat Nomor</p>
                        <span
                            class="rounded bg-gray-100 px-3 py-1 font-mono text-sm font-bold tracking-widest text-gray-800">
                            {{ $booking->plat_nomor }}
                        </span>
                    </div>
                </div>

                <div class="relative mb-10 mt-4">
                    <div class="relative flex items-center justify-between px-2 md:px-6">
                        <div class="absolute left-0 top-5 h-1 w-full bg-gray-200"></div>

                        @php
                            $width = '0%';
                            if ($booking->status == 'diproses' || $booking->status == 'Proses') {
                                $width = '33%';
                            }
                            if ($booking->status == 'selesai' || $booking->status == 'Selesai') {
                                $width = '66%';
                            }
                            if ($booking->status_pembayaran == 'lunas') {
                                $width = '100%';
                            }
                        @endphp
                        <div class="absolute left-0 top-5 h-1 bg-blue-500 transition-all duration-700 ease-in-out"
                            style="width: {{ $width }}"></div>

                        <div class="relative z-10 flex flex-col items-center">
                            <div
                                class="{{ in_array($booking->status, ['menunggu', 'Pending']) ? 'border-blue-500 bg-white' : 'border-blue-500 bg-blue-500' }} flex h-10 w-10 items-center justify-center rounded-full border-4">
                                <svg class="{{ in_array($booking->status, ['menunggu', 'Pending']) ? 'text-blue-500' : 'text-white' }} h-5 w-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="mt-2 text-[10px] font-bold uppercase md:text-xs">Antre</p>
                        </div>

                        <div class="relative z-10 flex flex-col items-center">
                            <div
                                class="{{ in_array($booking->status, ['diproses', 'Proses']) ? 'border-blue-500 bg-white' : (in_array($booking->status, ['selesai', 'Selesai']) || $booking->status_pembayaran == 'lunas' ? 'border-blue-500 bg-blue-500' : 'border-gray-200 bg-gray-200') }} flex h-10 w-10 items-center justify-center rounded-full border-4">
                                <svg class="{{ in_array($booking->status, ['diproses', 'Proses']) ? 'text-blue-500' : (in_array($booking->status, ['selesai', 'Selesai']) || $booking->status_pembayaran == 'lunas' ? 'text-white' : 'text-gray-400') }} h-5 w-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <p
                                class="{{ in_array($booking->status, ['diproses', 'Proses', 'selesai', 'Selesai']) || $booking->status_pembayaran == 'lunas' ? 'text-blue-600' : 'text-gray-400' }} mt-2 text-[10px] font-bold uppercase md:text-xs">
                                Dikerjakan</p>
                        </div>

                        <div class="relative z-10 flex flex-col items-center">
                            <div
                                class="{{ in_array($booking->status, ['selesai', 'Selesai']) && $booking->status_pembayaran != 'lunas' ? 'border-blue-500 bg-white' : ($booking->status_pembayaran == 'lunas' ? 'border-blue-500 bg-blue-500' : 'border-gray-200 bg-gray-200') }} flex h-10 w-10 items-center justify-center rounded-full border-4">
                                <svg class="{{ in_array($booking->status, ['selesai', 'Selesai']) && $booking->status_pembayaran != 'lunas' ? 'text-blue-500' : ($booking->status_pembayaran == 'lunas' ? 'text-white' : 'text-gray-400') }} h-5 w-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p
                                class="{{ in_array($booking->status, ['selesai', 'Selesai']) || $booking->status_pembayaran == 'lunas' ? 'text-blue-600' : 'text-gray-400' }} mt-2 text-[10px] font-bold uppercase md:text-xs">
                                Selesai</p>
                        </div>

                        <div class="relative z-10 flex flex-col items-center">
                            <div
                                class="{{ $booking->status_pembayaran == 'lunas' ? 'border-green-500 bg-green-500' : 'border-gray-200 bg-gray-200' }} flex h-10 w-10 items-center justify-center rounded-full border-4">
                                <svg class="{{ $booking->status_pembayaran == 'lunas' ? 'text-white' : 'text-gray-400' }} h-5 w-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p
                                class="{{ $booking->status_pembayaran == 'lunas' ? 'text-green-600' : 'text-gray-400' }} mt-2 text-[10px] font-bold uppercase md:text-xs">
                                Lunas</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-lg border border-blue-100 bg-blue-50 p-4 text-center">
                    @if (in_array($booking->status, ['menunggu', 'Pending']))
                        <p class="text-sm text-blue-700"><strong>Menunggu Antrean:</strong> Kendaraan Anda sudah
                            terdaftar dan menunggu giliran mekanik.</p>
                    @elseif(in_array($booking->status, ['diproses', 'Proses']))
                        <p class="text-sm text-blue-700"><strong>Sedang Dikerjakan:</strong> Mekanik kami sedang
                            melakukan perbaikan/perawatan pada motor Anda.</p>
                    @elseif(in_array($booking->status, ['selesai', 'Selesai']) && $booking->status_pembayaran != 'lunas')
                        <p class="text-sm font-bold text-blue-700">Servis Selesai! Silakan menuju kasir untuk melakukan
                            pembayaran.</p>
                    @elseif($booking->status_pembayaran == 'lunas')
                        <p class="text-sm font-bold text-green-700">Pembayaran Lunas! Kendaraan sudah bisa dibawa
                            pulang. Terima kasih!</p>
                    @else
                        <p class="text-sm font-bold text-red-600">Status pesanan dibatalkan atau tidak ditemukan.</p>
                    @endif
                </div>

            </div>
        @else
            <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 py-16 text-center shadow-sm">
                <svg style="width: 60px; height: 60px;" class="mx-auto mb-4 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="mb-2 text-lg font-medium text-gray-900">Belum Ada Servis Aktif</h3>
                <p class="mb-6 text-sm text-gray-500">Anda belum memiliki jadwal servis untuk saat ini.</p>
                <a href="{{ route('booking.create') }}"
                    class="rounded bg-blue-600 px-6 py-2.5 font-bold text-white shadow transition hover:bg-blue-700">
                    Booking Servis Sekarang
                </a>
            </div>
        @endif
    </div>
</x-layout>
