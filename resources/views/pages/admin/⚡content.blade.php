<?php

use App\Models\SiteSetting;
use Flux\Flux;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Site Content')] class extends Component {
    use WithFileUploads;

    public array $fields = [];
    public $heroPhoto = null;
    public $ogImage = null;

    public function mount(): void
    {
        $this->fields = SiteSetting::all()->keyBy('key')->map(fn ($s) => $s->value)->toArray();
    }

    public function save(string $group): void
    {
        foreach (SiteSetting::where('group', $group)->get() as $setting) {
            if (array_key_exists($setting->key, $this->fields)) {
                $setting->update(['value' => $this->fields[$setting->key]]);
            }
        }

        if ($group === 'hero' && $this->heroPhoto) {
            $this->validate(['heroPhoto' => 'image|max:2048']);
            $path = $this->heroPhoto->store('settings', 'public');
            SiteSetting::set('hero_photo', $path);
            $this->fields['hero_photo'] = $path;
            $this->heroPhoto = null;
        }

        if ($group === 'seo' && $this->ogImage) {
            $this->validate(['ogImage' => 'image|max:2048']);
            $path = $this->ogImage->store('settings', 'public');
            SiteSetting::set('seo_og_image', $path);
            $this->fields['seo_og_image'] = $path;
            $this->ogImage = null;
        }

        Flux::toast(variant: 'success', text: 'Saved.');
    }
}; ?>

<div>
    <flux:main>
        <flux:heading size="xl" class="mb-1">Site Content</flux:heading>
        <flux:subheading class="mb-8">Edit text and images shown on the public site. Each section saves independently.</flux:subheading>

        <div class="space-y-8">

            {{-- HERO --}}
            <section class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading size="lg" class="mb-5">Hero</flux:heading>
                <div class="grid sm:grid-cols-2 gap-5">
                    <flux:field>
                        <flux:label>Name</flux:label>
                        <flux:input wire:model="fields.hero_name" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Title / Role</flux:label>
                        <flux:input wire:model="fields.hero_title" />
                    </flux:field>
                    <flux:field class="sm:col-span-2">
                        <flux:label>Headline</flux:label>
                        <flux:input wire:model="fields.hero_headline" />
                    </flux:field>
                    <flux:field class="sm:col-span-2">
                        <flux:label>Sub-text</flux:label>
                        <flux:textarea wire:model="fields.hero_subtext" rows="3" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Specialisms (comma-separated)</flux:label>
                        <flux:input wire:model="fields.hero_specialisms" placeholder="B2B, B2C, SaaS" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Tenure</flux:label>
                        <flux:input wire:model="fields.hero_tenure" placeholder="7+ Years in the Field" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Primary CTA Label</flux:label>
                        <flux:input wire:model="fields.hero_cta_primary_label" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Secondary CTA Label</flux:label>
                        <flux:input wire:model="fields.hero_cta_secondary_label" />
                    </flux:field>
                    <flux:field class="sm:col-span-2">
                        <flux:label>LinkedIn URL</flux:label>
                        <flux:input type="url" wire:model="fields.hero_linkedin_url" />
                    </flux:field>
                    <flux:field class="sm:col-span-2">
                        <flux:label>Profile Photo</flux:label>
                        @if(!empty($fields['hero_photo']))
                            <img src="{{ asset('storage/'.$fields['hero_photo']) }}" class="w-16 h-16 rounded-full object-cover mb-2" alt="Profile" />
                        @endif
                        <input type="file" wire:model="heroPhoto" accept="image/*" class="text-sm text-zinc-600 dark:text-zinc-400" />
                        @error('heroPhoto') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </flux:field>
                </div>
                <div class="mt-5 flex justify-end">
                    <flux:button wire:click="save('hero')" variant="primary" wire:loading.attr="disabled" wire:target="save('hero')">Save Hero</flux:button>
                </div>
            </section>

            {{-- CREDIBILITY --}}
            <section class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading size="lg" class="mb-5">Credibility Bar</flux:heading>
                <div class="space-y-4">
                    <flux:field>
                        <flux:label>Tagline</flux:label>
                        <flux:input wire:model="fields.credibility_tagline" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Clients (comma-separated)</flux:label>
                        <flux:textarea wire:model="fields.credibility_clients" rows="3" />
                    </flux:field>
                </div>
                <div class="mt-5 flex justify-end">
                    <flux:button wire:click="save('credibility')" variant="primary">Save</flux:button>
                </div>
            </section>

            {{-- CAREER --}}
            <section class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading size="lg" class="mb-5">Career Timeline</flux:heading>
                <div class="grid sm:grid-cols-2 gap-5">
                    <flux:field>
                        <flux:label>Headline</flux:label>
                        <flux:input wire:model="fields.career_headline" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Sub-text</flux:label>
                        <flux:input wire:model="fields.career_subtext" />
                    </flux:field>
                </div>
                <div class="mt-5 flex justify-end">
                    <flux:button wire:click="save('career')" variant="primary">Save</flux:button>
                </div>
            </section>

            {{-- CONTACT --}}
            <section class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading size="lg" class="mb-5">Contact Section</flux:heading>
                <div class="grid sm:grid-cols-2 gap-5">
                    <flux:field>
                        <flux:label>Section Heading</flux:label>
                        <flux:input wire:model="fields.contact_heading" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Email Address</flux:label>
                        <flux:input type="email" wire:model="fields.contact_email" />
                    </flux:field>
                </div>
                <div class="mt-5 flex justify-end">
                    <flux:button wire:click="save('contact')" variant="primary">Save</flux:button>
                </div>
            </section>

            {{-- SEO --}}
            <section class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading size="lg" class="mb-5">SEO</flux:heading>
                <div class="space-y-4">
                    <flux:field>
                        <flux:label>Site Title</flux:label>
                        <flux:input wire:model="fields.seo_title" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Meta Description</flux:label>
                        <flux:textarea wire:model="fields.seo_description" rows="3" />
                    </flux:field>
                    <flux:field>
                        <flux:label>OG Image</flux:label>
                        @if(!empty($fields['seo_og_image']))
                            <img src="{{ asset('storage/'.$fields['seo_og_image']) }}" class="h-20 rounded object-cover mb-2" alt="OG" />
                        @endif
                        <input type="file" wire:model="ogImage" accept="image/*" class="text-sm text-zinc-600 dark:text-zinc-400" />
                        @error('ogImage') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </flux:field>
                </div>
                <div class="mt-5 flex justify-end">
                    <flux:button wire:click="save('seo')" variant="primary">Save</flux:button>
                </div>
            </section>

            {{-- INTEGRATIONS --}}
            <section class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading size="lg" class="mb-1">Integrations</flux:heading>
                <flux:subheading class="mb-5">Third-party tracking. Scripts are injected automatically when an ID is set.</flux:subheading>
                <flux:field>
                    <flux:label>Google Analytics 4 Measurement ID</flux:label>
                    <flux:input wire:model="fields.integration_ga4_id" placeholder="G-XXXXXXXXXX" />
                    <flux:description>Leave empty to disable GA4 tracking.</flux:description>
                </flux:field>
                <div class="mt-5 flex justify-end">
                    <flux:button wire:click="save('integrations')" variant="primary">Save</flux:button>
                </div>
            </section>
        </div>
    </flux:main>
</div>
