<?php

use App\Models\Message;
use App\Models\PageView;
use App\Models\Project;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Dashboard')] class extends Component {

    #[Computed]
    public function stats(): array
    {
        return [
            'views_today'   => PageView::whereDate('viewed_at', today())->count(),
            'views_7d'      => PageView::forPeriod(7)->count(),
            'views_30d'     => PageView::forPeriod(30)->count(),
            'unread'        => Message::unread()->count(),
            'total_messages'=> Message::count(),
            'projects'      => Project::where('published', true)->count(),
        ];
    }

    #[Computed]
    public function recentMessages(): \Illuminate\Database\Eloquent\Collection
    {
        return Message::orderByDesc('received_at')->limit(5)->get();
    }
}; ?>

<div>
    <flux:main>
        <flux:heading size="xl" class="mb-1">Dashboard</flux:heading>
        <flux:subheading class="mb-8">Welcome back, {{ auth()->user()->name }}.</flux:subheading>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-1">Today's Views</p>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($this->stats['views_today']) }}</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-1">Last 7 Days</p>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($this->stats['views_7d']) }}</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-1">Last 30 Days</p>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($this->stats['views_30d']) }}</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-1">Unread Messages</p>
                <p class="text-3xl font-bold {{ $this->stats['unread'] > 0 ? 'text-red-500' : 'text-zinc-900 dark:text-white' }}">
                    {{ $this->stats['unread'] }}
                </p>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-1">Total Messages</p>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $this->stats['total_messages'] }}</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-1">Live Projects</p>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $this->stats['projects'] }}</p>
            </div>
        </div>

        {{-- Recent messages --}}
        <flux:heading size="lg" class="mb-4">Recent Messages</flux:heading>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($this->recentMessages as $msg)
                <div class="flex items-start gap-4 px-5 py-4 {{ !$msg->is_read ? 'bg-zinc-50 dark:bg-zinc-800/50' : '' }}">
                    <flux:avatar :name="$msg->name" size="sm" class="shrink-0 mt-0.5" />
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 mb-0.5">
                            <p class="text-sm font-semibold text-zinc-900 dark:text-white truncate">
                                {{ $msg->name }}
                                @if(!$msg->is_read)
                                    <flux:badge size="sm" color="blue" class="ml-2">New</flux:badge>
                                @endif
                            </p>
                            <span class="text-xs text-zinc-400 shrink-0">{{ $msg->received_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-zinc-500 truncate">{{ $msg->email }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1 line-clamp-2">{{ $msg->body }}</p>
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-sm text-zinc-400">No messages yet.</div>
            @endforelse
        </div>

        @if($this->stats['total_messages'] > 5)
            <div class="mt-3 text-right">
                <flux:link :href="route('admin.messages')" wire:navigate class="text-sm">
                    View all messages →
                </flux:link>
            </div>
        @endif
    </flux:main>
</div>
