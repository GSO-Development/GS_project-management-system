# NexusPM – Testing Plan

## Test Framework
- **Pest** v3 with Laravel plugin
- Feature tests for all critical workflows
- Unit tests for service classes

## Test Coverage

### Authentication Tests
- [ ] Valid login redirects to dashboard
- [ ] Invalid credentials show error
- [ ] Disabled account blocked
- [ ] Remember me works
- [ ] Logout clears session
- [ ] Throttle on failed attempts

### Role & Permission Tests
- [ ] Super Admin can access all admin routes
- [ ] Project Manager cannot access /subsidiaries
- [ ] Project Manager cannot access /users
- [ ] Team Member cannot access /projects (create)
- [ ] Team Member cannot access unassigned projects
- [ ] Unauthorized project access by ID manipulation blocked

### Subsidiary Tests
- [ ] Super Admin can create subsidiary
- [ ] Super Admin can edit subsidiary
- [ ] Super Admin can archive subsidiary
- [ ] Cannot delete subsidiary with projects

### Project Tests
- [ ] Project creation stores correctly
- [ ] Project update works for authorized users
- [ ] Project archive soft-deletes
- [ ] PM cannot access unassigned project
- [ ] Progress auto-calculates on WBS update

### WBS Tests
- [ ] Phase created directly under project
- [ ] Work Package created under Phase
- [ ] Task created under Work Package
- [ ] Subtask created under Task
- [ ] Invalid hierarchy prevented (Phase under Task)
- [ ] Circular dependency prevented
- [ ] WBS auto-numbering correct after create
- [ ] WBS auto-numbering correct after reorder
- [ ] Parent progress calculated (equal weight)
- [ ] Parent progress calculated (weighted)
- [ ] Duplicate dependency prevented

### Approval Tests
- [ ] PM can submit approval request
- [ ] Super Admin can approve request
- [ ] Super Admin can reject request
- [ ] PM cannot approve own request
- [ ] Deadline not updated before approval
- [ ] Deadline updated after approval
- [ ] Baseline immutable after approval

### Document Tests
- [ ] File upload validation (type, size)
- [ ] Private storage (not in public/)
- [ ] Download requires project access
- [ ] PM from other project cannot download

### Search Tests
- [ ] Search results filtered by permission
- [ ] PM only sees own project results
- [ ] Team Member only sees assigned task results

### Report Tests
- [ ] Report filters apply correctly
- [ ] PDF export generates correctly
- [ ] Excel export generates correctly

## Running Tests
```bash
php artisan test
```

or with Pest:
```bash
./vendor/bin/pest
```
