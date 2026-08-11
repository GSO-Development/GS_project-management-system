# NexusPM – UI Component Plan

## Design System

### Colors
- **Primary**: Indigo/Navy (`#3730a3`, `#1e1b4b`)
- **Accent**: Cyan (`#06b6d4`)
- **Success**: Emerald (`#10b981`)
- **Warning**: Amber (`#f59e0b`)
- **Danger**: Rose (`#f43f5e`)
- **Neutral**: Slate grays

### Typography
- Font: `Inter` (Google Fonts)
- Headings: Semi-bold, tracked
- Body: Regular weight, comfortable line-height

## Blade Components

### Layout Components
| Component | Path | Purpose |
|---|---|---|
| app-layout | layouts/app.blade.php | Main authenticated layout |
| guest-layout | layouts/guest.blade.php | Auth pages layout |
| x-sidebar | components/sidebar.blade.php | Main sidebar nav |
| x-topnav | components/topnav.blade.php | Top navigation bar |
| x-breadcrumb | components/breadcrumb.blade.php | Breadcrumb trail |

### UI Components
| Component | Path | Purpose |
|---|---|---|
| x-modal | components/modal.blade.php | Confirmation/form modals |
| x-toast | components/toast.blade.php | Toast notification system |
| x-badge | components/badge.blade.php | Status/priority badges |
| x-avatar | components/avatar.blade.php | User avatars with fallback |
| x-progress-bar | components/progress-bar.blade.php | Progress display |
| x-progress-ring | components/progress-ring.blade.php | Circular progress |
| x-skeleton | components/skeleton.blade.php | Loading skeletons |
| x-empty-state | components/empty-state.blade.php | Empty list states |
| x-stat-card | components/stat-card.blade.php | Dashboard stat cards |
| x-card | components/card.blade.php | Rounded content card |
| x-dropdown | components/dropdown.blade.php | Dropdown menus |
| x-button | components/button.blade.php | Styled buttons |
| x-input | components/input.blade.php | Form inputs |
| x-select | components/select.blade.php | Select dropdowns |
| x-textarea | components/textarea.blade.php | Textarea inputs |
| x-label | components/label.blade.php | Form labels |
| x-input-error | components/input-error.blade.php | Validation errors |
| x-confirm-modal | components/confirm-modal.blade.php | Delete confirmations |
| x-pagination | components/pagination.blade.php | Custom pagination |

## Livewire Components

### Admin Components
| Component | Purpose |
|---|---|
| SubsidiaryManager | Full CRUD for subsidiaries |
| UserManager | Full CRUD for users |
| ProjectManager | Full CRUD for projects |

### Dashboard Components
| Component | Purpose |
|---|---|
| SuperAdminDashboard | Stats, charts, recent activity |
| ProjectManagerDashboard | Assigned projects, tasks |
| TeamMemberDashboard | My tasks, workload |

### Project Components
| Component | Purpose |
|---|---|
| ProjectWorkspace | Tabbed project detail page |
| WbsTree | Expandable WBS tree-table |
| WbsItemForm | Create/edit WBS items |
| KanbanBoard | Drag-drop Kanban |
| GanttChart | Gantt visualization |
| CalendarView | Multi-view calendar |
| StatusUpdateForm | Create status updates |
| ApprovalForm | Submit change requests |
| CommentThread | Threaded comments |
| DocumentManager | File upload/management |
| RiskManager | Risk tracking |
| BlockerManager | Task blockers |
| NotificationDropdown | Real-time notification bell |
| GlobalSearch | Debounced search |

## Page Layouts

### Login Page
- Split-screen: left=branding/visual, right=form
- NexusPM logo + tagline
- Abstract geometric illustration
- Clean form with show/hide password
- Demo credentials (local env only)

### Dashboard Pages
- Top: stat cards row
- Middle: charts (2 col)
- Bottom: activity/task lists

### Project Workspace
- Sticky project header
- Horizontal tabs
- Tab content areas (WBS, Kanban, etc.)

### WBS Tree Table
- Sticky header columns
- Indented rows by level
- Expand/collapse buttons
- Inline edit triggers
- Actions column (dropdown)
