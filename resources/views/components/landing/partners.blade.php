@php
    $dbPartners = \App\Models\Mitra::all();
    $useDb      = $dbPartners->isNotEmpty();

    $staticPartners = [
        ['name' => 'Global Persada',  'icon' => 'building'],
        ['name' => 'Industri Maju',   'icon' => 'briefcase'],
        ['name' => 'Mitra Abadi',     'icon' => 'shield-check'],
        ['name' => 'Unggul Jaya',     'icon' => 'chart-up'],
        ['name' => 'Sentra Karya',    'icon' => 'users'],
        ['name' => 'Logistik Cepat',  'icon' => 'clock'],
        ['name' => 'Teknologi Nusa',  'icon' => 'shield-check'],
        ['name' => 'Karya Mandiri',   'icon' => 'building'],
    ];
@endphp

<style>
    .partners-track {
        display: flex;
        width: max-content;
        will-change: transform;
    }
    .partners-track.is-animating {
        animation: partners-scroll var(--marquee-duration, 30s) linear infinite;
    }
    .partners-track:hover {
        animation-play-state: paused;
    }
    @keyframes partners-scroll {
        0%   { transform: translateX(0); }
        100% { transform: translateX(var(--marquee-offset, -50%)); }
    }
</style>

<section class="py-20 bg-white overflow-hidden border-t border-b border-slate-50">
    {{-- Section Label --}}
    <div class="max-w-7xl mx-auto px-4 mb-12">
        <div class="flex items-center gap-4">
            <div class="h-[1px] flex-grow bg-slate-200"></div>
            <h2 class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-[0.3em] whitespace-nowrap">
                Mitra Strategis &amp; Partner Terpercaya
            </h2>
            <div class="h-[1px] flex-grow bg-slate-200"></div>
        </div>
    </div>

    {{-- Marquee Wrapper --}}
    <div class="relative overflow-hidden" id="partners-outer">
        {{-- Fade edges --}}
        <div class="absolute inset-y-0 left-0 w-28 md:w-40 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-28 md:w-40 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>

        {{-- Track: ONE group of items — JS will clone this until it fills the screen --}}
        <div class="partners-track" id="partners-track">
            <div class="partners-group flex items-center" id="partners-seed">

                @if($useDb)
                    @foreach($dbPartners as $mitra)
                    <div class="group flex items-center gap-3 opacity-40 hover:opacity-100
                                transition-all duration-500 cursor-default mx-10 md:mx-14">
                        {{-- Logo box --}}
                        <div class="w-12 h-12 shrink-0 rounded-2xl bg-slate-50 border border-slate-100
                                    flex items-center justify-center p-2
                                    group-hover:bg-blue-50 group-hover:border-blue-100
                                    group-hover:shadow-md group-hover:shadow-blue-100/50
                                    transition-all duration-500">
                            <img src="{{ asset('storage/' . $mitra->logo_path) }}"
                                 alt="{{ $mitra->name }}"
                                 class="w-full h-full object-contain
                                        filter grayscale group-hover:grayscale-0
                                        transition-all duration-500">
                        </div>
                        {{-- Name --}}
                        <span class="text-base font-bold text-slate-600
                                     group-hover:text-[#003d7c]
                                     transition-colors duration-500 whitespace-nowrap tracking-tight">
                            {{ $mitra->name }}
                        </span>
                    </div>
                    @endforeach

                @else
                    @foreach($staticPartners as $partner)
                    <div class="group flex items-center gap-3 opacity-30 hover:opacity-100
                                transition-all duration-500 cursor-default mx-10 md:mx-14">
                        <div class="w-10 h-10 shrink-0 rounded-xl bg-slate-50
                                    flex items-center justify-center
                                    group-hover:bg-blue-50
                                    transition-colors duration-500">
                            <x-landing.icon :name="$partner['icon']"
                                class="w-5 h-5 text-slate-400 group-hover:text-[#003d7c] transition-colors duration-500" />
                        </div>
                        <span class="text-lg font-bold text-slate-700
                                     group-hover:text-[#003d7c]
                                     transition-colors duration-500 whitespace-nowrap tracking-tight">
                            {{ $partner['name'] }}
                        </span>
                    </div>
                    @endforeach
                @endif

            </div>{{-- /partners-seed --}}
        </div>{{-- /partners-track --}}
    </div>{{-- /partners-outer --}}
</section>

<script>
(function () {
    'use strict';

    function initPartners() {
        var outer  = document.getElementById('partners-outer');
        var track  = document.getElementById('partners-track');
        var seed   = document.getElementById('partners-seed');

        if (!outer || !track || !seed) return;

        // ── 1. Clone the seed group until the track is at least 3× the viewport width
        //       This guarantees smooth looping even with only 1 partner.
        var viewW  = window.innerWidth;
        var minW   = viewW * 3.5;          // need 3.5× viewport so loop never shows a gap

        // Remove any previously cloned copies (in case of resize re-init)
        track.querySelectorAll('.partners-clone').forEach(function (el) { el.remove(); });

        var seedW  = seed.offsetWidth;
        if (seedW === 0) return;           // not yet painted

        var copies = Math.ceil(minW / seedW) + 1;   // how many extra copies beyond the seed

        for (var i = 0; i < copies; i++) {
            var clone = seed.cloneNode(true);
            clone.id  = '';
            clone.classList.add('partners-clone');
            track.appendChild(clone);
        }

        // ── 2. Total track width after cloning
        var totalW   = track.scrollWidth;
        var oneSetW  = seed.offsetWidth;    // width of ONE set = the original seed

        // ── 3. Animation: scroll exactly one seed-width so the loop is perfectly seamless
        //       Translate from 0 → -oneSetW, then jump back to 0.
        var offset   = oneSetW;             // pixels to scroll per loop
        var speed    = 80;                  // px per second — increase = faster
        var duration = offset / speed;      // seconds

        track.style.setProperty('--marquee-offset', '-' + offset + 'px');
        track.style.setProperty('--marquee-duration', duration + 's');

        // Switch to pixel-based keyframe for rock-solid looping
        // We inject a dynamic @keyframes so the translate is exact to the pixel.
        var styleId = 'partners-keyframes';
        var existing = document.getElementById(styleId);
        if (existing) existing.remove();

        var style = document.createElement('style');
        style.id  = styleId;
        style.textContent =
            '@keyframes partners-scroll {' +
            '  0%   { transform: translateX(0px); }' +
            '  100% { transform: translateX(-' + offset + 'px); }' +
            '}';
        document.head.appendChild(style);

        track.classList.add('is-animating');
    }

    // Run after layout is available
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPartners);
    } else {
        // Small delay to allow images to affect layout
        setTimeout(initPartners, 100);
    }

    // Re-init on resize (debounced)
    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            var track = document.getElementById('partners-track');
            if (track) track.classList.remove('is-animating');
            initPartners();
        }, 200);
    });
})();
</script>