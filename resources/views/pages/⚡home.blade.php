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
<section class="relative min-h-[92vh] bg-zinc-950 text-white flex items-center overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(255,255,255,0.04)_0%,_transparent_60%)] pointer-events-none"></div>
    <div class="max-w-6xl mx-auto px-6 py-24 w-full">
        <div class="max-w-3xl">

            {{-- Name + specialisms --}}
            <div class="flex flex-wrap items-center gap-3 mb-8">
                @php $photo = $this->settings->get('hero_photo')?->value; @endphp
                @if($photo)
                    <img src="{{ asset('storage/'.$photo) }}" alt="{{ $this->settings->get('hero_name')?->value ?? 'Faith O. Adeoye' }}"
                         class="w-12 h-12 rounded-full object-cover ring-2 ring-white/20" />
                @endif
                <div>
                    <p class="text-zinc-400 text-sm tracking-widest uppercase font-medium">
                        {{ $this->settings->get('hero_tenure')?->value ?? '7+ Years in the Field' }}
                    </p>
                </div>
            </div>

            {{-- Specialisms tags --}}
            <div class="flex flex-wrap gap-2 mb-10">
                @foreach(explode(',', $this->settings->get('hero_specialisms')?->value ?? 'B2B, B2C, DTC, SaaS, Marketplace') as $tag)
                    <span class="text-xs font-medium px-3 py-1 rounded-full border border-white/20 text-zinc-300">
                        {{ trim($tag) }}
                    </span>
                @endforeach
            </div>

            {{-- Headline --}}
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.1] tracking-tight mb-6">
                {{ $this->settings->get('hero_headline')?->value ?? 'Your product solves a problem. I make sure the right people know it.' }}
            </h1>

            {{-- Subtext --}}
            <p class="text-lg sm:text-xl text-zinc-400 leading-relaxed max-w-2xl mb-12">
                {{ $this->settings->get('hero_subtext')?->value ?? 'From positioning and GTM strategy to messaging that converts, I bridge the gap between what a product does and why a customer needs it.' }}
            </p>

            {{-- CTAs --}}
            <div class="flex flex-wrap gap-4 items-center">
                <a href="#projects"
                   class="inline-flex items-center gap-2 bg-white text-zinc-900 font-semibold px-6 py-3 rounded-lg hover:bg-zinc-100 transition-colors text-sm">
                    {{ $this->settings->get('hero_cta_primary_label')?->value ?? 'View my work' }}
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/></svg>
                </a>
                <a href="#contact"
                   class="inline-flex items-center gap-2 border border-white/30 text-white font-medium px-6 py-3 rounded-lg hover:bg-white/10 transition-colors text-sm">
                    {{ $this->settings->get('hero_cta_secondary_label')?->value ?? 'Get in touch' }}
                </a>
                @php $linkedinUrl = $this->settings->get('hero_linkedin_url')?->value; @endphp
                @if($linkedinUrl)
                    <a href="{{ $linkedinUrl }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 text-zinc-400 hover:text-white transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        LinkedIn
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg class="w-5 h-5 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
        </svg>
    </div>
</section>

{{-- =================== CREDIBILITY =================== --}}
<section class="bg-white border-b border-zinc-100 py-10">
    <div class="max-w-6xl mx-auto px-6">
        <p class="text-center text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-8">
            {{ $this->settings->get('credibility_tagline')?->value ?? 'Driven strategy for the most recognisable names across Africa, US, UK and beyond.' }}
        </p>
        <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-4">
            @foreach(explode(',', $this->settings->get('credibility_clients')?->value ?? '') as $client)
                @if(trim($client))
                    <span class="text-sm font-semibold text-zinc-400 hover:text-zinc-700 transition-colors cursor-default tracking-wide">
                        {{ trim($client) }}
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
<section class="bg-white py-24">
    <div class="max-w-6xl mx-auto px-6">
        <div class="mb-14">
            <p class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-3">The Journey</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900">
                {{ $this->settings->get('career_headline')?->value ?? 'From an agency intern to a full-funnel product marketing specialist' }}
            </h2>
            <p class="mt-3 text-zinc-500">{{ $this->settings->get('career_subtext')?->value ?? 'Here\'s how the story unfolded.' }}</p>
        </div>

        {{-- Timeline --}}
        <div class="relative">
            {{-- Horizontal line on desktop --}}
            <div class="hidden lg:block absolute top-5 left-0 right-0 h-px bg-zinc-200"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min(count($this->milestones), 7) }} gap-6 lg:gap-4">
                @foreach($this->milestones as $i => $milestone)
                    <div class="relative flex flex-col lg:items-center">
                        {{-- Dot --}}
                        <div class="relative z-10 w-10 h-10 rounded-full border-2 border-zinc-200 bg-white flex items-center justify-center mb-4 shrink-0
                                    {{ $loop->last ? 'border-zinc-900 bg-zinc-900' : '' }}">
                            <span class="text-xs font-bold {{ $loop->last ? 'text-white' : 'text-zinc-400' }}">
                                {{ $i + 1 }}
                            </span>
                        </div>
                        <div class="lg:text-center">
                            <p class="text-xs text-zinc-400 font-medium mb-1">{{ $milestone->period }}</p>
                            <p class="text-sm font-semibold text-zinc-900 leading-snug">{{ $milestone->role }}</p>
                            @if($milestone->company)
                                <p class="text-xs text-zinc-500 mt-0.5">{{ $milestone->company }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
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
