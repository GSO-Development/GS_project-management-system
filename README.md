# NexusPM – Enterprise Project Management Web Application

NexusPM is a centralized project planning, execution, monitoring, governance approval, and reporting web application built for multi-subsidiary organizations.

## Environment & Tech Stack
- **Backend Framework**: Laravel 12.64.0
- **PHP Version**: 8.2.12
- **Frontend Stack**: Laravel Blade, Livewire 3 / 4, Alpine.js, Tailwind CSS 4
- **Database**: MySQL (`project_m`)
- **Build Tool**: Vite 7
- **Authentication**: Laravel Breeze (Blade)
- **Role & Permission Management**: Spatie Laravel Permission
- **Activity & Audit Logs**: Spatie Laravel Activitylog
- **Charts & Drag-and-Drop**: Chart.js, SortableJS
- **PDF / Excel Exports**: Barryvdh Laravel-DomPDF, Maatwebsite Laravel-Excel
- **Automated Testing**: Pest v3 (33 test assertions passed)

---

## Local Development Demo Accounts

The database is seeded with realistic organizational demo data:

| Role | Email | Password | Allowed Access |
|---|---|---|---|
| **Super Admin** | `admin@nexuspm.local` | `Password@123` | Full organization-wide access, subsidiaries, users, all projects, approvals, audit logs, settings, reports |
| **Project Manager** | `manager@nexuspm.local` | `Password@123` | Assigned projects, WBS planning, Kanban, Gantt, status updates, approval submissions, risks, blockers |
| **Team Member** | `member@nexuspm.local` | `Password@123` | Assigned tasks, progress sliders, Kanban card moves (own tasks), task blockers, discussion comments |

---

## Key Features

1. **Multi-Subsidiary Management**: Subsidiary codes, contacts, logo management, performance metrics, soft deletes.
2. **User & Role Management**: Spatie RBAC integration, active/deactive status toggling, subsidiary assignments.
3. **WBS Planning**: Expandable tree table hierarchy (Phase &rarr; Work Package &rarr; Task &rarr; Subtask &rarr; Milestone), automatic WBS code recalculation (1.0, 1.1, 1.1.1, 1.1.1.1), drag-and-drop sort reordering.
4. **Automated Progress Calculation**: Equal Weight & Weighted Progress formulas. Leaf nodes update parent progress through all ancestor levels up to overall project progress.
5. **Task Dependencies**: Finish-to-Start, Start-to-Start, Finish-to-Finish, Start-to-Finish validation. Depth-First Search circular dependency and cross-project dependency prevention.
6. **Kanban & Gantt & Calendar**: Drag-and-drop SortableJS Kanban board with permission guards, interactive Gantt chart timeline with milestones, and calendar schedule.
7. **Approval Workflows**: Submissions for deadline extensions, budget changes, WBS baselines, project completions. Official project parameters update ONLY upon Super Admin approval.
8. **Secure Document Management**: Private storage outside public directory with role/project authorization checks for downloads.
9. **Executive Reporting & Export**: Real-time metric cards, DomPDF PDF download, and CSV data export.
10. **Security & Governance**: Spatie roles/permissions middleware, policies, CSRF, XSS protection, database transaction safety, and audit logs.

---

## Local Commands

### Start Development Server
```bash
php artisan serve
```
Application is running locally at **http://127.0.0.1:8000**.

### Run Automated Tests
```bash
php artisan test
```

### Build Vite Production Bundle
```bash
npm run build
```
