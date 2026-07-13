@props(['postings'])

<section id="lowongan-kerja" class="section-shell bg-white">
    <div class="absolute top-0 right-0 w-1/3 h-full bg-slate-50/50 -skew-x-12 transform origin-top translate-x-20 z-0">
    </div>
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col lg:flex-row gap-20">
            <!-- Left Column: Context & Categories -->
            <div class="lg:w-[32%]">
                <div class="mb-10">
                    <span class="section-kicker mb-4">Portal Lowongan</span>
                    <h2 class="section-title mb-6 leading-[1.1]">Temukan Karier <br /> <span
                            class="text-primary-600">Impian Anda</span></h2>
                    <p class="section-copy">Jelajahi berbagai peluang karier dari perusahaan-perusahaan terkemuka yang
                        menjadi mitra strategis kami. Mulai langkah profesional Anda hari ini.</p>
                </div>

                <div class="relative group">
                    <div
                        class="absolute -inset-2 bg-gradient-to-r from-primary-600 to-blue-400 rounded-[2rem] opacity-20 blur-xl group-hover:opacity-30 transition duration-500">
                    </div>
                    <img src="https://images.unsplash.com/photo-1542744095-fcf48d80b0fd?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxqb2IlMjByZWNydWl0bWVudCUyMGludGVydmlldyUyMGhyfGVufDF8fHx8MTc3ODQ3MTkwMnww&ixlib=rb-4.1.0&q=80&w=1080"
                        alt="Professional Recruitment"
                        class="relative rounded-[1.8rem] shadow-2xl w-full h-64 object-cover object-center" />
                </div>
            </div>

            <!-- Right Column: Job Listings -->
            <div class="lg:w-[68%]">
                <div class="flex items-end justify-between mb-10 pb-6 border-b border-slate-100">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-950">Lowongan Terbaru</h3>
                        <p class="text-slate-500 mt-1">Peluang kerja yang baru saja ditambahkan</p>
                    </div>
                    <a href="{{ route('pelamar.lowongan') }}"
                        class="flex items-center gap-1 text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors">
                        Lihat Semua
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="space-y-6">
                    @forelse(($postings ?? collect()) as $posting)
                        @php
                            $location = $posting->second_requires_placement_ready
                                ? 'Siap ditempatkan sesuai penempatan'
                                : ($posting->location_city ?: '-');
                            $shift = $posting->shift_type === 'shift' ? 'Shift' : ($posting->shift_type === 'non_shift' ? 'Non Shift' : null);
                            $salary = null;
                            if (!$posting->salary_hidden && $posting->salary_min && $posting->salary_max) {
                                $salary = 'Rp ' . number_format($posting->salary_min, 0, ',', '.') . ' - Rp ' . number_format($posting->salary_max, 0, ',', '.');
                            }
                            $applyUrl = auth()->check() && auth()->user()->role === 'pelamar'
                                ? route('pelamar.lowongan.apply', $posting)
                                : route('login');
                            $job = [
                                'title' => $posting->title,
                                'company' => 'PT. Unggul Cipta Indah',
                                'location' => $location,
                                'type' => $shift,
                                'posted' => $posting->created_at?->diffForHumans(),
                                'salary' => $salary,
                                'apply_url' => $applyUrl,
                                'active_until' => $posting->active_until ? $posting->active_until->format('d M Y') : null,
                            ];
                        @endphp
                        <x-landing.job-card :job="$job" />
                    @empty
                        <div
                            class="rounded-2xl border border-dashed border-slate-200 p-6 text-sm text-slate-500 text-center">
                            Belum ada lowongan aktif.
                        </div>
                    @endforelse

                    @if($postings instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $postings->hasPages())
                        <div class="mt-12 flex items-center justify-between border-t border-slate-100 pt-6">
                            <!-- Mobile Navigation (Simple Prev/Next buttons) -->
                            <div class="flex flex-1 justify-between sm:hidden">
                                @if ($postings->onFirstPage())
                                    <span class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-slate-400 bg-slate-50 border border-slate-100 rounded-xl cursor-not-allowed select-none">
                                        Sebelumnya
                                    </span>
                                @else
                                    <a href="{{ $postings->previousPageUrl() }}#lowongan-kerja" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                        Sebelumnya
                                    </a>
                                @endif

                                @if ($postings->hasMorePages())
                                    <a href="{{ $postings->nextPageUrl() }}#lowongan-kerja" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                        Selanjutnya
                                    </a>
                                @else
                                    <span class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-slate-400 bg-slate-50 border border-slate-100 rounded-xl cursor-not-allowed select-none">
                                        Selanjutnya
                                    </span>
                                @endif
                            </div>

                            <!-- Desktop Navigation -->
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-slate-500">
                                        Menampilkan <span class="font-semibold text-slate-900">{{ $postings->firstItem() }}</span>
                                        sampai <span class="font-semibold text-slate-900">{{ $postings->lastItem() }}</span>
                                        dari <span class="font-semibold text-slate-900">{{ $postings->total() }}</span> lowongan
                                    </p>
                                </div>
                                <div>
                                    <nav class="isolate inline-flex -space-x-px rounded-xl gap-2" aria-label="Pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($postings->onFirstPage())
                                            <span class="relative inline-flex items-center justify-center w-10 h-10 text-slate-400 bg-slate-50 border border-slate-100 rounded-xl cursor-not-allowed select-none">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @else
                                            <a href="{{ $postings->previousPageUrl() }}#lowongan-kerja" class="relative inline-flex items-center justify-center w-10 h-10 text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif

                                        {{-- Page Numbers --}}
                                        @foreach ($postings->getUrlRange(1, $postings->lastPage()) as $page => $url)
                                            @if ($page == $postings->currentPage())
                                                <span aria-current="page" class="relative z-10 inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-blue-600 rounded-xl shadow-md shadow-blue-500/20">
                                                    {{ $page }}
                                                </span>
                                            @else
                                                <a href="{{ $url }}#lowongan-kerja" class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($postings->hasMorePages())
                                            <a href="{{ $postings->nextPageUrl() }}#lowongan-kerja" class="relative inline-flex items-center justify-center w-10 h-10 text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @else
                                            <span class="relative inline-flex items-center justify-center w-10 h-10 text-slate-400 bg-slate-50 border border-slate-100 rounded-xl cursor-not-allowed select-none">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </nav>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
