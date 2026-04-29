<?php

use App\Models\Project;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Layout('layouts.public')] #[Title('My Projects — Faith O. Adeoye')] class extends Component {

    #[Url(as: 'category')]
    public string $activeCategory = '';

    #[Computed]
    public function categories(): array
    {
        return Project::where('published', true)
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();
    }

    #[Computed]
    public function projects(): \Illuminate\Database\Eloquent\Collection
    {
        return Project::published()
            ->when($this->activeCategory, fn ($q) => $q->where('category', $this->activeCategory))
            ->get();
    }

    public function filterBy(string $category): void
    {
        $this->activeCategory = $this->activeCategory === $category ? '' : $category;
    }
}; ?>

<div>
{{-- Hero --}}
<section class="bg-zinc-950 text-white pt-20 pb-16">
    <div class="max-w-6xl mx-auto px-6">
        <p class="text-xs font-semibold tracking-widest uppercase text-zinc-500 mb-4">Portfolio</p>
        <h1 class="text-4xl sm:text-5xl font-bold mb-4">My Projects</h1>
        <p class="text-zinc-400 text-lg max-w-xl">A selection of problems I've been brought in to solve — across strategy, content, SEO, and brand.</p>
    </div>
</section>

{{-- Filter + Grid --}}
<section class="bg-zinc-50 min-h-screen py-16">
    <div class="max-w-6xl mx-auto px-6">

        {{-- Category filters --}}
        <div class="flex flex-wrap gap-3 mb-12">
            <button wire:click="filterBy('')"
                    class="text-sm font-medium px-4 py-2 rounded-full transition-all
                           {{ $activeCategory === '' ? 'bg-zinc-900 text-white' : 'bg-white text-zinc-600 border border-zinc-200 hover:border-zinc-400' }}">
                All
            </button>
            @foreach($this->categories as $category)
                <button wire:click="filterBy('{{ $category }}')"
                        class="text-sm font-medium px-4 py-2 rounded-full transition-all
                               {{ $activeCategory === $category ? 'bg-zinc-900 text-white' : 'bg-white text-zinc-600 border border-zinc-200 hover:border-zinc-400' }}">
                    {{ $category }}
                </button>
            @endforeach
        </div>

        {{-- Project grid --}}
        <div class="grid sm:grid-cols-2 gap-6" wire:loading.class="opacity-60">
            @forelse($this->projects as $project)
                <a href="{{ route('project.show', $project->slug) }}" wire:navigate
                   class="group bg-white rounded-2xl border border-zinc-200 hover:border-zinc-400 hover:shadow-md transition-all overflow-hidden">

                    @if($project->cover_image)
                        <div class="aspect-video bg-zinc-100 overflow-hidden">
                            <img src="{{ asset('storage/'.$project->cover_image) }}" alt="{{ $project->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </div>
                    @else
                        <div class="aspect-video bg-gradient-to-br from-zinc-100 to-zinc-200 flex items-center justify-center">
                            <span class="text-3xl font-bold text-zinc-300">{{ mb_substr($project->company, 0, 1) }}</span>
                        </div>
                    @endif

                    <div class="p-8">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-zinc-100 text-zinc-500">
                                {{ $project->category }}
                            </span>
                            <span class="text-xs text-zinc-400">{{ $project->year }}</span>
                        </div>

                        <h2 class="text-xl font-bold text-zinc-900 mb-1 group-hover:text-zinc-600 transition-colors">
                            {{ $project->title }}
                        </h2>
                        <p class="text-sm text-zinc-500 mb-4">{{ $project->company }} · {{ $project->country }}</p>

                        <p class="text-sm text-zinc-600 leading-relaxed line-clamp-2 mb-6">
                            {{ $project->the_problem }}
                        </p>

                        <div class="flex flex-wrap gap-2">
                            @foreach($project->skills_tags as $skill)
                                <span class="text-xs text-zinc-500 border border-zinc-100 bg-zinc-50 rounded px-2 py-0.5">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-2 py-20 text-center">
                    <p class="text-zinc-400">No projects found in this category.</p>
                    <button wire:click="filterBy('')" class="mt-4 text-sm text-zinc-600 underline hover:text-zinc-900">
                        Clear filter
                    </button>
                </div>
            @endforelse
        </div>
    </div>
</section>
</div>
