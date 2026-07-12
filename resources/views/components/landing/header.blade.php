<header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <a href="#beranda" class="flex items-center gap-2">
                        <div class="bg-white p-1 rounded-lg border border-slate-200 shadow-sm">
                            <img src="{{ asset('image/LOGO UCI.jpeg') }}" alt="Logo PT. Unggul Cipta Indah" class="w-8 h-8 object-contain rounded">
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-slate-900 leading-tight">PT. Unggul Cipta Indah</h1>
                            <p class="text-xs text-[#003d7c] font-semibold tracking-wide uppercase">Professional Outsourcing
                            </p>
                        </div>
                    </a>

                    <nav class="hidden md:flex items-center gap-8">
                        <a href="#beranda"
                            class="js-nav-link text-sm font-medium text-slate-600 hover:text-[#003d7c] transition-colors">Beranda</a>
                        <a href="#tentang-kami"
                            class="js-nav-link text-sm font-medium text-slate-600 hover:text-[#003d7c] transition-colors">Tentang
                            Kami</a>
                        <a href="#visi-misi"
                            class="js-nav-link text-sm font-medium text-slate-600 hover:text-[#003d7c] transition-colors">Visi &
                            Misi</a>
                        <a href="#lowongan-kerja"
                            class="js-nav-link text-sm font-medium text-slate-600 hover:text-[#003d7c] transition-colors">Lowongan
                            Kerja</a>
                        <a href="#kontak"
                            class="js-nav-link text-sm font-medium text-slate-600 hover:text-[#003d7c] transition-colors">Kontak</a>
                        <div class="flex items-center gap-3 ml-4 border-l pl-6 border-slate-200">
                            @guest
                                <a href="{{ route('login') }}"
                                    class="text-sm font-medium text-slate-600 hover:text-[#003d7c] transition-colors">Masuk</a>
                                <a href="{{ route('register') }}"
                                    class="inline-block bg-[#003d7c] hover:bg-[#002d5c] text-white text-sm font-medium py-2.5 px-5 rounded-lg transition-colors shadow-sm">Daftar
                                    Sekarang</a>
                            @endguest
                            @auth
                                <a href="{{ Auth::user()->role === 'hrd' ? route('hrd.dashboard') : route('pelamar.dashboard') }}" class="text-sm font-semibold text-[#003d7c] hover:underline mr-2">Dashboard</a>
                                <span class="text-sm font-medium text-slate-400">|</span>
                                <span class="text-sm font-semibold text-slate-700 ml-2">Halo, {{ Auth::user()->name }}</span>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors ml-2">Keluar</button>
                                </form>
                            @endauth
                        </div>
                    </nav>
                </div>
            </div>
        </header>