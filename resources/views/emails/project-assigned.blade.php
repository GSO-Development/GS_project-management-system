<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Project Assignment – GS NexusPM | George Steuart Group</title>
</head>

<body
    style="margin:0;padding:0;background-color:#f8fafc;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;-webkit-font-smoothing:antialiased;">

    <!-- Hidden preheader -->
    <div style="display:none;max-height:0;overflow:hidden;font-size:1px;color:#f8fafc;">
        You have been assigned to project: {{ $project->name }} ({{ $project->code }}) — George Steuart Project
        Management Suite
    </div>

    <!-- Outer table -->
    <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#f8fafc">
        <tr>
            <td align="center" style="padding:40px 16px;">

                <!-- Container -->
                <table cellpadding="0" cellspacing="0" border="0" width="600"
                    style="max-width:600px;background-color:#ffffff;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.08);overflow:hidden;border:1px solid #f1f5f9;">

                    <!-- ── TOP GOLD/CRIMSON GLOW LINE ── -->
                    <tr>
                        <td bgcolor="#c3122e"
                            style="height:4px;background:linear-gradient(to right, #c3122e, #f59e0b, #c3122e);"></td>
                    </tr>

                    <!-- ── HEADER ── -->
                    <tr>
                        <td bgcolor="#18060c"
                            style="background-color:#18060c;background:linear-gradient(135deg, #18060c 0%, #300a16 50%, #1b0710 100%);padding:44px 44px 36px;text-align:center;">

                            <!-- Brand Pill -->
                            <table cellpadding="0" cellspacing="0" border="0" align="center"
                                style="margin-bottom:22px;">
                                <tr>
                                    <td bgcolor="#3b0a16"
                                        style="background-color:#3b0a16;border:1px solid rgba(245, 158, 11, 0.4);border-radius:24px;padding:8px 20px;">
                                        <span
                                            style="color:#fbbf24;font-size:11px;font-weight:800;letter-spacing:2px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            GS NEXUSPM &bull; GEORGE STEUART GROUP
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Accent Bars (Crimson & Gold) -->
                            <table cellpadding="0" cellspacing="0" border="0" align="center"
                                style="margin-bottom:20px;">
                                <tr>
                                    <td bgcolor="#c3122e"
                                        style="background-color:#c3122e;width:44px;height:3px;border-radius:2px;"></td>
                                    <td style="width:8px;"></td>
                                    <td bgcolor="#f59e0b"
                                        style="background-color:#f59e0b;width:20px;height:3px;border-radius:2px;"></td>
                                </tr>
                            </table>

                            @php
                                $roleLabel = match($role) {
                                    'lead' => 'Project Leader',
                                    'sponsor' => 'Project Sponsor',
                                    'owner' => 'Project Owner',
                                    'steering_committee' => 'Steering Committee',
                                    default => 'Core Project Team',
                                };
                                $roleSubtitle = match($role) {
                                    'lead' => 'You have been designated as Project Leader',
                                    'sponsor' => 'You have been designated as Project Sponsor',
                                    'owner' => 'You have been designated as Project Owner',
                                    'steering_committee' => 'You have been appointed to the Steering Committee',
                                    default => 'You have been added to the Core Project Team',
                                };
                                $noticeText = match($role) {
                                    'lead' => 'GOVERNANCE ACTION REQUIRED — REVIEW & ACCEPT LEADERSHIP',
                                    'sponsor' => 'GOVERNANCE UPDATE — EXECUTIVE PROJECT SPONSOR',
                                    'owner' => 'GOVERNANCE UPDATE — BUSINESS PROJECT OWNER',
                                    'steering_committee' => 'GOVERNANCE UPDATE — STEERING COMMITTEE MEMBER',
                                    default => 'PROJECT ASSIGNMENT — REVIEW YOUR ROLE & DELIVERABLES',
                                };
                            @endphp

                            <!-- Title -->
                            <p
                                style="margin:0 0 8px;color:#ffffff;font-size:26px;font-weight:800;letter-spacing:-0.5px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                Project Assignment</p>
                            <p
                                style="margin:0;color:#fca5a5;font-size:13px;font-weight:500;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                {{ $roleSubtitle }}
                            </p>

                        </td>
                    </tr>

                    <!-- ── NOTICE BAND ── -->
                    <tr>
                        <td bgcolor="#c3122e"
                            style="background-color:#c3122e;background:linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);padding:12px 40px;text-align:center;">
                            <p
                                style="margin:0;color:#ffffff;font-size:11px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                {{ $noticeText }}
                            </p>
                        </td>
                    </tr>

                    <!-- ── BODY ── -->
                    <tr>
                        <td bgcolor="#ffffff" style="background-color:#ffffff;padding:40px 44px;">

                            <!-- Greeting -->
                            <p
                                style="margin:0 0 6px;color:#0f172a;font-size:20px;font-weight:800;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                Hello, {{ $assignee->name }}
                            </p>
                            <p
                                style="margin:0 0 28px;color:#475569;font-size:14px;line-height:1.7;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                You have been assigned to a strategic project in the
                                <strong style="color:#c3122e;">GS NexusPM Project Management System</strong>.
                                Please review the project summary and parameters below to get started.
                            </p>

                            <!-- ── PROJECT CARD ── -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:28px;">
                                <tr>
                                    <td bgcolor="#fff8f8"
                                        style="background-color:#fff8f8;border:1px solid #fee2e2;border-left:4px solid #c3122e;border-radius:14px;padding:24px;">

                                        <!-- Code badge & Subsidiary -->
                                        <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;"
                                            width="100%">
                                            <tr>
                                                <td>
                                                    <span
                                                        style="background-color:#fdf4f4;border:1px solid #faeaea;border-radius:6px;padding:4px 10px;color:#c3122e;font-size:11px;font-weight:800;letter-spacing:1px;font-family:monospace;">
                                                        {{ $project->code ?? 'GSH-PRJ' }}
                                                    </span>
                                                    @if($project->subsidiary)
                                                        <span
                                                            style="color:#64748b;font-size:12px;font-weight:600;margin-left:8px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                            &bull; {{ $project->subsidiary->name }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td align="right">
                                                    <span
                                                        style="background-color:#fee2e2;border-radius:20px;padding:3px 12px;color:#991b1b;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:1px;">
                                                        {{ $roleLabel }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Project name -->
                                        <p
                                            style="margin:0 0 6px;color:#0f172a;font-size:18px;font-weight:800;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            {{ $project->name }}
                                        </p>

                                        @if($project->description)
                                            <p
                                                style="margin:0 0 20px;color:#64748b;font-size:13px;line-height:1.6;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                {{ Str::limit($project->description, 160) }}
                                            </p>
                                        @else
                                            <div style="height:12px;"></div>
                                        @endif

                                        <!-- Detail rows (2-column responsive layout) -->
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td width="49%" bgcolor="#ffffff"
                                                    style="background-color:#ffffff;border:1px solid #fce7e7;border-radius:10px;padding:12px 16px;vertical-align:top;">
                                                    <p
                                                        style="margin:0 0 3px;color:#94a3b8;font-size:10px;font-weight:800;letter-spacing:1px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                        Start Date</p>
                                                    <p
                                                        style="margin:0;color:#0f172a;font-size:13px;font-weight:700;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                        {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : 'TBD' }}
                                                    </p>
                                                </td>
                                                <td width="2%"></td>
                                                <td width="49%" bgcolor="#ffffff"
                                                    style="background-color:#ffffff;border:1px solid #fce7e7;border-radius:10px;padding:12px 16px;vertical-align:top;">
                                                    <p
                                                        style="margin:0 0 3px;color:#94a3b8;font-size:10px;font-weight:800;letter-spacing:1px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                        Target Deadline</p>
                                                    <p
                                                        style="margin:0;color:#c3122e;font-size:13px;font-weight:800;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                        {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('M d, Y') : 'TBD' }}
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="3" style="height:8px;"></td>
                                            </tr>

                                            <tr>
                                                <td width="49%" bgcolor="#ffffff"
                                                    style="background-color:#ffffff;border:1px solid #fce7e7;border-radius:10px;padding:12px 16px;vertical-align:top;">
                                                    <p
                                                        style="margin:0 0 3px;color:#94a3b8;font-size:10px;font-weight:800;letter-spacing:1px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                        Execution Status</p>
                                                    <p
                                                        style="margin:0;color:#0f172a;font-size:13px;font-weight:700;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                        {{ ucfirst(str_replace('_', ' ', $project->status?->value ?? 'Planning')) }}
                                                    </p>
                                                </td>
                                                <td width="2%"></td>
                                                <td width="49%" bgcolor="#ffffff"
                                                    style="background-color:#ffffff;border:1px solid #fce7e7;border-radius:10px;padding:12px 16px;vertical-align:top;">
                                                    <p
                                                        style="margin:0 0 3px;color:#94a3b8;font-size:10px;font-weight:800;letter-spacing:1px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                        Priority Tier</p>
                                                    <p
                                                        style="margin:0;color:#b45309;font-size:13px;font-weight:700;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                        {{ ucfirst($project->priority?->value ?? 'Medium') }} Priority
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>

                            <!-- ── CTA BUTTON ── -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:28px;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td bgcolor="#c3122e"
                                                    style="background-color:#c3122e;background:linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);border-radius:12px;border:1px solid rgba(245, 158, 11, 0.4);box-shadow:0 4px 14px rgba(195,18,46,0.35);">
                                                    <a href="{{ url('/projects/' . $project->id) }}"
                                                        style="display:inline-block;color:#ffffff;font-size:14px;font-weight:800;text-decoration:none;padding:15px 38px;border-radius:12px;letter-spacing:0.3px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                        {{ $role === 'lead' ? '👑 Accept Leadership & Open Workspace' : '🚀 Open Project Workspace' }}
                                                        &nbsp;&rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- ── NEXT STEPS BOX ── -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td bgcolor="#fffbf0"
                                        style="background-color:#fffbf0;border:1px solid #fef3c7;border-left:3px solid #f59e0b;border-radius:10px;padding:16px 20px;">
                                        <p
                                            style="margin:0;color:#78350f;font-size:12px;line-height:1.7;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            <strong style="color:#92400e;">Governance Guidance:</strong>&nbsp;
                                            Log in to the GS NexusPM Suite, review the WBS timeline deliverables, and
                                            submit daily status updates to keep project stakeholders synchronized.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- ── FOOTER ── -->
                    <tr>
                        <td bgcolor="#f8fafc"
                            style="background-color:#f8fafc;border-top:1px solid #f1f5f9;border-radius:0 0 20px 20px;padding:26px 44px;text-align:center;">
                            <p
                                style="margin:0 0 4px;color:#64748b;font-size:12px;font-weight:700;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                George Steuart Group &bull; GS NexusPM Enterprise Suite
                            </p>
                            <p
                                style="margin:0;color:#94a3b8;font-size:11px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                This automated governance notification was generated securely &bull; &copy;
                                {{ date('Y') }} George Steuart &amp; Co. Ltd.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- /Container -->

            </td>
        </tr>
    </table>

</body>

</html>