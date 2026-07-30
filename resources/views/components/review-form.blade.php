<!-- Review Form Component with Premium UI/UX -->
<div class="review-form bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50">
    <form action="{{ $actionUrl }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($review))
            @method('PUT')
        @endif

        <!-- Rating Input -->
        <div class="form-group">
            <label class="block text-slate-700 text-xs font-black uppercase tracking-wider mb-2">
                Beri Penilaian Bintang <span class="text-rose-500">*</span>
            </label>
            <div class="flex items-center gap-2 py-1" id="ratingInputContainer">
                <input type="hidden" name="rating" id="ratingInput" value="{{ isset($review) ? $review->rating : '' }}"
                    required>
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button"
                        class="star-btn focus:outline-none transition-all duration-150 transform hover:scale-125 select-none"
                        data-rating="{{ $i }}">
                        <i
                            class="fa-solid fa-star text-4xl text-slate-200 transition-colors duration-150 cursor-pointer pointer-events-none"></i>
                    </button>
                @endfor
            </div>
            @error('rating')
                <span class="text-rose-500 text-xs font-semibold mt-1.5 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Title Input -->
        <div class="form-group">
            <label for="title" class="block text-slate-700 text-xs font-black uppercase tracking-wider mb-2">
                Judul Review
            </label>
            <input type="text" name="title" id="title"
                class="w-full px-4 py-3.5 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-slate-800 placeholder-slate-400"
                placeholder="Tulis ringkasan singkat ulasan Anda" value="{{ isset($review) ? $review->title : '' }}"
                maxlength="255">
            @error('title')
                <span class="text-rose-500 text-xs font-semibold mt-1.5 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Review Text Input -->
        <div class="form-group">
            <label for="review_text" class="block text-slate-700 text-xs font-black uppercase tracking-wider mb-2">
                Testimoni Pengalaman Anda (Minimal 10 karakter)
            </label>
            <textarea name="review_text" id="review_text" rows="5"
                class="w-full px-4 py-3.5 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-slate-800 placeholder-slate-400"
                placeholder="Bagikan pengalaman menarik atau masukan membangun Anda tentang acara ini...">{{ isset($review) ? $review->review_text : '' }}</textarea>
            <div class="flex items-center justify-between mt-2.5">
                <small class="text-slate-400 font-medium text-xs">Ulasan yang bermanfaat membantu calon peserta
                    lainnya</small>
                <span class="text-xs font-bold text-slate-400" id="charCount">0 karakter</span>
            </div>
            @error('review_text')
                <span class="text-rose-500 text-xs font-semibold mt-1.5 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <button type="submit"
                class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-2xl flex items-center justify-center gap-2 font-bold text-sm shadow-lg shadow-indigo-100/70 transition-all">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                {{ isset($review) ? 'Simpan Perubahan' : 'Kirim Ulasan' }}
            </button>
            <a href="{{ route('events.show', $event) }}"
                class="px-8 py-3.5 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 rounded-2xl flex items-center justify-center gap-2 font-bold text-sm transition-all">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('ratingInputContainer');
        const ratingInput = document.getElementById('ratingInput');
        const stars = container.querySelectorAll('.star-btn i');
        const buttons = container.querySelectorAll('.star-btn');
        const textarea = document.getElementById('review_text');
        const charCount = document.getElementById('charCount');

        // Smooth Interactive Hover & Click Rating System
        function setHighlight(rating) {
            stars.forEach((star, index) => {
                const currentStarNum = index + 1;
                if (currentStarNum <= rating) {
                    star.classList.remove('text-slate-200', 'scale-100');
                    star.classList.add('text-amber-400');
                } else {
                    star.classList.remove('text-amber-400');
                    star.classList.add('text-slate-200');
                }
            });
        }

        // Initial load value
        const initialRating = parseInt(ratingInput.value) || 0;
        setHighlight(initialRating);

        buttons.forEach((btn, index) => {
            const rating = index + 1;

            // Hover: follow cursor smoothly
            btn.addEventListener('mouseenter', () => {
                setHighlight(rating);
            });

            // Set value on click
            btn.addEventListener('click', () => {
                ratingInput.value = rating;
                setHighlight(rating);

                // Add subtle success scale pop on click
                const currentStar = btn.querySelector('i');
                currentStar.classList.add('scale-125');
                setTimeout(() => {
                    currentStar.classList.remove('scale-125');
                }, 150);
            });
        });

        // Reset back to selected value when mouse leaves star container
        container.addEventListener('mouseleave', () => {
            const selectedRating = parseInt(ratingInput.value) || 0;
            setHighlight(selectedRating);
        });

        // Dynamic Character Counter
        function updateCharCount() {
            if (textarea) {
                const count = textarea.value.length;
                charCount.textContent = `${count} karakter`;
                if (count < 10 && count > 0) {
                    charCount.classList.remove('text-slate-400', 'text-emerald-600');
                    charCount.classList.add('text-rose-500');
                } else if (count >= 10) {
                    charCount.classList.remove('text-slate-400', 'text-rose-500');
                    charCount.classList.add('text-emerald-600');
                } else {
                    charCount.classList.remove('text-rose-500', 'text-emerald-600');
                    charCount.classList.add('text-slate-400');
                }
            }
        }

        if (textarea) {
            textarea.addEventListener('input', updateCharCount);
            updateCharCount(); // Trigger initial count
        }
    });
</script>