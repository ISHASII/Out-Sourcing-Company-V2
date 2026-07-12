@props(['stat'])

<div
    class="stat-card border-b border-white/10 last:border-b-0 lg:border-b-0 lg:border-r lg:last:border-r-0 lg:border-white/10">
    <div class="text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $stat['value'] }}</div>
    <div class="text-sm font-medium text-white/85 sm:text-base">{{ $stat['label'] }}</div>
</div>