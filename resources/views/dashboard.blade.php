<x-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
        <p class="text-slate-500 text-sm">Ringkasan aktivitas bengkel hari ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-md transition">
            <h3 class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Total Booking Baru</h3>
            <p class="text-4xl font-black text-slate-800 mt-3">5</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-amber-500 hover:shadow-md transition">
            <h3 class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Sedang Dikerjakan</h3>
            <p class="text-4xl font-black text-slate-800 mt-3">2</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-emerald-500 hover:shadow-md transition">
            <h3 class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Selesai Hari Ini</h3>
            <p class="text-4xl font-black text-slate-800 mt-3">12</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-8 border border-slate-100">
        <h2 class="text-xl font-bold text-slate-800 mb-2">Selamat Datang di Sistem Booking Servis! 🚀</h2>
        <p class="text-slate-600 leading-relaxed">
            Kerangka UI Tailwind berhasil diimplementasikan menggunakan Blade Components. Sekarang kita tidak perlu menulis ulang kodingan Sidebar dan Navbar di setiap halaman.
        </p>
    </div>
</x-layout>