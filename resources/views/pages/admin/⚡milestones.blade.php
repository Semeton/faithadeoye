<?php

use App\Models\CareerMilestone;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Career Timeline')] class extends Component {

    public ?int $editingId = null;
    public string $period = '';
    public string $role = '';
    public string $company = '';
    public bool $showForm = false;

    #[Computed]
    public function milestones(): \Illuminate\Database\Eloquent\Collection
    {
        return CareerMilestone::orderBy('sort_order')->get();
    }

    public function create(): void
    {
        $this->editingId = null;
        $this->period = '';
        $this->role = '';
        $this->company = '';
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $m = CareerMilestone::findOrFail($id);
        $this->editingId = $id;
        $this->period = $m->period;
        $this->role = $m->role;
        $this->company = $m->company ?? '';
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'period' => 'required|string|max:50',
            'role'   => 'required|string|max:150',
            'company'=> 'nullable|string|max:150',
        ]);

        $data = ['period' => $this->period, 'role' => $this->role, 'company' => $this->company ?: null];

        if ($this->editingId) {
            CareerMilestone::findOrFail($this->editingId)->update($data);
            Flux::toast(variant: 'success', text: 'Milestone updated.');
        } else {
            $data['sort_order'] = CareerMilestone::max('sort_order') + 1;
            CareerMilestone::create($data);
            Flux::toast(variant: 'success', text: 'Milestone added.');
        }

        $this->showForm = false;
        unset($this->milestones);
    }

    public function delete(int $id): void
    {
        CareerMilestone::findOrFail($id)->delete();
        Flux::toast(text: 'Milestone deleted.');
        unset($this->milestones);
    }

    public function moveUp(int $id): void
    {
        $current = CareerMilestone::findOrFail($id);
        $prev = CareerMilestone::where('sort_order', '<', $current->sort_order)->orderByDesc('sort_order')->first();
        if ($prev) {
            [$current->sort_order, $prev->sort_order] = [$prev->sort_order, $current->sort_order];
            $current->save();
            $prev->save();
            unset($this->milestones);
        }
    }

    public function moveDown(int $id): void
    {
        $current = CareerMilestone::findOrFail($id);
        $next = CareerMilestone::where('sort_order', '>', $current->sort_order)->orderBy('sort_order')->first();
        if ($next) {
            [$current->sort_order, $next->sort_order] = [$next->sort_order, $current->sort_order];
            $current->save();
            $next->save();
            unset($this->milestones);
        }
    }
}; ?>

<div>
    <flux:main>
        <div class="flex items-center justify-between mb-8">
            <div>
                <flux:heading size="xl" class="mb-1">Career Timeline</flux:heading>
                <flux:subheading>Manage the milestones shown on the homepage.</flux:subheading>
            </div>
            <flux:button wire:click="create" variant="primary" icon="plus">Add Milestone</flux:button>
        </div>

        @if($showForm)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 mb-8">
                <flux:heading size="lg" class="mb-5">{{ $editingId ? 'Edit' : 'New' }} Milestone</flux:heading>
                <div class="grid sm:grid-cols-3 gap-5">
                    <flux:field>
                        <flux:label>Period <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="period" placeholder="2024 – 2025" />
                        <flux:error name="period" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Role <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="role" placeholder="Senior Content Writer" />
                        <flux:error name="role" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Company</flux:label>
                        <flux:input wire:model="company" placeholder="Optional" />
                    </flux:field>
                </div>
                <div class="mt-5 flex items-center justify-end gap-3">
                    <flux:button wire:click="$set('showForm', false)" variant="ghost">Cancel</flux:button>
                    <flux:button wire:click="save" variant="primary">{{ $editingId ? 'Update' : 'Add' }}</flux:button>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($this->milestones as $milestone)
                <div class="flex items-center gap-4 px-5 py-4">
                    <div class="flex flex-col gap-1">
                        <flux:button wire:click="moveUp({{ $milestone->id }})" variant="ghost" size="sm" icon="chevron-up" class="p-0.5 h-5 w-5" />
                        <flux:button wire:click="moveDown({{ $milestone->id }})" variant="ghost" size="sm" icon="chevron-down" class="p-0.5 h-5 w-5" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-zinc-900 dark:text-white text-sm">{{ $milestone->role }}</p>
                        <p class="text-xs text-zinc-500">{{ $milestone->period }}{{ $milestone->company ? ' · '.$milestone->company : '' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:button wire:click="edit({{ $milestone->id }})" variant="ghost" size="sm" icon="pencil" />
                        <flux:button wire:click="delete({{ $milestone->id }})" wire:confirm="Delete this milestone?" variant="ghost" size="sm" icon="trash" />
                    </div>
                </div>
            @empty
                <div class="px-5 py-12 text-center text-zinc-400 text-sm">No milestones yet.</div>
            @endforelse
        </div>
    </flux:main>
</div>
