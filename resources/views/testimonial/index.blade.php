<x-layout>
    <div>
        <div class="page-header mb-6">
            <div class="page-header-split">
                <h1 class="text-2xl font-bold text-slate-900">Manajemen Testimonial</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola ulasan dari pelanggan.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-emerald-50 p-4 text-emerald-700 ring-1 ring-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Pelanggan</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Rating</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Ulasan</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                            <th scope="col" class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($testimonials as $testimonial)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                                    {{ $testimonial->user->name ?? 'User Terhapus' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                    <div class="flex text-amber-400">
                                        @for ($i = 0; $i < $testimonial->rating; $i++) ★ @endfor
                                        @for ($i = $testimonial->rating; $i < 5; $i++) <span class="text-slate-300">★</span> @endfor
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    <p class="line-clamp-2 max-w-xs" title="{{ $testimonial->isi_testimonial }}">
                                        {{ $testimonial->isi_testimonial }}
                                    </p>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    @if ($testimonial->status === 'pending')
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">Pending</span>
                                    @elseif ($testimonial->status === 'approved')
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Approved</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/20">Rejected</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        @if ($testimonial->status !== 'approved')
                                            <form action="{{ route('admin.testimonial.updateStatus', $testimonial->id) }}" method="POST">
                                                @csrf @method('PATCH') <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="text-emerald-600 hover:text-emerald-900 font-bold" title="Approve">✓</button>
                                            </form>
                                        @endif
                                        
                                        @if ($testimonial->status !== 'rejected')
                                            <form action="{{ route('admin.testimonial.updateStatus', $testimonial->id) }}" method="POST">
                                                @csrf @method('PATCH') <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="text-rose-600 hover:text-rose-900 font-bold" title="Reject">✕</button>
                                            </form>
                                        @endif

                                        <span class="text-slate-200">|</span>

                                        <form action="{{ route('admin.testimonial.destroy', $testimonial->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus testimonial ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600" title="Hapus">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data testimonial.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($testimonials->hasPages())
                <div class="border-t border-slate-200 p-4">{{ $testimonials->links() }}</div>
            @endif
        </div>
    </div>
</x-layout>
