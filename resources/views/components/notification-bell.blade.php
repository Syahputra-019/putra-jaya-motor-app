@auth
<div class="relative inline-block text-left" id="notificationDropdownContainer">
    <button type="button" onclick="document.getElementById('notificationDropdown').classList.toggle('hidden')" class="relative p-2 text-gray-400 hover:text-gray-600 focus:outline-none">
        <!-- Bell Icon -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <!-- Bulatan Merah Notifikasi -->
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    <!-- Dropdown Panel -->
    <div id="notificationDropdown" class="hidden absolute right-0 z-50 mt-2 w-80 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-700">Notifikasi</h3>
        </div>
        <div class="max-h-64 overflow-y-auto">
            @forelse(auth()->user()->unreadNotifications as $notification)
                <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100 {{ $notification->read_at ? 'opacity-50' : '' }}">
                    <p class="text-sm font-bold text-gray-800">{{ $notification->data['title'] ?? 'Info Baru' }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <div class="px-4 py-4 text-sm text-gray-500 text-center">
                    Belum ada notifikasi baru 🎉
                </div>
            @endforelse
        </div>
        
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50 text-center">
            <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>
</div>
@endauth
