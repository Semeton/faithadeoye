<?php

use App\Models\Message;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Messages')] class extends Component {

    public ?int $viewingId = null;

    #[Computed]
    public function messages(): \Illuminate\Database\Eloquent\Collection
    {
        return Message::orderByDesc('received_at')->get();
    }

    #[Computed]
    public function viewing(): ?Message
    {
        return $this->viewingId ? Message::find($this->viewingId) : null;
    }

    public function open(int $id): void
    {
        $this->viewingId = $id;
        Message::findOrFail($id)->update(['is_read' => true]);
        unset($this->messages, $this->viewing);
    }

    public function markUnread(int $id): void
    {
        Message::findOrFail($id)->update(['is_read' => false]);
        unset($this->messages);
    }

    public function delete(int $id): void
    {
        Message::findOrFail($id)->delete();
        if ($this->viewingId === $id) {
            $this->viewingId = null;
        }
        Flux::toast(text: 'Message deleted.');
        unset($this->messages, $this->viewing);
    }

    public function markAllRead(): void
    {
        Message::unread()->update(['is_read' => true]);
        unset($this->messages);
        Flux::toast(variant: 'success', text: 'All messages marked as read.');
    }
}; ?>

<div>
    <flux:main>
        <div class="flex items-center justify-between mb-8">
            <div>
                <flux:heading size="xl" class="mb-1">Messages</flux:heading>
                <flux:subheading>Contact form submissions from the public site.</flux:subheading>
            </div>
            @if($this->messages->where('is_read', false)->count() > 0)
                <flux:button wire:click="markAllRead" variant="ghost" size="sm">Mark all read</flux:button>
            @endif
        </div>

        <div class="grid lg:grid-cols-5 gap-6">

            {{-- Message list --}}
            <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 divide-y divide-zinc-100 dark:divide-zinc-800 overflow-hidden">
                @forelse($this->messages as $msg)
                    <button wire:click="open({{ $msg->id }})"
                            class="w-full text-left px-4 py-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors
                                   {{ $viewingId === $msg->id ? 'bg-zinc-50 dark:bg-zinc-800/50' : '' }}
                                   {{ !$msg->is_read ? 'border-l-2 border-l-blue-500' : '' }}">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm font-semibold text-zinc-900 dark:text-white truncate">{{ $msg->name }}</p>
                            <span class="text-xs text-zinc-400 shrink-0 ml-2">{{ $msg->received_at->format('d M') }}</span>
                        </div>
                        <p class="text-xs text-zinc-500 truncate mb-1">{{ $msg->email }}</p>
                        <p class="text-xs text-zinc-400 truncate">{{ $msg->subject ?: $msg->body }}</p>
                    </button>
                @empty
                    <div class="px-4 py-12 text-center text-sm text-zinc-400">No messages yet.</div>
                @endforelse
            </div>

            {{-- Message detail --}}
            <div class="lg:col-span-3">
                @if($this->viewing)
                    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                        <div class="flex items-start justify-between gap-4 mb-6">
                            <div>
                                <flux:heading size="lg">{{ $this->viewing->name }}</flux:heading>
                                <a href="mailto:{{ $this->viewing->email }}" class="text-sm text-blue-500 hover:underline">
                                    {{ $this->viewing->email }}
                                </a>
                                @if($this->viewing->subject)
                                    <p class="text-sm text-zinc-500 mt-1"><span class="font-medium">Subject:</span> {{ $this->viewing->subject }}</p>
                                @endif
                                <p class="text-xs text-zinc-400 mt-1">{{ $this->viewing->received_at->format('d M Y, g:ia') }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <flux:button
                                    wire:click="markUnread({{ $this->viewing->id }})"
                                    variant="ghost" size="sm">
                                    Mark unread
                                </flux:button>
                                <a href="mailto:{{ $this->viewing->email }}?subject=Re: {{ $this->viewing->subject }}"
                                   class="inline-flex">
                                    <flux:button variant="primary" size="sm" icon="paper-airplane">Reply</flux:button>
                                </a>
                                <flux:button
                                    wire:click="delete({{ $this->viewing->id }})"
                                    wire:confirm="Delete this message?"
                                    variant="ghost" size="sm" icon="trash" />
                            </div>
                        </div>
                        <flux:separator class="mb-6" />
                        <p class="text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap leading-relaxed text-sm">{{ $this->viewing->body }}</p>
                    </div>
                @else
                    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-12 text-center text-zinc-400">
                        <flux:icon name="envelope" class="w-10 h-10 mx-auto mb-3 text-zinc-200 dark:text-zinc-700" />
                        <p class="text-sm">Select a message to read it</p>
                    </div>
                @endif
            </div>
        </div>
    </flux:main>
</div>
