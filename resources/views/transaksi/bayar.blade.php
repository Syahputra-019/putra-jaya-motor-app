<x-layout>
    <div class="max-w-5xl mx-auto py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Pilih Metode Pembayaran 💸</h1>
            <p class="text-slate-500 mt-1">Selesaikan pembayaran untuk nota servis <b>{{ $transaksi->kode_transaksi }}</b></p>
        </div>

        <div class="bg-blue-50 rounded-xl border border-blue-100 p-6 mb-8 flex justify-between items-center">
            <div>
                <p class="text-sm text-blue-600 font-semibold mb-1">Total Tagihan:</p>
                <h2 class="text-3xl font-black text-blue-800">Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</h2>
                <p class="text-sm text-slate-600 mt-1">Pelanggan: {{ $transaksi->pelanggan->nama_pelanggan ?? 'Umum' }}</p>
            </div>
            <div class="hidden sm:block">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center flex flex-col justify-between hover:border-blue-400 transition">
                <div>
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Bayar Otomatis</h3>
                    <p class="text-slate-500 text-sm mb-6">Bayar praktis pakai QRIS, M-Banking, GoPay, atau ShopeePay. Konfirmasi langsung detik ini juga tanpa nunggu admin.</p>
                </div>
                
                <button id="pay-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                    Bayar Sekarang (Midtrans)
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 hover:border-green-400 transition">
                <div class="text-center mb-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Transfer Manual</h3>
                    <p class="text-slate-500 text-sm">Transfer ke <b>BCA 123456789</b> (A.N. Putra Jaya Motor), lalu upload foto struk di bawah ini.</p>
                </div>
                
                <form action="{{ route('transaksi.uploadStruk', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <input type="file" name="bukti_struk" accept="image/*" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-slate-200 rounded-lg p-2 cursor-pointer">
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                        Kirim Bukti Transfer
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // Manggil Snap Token yang udah kita bikin di Controller tadi
            snap.pay('{{ $transaksi->snap_token }}', {
                onSuccess: function(result){
                    alert("Wih sukses bro! Pembayaran berhasil.");
                    // Otomatis lempar ke halaman cetak nota
                    window.location.href = "{{ route('transaksi.cetak', $transaksi->id) }}";
                },
                onPending: function(result){
                    alert("Menunggu pembayaran...");
                },
                onError: function(result){
                    alert("Waduh, pembayarannya gagal bro.");
                },
                onClose: function(){
                    alert('Pop-up ditutup sebelum bayar. Jangan lupa bayar ya!');
                }
            });
        };
    </script>
</x-layout>