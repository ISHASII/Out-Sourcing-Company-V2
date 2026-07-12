@props(['eyebrow', 'title', 'description' => null, 'align' => 'center'])

@php
    $isLeft = $align === 'left';
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col gap-5 ' . ($isLeft ? 'items-start text-left' : 'items-center text-center')]) }}>
    <div class="flex items-center gap-3 {{ $isLeft ? 'justify-start' : 'justify-center' }}">
        <span class="h-1.5 w-14 rounded-full bg-primary-600"></span>
        <span class="text-sm font-bold uppercase tracking-[0.22em] text-primary-600">{{ $eyebrow }}</span>
        <span class="h-1.5 w-14 rounded-full bg-primary-600"></span>
    </div>

    <h2
        class="max-w-4xl text-3xl font-extrabold leading-tight tracking-tight text-slate-950 sm:text-4xl lg:text-5xl {{ $isLeft ? '' : 'mx-auto' }}">
        {{ $title }}
    </h2>

    @if ($description)
        <p class="max-w-3xl text-base leading-8 text-slate-600 sm:text-lg {{ $isLeft ? '' : 'mx-auto' }}">
            {{ $description }}
        </p>
    @endif
</div>