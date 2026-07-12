<footer id="kontak" class="bg-[#003d7c] text-white pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-xl overflow-hidden p-1">
                                <img src="{{ asset('image/LOGO UCI.jpeg') }}" alt="Logo PT. Unggul Cipta Indah" class="w-full h-full object-contain rounded-xl">
                            </div>
                            <div class="font-bold text-xl tracking-tight leading-tight">
                                PT. Unggul Cipta<br/>Indah
                            </div>
                        </div>
                        <p class="text-blue-100/60 text-sm leading-relaxed max-w-sm font-medium">
                            Penyedia layanan outsourcing & facility management profesional, berakhlak, dan bertanggung jawab sejak 1994.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-white font-bold mb-8 uppercase tracking-widest text-sm">Perusahaan</h3>
                        <ul class="space-y-4">
                            <li><a href="#beranda" class="text-blue-100/60 hover:text-white transition-colors text-sm font-medium flex items-center gap-2 group"><span class="text-red-500 group-hover:translate-x-1 transition-transform">›</span> Beranda</a></li>
                            <li><a href="#tentang-kami" class="text-blue-100/60 hover:text-white transition-colors text-sm font-medium flex items-center gap-2 group"><span class="text-red-500 group-hover:translate-x-1 transition-transform">›</span> Tentang Kami</a></li>
                            <li><a href="#visi-misi" class="text-blue-100/60 hover:text-white transition-colors text-sm font-medium flex items-center gap-2 group"><span class="text-red-500 group-hover:translate-x-1 transition-transform">›</span> Visi & Misi</a></li>
                            <li><a href="#lowongan-kerja" class="text-blue-100/60 hover:text-white transition-colors text-sm font-medium flex items-center gap-2 group"><span class="text-red-500 group-hover:translate-x-1 transition-transform">›</span> Lowongan Kerja</a></li>
                            <li><a href="#kontak" class="text-blue-100/60 hover:text-white transition-colors text-sm font-medium flex items-center gap-2 group"><span class="text-red-500 group-hover:translate-x-1 transition-transform">›</span> Kontak</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-white font-bold mb-8 uppercase tracking-widest text-sm">Layanan Kami</h3>
                        <ul class="space-y-4">
                            <li class="text-blue-100/60 text-sm font-medium flex items-center gap-2"><span class="text-red-500">›</span> Cleaning Service</li>
                            <li class="text-blue-100/60 text-sm font-medium flex items-center gap-2"><span class="text-red-500">›</span> Asisten Keperawatan</li>
                            <li class="text-blue-100/60 text-sm font-medium flex items-center gap-2"><span class="text-red-500">›</span> Runner & Gardener</li>
                            <li class="text-blue-100/60 text-sm font-medium flex items-center gap-2"><span class="text-red-500">›</span> Driver & Bell Boy</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-white font-bold mb-8 uppercase tracking-widest text-sm">Hubungi Kami</h3>
                        <ul class="space-y-5">
                            <li class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                                    <x-landing.icon name="pin" class="w-4 h-4 text-red-500" />
                                </div>
                                <p class="text-blue-100/60 text-[13px] font-medium leading-relaxed">
                                    Jatisampurna, Bekasi<br/>Jawa Barat, Indonesia
                                </p>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                                    <x-landing.icon name="phone" class="w-4 h-4 text-red-500" />
                                </div>
                                <a href="tel:02184312450" class="text-blue-100/60 hover:text-white transition-colors text-[13px] font-medium">021-8431 2450</a>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                                    <x-landing.icon name="mail" class="w-4 h-4 text-red-500" />
                                </div>
                                <a href="mailto:unggulcuptaindah@gmail.com" class="text-blue-100/60 hover:text-white transition-colors text-[13px] font-medium">unggulcuptaindah@gmail.com</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pt-8 border-t border-white/10 text-blue-100/40 text-xs flex flex-col md:flex-row justify-between items-center gap-4">
                    <p>&copy; {{ date('Y') }} PT. Unggul Cipta Indah. Hak Cipta Dilindungi.</p>
                    <div class="flex gap-6">
                        <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                        <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </footer>