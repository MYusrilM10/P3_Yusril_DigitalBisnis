<!-- Review List Display Component -->
<div class="review-list space-y-4">
    @forelse($reviews as $review)
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <!-- Review Header -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $review->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <!-- Action Menu -->
                @auth
                    @if(auth()->id() === $review->user_id)
                        <div class="relative group">
                            <button class="text-gray-400 hover:text-gray-600">⋮</button>
                            <div class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition z-10">
                                <a href="{{ route('reviews.edit', $review) }}" class="block px-4 py-2 text-sm text-blue-600 hover:bg-gray-100">Edit</a>
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" onclick="return confirm('Apakah Anda yakin ingin menghapus review ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Rating Display -->
            <div class="flex items-center gap-2 mb-2">
                <div class="flex gap-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="text-lg {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                    @endfor
                </div>
                <span class="text-sm font-semibold text-gray-700">{{ $review->rating }}/5</span>
                @if($review->is_verified_purchase)
                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full inline-flex items-center gap-1">
                        <i class="fa-solid fa-circle-check"></i> Pembeli Terverifikasi
                    </span>
                @endif
            </div>

            <!-- Review Title -->
            @if($review->title)
                <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $review->title }}</h4>
            @endif

            <!-- Review Text -->
            @if($review->review_text)
                <p class="text-gray-700 mb-3 leading-relaxed">{{ $review->review_text }}</p>
            @endif

            <!-- Helpful Section -->
            <div class="pt-3 border-t border-gray-100">
                <button class="text-sm text-gray-500 hover:text-gray-700 transition">
                    <i class="fa-solid fa-thumbs-up"></i> Membantu ({{ $review->helpful_count }})
                </button>
            </div>
        </div>
    @empty
        <div class="bg-gray-50 p-8 rounded-lg text-center">
            <p class="text-gray-500 text-lg">Belum ada review untuk acara ini.</p>
            <p class="text-gray-400 text-sm mt-2">Jadilah yang pertama memberikan review!</p>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($reviews->hasPages())
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
