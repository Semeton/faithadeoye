<?php

use App\Models\Project;
use Flux\Flux;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Projects')] class extends Component {
    use WithFileUploads;

    public bool $showForm = false;
    public ?int $editingId = null;

    #[Validate('required|string|max:150')]
    public string $title = '';

    #[Validate('required|string|max:100')]
    public string $category = '';

    #[Validate('required|string|max:100')]
    public string $company = '';

    #[Validate('required|string|max:150')]
    public string $country = '';

    #[Validate('required|string|max:20')]
    public string $year = '';

    #[Validate('required|string')]
    public string $the_problem = '';

    public array $what_i_did = [''];
    public string $skills_raw = '';
    public bool $is_featured = false;
    public bool $published = true;
    public $coverImage = null;

    #[Computed]
    public function projects(): \Illuminate\Database\Eloquent\Collection
    {
        return Project::orderBy('sort_order')->orderByDesc('id')->get();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function edit(int $id): void
    {
        $project = Project::findOrFail($id);
        $this->editingId = $id;
        $this->title = $project->title;
        $this->category = $project->category;
        $this->company = $project->company;
        $this->country = $project->country;
        $this->year = $project->year;
        $this->the_problem = $project->the_problem;
        $this->what_i_did = $project->what_i_did ?: [''];
        $this->skills_raw = implode(', ', $project->skills_tags ?? []);
        $this->is_featured = $project->is_featured;
        $this->published = $project->published;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'       => $this->title,
            'slug'        => Str::slug($this->title),
            'category'    => $this->category,
            'company'     => $this->company,
            'country'     => $this->country,
            'year'        => $this->year,
            'the_problem' => $this->the_problem,
            'what_i_did'  => array_values(array_filter($this->what_i_did)),
            'skills_tags' => array_map('trim', explode(',', $this->skills_raw)),
            'is_featured' => $this->is_featured,
            'published'   => $this->published,
        ];

        if ($this->coverImage) {
            $this->validate(['coverImage' => 'image|max:4096']);
            $data['cover_image'] = $this->coverImage->store('projects', 'public');
        }

        if ($this->editingId) {
            Project::findOrFail($this->editingId)->update($data);
            Flux::toast(variant: 'success', text: 'Project updated.');
        } else {
            $data['sort_order'] = Project::max('sort_order') + 1;
            Project::create($data);
            Flux::toast(variant: 'success', text: 'Project created.');
        }

        $this->showForm = false;
        unset($this->projects);
    }

    public function toggleFeatured(int $id): void
    {
        $project = Project::findOrFail($id);
        $project->update(['is_featured' => ! $project->is_featured]);
        unset($this->projects);
    }

    public function togglePublished(int $id): void
    {
        $project = Project::findOrFail($id);
        $project->update(['published' => ! $project->published]);
        unset($this->projects);
    }

    public function delete(int $id): void
    {
        Project::findOrFail($id)->delete();
        Flux::toast(text: 'Project deleted.');
        unset($this->projects);
    }

    public function addBullet(): void
    {
        $this->what_i_did[] = '';
    }

    public function removeBullet(int $index): void
    {
        array_splice($this->what_i_did, $index, 1);
    }

    private function resetForm(): void
    {
        $this->title = '';
        $this->category = '';
        $this->company = '';
        $this->country = '';
        $this->year = '';
        $this->the_problem = '';
        $this->what_i_did = [''];
        $this->skills_raw = '';
        $this->is_featured = false;
        $this->published = true;
        $this->coverImage = null;
        $this->resetValidation();
    }
}; ?>

<div>
    <flux:main>
        <div class="flex items-center justify-between mb-8">
            <div>
                <flux:heading size="xl" class="mb-1">Projects</flux:heading>
                <flux:subheading>Manage case studies shown on the public site.</flux:subheading>
            </div>
            <flux:button wire:click="create" variant="primary" icon="plus">Add Project</flux:button>
        </div>

        {{-- Form --}}
        @if($showForm)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 mb-8">
                <flux:heading size="lg" class="mb-5">{{ $editingId ? 'Edit Project' : 'New Project' }}</flux:heading>
                <div class="grid sm:grid-cols-2 gap-5">
                    <flux:field class="sm:col-span-2">
                        <flux:label>Title <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="title" />
                        <flux:error name="title" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Category <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="category" placeholder="SEO & Lead Generation" />
                        <flux:error name="category" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Year <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="year" placeholder="2024 – 2025" />
                        <flux:error name="year" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Company <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="company" />
                        <flux:error name="company" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Country / Market <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="country" placeholder="Nigeria, Kenya" />
                        <flux:error name="country" />
                    </flux:field>
                    <flux:field class="sm:col-span-2">
                        <flux:label>The Problem <span class="text-red-500">*</span></flux:label>
                        <flux:textarea wire:model="the_problem" rows="3" />
                        <flux:error name="the_problem" />
                    </flux:field>

                    {{-- What I did bullets --}}
                    <div class="sm:col-span-2">
                        <flux:label class="mb-2">What I Did</flux:label>
                        <div class="space-y-2">
                            @foreach($what_i_did as $i => $bullet)
                                <div class="flex gap-2">
                                    <flux:input wire:model="what_i_did.{{ $i }}" placeholder="Bullet point..." class="flex-1" />
                                    @if(count($what_i_did) > 1)
                                        <flux:button wire:click="removeBullet({{ $i }})" variant="ghost" icon="trash" size="sm" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <flux:button wire:click="addBullet" variant="ghost" icon="plus" size="sm" class="mt-2">Add bullet</flux:button>
                    </div>

                    <flux:field class="sm:col-span-2">
                        <flux:label>Skills / Tags (comma-separated)</flux:label>
                        <flux:input wire:model="skills_raw" placeholder="SEO, Copywriting, Research" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Cover Image</flux:label>
                        <input type="file" wire:model="coverImage" accept="image/*" class="text-sm text-zinc-600 dark:text-zinc-400" />
                        @error('coverImage') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </flux:field>
                    <div class="flex flex-col gap-3 justify-center">
                        <flux:checkbox wire:model="is_featured" label="Featured on homepage" />
                        <flux:checkbox wire:model="published" label="Published" />
                    </div>
                </div>
                <div class="mt-5 flex items-center justify-end gap-3">
                    <flux:button wire:click="$set('showForm', false)" variant="ghost">Cancel</flux:button>
                    <flux:button wire:click="save" variant="primary">{{ $editingId ? 'Update' : 'Create' }}</flux:button>
                </div>
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="text-left px-5 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Project</th>
                        <th class="text-left px-5 py-3 font-semibold text-zinc-600 dark:text-zinc-400 hidden sm:table-cell">Category</th>
                        <th class="text-left px-5 py-3 font-semibold text-zinc-600 dark:text-zinc-400 hidden md:table-cell">Year</th>
                        <th class="text-center px-5 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Featured</th>
                        <th class="text-center px-5 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Live</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($this->projects as $project)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-zinc-900 dark:text-white">{{ $project->title }}</p>
                                <p class="text-xs text-zinc-500">{{ $project->company }} · {{ $project->country }}</p>
                            </td>
                            <td class="px-5 py-4 text-zinc-600 dark:text-zinc-400 hidden sm:table-cell">{{ $project->category }}</td>
                            <td class="px-5 py-4 text-zinc-600 dark:text-zinc-400 hidden md:table-cell">{{ $project->year }}</td>
                            <td class="px-5 py-4 text-center">
                                <flux:switch wire:click="toggleFeatured({{ $project->id }})" :checked="$project->is_featured" />
                            </td>
                            <td class="px-5 py-4 text-center">
                                <flux:switch wire:click="togglePublished({{ $project->id }})" :checked="$project->published" />
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button wire:click="edit({{ $project->id }})" variant="ghost" size="sm" icon="pencil" />
                                    <flux:button wire:click="delete({{ $project->id }})" wire:confirm="Delete this project?" variant="ghost" size="sm" icon="trash" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-zinc-400">No projects yet. Click "Add Project" to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </flux:main>
</div>
