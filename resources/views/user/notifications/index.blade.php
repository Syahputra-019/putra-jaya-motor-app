@if (auth()->user()->role === 'pelanggan')
    @extends('components.app')
@endif

@section('content')
    <div class="container mx-auto max-w-4xl px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Notifikasi</h1>

            @if (auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        Tandai Semua Sudah Dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @forelse($notifications as $notification)
                <a href="{{ route('notifications.read', $notification->id) }}"
                    class="{{ is_null($notification->read_at) ? 'bg-blue-50/40' : 'opacity-80' }} block border-b border-slate-100 p-5 transition hover:bg-slate-50">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="mb-1 flex items-center gap-2">
                                <h3
                                    class="{{ is_null($notification->read_at) ? 'text-slate-900' : 'text-slate-700' }} font-bold">
                                    {{ $notification->data['title'] ?? 'Notifikasi Baru' }}
                                </h3>
                                @if (is_null($notification->read_at))
                                    <span
                                        class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-700">Baru</span>
                                @endif
                            </div>
                            <p class="{{ is_null($notification->read_at) ? 'text-slate-700' : 'text-slate-500' }} text-sm">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                        </div>
                        <span class="whitespace-nowrap text-xs font-medium text-slate-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="p-10 text-center text-slate-500">
                    <div class="mb-3 text-4xl opacity-50">📭</div>
                    Belum ada riwayat notifikasi saat ini.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection

@if (auth()->user()->role !== 'pelanggan')
    <x-layout>
        @yield('content')
    </x-layout>
@endif
