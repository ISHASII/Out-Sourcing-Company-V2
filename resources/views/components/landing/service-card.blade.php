@props(['item'])

<article
    class="card group h-full transition-transform duration-300 hover:-translate-y-1 hover:shadow-[0_22px_50px_rgba(15,23,42,0.1)]">
    <div class="icon-box mb-6 group-hover:bg-primary-100">
        <x-landing.icon :name="$item['icon']" class="h-6 w-6" />
    </div>
    <h3 class="text-2xl font-bold tracking-tight text-slate-950">{{ $item['title'] }}</h3>
    <p class="mt-4 text-base leading-8 text-slate-600">{{ $item['description'] }}</p>
    <a href="#layanan"
        class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-primary-600 transition-colors duration-200 hover:text-primary-700">
        {{ $item['link'] }}
        <x-landing.icon name="arrow-right" class="h-4 w-4" />
    </a>
</article>