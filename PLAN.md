# Faith O. Adeoye — Portfolio Implementation Plan

## Core Design Philosophy

Senior-level framing means the homepage leads with **impact and authority**, not a list of tasks. Projects are subordinated — discoverable but not the headline. The structure mirrors how senior hires are evaluated: *"What did you move?"* before *"What did you build?"*

---

## 1. Database Schema

### 1.1 Content & CMS

**`site_settings`** — key-value store for all editable content

| column | type | notes |
|---|---|---|
| `key` | string (unique) | e.g. `hero_headline`, `hero_subtext` |
| `value` | text | the editable content |
| `type` | enum | `text`, `richtext`, `image`, `url`, `boolean` |
| `group` | string | `hero`, `credibility`, `contact`, `seo`, `integrations` |
| `label` | string | human-readable label for admin UI |

**`projects`**

| column | type |
|---|---|
| `slug` | string (unique) |
| `category` | string (Website, SEO & Lead Gen, Brand, etc.) |
| `title` | string |
| `company` | string |
| `country` | string |
| `year` | string |
| `the_problem` | text |
| `what_i_did` | json (array of bullet points) |
| `skills_tags` | json (array) |
| `is_featured` | boolean |
| `sort_order` | integer |
| `published` | boolean |
| `cover_image` | string (path) |

**`career_milestones`**

| column | type |
|---|---|
| `period` | string (e.g. `2024 – 2025`) |
| `role` | string |
| `company` | string (optional) |
| `sort_order` | integer |

**`impact_areas`** — the "Practical Solutions" section

| column | type |
|---|---|
| `title` | string |
| `tagline` | string |
| `bullets` | json |
| `sort_order` | integer |

### 1.2 Analytics & Engagement

**`page_views`** — raw hit log

| column | type |
|---|---|
| `page` | string |
| `ip_hash` | string (hashed for privacy) |
| `user_agent` | string |
| `referrer` | string (nullable) |
| `country` | string (nullable) |
| `session_id` | string |
| `viewed_at` | timestamp |

**`messages`** — contact form submissions

| column | type |
|---|---|
| `name` | string |
| `email` | string |
| `subject` | string (nullable) |
| `body` | text |
| `is_read` | boolean |
| `ip_hash` | string |
| `received_at` | timestamp |

---

## 2. Routes & Pages

### 2.1 Public Site

| Route | Page | Notes |
|---|---|---|
| `/` | Homepage | Single scroll page |
| `/projects` | All Projects | Filterable by category |
| `/projects/{slug}` | Project Detail | Full case study |
| `POST /contact` | — | Form submission endpoint |

### 2.2 Admin (behind Fortify auth, prefix `/admin`)

| Route | Page |
|---|---|
| `/admin` | Dashboard — metrics overview |
| `/admin/content` | Content editor (section-by-section) |
| `/admin/projects` | Projects CRUD |
| `/admin/milestones` | Career timeline management |
| `/admin/impact-areas` | Practical solutions management |
| `/admin/messages` | Inbox — contact submissions |
| `/admin/analytics` | Charts, page views, referrers |
| `/admin/settings` | Integrations (GA4 ID, social links, SEO) |

---

## 3. Homepage Sections (Senior-first order)

### Section 1 — Navigation
Minimal: logo/name left, `Projects` + `Contact Me` right. Clean, confident.

### Section 2 — Hero (above the fold, impact-led)
- **Name + LinkedIn badge** (top right, pulled from settings)
- **Title**: Product Marketing & Content Specialist
- **Specialisms**: B2B · B2C · DTC · SaaS · Marketplace (pills/tags)
- **Tenure**: 7+ Years
- **Headline**: *"Your product solves a problem. I make sure the right people know it."*
- **Subtext**: GTM/positioning copy from portfolio
- **CTAs**: `View my work` (anchor to projects preview) | `Get in touch` (anchor to contact)

### Section 3 — Credibility Bar
*"Driven strategy for the most recognisable names across Africa, US, UK and beyond."*

Horizontally scrolling or static logo/name strip:
Anakle · Fundall · Jeetar · Faramove · Studio14 · Leatherback · TheChumEffect · Simius AI · FourthCanvas · SeamlessHR

### Section 4 — Impact Areas *(the senior differentiator)*
Replaces a traditional skills/services block. Four cards, each led by a bold impact statement, not a job description:

- **Product Marketing** — "Positioning products for the right market, at the right time"
- **Content Marketing & SEO** — "Building content systems that attract, educate, and convert"
- **Growth & Lifecycle** — "Turning users into loyal customers through targeted communication"
- **Brand & Campaign** — "Building brand presence and campaigns that cut through"

Each card expands or links to detail — skills listed as supporting tags, not headline items.

### Section 5 — Career Trajectory
Timeline strip: 2019 → Present. Minimal, horizontal scroll on mobile. Roles shown as progression, not a list. The arc matters more than each step.

| Period | Role |
|---|---|
| 2019 – 2020 | Social Media Intern |
| 2020 – 2021 | Content Associate |
| 2021 | Brand Engagement Manager |
| 2021 – 2023 | Content Marketing Associate |
| 2023 – 2024 | Senior Content Writer / Storyteller |
| 2024 – 2025 | Growth & Product-Content Marketing Specialist |
| 2025 – Present | Product Marketing Specialist |

### Section 6 — Selected Work *(projects as footnote)*
*"Every project started with a problem. Here's how I solved them."*

2–3 featured case study cards (controlled from admin). Each card shows: category tag, company, year, one-line problem, skills used. CTA: `View all projects →`

**Seeded projects from portfolio:**
1. **M-KOPA Website** — FourthCanvas · Kenya · 2023 · Website
2. **SeamlessHR Inbound Marketing** — SeamlessHR · Kenya/Nigeria/Ghana/Uganda · 2024–2025 · SEO & Lead Generation

### Section 7 — Contact
*"Say Hello to Faith."*

Simple form (name, email, message) + email address + LinkedIn link. Form submission stored in DB and triggers email notification.

- Email: faithadeoye@gmail.com
- LinkedIn: linkedin.com/in/faithadeoye

---

## 4. Projects Page

- Filter bar by category (Website / SEO & Lead Gen / Brand & Campaign / Content)
- Card grid — each card: cover image, category tag, company, country, year, problem summary
- Click → `/projects/{slug}` for full case study detail

---

## 5. Admin Module

### 5.1 Auth
Single admin account using **Fortify**. No public registration route. Login at `/admin/login`.

### 5.2 Dashboard
- Today / 7-day / 30-day page views
- Total messages, unread count
- Most visited pages
- Recent messages preview
- Quick links to each content section

### 5.3 Content Editor
Section-grouped form driven by `site_settings`:

- **Hero**: headline, subtext, CTA labels, profile photo, LinkedIn URL, tenure, specialisms
- **Credibility**: tagline, client names list
- **Contact**: section heading, email address
- **SEO**: site title, meta description, OG image
- **Integrations**: Google Analytics Measurement ID (GA4), any future script injections

Image fields use file upload → stored in `storage/public`.

### 5.4 Projects Manager
Full CRUD Livewire table:
- Create / edit / delete projects
- Toggle `published` and `is_featured`
- Drag-to-reorder (`sort_order`)
- Manage `what_i_did` bullets as a dynamic list
- Cover image upload

### 5.5 Milestones & Impact Areas
Simple ordered list editors — add / edit / delete / reorder entries.

### 5.6 Messages Inbox
Table of contact submissions. Mark read / unread. View full message. Delete. No reply from admin (link to email client instead).

### 5.7 Analytics
- Line chart: daily page views (30 / 90 days)
- Bar chart: top pages
- Referrer breakdown table
- Country breakdown (if geo-IP available)
- All powered by the `page_views` table — no external dependency required

**Google Analytics Integration**: when a GA4 Measurement ID is saved in settings, the layout injects the `gtag.js` script automatically. Other tools (Hotjar, Plausible, etc.) can be added via the same integration hook later.

---

## 6. Supporting Infrastructure

### Middleware — Page View Tracking
A `TrackPageView` middleware on all public routes:
- Hashes IP for privacy
- Records: page path, referrer, user_agent, session_id, timestamp
- Excludes admin routes and known bot user agents

### Contact Form
Livewire component → validates → stores `Message` → dispatches queued mail notification to Faith's email. Rate limited at 5 submissions per minute per IP.

### Image Management
`php artisan storage:link` — images stored in `storage/app/public`, served via `/storage`. No S3 needed initially; path is swappable to a cloud driver later.

---

## 7. Implementation Phases

### Phase 1 — Foundation
- [ ] Migrations for all tables (`site_settings`, `projects`, `career_milestones`, `impact_areas`, `page_views`, `messages`)
- [ ] Models + factories for each
- [ ] Fortify admin auth (single user, no registration)
- [ ] Seed default `site_settings` with Faith's copy from the portfolio
- [ ] Seed career milestones and impact areas

### Phase 2 — Public Site
- [ ] Layout, nav, footer
- [ ] Homepage — all 7 sections, content pulled from `site_settings`
- [ ] Projects index page with category filter
- [ ] Project detail page (`/projects/{slug}`)
- [ ] Contact form + message storage + email notification
- [ ] `TrackPageView` middleware wired to public routes

### Phase 3 — Admin
- [ ] Admin layout (sidebar nav, breadcrumbs, logout)
- [ ] Dashboard with stats cards + recent messages
- [ ] Content editor (grouped by section)
- [ ] Projects CRUD table (Livewire, sortable, image upload)
- [ ] Milestones + impact areas ordered list editors
- [ ] Messages inbox (read/unread/delete)
- [ ] Analytics page (line chart, top pages, referrers)

### Phase 4 — Polish & Integration
- [ ] GA4 script injection from `site_settings`
- [ ] LinkedIn badge rendering in hero
- [ ] Dynamic SEO meta tags per page
- [ ] Responsive QA (mobile, tablet, desktop)
- [ ] Pest feature tests for all public routes + admin actions

---

## Key Design Decisions

| Decision | Rationale |
|---|---|
| `site_settings` key-value table | Maximum flexibility — add new editable fields without schema changes |
| Impact areas before projects (Section 4 before Section 6) | Positions Faith as a strategist, not a task executor |
| Self-hosted analytics first | No GDPR/cookie consent overhead; GA4 is additive when needed |
| Livewire for all admin UI | Consistent with stack; reactive without JS complexity |
| Single admin user, no registration | Simplest secure setup for a personal portfolio |
| Projects as seeded data + admin-managed | Easy to update without code changes |
