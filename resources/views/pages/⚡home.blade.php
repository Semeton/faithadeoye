<?php

use App\Mail\ContactMessageReceived;
use App\Models\CareerMilestone;
use App\Models\ImpactArea;
use App\Models\Message;
use App\Models\Project;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts.public')] #[Title('Faith O. Adeoye — Product Marketing & Content Specialist')] class extends Component {

    #[Validate('required|string|max:100')]
    public string $contactName = '';

    #[Validate('required|email|max:150')]
    public string $contactEmail = '';

    #[Validate('nullable|string|max:150')]
    public string $contactSubject = '';

    #[Validate('required|string|min:10|max:2000')]
    public string $contactBody = '';

    public bool $messageSent = false;

    #[Computed]
    public function settings(): \Illuminate\Database\Eloquent\Collection
    {
        return SiteSetting::all()->keyBy('key');
    }

    #[Computed]
    public function featuredProjects(): \Illuminate\Database\Eloquent\Collection
    {
        return Project::featured()->get();
    }

    #[Computed]
    public function milestones(): \Illuminate\Database\Eloquent\Collection
    {
        return CareerMilestone::orderBy('sort_order')->get();
    }

    #[Computed]
    public function impactAreas(): \Illuminate\Database\Eloquent\Collection
    {
        return ImpactArea::orderBy('sort_order')->get();
    }

    public function sendMessage(): void
    {
        $key = 'contact.'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('contactBody', 'Too many messages. Please try again later.');

            return;
        }

        $this->validate();

        RateLimiter::hit($key, 60);

        $message = Message::create([
            'name' => $this->contactName,
            'email' => $this->contactEmail,
            'subject' => $this->contactSubject ?: null,
            'body' => $this->contactBody,
            'ip_hash' => hash('sha256', request()->ip()),
            'received_at' => now(),
        ]);

        $adminEmail = SiteSetting::get('contact_email', 'faithadeoye@gmail.com');
        Mail::to($adminEmail)->queue(new ContactMessageReceived($message));

        $this->reset('contactName', 'contactEmail', 'contactSubject', 'contactBody');
        $this->messageSent = true;
    }
}; ?>

<div>
{{-- =================== HERO =================== --}}
@php
    $photo       = $this->settings->get('hero_photo')?->value;
    $heroName    = $this->settings->get('hero_name')?->value ?? 'Faith O. Adeoye';
    $linkedinUrl = $this->settings->get('hero_linkedin_url')?->value;
@endphp
<section class="relative min-h-[92vh] bg-zinc-950 text-white flex items-center overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_rgba(245,158,11,0.04)_0%,_transparent_50%)] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-6 py-24 w-full">
        <div class="grid lg:grid-cols-2 gap-16 lg:gap-20 items-center">

            {{-- Left: copy --}}
            <div class="order-2 lg:order-1">

                {{-- Tenure badge --}}
                <div class="inline-flex items-center gap-2 mb-8 px-3 py-1.5 rounded-full border border-white/10 bg-white/5 text-xs font-semibold tracking-widest uppercase text-zinc-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>
                    {{ $this->settings->get('hero_tenure')?->value ?? '7+ Years in the Field' }}
                </div>

                {{-- Headline --}}
                <h1 class="text-4xl sm:text-5xl lg:text-[3.25rem] font-bold leading-[1.08] tracking-tight mb-6">
                    {{ $this->settings->get('hero_headline')?->value ?? 'Your product solves a problem. I make sure the right people know it.' }}
                </h1>

                {{-- Subtext --}}
                <p class="text-base sm:text-lg text-zinc-400 leading-relaxed mb-8">
                    {{ $this->settings->get('hero_subtext')?->value ?? 'From positioning and GTM strategy to messaging that converts, I bridge the gap between what a product does and why a customer needs it.' }}
                </p>

                {{-- Specialisms --}}
                <div class="flex flex-wrap gap-2 mb-10">
                    @foreach(explode(',', $this->settings->get('hero_specialisms')?->value ?? 'B2B, B2C, DTC, SaaS, Marketplace') as $tag)
                        <span class="text-xs font-medium px-3 py-1 rounded-full border border-white/15 text-zinc-300 bg-white/5">
                            {{ trim($tag) }}
                        </span>
                    @endforeach
                </div>

                {{-- CTAs --}}
                <div class="flex flex-wrap gap-3 items-center">
                    <a href="#projects"
                       class="inline-flex items-center gap-2 bg-white text-zinc-900 font-semibold px-6 py-3 rounded-lg hover:bg-zinc-100 transition-colors text-sm">
                        {{ $this->settings->get('hero_cta_primary_label')?->value ?? 'View my work' }}
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/></svg>
                    </a>
                    <a href="#contact"
                       class="inline-flex items-center gap-2 border border-white/25 text-white font-medium px-6 py-3 rounded-lg hover:bg-white/8 transition-colors text-sm">
                        {{ $this->settings->get('hero_cta_secondary_label')?->value ?? 'Get in touch' }}
                    </a>
                    @if($linkedinUrl)
                        <a href="{{ $linkedinUrl }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 text-zinc-500 hover:text-white transition-colors text-sm font-medium">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            LinkedIn
                        </a>
                    @endif
                </div>
            </div>

            {{-- Right: photo --}}
            <div class="order-1 lg:order-2 flex justify-center lg:justify-end">
                @if($photo)
                    <div class="relative w-72 h-72 sm:w-80 sm:h-80 lg:w-[26rem] lg:h-[26rem]">
                        {{-- Decorative amber ring --}}
                        <div class="absolute -inset-3 rounded-full border border-amber-400/20"></div>
                        <div class="absolute -inset-6 rounded-full border border-white/5"></div>
                        {{-- Photo --}}
                        <img src="{{ asset('storage/'.$photo) }}"
                             alt="{{ $heroName }}"
                             class="w-full h-full rounded-full object-cover object-top grayscale contrast-110 brightness-90 ring-1 ring-white/10" />
                        {{-- Subtle gradient overlay at the bottom to blend into the dark bg --}}
                        <div class="absolute inset-x-0 bottom-0 h-1/4 rounded-b-full bg-gradient-to-t from-zinc-950/60 to-transparent pointer-events-none"></div>
                        {{-- Name badge --}}
                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 whitespace-nowrap bg-zinc-900 border border-zinc-800 rounded-full px-5 py-2 text-sm font-semibold text-white shadow-xl">
                            {{ $heroName }}
                        </div>
                    </div>
                @else
                    {{-- Placeholder when no photo is set --}}
                    <div class="relative w-72 h-72 sm:w-80 sm:h-80 lg:w-96 lg:h-96 rounded-full bg-zinc-900 border border-zinc-800 flex flex-col items-center justify-center gap-2">
                        <svg class="w-10 h-10 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        <p class="text-xs text-zinc-600">Upload photo via admin</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg class="w-5 h-5 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
        </svg>
    </div>
</section>

{{-- =================== CREDIBILITY =================== --}}
@php $clients = $this->settings->get('credibility_clients')?->value ?? ''; @endphp
<section class="bg-white border-y border-zinc-100 py-14 overflow-hidden">

    {{-- Tagline --}}
    <div class="max-w-6xl mx-auto px-6 text-center mb-10">
        <p class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-3">Worked With</p>
        <p class="text-xl sm:text-2xl font-semibold text-zinc-800 max-w-xl mx-auto leading-snug">
            {{ $this->settings->get('credibility_tagline')?->value ?? 'Driven strategy for the most recognisable names across Africa, US, UK and beyond.' }}
        </p>
    </div>

    {{-- Scrolling ticker --}}
    <div class="relative flex">
        {{-- Gradient masks --}}
        <div class="pointer-events-none absolute left-0 top-0 bottom-0 w-28 z-10 bg-gradient-to-r from-white to-transparent"></div>
        <div class="pointer-events-none absolute right-0 top-0 bottom-0 w-28 z-10 bg-gradient-to-l from-white to-transparent"></div>

        {{-- Two identical tracks side-by-side; CSS moves them left by 50% for a seamless loop --}}
        <div class="flex shrink-0 w-full" style="animation: marquee 28s linear infinite;">
            {{-- Track A --}}
            @foreach(array_merge(explode(',', $clients), explode(',', $clients)) as $client)
                @if(trim($client))
                    <span class="inline-flex items-center gap-6 px-6 whitespace-nowrap">
                        <span class="text-sm font-bold tracking-wide text-zinc-400 uppercase">{{ trim($client) }}</span>
                        <span class="w-1 h-1 rounded-full bg-zinc-200 shrink-0"></span>
                    </span>
                @endif
            @endforeach
        </div>
    </div>
</section>

{{-- =================== IMPACT AREAS =================== --}}
<section class="bg-zinc-50 py-24">
    <div class="max-w-6xl mx-auto px-6">
        <div class="mb-14">
            <p class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-3">What I Do</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900">Built for impact. Measured in results.</h2>
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            @foreach($this->impactAreas as $area)
                <div class="bg-white rounded-2xl p-8 border border-zinc-200 hover:border-zinc-300 hover:shadow-sm transition-all group"
                     x-data="{ open: false }">
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">{{ $area->title }}</h3>
                    <p class="text-sm text-zinc-500 mb-6 leading-relaxed">{{ $area->tagline }}</p>
                    <ul class="space-y-2 text-sm text-zinc-600 leading-relaxed" x-show="open" x-collapse>
                        @foreach($area->bullets as $bullet)
                            <li class="flex gap-2">
                                <span class="text-zinc-300 mt-1 shrink-0">—</span>
                                <span>{{ $bullet }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <button @click="open = !open"
                            class="mt-6 text-xs font-semibold tracking-wide text-zinc-400 hover:text-zinc-900 transition-colors flex items-center gap-1">
                        <span x-text="open ? 'Show less' : 'See details'"></span>
                        <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- =================== CAREER TIMELINE =================== --}}
<section class="bg-white py-24 overflow-hidden">
    <div class="max-w-5xl mx-auto px-6">

        {{-- Header --}}
        <div class="mb-16 max-w-2xl">
            <p class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-3">The Journey</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900">
                {{ $this->settings->get('career_headline')?->value ?? 'From an agency intern to a full-funnel product marketing specialist' }}
            </h2>
            <p class="mt-4 text-zinc-500 leading-relaxed">
                {{ $this->settings->get('career_subtext')?->value ?? "Here's how the story unfolded." }}
            </p>
        </div>

        {{-- ── Zigzag timeline ─────────────────────────────────────── --}}
        <div class="relative">

            {{-- Centre spine (desktop) --}}
            <div class="hidden lg:block absolute left-1/2 -translate-x-px top-3 bottom-3 w-0.5
                         bg-gradient-to-b from-zinc-100 via-zinc-300 to-amber-400 rounded-full"></div>

            {{-- Left spine (mobile) --}}
            <div class="lg:hidden absolute left-5 top-3 bottom-3 w-0.5
                         bg-gradient-to-b from-zinc-100 via-zinc-300 to-amber-400 rounded-full"></div>

            <div class="space-y-0">
                @foreach($this->milestones as $i => $milestone)
                @php $isLeft = $i % 2 === 0; @endphp

                <div class="relative {{ $loop->last ? 'pb-0' : 'pb-10 lg:pb-14' }}">

                    {{-- ── Mobile layout (single column) ── --}}
                    <div class="flex gap-0 lg:hidden">
                        {{-- Dot column --}}
                        <div class="w-10 shrink-0 flex flex-col items-center relative z-10 pt-0.5">
                            @if($loop->last)
                                <span class="relative flex h-6 w-6">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-30" style="animation-duration:2.2s"></span>
                                    <span class="relative flex h-6 w-6 rounded-full bg-amber-400 border-2 border-white shadow-lg shadow-amber-200/60"></span>
                                </span>
                            @elseif($loop->first)
                                <span class="h-5 w-5 rounded-full bg-zinc-900 border-2 border-white shadow-sm flex items-center justify-center">
                                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                </span>
                            @else
                                <span class="h-3.5 w-3.5 rounded-full border-2 border-zinc-200 bg-white mt-1"></span>
                            @endif
                        </div>
                        {{-- Content --}}
                        <div class="flex-1 pb-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-xs font-semibold tracking-widest uppercase
                                             {{ $loop->last ? 'text-amber-500' : 'text-zinc-400' }}">
                                    {{ $milestone->period }}
                                </span>
                                @if($loop->last)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 border border-amber-200 font-bold">Now</span>
                                @endif
                            </div>
                            <h3 class="font-bold leading-snug {{ $loop->last ? 'text-zinc-900 text-lg' : 'text-zinc-700 text-base' }}">
                                {{ $milestone->role }}
                            </h3>
                            @if($milestone->company)
                                <p class="text-sm text-zinc-500 mt-0.5">{{ $milestone->company }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- ── Desktop layout (zigzag) ── --}}
                    <div class="hidden lg:grid lg:grid-cols-[1fr_80px_1fr] items-start">

                        {{-- Left content --}}
                        <div class="{{ $isLeft ? 'pr-10 text-right' : '' }}">
                            @if($isLeft)
                                <div class="inline-flex flex-row-reverse flex-wrap items-center gap-2 mb-1">
                                    <span class="text-xs font-semibold tracking-widest uppercase
                                                 {{ $loop->last ? 'text-amber-500' : 'text-zinc-400' }}">
                                        {{ $milestone->period }}
                                    </span>
                                    @if($loop->last)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 border border-amber-200 font-bold">Now</span>
                                    @endif
                                </div>
                                <h3 class="font-bold leading-snug {{ $loop->last ? 'text-zinc-900 text-xl' : 'text-zinc-800 text-lg' }}">
                                    {{ $milestone->role }}
                                </h3>
                                @if($milestone->company)
                                    <p class="text-sm text-zinc-500 mt-0.5">{{ $milestone->company }}</p>
                                @endif
                            @endif
                        </div>

                        {{-- Centre dot --}}
                        <div class="flex justify-center pt-1 relative z-10">
                            @if($loop->last)
                                <span class="relative flex h-7 w-7 items-center justify-center">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-25" style="animation-duration:2.2s"></span>
                                    <span class="relative flex h-7 w-7 rounded-full bg-amber-400 border-[3px] border-white shadow-xl shadow-amber-300/40"></span>
                                </span>
                            @elseif($loop->first)
                                <span class="h-6 w-6 rounded-full bg-zinc-900 border-[3px] border-white shadow-md"></span>
                            @else
                                <span class="h-4 w-4 rounded-full border-2 border-zinc-200 bg-white mt-1.5 shadow-sm"></span>
                            @endif
                        </div>

                        {{-- Right content --}}
                        <div class="{{ !$isLeft ? 'pl-10' : '' }}">
                            @if(!$isLeft)
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="text-xs font-semibold tracking-widest uppercase
                                                 {{ $loop->last ? 'text-amber-500' : 'text-zinc-400' }}">
                                        {{ $milestone->period }}
                                    </span>
                                    @if($loop->last)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 border border-amber-200 font-bold">Now</span>
                                    @endif
                                </div>
                                <h3 class="font-bold leading-snug {{ $loop->last ? 'text-zinc-900 text-xl' : 'text-zinc-800 text-lg' }}">
                                    {{ $milestone->role }}
                                </h3>
                                @if($milestone->company)
                                    <p class="text-sm text-zinc-500 mt-0.5">{{ $milestone->company }}</p>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
        {{-- ─────────────────────────────────────────────────────────── --}}

    </div>
</section>

{{-- =================== FEATURED PROJECTS =================== --}}
<section id="projects" class="bg-zinc-950 py-24">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-14">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-zinc-500 mb-3">Selected Work</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-white">
                    {{ $this->settings->get('projects_section_heading')?->value ?? 'Every project started with a problem. Here\'s how I solved them.' }}
                </h2>
            </div>
            <a href="{{ route('projects') }}" wire:navigate
               class="shrink-0 inline-flex items-center gap-2 text-sm font-semibold text-zinc-400 hover:text-white transition-colors">
                View all projects
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/></svg>
            </a>
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            @forelse($this->featuredProjects as $project)
                <a href="{{ route('project.show', $project->slug) }}" wire:navigate
                   class="group block bg-zinc-900 rounded-2xl p-8 border border-zinc-800 hover:border-zinc-700 transition-all hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-zinc-800 text-zinc-400">
                            {{ $project->category }}
                        </span>
                        <span class="text-xs text-zinc-600">{{ $project->year }}</span>
                    </div>

                    <h3 class="text-xl font-bold text-white mb-1 group-hover:text-zinc-300 transition-colors">
                        {{ $project->title }}
                    </h3>
                    <p class="text-sm text-zinc-500 mb-6">{{ $project->company }} · {{ $project->country }}</p>

                    <p class="text-sm text-zinc-400 leading-relaxed line-clamp-3 mb-6">
                        {{ $project->the_problem }}
                    </p>

                    <div class="flex flex-wrap gap-2">
                        @foreach($project->skills_tags as $skill)
                            <span class="text-xs text-zinc-500 border border-zinc-800 rounded px-2 py-0.5">{{ $skill }}</span>
                        @endforeach
                    </div>
                </a>
            @empty
                <p class="text-zinc-500 col-span-2">Projects coming soon.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- =================== CONTACT =================== --}}
<section id="contact" class="bg-white py-24">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16 items-start">

            {{-- Left --}}
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-3">Get in touch</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 mb-6">
                    {{ $this->settings->get('contact_heading')?->value ?? 'Say Hello to Faith.' }}
                </h2>
                <p class="text-zinc-500 leading-relaxed mb-8">
                    Ready to discuss your next product launch, content strategy, or GTM plan? Drop a message and I'll get back to you.
                </p>
                <div class="space-y-4">
                    <a href="mailto:{{ $this->settings->get('contact_email')?->value ?? 'faithadeoye@gmail.com' }}"
                       class="flex items-center gap-3 text-sm font-medium text-zinc-700 hover:text-zinc-900 transition-colors group">
                        <span class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center group-hover:bg-zinc-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        </span>
                        {{ $this->settings->get('contact_email')?->value ?? 'faithadeoye@gmail.com' }}
                    </a>
                    @php $linkedinUrl = $this->settings->get('hero_linkedin_url')?->value; @endphp
                    @if($linkedinUrl)
                        <a href="{{ $linkedinUrl }}" target="_blank" rel="noopener noreferrer"
                           class="flex items-center gap-3 text-sm font-medium text-zinc-700 hover:text-zinc-900 transition-colors group">
                            <span class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center group-hover:bg-zinc-200 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </span>
                            LinkedIn
                        </a>
                    @endif
                </div>
            </div>

            {{-- Right: Form --}}
            <div>
                @if($messageSent)
                    <div class="bg-zinc-50 rounded-2xl p-10 text-center border border-zinc-200">
                        <div class="w-12 h-12 rounded-full bg-zinc-900 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900 mb-2">Message sent!</h3>
                        <p class="text-sm text-zinc-500">Thanks for reaching out. I'll get back to you soon.</p>
                        <button wire:click="$set('messageSent', false)"
                                class="mt-6 text-sm text-zinc-400 hover:text-zinc-700 transition-colors">
                            Send another message
                        </button>
                    </div>
                @else
                    <form wire:submit="sendMessage" class="space-y-5">
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                                <input wire:model="contactName" type="text" placeholder="Your name"
                                       class="w-full px-4 py-3 rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition" />
                                @error('contactName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input wire:model="contactEmail" type="email" placeholder="you@example.com"
                                       class="w-full px-4 py-3 rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition" />
                                @error('contactEmail') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1.5">Subject</label>
                            <input wire:model="contactSubject" type="text" placeholder="What's this about?"
                                   class="w-full px-4 py-3 rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                            <textarea wire:model="contactBody" rows="5" placeholder="Tell me about your project or opportunity..."
                                      class="w-full px-4 py-3 rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition resize-none"></textarea>
                            @error('contactBody') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                class="w-full bg-zinc-900 text-white font-semibold py-3 px-6 rounded-lg hover:bg-zinc-700 transition-colors text-sm flex items-center justify-center gap-2"
                                wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed">
                            <span wire:loading.remove>Send Message</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>
</div>
