@props(['name', 'class' => 'h-5 w-5'])

@php
    $paths = [
        'briefcase' => 'M10 6V5a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v1m-8 0h8a2 2 0 0 1 2 2v2H6V8a2 2 0 0 1 2-2Zm-2 4h12v7a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-7Zm4 1v2m4-2v2',
        'search' => 'm21 21-4.3-4.3m1.3-5.2a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z',
        'pin' => 'M12 21s6-5.2 6-11a6 6 0 1 0-12 0c0 5.8 6 11 6 11Zm0-8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z',
        'users' => 'M17 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m14-10a4 4 0 1 0-8 0 4 4 0 0 0 8 0Zm6 10v-2a4 4 0 0 0-3-3.9M15 3.1a4 4 0 0 1 0 7.8',
        'shield-check' => 'M12 22s8-3.6 8-10V5l-8-3-8 3v7c0 6.4 8 10 8 10Zm-3.5-9 2.3 2.3L16 8.8',
        'chart-up' => 'm3 17 6-6 4 4 7-8m-4 0h4v4',
        'check' => 'M5 13l4 4L19 7',
        'clock' => 'M12 6v6l4 2m6 0a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z',
        'building' => 'M4 21V7a2 2 0 0 1 2-2h4v16H4Zm8 0V3h6a2 2 0 0 1 2 2v16h-8Zm-4-8h2m6-4h2m-2 4h2m-8-4h2m-2 4h2',
        'phone' => 'M6.5 3.5 9 6l-2 2c1.8 3.5 4.5 6.2 8 8l2-2 2.5 2.5c.5.5.5 1.3 0 1.8-1 1-2.2 1.7-3.5 2-8.3 1.9-16.4-6.2-14.5-14.5.3-1.3 1-2.5 2-3.5.5-.5 1.3-.5 1.8 0Z',
        'mail' => 'M4 6h16v12H4z M4 6l8 7 8-7',
        'arrow-right' => 'm13 5 7 7-7 7M20 12H4',
        'menu' => 'M4 6h16M4 12h16M4 18h16',
    ];
@endphp

<svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    <path d="{{ $paths[$name] ?? $paths['check'] }}"></path>
</svg>
