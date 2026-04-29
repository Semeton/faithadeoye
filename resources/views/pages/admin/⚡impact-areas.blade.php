<?php

use App\Models\ImpactArea;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Impact Areas')] class extends Component {

    public ?int $editingId = null;
    public bool $showForm = false;
    public string $title = '';
    public string $tagline = '';
    public array $bullets = [''];

    #[Computed]
    public function areas(): \Illuminate\Database\Eloquent\Collection
    {
        return ImpactArea::orderBy('sort_order')->get();
    }

    public function create(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->tagline = '';
        $this->bullets = [''];
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $area = ImpactArea::findOrFail($id);
        $this->editingId = $id;
        $this->title = $area->title;
        $this->tagline = $area->tagline;
        $this->bullets = $area->bullets ?: [''];
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'title'   => 'required|string|max:100',
            'tagline' => 'required|string|max:255',
            'bullets' => 'required|array|min:1',
            'bullets.*' => 'required|string',
        ]);

        $data = [
            'title'   => $this->title,
            'tagline' => $this->tagline,
            'bullets' => array_values(array_filter($this->bullets)),
        ];

        if ($this->editingId) {
            ImpactArea::findOrFail($this->editingId)->update($data);
            Flux::toast(variant: 'success', text: 'Impact area updated.');
        } else {
            $data['sort_order'] = ImpactArea::max('sort_order') + 1;
            ImpactArea::create($data);
            Flux::toast(variant: 'success', text: 'Impact area created.');
        }

        $this->showForm = false;
        unset($this->areas);
    }

    public function delete(int $id): void
    {
        ImpactArea::findOrFail($id)->delete();
        Flux::toast(text: 'Impact area deleted.');
        unset($this->areas);
    }

    public function addBullet(): void
    {
        $this->bullets[] = '';
    }

    public function removeBullet(int $i): void
    {
        array_splice($this->bullets, $i, 1);
    }

    public function moveUp(int $id): void
    {
        $current = ImpactArea::findOrFail($id);
        $prev = ImpactArea::where('sort_order', '<', $current->sort_order)->orderByDesc('sort_order')->first();
        if ($prev) {
            [$current->sort_order, $prev->sort_order] = [$prev->sort_order, $current->sort_order];
            $current->save();
            $prev->save();
            unset($this->areas);
        }
    }

    public function moveDown(int $id): void
    {
        $current = ImpactArea::findOrFail($id);
        $next = ImpactArea::where('sort_order', '>', $current->sort_order)->orderBy('sort_order')->first();
        if ($next) {
            [$current->sort_order, $next->sort_order] = [$next->sort_order, $current->sort_order];
            $current->save();
            $next->save();
            unset($this->areas);
        }
    }
}; ?>

<div>
    <flux:main>
        <div class="flex items-center justify-between mb-8">
            <div>
                <flux:heading size="xl" class="mb-1">Impact Areas</flux:heading>
                <flux:subheading>The four capability cards shown on the homepage.</flux:subheading>
            </div>
            <flux:button wire:click="create" variant="primary" icon="plus">Add Area</flux:button>
        </div>

        @if($showForm)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 mb-8">
                <flux:heading size="lg" class="mb-5">{{ $editingId ? 'Edit' : 'New' }} Impact Area</flux:heading>
                <div class="space-y-5">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <flux:field>
                            <flux:label>Title <span class="text-red-500">*</span></flux:label>
                            <flux:input wire:model="title" placeholder="Product Marketing" />
                            <flux:error name="title" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Tagline <span class="text-red-500">*</span></flux:label>
                            <flux:input wire:model="tagline" placeholder="Short punchy description" />
                            <flux:error name="tagline" />
                        </flux:field>
                    </div>

                    <div>
                        <flux:label class="mb-2">Bullet Points <span class="text-red-500">*</span></flux:label>
                        <div class="space-y-2">
                            @foreach($bullets as $i => $bullet)
                                <div class="flex gap-2">
                                    <flux:input wire:model="bullets.{{ $i }}" placeholder="Achievement or capability..." class="flex-1" />
                                    @if(count($bullets) > 1)
                                        <flux:button wire:click="removeBullet({{ $i }})" variant="ghost" icon="trash" size="sm" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <flux:button wire:click="addBullet" variant="ghost" icon="plus" size="sm" class="mt-2">Add bullet</flux:button>
                    </div>
                </div>
                <div class="mt-5 flex items-center justify-end gap-3">
                    <flux:button wire:click="$set('showForm', false)" variant="ghost">Cancel</flux:button>
                    <flux:button wire:click="save" variant="primary">{{ $editingId ? 'Update' : 'Create' }}</flux:button>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($this->areas as $area)
                <div class="flex items-start gap-4 px-5 py-5">
                    <div class="flex flex-col gap-1 mt-1">
                        <flux:button wire:click="moveUp({{ $area->id }})" variant="ghost" size="sm" icon="chevron-up" class="p-0.5 h-5 w-5" />
                        <flux:button wire:click="moveDown({{ $area->id }})" variant="ghost" size="sm" icon="chevron-down" class="p-0.5 h-5 w-5" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-zinc-900 dark:text-white text-sm mb-0.5">{{ $area->title }}</p>
                        <p class="text-xs text-zinc-500 mb-2">{{ $area->tagline }}</p>
                        <ul class="text-xs text-zinc-400 space-y-0.5 list-disc list-inside">
                            @foreach(array_slice($area->bullets, 0, 3) as $bullet)
                                <li class="truncate">{{ $bullet }}</li>
                            @endforeach
                            @if(count($area->bullets) > 3)
                                <li class="text-zinc-300">+{{ count($area->bullets) - 3 }} more</li>
                            @endif
                        </ul>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <flux:button wire:click="edit({{ $area->id }})" variant="ghost" size="sm" icon="pencil" />
                        <flux:button wire:click="delete({{ $area->id }})" wire:confirm="Delete this area?" variant="ghost" size="sm" icon="trash" />
                    </div>
                </div>
            @empty
                <div class="px-5 py-12 text-center text-zinc-400 text-sm">No impact areas yet.</div>
            @endforelse
        </div>
    </flux:main>
</div>
