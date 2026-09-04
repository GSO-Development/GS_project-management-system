<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Project Schedule Updated – GS NexusPM | George Steuart Group</title>
</head>

<body
    style="margin:0;padding:0;background-color:#f8fafc;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;-webkit-font-smoothing:antialiased;">

    <!-- Hidden preheader -->
    <div style="display:none;max-height:0;overflow:hidden;font-size:1px;color:#f8fafc;">
        Schedule update on {{ $project->name }} ({{ $project->code }}) by {{ $editor->name }} — George Steuart PMO Suite
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
                            style="background-color:#18060c;background:linear-gradient(135deg, #18060c 0%, #300a16 50%, #1b0710 100%);padding:40px 44px 34px;text-align:center;">

                            <!-- Brand Pill -->
                            <table cellpadding="0" cellspacing="0" border="0" align="center"
                                style="margin-bottom:20px;">
                                <tr>
                                    <td bgcolor="#3b0a16"
                                        style="background-color:#3b0a16;border:1px solid rgba(245, 158, 11, 0.4);border-radius:24px;padding:7px 18px;">
                                        <span
                                            style="color:#fbbf24;font-size:11px;font-weight:800;letter-spacing:2px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            GS NEXUSPM &bull; GEORGE STEUART GROUP
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Accent Bars (Crimson & Gold) -->
                            <table cellpadding="0" cellspacing="0" border="0" align="center"
                                style="margin-bottom:18px;">
                                <tr>
                                    <td bgcolor="#c3122e"
                                        style="background-color:#c3122e;width:40px;height:3px;border-radius:2px;"></td>
                                    <td style="width:8px;"></td>
                                    <td bgcolor="#f59e0b"
                                        style="background-color:#f59e0b;width:18px;height:3px;border-radius:2px;"></td>
                                </tr>
                            </table>

                            <!-- Badge & Title -->
                            <div style="display:inline-block;background-color:rgba(239, 68, 68, 0.2);border:1px solid rgba(239, 68, 68, 0.4);border-radius:20px;padding:4px 14px;margin-bottom:12px;">
                                <span style="color:#fca5a5;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
                                    ⚠️ GOVERNANCE ALERT — SCHEDULE MODIFIED
                                </span>
                            </div>

                            <p
                                style="margin:0 0 6px;color:#ffffff;font-size:24px;font-weight:800;letter-spacing:-0.5px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                Project Timeline Updated
                            </p>
                            <p
                                style="margin:0;color:#fca5a5;font-size:13px;font-weight:500;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                Changes made by Project Manager <strong style="color:#ffffff;">{{ $editor->name }}</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- ── MAIN CONTENT ── -->
                    <tr>
                        <td style="padding:32px 36px 28px;">

                            <!-- Project Overview Card -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="background-color:#fff5f6;border:1px solid #fecdd3;border-radius:14px;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td style="vertical-align:top;">
                                                    <span style="display:inline-block;background-color:#c3122e;color:#ffffff;font-size:11px;font-weight:800;padding:3px 8px;border-radius:6px;letter-spacing:0.5px;margin-bottom:6px;">
                                                        {{ $project->code }}
                                                    </span>
                                                    <h2 style="margin:0 0 4px;font-size:18px;font-weight:800;color:#1e293b;letter-spacing:-0.3px;">
                                                        {{ $project->name }}
                                                    </h2>
                                                    <p style="margin:0;font-size:12px;color:#64748b;font-weight:500;">
                                                        Subsidiary: <strong style="color:#334155;">{{ $project->subsidiary->name ?? 'Corporate / Group' }}</strong>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Change Description Intro -->
                            <p style="margin:0 0 18px;font-size:14px;line-height:1.6;color:#334155;">
                                This is an automated governance notification for PMO Admins. The project schedule has been modified by <strong>{{ $editor->name }}</strong> ({{ $editor->email }}). Below are the specific timeline parameter adjustments:
                            </p>

                            <!-- Timeline Comparison Table -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:24px;border-collapse:collapse;">
                                <thead>
                                    <tr style="background-color:#f8fafc;border-bottom:2px solid #e2e8f0;">
                                        <th align="left" style="padding:12px 14px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.5px;">Parameter</th>
                                        <th align="left" style="padding:12px 14px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.5px;">Previous Value</th>
                                        <th align="left" style="padding:12px 14px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.5px;">Updated Value</th>
                                        <th align="center" style="padding:12px 14px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Start Date Row -->
                                    <tr style="border-bottom:1px solid #e2e8f0;background-color:{{ $startDateChanged ? '#fffbeb' : '#ffffff' }};">
                                        <td style="padding:14px;font-size:13px;font-weight:700;color:#1e293b;">
                                            🛫 Start Date
                                        </td>
                                        <td style="padding:14px;font-size:13px;color:{{ $startDateChanged ? '#94a3b8' : '#334155' }};{{ $startDateChanged ? 'text-decoration:line-through;' : '' }}">
                                            {{ $oldStartDate ? \Carbon\Carbon::parse($oldStartDate)->format('M d, Y') : 'Not Set' }}
                                        </td>
                                        <td style="padding:14px;font-size:13px;font-weight:{{ $startDateChanged ? '700' : '500' }};color:{{ $startDateChanged ? '#b45309' : '#334155' }};">
                                            {{ $newStartDate ? \Carbon\Carbon::parse($newStartDate)->format('M d, Y') : 'Not Set' }}
                                        </td>
                                        <td align="center" style="padding:14px;">
                                            @if($startDateChanged)
                                                <span style="display:inline-block;background-color:#fef3c7;color:#92400e;border:1px solid #fde68a;border-radius:6px;font-size:10px;font-weight:800;padding:2px 8px;text-transform:uppercase;">
                                                    Changed
                                                </span>
                                            @else
                                                <span style="display:inline-block;background-color:#f1f5f9;color:#64748b;border-radius:6px;font-size:10px;font-weight:600;padding:2px 8px;text-transform:uppercase;">
                                                    Unchanged
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Deadline Row -->
                                    <tr style="background-color:{{ $deadlineChanged ? '#fef2f2' : '#ffffff' }};">
                                        <td style="padding:14px;font-size:13px;font-weight:700;color:#1e293b;">
                                            🎯 Deadline
                                        </td>
                                        <td style="padding:14px;font-size:13px;color:{{ $deadlineChanged ? '#94a3b8' : '#334155' }};{{ $deadlineChanged ? 'text-decoration:line-through;' : '' }}">
                                            {{ $oldDeadline ? \Carbon\Carbon::parse($oldDeadline)->format('M d, Y') : 'Not Set' }}
                                        </td>
                                        <td style="padding:14px;font-size:13px;font-weight:{{ $deadlineChanged ? '700' : '500' }};color:{{ $deadlineChanged ? '#b91c1c' : '#334155' }};">
                                            {{ $newDeadline ? \Carbon\Carbon::parse($newDeadline)->format('M d, Y') : 'Not Set' }}
                                        </td>
                                        <td align="center" style="padding:14px;">
                                            @if($deadlineChanged)
                                                <span style="display:inline-block;background-color:#fee2e2;color:#991b1b;border:1px solid #fecaca;border-radius:6px;font-size:10px;font-weight:800;padding:2px 8px;text-transform:uppercase;">
                                                    Changed
                                                </span>
                                            @else
                                                <span style="display:inline-block;background-color:#f1f5f9;color:#64748b;border-radius:6px;font-size:10px;font-weight:600;padding:2px 8px;text-transform:uppercase;">
                                                    Unchanged
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            @if(!empty($varianceText))
                            <!-- Variance Highlight Box -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="background-color:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:12px 16px;font-size:12px;color:#166534;font-weight:600;">
                                        📊 Variance Impact: {{ $varianceText }}
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- CTA Button -->
                            <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin:28px auto 10px;">
                                <tr>
                                    <td bgcolor="#c3122e" align="center"
                                        style="border-radius:12px;background:linear-gradient(135deg, #c3122e 0%, #a00e24 100%);box-shadow:0 4px 14px rgba(195,18,46,0.3);">
                                        <a href="{{ route('projects.show', $project->id) }}" target="_blank"
                                            style="display:inline-block;padding:14px 32px;font-size:14px;font-weight:800;color:#ffffff;text-decoration:none;letter-spacing:0.3px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            Open Project Workspace &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- ── FOOTER ── -->
                    <tr>
                        <td bgcolor="#f8fafc"
                            style="background-color:#f8fafc;border-top:1px solid #f1f5f9;padding:24px 36px;text-align:center;">
                            <p style="margin:0 0 6px;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:1px;">
                                George Steuart Group &bull; Corporate PMO Governance Suite
                            </p>
                            <p style="margin:0;font-size:11px;color:#94a3b8;line-height:1.5;">
                                This is an automated governance message triggered by project timeline alterations. If you have questions regarding this schedule adjustment, please contact the designated Project Manager or your PMO steering committee.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
