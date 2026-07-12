@props(['job'])

<div
    class="bg-white rounded-[1.25rem] border border-slate-200 p-5 transition-all hover:border-blue-100 hover:shadow-sm">
    <div class="flex items-start gap-4">
        <!-- Icon -->
        <div
            class="flex-shrink-0 w-12 h-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center">
            <x-landing.icon name="building" class="h-6 w-6 text-slate-400" />
        </div>

        <!-- Content Area -->
        <div class="flex-grow flex flex-col md:flex-row justify-between gap-4">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <h4 class="text-lg font-bold text-slate-900">{{ $job['title'] }}</h4>
                </div>
                <p class="text-sm font-medium text-slate-500">{{ $job['company'] }}</p>

                <div class="mt-4 flex flex-wrap gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 border border-slate-100 text-slate-500 rounded-lg text-xs font-medium whitespace-nowrap">
                        <x-landing.icon name="pin" class="h-3 w-3 text-slate-400" />
                        {{ $job['location'] }}
                    </span>
                    @if(!empty($job['type']))
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 border border-slate-100 text-slate-500 rounded-lg text-xs font-medium whitespace-nowrap">
                            <x-landing.icon name="briefcase" class="h-3 w-3 text-slate-400" />
                            {{ $job['type'] }}
                        </span>
                    @endif
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 border border-slate-100 text-slate-500 rounded-lg text-xs font-medium whitespace-nowrap">
                        <x-landing.icon name="clock" class="h-3 w-3 text-slate-400" />
                        {{ $job['posted'] }}
                    </span>
                </div>
            </div>

            <!-- Right Side: Salary & Action -->
            <div class="flex flex-col items-end justify-between min-w-max">
                @if(!empty($job['salary']))
                    <div class="text-sm font-bold text-slate-900">{{ $job['salary'] }}</div>
                @endif
                <a href="{{ $job['apply_url'] ?? route('login') }}"
                    class="mt-4 md:mt-0 px-8 py-2 bg-blue-50 text-blue-600 rounded-xl text-sm font-bold hover:bg-blue-600 hover:text-white transition-all">
                    Lamar
                </a>
            </div>
        </div>
    </div>
</div>
