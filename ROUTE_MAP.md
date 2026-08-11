# NexusPM – Route Map

## Public Routes (unauthenticated)
| Method | URI | Controller | Name |
|---|---|---|---|
| GET | / | → redirect to /login | - |
| GET | /login | AuthenticatedSessionController@create | login |
| POST | /login | AuthenticatedSessionController@store | - |
| GET | /forgot-password | PasswordResetLinkController@create | password.request |
| POST | /forgot-password | PasswordResetLinkController@store | password.email |
| GET | /reset-password/{token} | NewPasswordController@create | password.reset |
| POST | /reset-password | NewPasswordController@store | password.store |

## Authenticated Routes (all roles)
| Method | URI | Controller / Livewire | Name |
|---|---|---|---|
| POST | /logout | AuthenticatedSessionController@destroy | logout |
| GET | /dashboard | DashboardController@index | dashboard |
| GET | /profile | ProfileController@edit | profile.edit |
| PATCH | /profile | ProfileController@update | profile.update |
| DELETE | /profile | ProfileController@destroy | profile.destroy |
| GET | /notifications | NotificationController@index | notifications.index |
| POST | /notifications/{id}/read | NotificationController@markRead | notifications.read |
| POST | /notifications/read-all | NotificationController@markAllRead | notifications.readAll |
| GET | /search | SearchController@index | search.index |
| GET | /my-tasks | MyTasksController@index | my-tasks.index |
| GET | /calendar | CalendarController@index | calendar.index |

## Super Admin Only Routes
| Method | URI | Controller | Name |
|---|---|---|---|
| GET/POST | /subsidiaries | SubsidiaryController | subsidiaries.* |
| GET/POST | /users | UserController | users.* |
| GET | /audit-logs | AuditLogController@index | audit-logs.index |
| GET | /settings | SettingController@index | settings.index |
| GET | /reports | ReportController@index | reports.index |
| GET | /approvals | ApprovalController@index | approvals.index |
| POST | /approvals/{id}/approve | ApprovalController@approve | - |
| POST | /approvals/{id}/reject | ApprovalController@reject | - |

## Project Manager + Super Admin Routes
| Method | URI | Controller | Name |
|---|---|---|---|
| GET | /projects | ProjectController@index | projects.index |
| POST | /projects | ProjectController@store | projects.store |
| GET | /projects/{project} | ProjectController@show | projects.show |
| PATCH | /projects/{project} | ProjectController@update | projects.update |
| GET | /projects/{project}/wbs | WbsController@index | projects.wbs |
| GET | /projects/{project}/kanban | KanbanController@index | projects.kanban |
| GET | /projects/{project}/gantt | GanttController@index | projects.gantt |
| GET | /projects/{project}/reports | ProjectReportController@index | - |
| POST | /projects/{project}/status-updates | StatusUpdateController@store | - |
| POST | /projects/{project}/documents | DocumentController@store | - |
| GET | /projects/{project}/documents/{doc}/download | DocumentController@download | - |
| POST | /projects/{project}/risks | RiskController@store | - |
| POST | /wbs-items/{item}/blockers | BlockerController@store | - |

## Livewire Components (full-page or embedded)
| Component | Route / Embed | Purpose |
|---|---|---|
| SubsidiaryIndex | /subsidiaries | List + filter subsidiaries |
| SubsidiaryForm | /subsidiaries/create | Create/edit subsidiary |
| UserIndex | /users | List + filter users |
| UserForm | /users/create | Create/edit user |
| ProjectIndex | /projects | Grid/table/kanban view |
| ProjectForm | /projects/create | Create/edit project |
| ProjectDashboard | /projects/{id} | Full project workspace |
| WbsTree | embedded | Interactive WBS tree-table |
| KanbanBoard | /projects/{id}/kanban | Kanban with drag-drop |
| GanttChart | /projects/{id}/gantt | Gantt visualization |
| CalendarView | /calendar | Calendar with events |
| ApprovalList | /approvals | Review approval requests |
| NotificationList | /notifications | Notification management |
| GlobalSearch | embedded (topnav) | Search across records |
| ActivityLog | /audit-logs | Audit log viewer |
| ReportViewer | /reports | Report builder + export |
