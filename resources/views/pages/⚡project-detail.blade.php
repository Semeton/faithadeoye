<?php

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.public')] class extends Component {

    public Project $project;

    public function mount(string $slug): void
    {
        $this->project = Project::where('slug', $slug)->where('published', true)->firstOrFail();
    }

    public function title(): string
    {
        return $this->project->title.' — Faith O. Adeoye';
    }
}; ?>

<div>
{{-- Hero --}}
<section class="bg-zinc-950 text-white pt-20 pb-16">
    <div class="max-w-4xl mx-auto px-6">
        <a href="{{ route('projects') }}" wire:navigate
           class="inline-flex items-center gap-2 text-zinc-500 hover:text-zinc-300 transition-colors text-sm mb-10">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18"/>
            </svg>
            All Projects
        </a>

        <div class="flex flex-wrap gap-3 items-center mb-6">
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-zinc-800 text-zinc-400">
                {{ $project->category }}
            </span>
            <span class="text-xs text-zinc-600">{{ $project->year }}</span>
        </div>

        <h1 class="text-4xl sm:text-5xl font-bold mb-4">{{ $project->title }}</h1>
        <p class="text-zinc-400 text-lg">{{ $project->company }} · {{ $project->country }}</p>
    </div>
</section>

{{-- Cover image --}}
@if($project->cover_image)
    <div class="bg-zinc-900">
        <div class="max-w-4xl mx-auto px-6">
            <img src="{{ asset('storage/'.$project->cover_image) }}" alt="{{ $project->title }}"
                 class="w-full rounded-b-2xl object-cover max-h-96" />
        </div>
    </div>
@endif

{{-- Body --}}
<section class="bg-white py-20">
    <div class="max-w-4xl mx-auto px-6">
        <div class="grid lg:grid-cols-3 gap-12">

            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-12">

                {{-- The Problem --}}
                <div>
                    <h2 class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-4">The Problem</h2>
                    <p class="text-lg text-zinc-700 leading-relaxed">{{ $project->the_problem }}</p>
                </div>

                {{-- What I Did --}}
                <div>
                    <h2 class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-6">What I Did</h2>
                    <ul class="space-y-4">
                        @foreach($project->what_i_did as $item)
                            <li class="flex gap-4">
                                <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-zinc-900 shrink-0"></span>
                                <span class="text-zinc-700 leading-relaxed">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-8">
                <div class="bg-zinc-50 rounded-2xl p-6 border border-zinc-100">
                    <h3 class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-5">Project Details</h3>
                    <dl class="space-y-4 text-sm">
                        <div>
                            <dt class="text-zinc-400 font-medium mb-0.5">Company</dt>
                            <dd class="text-zinc-900 font-semibold">{{ $project->company }}</dd>
                        </div>
                        <div>
                            <dt class="text-zinc-400 font-medium mb-0.5">Market</dt>
                            <dd class="text-zinc-900 font-semibold">{{ $project->country }}</dd>
                        </div>
                        <div>
                            <dt class="text-zinc-400 font-medium mb-0.5">Year</dt>
                            <dd class="text-zinc-900 font-semibold">{{ $project->year }}</dd>
                        </div>
                        <div>
                            <dt class="text-zinc-400 font-medium mb-0.5">Type</dt>
                            <dd class="text-zinc-900 font-semibold">{{ $project->category }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-zinc-50 rounded-2xl p-6 border border-zinc-100">
                    <h3 class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-5">Skills Applied</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($project->skills_tags as $skill)
                            <span class="text-xs font-medium text-zinc-600 border border-zinc-200 bg-white rounded-full px-3 py-1">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>

        {{-- Back + CTA --}}
        <div class="mt-20 pt-10 border-t border-zinc-100 flex flex-col sm:flex-row items-center justify-between gap-6">
            <a href="{{ route('projects') }}" wire:navigate
               class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18"/>
                </svg>
                Back to all projects
            </a>
            <a href="{{ route('home') }}#contact" wire:navigate
               class="inline-flex items-center gap-2 bg-zinc-900 text-white font-semibold px-6 py-3 rounded-lg hover:bg-zinc-700 transition-colors text-sm">
                Work with me
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
</div>
