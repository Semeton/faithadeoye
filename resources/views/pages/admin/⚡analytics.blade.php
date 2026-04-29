<?php

use App\Models\PageView;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Analytics')] class extends Component {

    public int $days = 30;

    #[Computed]
    public function dailyViews(): array
    {
        $start = now()->subDays($this->days - 1)->startOfDay();

        $rows = PageView::where('viewed_at', '>=', $start)
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $result = [];
        for ($i = $this->days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $result[$date] = $rows[$date] ?? 0;
        }

        return $result;
    }

    #[Computed]
    public function topPages(): \Illuminate\Support\Collection
    {
        return PageView::forPeriod($this->days)
            ->selectRaw('page, COUNT(*) as total')
            ->groupBy('page')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    #[Computed]
    public function topReferrers(): \Illuminate\Support\Collection
    {
        return PageView::forPeriod($this->days)
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->selectRaw('referrer, COUNT(*) as total')
            ->groupBy('referrer')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    #[Computed]
    public function totalViews(): int
    {
        return array_sum($this->dailyViews);
    }

    #[Computed]
    public function maxDaily(): int
    {
        return max(1, max($this->dailyViews));
    }
}; ?>

<div>
    <flux:main>
        <div class="flex items-center justify-between mb-8">
            <div>
                <flux:heading size="xl" class="mb-1">Analytics</flux:heading>
                <flux:subheading>Self-hosted page view data. No cookies, no third-party tracking required.</flux:subheading>
            </div>
            <flux:select wire:model.live="days" class="w-36">
                <flux:select.option value="7">Last 7 days</flux:select.option>
                <flux:select.option value="30">Last 30 days</flux:select.option>
                <flux:select.option value="90">Last 90 days</flux:select.option>
            </flux:select>
        </div>

        {{-- Summary stat --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-1">Total Views</p>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($this->totalViews) }}</p>
                <p class="text-xs text-zinc-400 mt-1">Last {{ $days }} days</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-1">Daily Average</p>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($this->totalViews / $days, 1) }}</p>
                <p class="text-xs text-zinc-400 mt-1">views / day</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5 col-span-2 sm:col-span-1">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-1">Peak Day</p>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($this->maxDaily) }}</p>
                <p class="text-xs text-zinc-400 mt-1">views in a single day</p>
            </div>
        </div>

        {{-- Bar chart --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 mb-8">
            <flux:heading size="md" class="mb-6">Daily Page Views</flux:heading>
            <div class="flex items-end gap-1 h-40">
                @foreach($this->dailyViews as $date => $count)
                    <div class="flex-1 flex flex-col items-center gap-1 group relative">
                        <div class="w-full bg-zinc-900 dark:bg-zinc-100 rounded-sm transition-all"
                             style="height: {{ $this->maxDaily > 0 ? round(($count / $this->maxDaily) * 100) : 0 }}%"
                             title="{{ $count }} views on {{ $date }}">
                        </div>
                        {{-- Tooltip --}}
                        <div class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-zinc-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap z-10">
                            {{ $count }} · {{ \Carbon\Carbon::parse($date)->format('d M') }}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-2 text-xs text-zinc-400">
                <span>{{ \Carbon\Carbon::parse(array_key_first($this->dailyViews))->format('d M') }}</span>
                <span>{{ \Carbon\Carbon::parse(array_key_last($this->dailyViews))->format('d M') }}</span>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">

            {{-- Top pages --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading size="md" class="mb-5">Top Pages</flux:heading>
                <div class="space-y-3">
                    @forelse($this->topPages as $row)
                        @php $pct = $this->totalViews > 0 ? round(($row->total / $this->totalViews) * 100) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="font-medium text-zinc-700 dark:text-zinc-300 truncate">{{ $row->page }}</span>
                                <span class="text-zinc-500 shrink-0 ml-2">{{ number_format($row->total) }}</span>
                            </div>
                            <div class="h-1.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                <div class="h-full bg-zinc-900 dark:bg-zinc-100 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-400">No data yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Top referrers --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading size="md" class="mb-5">Top Referrers</flux:heading>
                <div class="space-y-3">
                    @forelse($this->topReferrers as $row)
                        @php $pct = $this->totalViews > 0 ? round(($row->total / $this->totalViews) * 100) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="font-medium text-zinc-700 dark:text-zinc-300 truncate text-xs">{{ $row->referrer }}</span>
                                <span class="text-zinc-500 shrink-0 ml-2">{{ number_format($row->total) }}</span>
                            </div>
                            <div class="h-1.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                <div class="h-full bg-zinc-900 dark:bg-zinc-100 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-400">No referrer data yet — direct traffic only.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </flux:main>
</div>
