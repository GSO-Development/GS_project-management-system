# NexusPM – Permission Matrix

| Action | Super Admin | Project Manager | Team Member |
|---|---|---|---|
| **Subsidiaries** | | | |
| View all subsidiaries | ✅ | ❌ | ❌ |
| Create subsidiary | ✅ | ❌ | ❌ |
| Edit subsidiary | ✅ | ❌ | ❌ |
| Archive/restore subsidiary | ✅ | ❌ | ❌ |
| **Users** | | | |
| View all users | ✅ | ❌ | ❌ |
| Create user | ✅ | ❌ | ❌ |
| Edit user | ✅ | ❌ | ❌ |
| Activate/deactivate user | ✅ | ❌ | ❌ |
| Assign roles | ✅ | ❌ | ❌ |
| **Projects** | | | |
| View all projects | ✅ | ❌ | ❌ |
| View assigned projects | ✅ | ✅ | ✅ |
| Create project | ✅ | ❌ | ❌ |
| Edit project | ✅ | ✅ (limited) | ❌ |
| Archive/restore project | ✅ | ❌ | ❌ |
| **WBS** | | | |
| View WBS | ✅ | ✅ | ✅ |
| Create WBS items | ✅ | ✅ | ❌ |
| Edit WBS items | ✅ | ✅ | ❌ |
| Delete/archive WBS items | ✅ | ✅ | ❌ |
| Reorder WBS items | ✅ | ✅ | ❌ |
| Submit WBS for approval | ❌ | ✅ | ❌ |
| Approve WBS | ✅ | ❌ | ❌ |
| Set WBS baseline | ✅ | ❌ | ❌ |
| **Tasks** | | | |
| Update task progress (leaf) | ✅ | ✅ | ✅ (assigned) |
| Update task status | ✅ | ✅ | ✅ (assigned) |
| Move Kanban cards | ✅ | ✅ | ✅ (assigned) |
| **Approvals** | | | |
| Submit approval requests | ❌ | ✅ | ❌ |
| Approve/reject requests | ✅ | ❌ | ❌ |
| **Reports** | | | |
| View all reports | ✅ | ❌ | ❌ |
| View own project reports | ✅ | ✅ | ❌ |
| **Audit Logs** | | | |
| View audit logs | ✅ | ❌ | ❌ |
| **Settings** | | | |
| Manage system settings | ✅ | ❌ | ❌ |

## Enforcement Strategy
- Laravel Middleware (auth, role check)
- Laravel Policies (per-model)
- Form Request Validation (per-request)
- Livewire component guards (never trust properties without authorization)
- Never rely solely on hiding UI elements
