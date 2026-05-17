@extends('components.app')

@section('title', 'Beri Testimonial')

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-8">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:p-8">
            <h1 class="mb-6 text-2xl font-bold text-slate-900">Beri Testimonial</h1>

            @if (session('success'))
                <div class="mb-6 rounded-lg bg-emerald-50 p-4 text-emerald-700 ring-1 ring-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('testimonial.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Rating (Bintang)</label>
                    <div class="flex items-center gap-2" x-data="{ rating: 5, hoverRating: 0 }">
                        <input type="hidden" name="rating" x-model="rating">
                        <template x-for="i in 5">
                            <button type="button" @click="rating = i" @mouseenter="hoverRating = i" @mouseleave="hoverRating = 0"
                                class="text-4xl transition-colors focus:outline-none"
                                :class="(hoverRating >= i || (hoverRating == 0 && rating >= i)) ? 'text-amber-400' : 'text-slate-300'">
                                ★
                            </button>
                        </template>
                    </div>
                    @error('rating') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="isi_testimonial" class="mb-2 block text-sm font-medium text-slate-700">Ulasan Anda</label>
                    <textarea name="isi_testimonial" id="isi_testimonial" rows="4" class="block w-full rounded-lg border-slate-200 p-3 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ceritakan pengalaman Anda servis di Putra Jaya Motor..."></textarea>
                    @error('isi_testimonial') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Kirim Testimonial</button>
                </div>
            </form>
        </div>
    </div>
@endsection
