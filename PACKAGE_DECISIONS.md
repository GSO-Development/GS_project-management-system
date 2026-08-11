# NexusPM – Package Decisions

## PHP Packages

| Package | Version | Purpose | Status |
|---|---|---|---|
| laravel/breeze | ^2.3 | Authentication scaffold (Blade stack) | To Install |
| livewire/livewire | ^3.6 | Reactive server-side UI | To Install |
| spatie/laravel-permission | ^6.10 | Role & permission management | To Install |
| spatie/laravel-activitylog | ^4.9 | Activity/audit logging | To Install |
| barryvdh/laravel-dompdf | ^3.0 | PDF generation | To Install |
| maatwebsite/excel | ^4.1 | Excel & CSV export | To Install |
| pestphp/pest | ^3.8 | Testing framework | To Install |
| pestphp/pest-plugin-laravel | ^3.2 | Laravel-specific Pest helpers | To Install |

## NPM Packages

| Package | Version | Purpose | Status |
|---|---|---|---|
| alpinejs | ^3.14 | Lightweight JS interactions | To Install |
| sortablejs | ^1.15 | Drag-and-drop (Kanban, WBS) | To Install |
| chart.js | ^4.4 | Charts on dashboards | To Install |
| @tailwindcss/forms | ^0.5 | Better form styling with Tailwind | To Install |
| @tailwindcss/typography | ^0.5 | Rich text typography | To Install |

## Compatibility Notes

- **Laravel 12** requires PHP ^8.2 – confirmed ✅
- **Livewire 3** supports Laravel 12 – confirmed ✅
- **Spatie Permission v6** supports Laravel 12 – confirmed ✅
- **Spatie Activitylog v4** supports Laravel 12 – confirmed ✅
- **Tailwind CSS 4** (already installed as @tailwindcss/vite) – ✅
- **laravel-excel ^4.1** supports Laravel 12 + PHP 8.2 – confirmed ✅
- **laravel-dompdf ^3.0** supports PHP 8.2 – confirmed ✅

## Excluded Packages (per requirements)
- Inertia.js – NOT installed
- React / Vue – NOT installed
- Firebase / Supabase – NOT used
- Any abandoned or unmaintained package
