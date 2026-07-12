@props(['brand', 'navigation'])

<header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <a href="#beranda" class="flex items-center gap-3">
            <span
                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-600 text-white shadow-[0_18px_30px_rgba(37,99,235,0.25)]">
                <x-landing.icon name="briefcase" class="h-6 w-6" />
            </span>
            <span class="leading-tight">
                <span
                    class="block text-lg font-extrabold tracking-tight text-slate-950 sm:text-xl">{{ $brand['name'] }}</span>
                <span
                    class="block text-[11px] font-semibold uppercase tracking-[0.22em] text-primary-600">{{ $brand['tagline'] }}</span>
            </span>
        </a>

        <nav class="hidden items-center gap-8 lg:flex" aria-label="Primary">
            @foreach ($navigation as $item)
                <a href="{{ $item['href'] }}"
                    class="js-nav-link nav-link {{ !empty($item['active']) ? 'nav-link-active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="hidden items-center gap-4 lg:flex">
            <a href="#masuk" class="nav-link">Masuk</a>
            <a href="#daftar" class="btn-primary rounded-full px-6 py-3">Daftar Sekarang</a>
        </div>

        <details class="relative lg:hidden">
            <summary
                class="flex cursor-pointer list-none items-center justify-center rounded-2xl border border-slate-200 bg-white p-3 text-slate-700 shadow-sm">
                <x-landing.icon name="menu" class="h-6 w-6" />
            </summary>
            <div class="glass-panel absolute right-0 mt-3 w-[min(20rem,calc(100vw-2rem))] p-4">
                <div class="mb-3 flex items-center justify-between border-b border-slate-200 pb-3">
                    <span class="text-sm font-semibold text-slate-500">Navigasi</span>
                    <span
                        class="rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700">Menu</span>
                </div>
                <div class="flex flex-col gap-3">
                    @foreach ($navigation as $item)
                        <a href="{{ $item['href'] }}"
                            class="rounded-2xl px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    <a href="#masuk"
                        class="rounded-2xl px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Masuk</a>
                    <a href="#daftar" class="btn-primary justify-center rounded-2xl px-4 py-3">Daftar Sekarang</a>
                </div>
            </div>
        </details>
    </div>
</header>