# NexusPM – Database Design

## ER Diagram (Mermaid)

```mermaid
erDiagram
    users {
        bigint id PK
        string name
        string email
        string password
        string phone_number
        string profile_image
        bigint subsidiary_id FK
        boolean is_active
        timestamp last_login_at
        timestamps
        softDeletes
    }
    subsidiaries {
        bigint id PK
        string code
        string name
        string logo
        text description
        text address
        string contact_person
        string contact_email
        string contact_phone
        enum status
        timestamps
        softDeletes
    }
    projects {
        bigint id PK
        string code
        string name
        text description
        bigint subsidiary_id FK
        string category
        bigint project_manager_id FK
        bigint created_by FK
        enum priority
        date start_date
        date deadline
        enum status
        enum health
        decimal estimated_budget
        decimal actual_cost
        decimal estimated_hours
        decimal actual_hours
        integer overall_progress
        timestamps
        softDeletes
    }
    project_members {
        bigint id PK
        bigint project_id FK
        bigint user_id FK
        string role
        timestamps
    }
    wbs_items {
        bigint id PK
        bigint project_id FK
        bigint parent_id FK
        string wbs_code
        enum item_type
        string title
        text description
        bigint assigned_user_id FK
        date start_date
        date end_date
        integer duration
        enum status
        enum priority
        integer progress
        decimal estimated_hours
        decimal actual_hours
        decimal weight
        boolean is_milestone
        integer sort_order
        bigint created_by FK
        bigint updated_by FK
        timestamps
        softDeletes
    }
    wbs_dependencies {
        bigint id PK
        bigint predecessor_id FK
        bigint successor_id FK
        enum dependency_type
        integer lag_days
        timestamps
    }
    wbs_versions {
        bigint id PK
        bigint project_id FK
        integer version_number
        text change_summary
        bigint created_by FK
        enum status
        bigint reviewed_by FK
        timestamp submitted_at
        timestamp reviewed_at
        text review_comments
        timestamps
    }
    wbs_baselines {
        bigint id PK
        bigint project_id FK
        bigint wbs_version_id FK
        integer baseline_number
        bigint created_by FK
        bigint approved_by FK
        json baseline_data
        timestamps
    }
    approval_requests {
        bigint id PK
        bigint project_id FK
        enum request_type
        bigint requested_by FK
        json current_value
        json requested_value
        text reason
        enum status
        bigint reviewed_by FK
        timestamp submitted_at
        timestamp reviewed_at
        text review_comment
        timestamps
    }
    project_status_updates {
        bigint id PK
        bigint project_id FK
        string title
        text summary
        string reporting_period
        integer current_progress
        text work_completed
        text current_blockers
        text current_risks
        text next_steps
        enum updated_status
        bigint created_by FK
        timestamps
    }
    comments {
        bigint id PK
        bigint commentable_id
        string commentable_type
        bigint user_id FK
        bigint parent_id FK
        text content
        timestamps
        softDeletes
    }
    project_documents {
        bigint id PK
        bigint project_id FK
        bigint wbs_item_id FK
        string file_name
        string original_name
        string mime_type
        bigint file_size
        string storage_path
        bigint uploaded_by FK
        integer version
        text description
        timestamps
        softDeletes
    }
    project_risks {
        bigint id PK
        bigint project_id FK
        string title
        text description
        string category
        enum probability
        enum impact
        integer risk_score
        bigint owner_id FK
        text mitigation_plan
        text contingency_plan
        enum status
        timestamps
    }
    task_blockers {
        bigint id PK
        bigint wbs_item_id FK
        bigint reported_by FK
        text description
        enum severity
        text resolution
        bigint resolved_by FK
        timestamp resolved_at
        enum status
        timestamps
    }
    activity_logs {
        bigint id PK
        bigint user_id FK
        string action
        string module
        string record_type
        bigint record_id
        json previous_values
        json new_values
        string ip_address
        string user_agent
        timestamps
    }
    system_settings {
        bigint id PK
        string key
        text value
        string group
        timestamps
    }

    users }|--|| subsidiaries : "belongs to"
    projects }|--|| subsidiaries : "belongs to"
    projects }|--|| users : "managed by"
    project_members }|--|| projects : "in"
    project_members }|--|| users : "is"
    wbs_items }|--|| projects : "in"
    wbs_items }o--|| users : "assigned to"
    wbs_dependencies }|--|| wbs_items : "predecessor"
    wbs_dependencies }|--|| wbs_items : "successor"
    wbs_versions }|--|| projects : "for"
    wbs_baselines }|--|| projects : "for"
    approval_requests }|--|| projects : "for"
    project_status_updates }|--|| projects : "for"
    comments }o--|| users : "by"
    project_documents }|--|| projects : "in"
    project_risks }|--|| projects : "in"
    task_blockers }|--|| wbs_items : "on"
```

## Key Design Decisions

1. **Single source of truth**: `wbs_items` table serves Tasks, Kanban, Gantt, and Calendar.
2. **Soft deletes** on all major business records.
3. **Polymorphic comments** to support both project and WBS item comments.
4. **JSON columns** only for baseline snapshots and approval value diffs.
5. **Decimal(15,2)** for all money fields.
6. **Enum classes** in PHP for all status/type fields.
