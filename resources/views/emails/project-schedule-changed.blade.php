<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Project Schedule Updated – GS NexusPM | George Steuart Group</title>
</head>

<body style="margin:0;padding:0;background-color:#f8fafc;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;-webkit-font-smoothing:antialiased;">

    <!-- Hidden preheader -->
    <div style="display:none;max-height:0;overflow:hidden;font-size:1px;color:#f8fafc;line-height:1px;">
        Schedule update on {{ $project->name }} ({{ $project->code }}) by {{ $editor->name }} — George Steuart PMO Suite
    </div>

    <!-- Outer table -->
    <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#f8fafc" style="background-color:#f8fafc;width:100%;">
        <tr>
            <td align="center" style="padding:40px 16px;">

                <!-- Container -->
                <table cellpadding="0" cellspacing="0" border="0" width="600" style="width:100%;max-width:600px;background-color:#ffffff;border-radius:20px;box-shadow:0 12px 36px rgba(0,0,0,0.08);overflow:hidden;border:1px solid #e2e8f0;">

                    <!-- ── TOP GOLD/CRIMSON GLOW LINE ── -->
                    <tr>
                        <td bgcolor="#c3122e" style="height:5px;background-color:#c3122e;font-size:1px;line-height:1px;">&nbsp;</td>
                    </tr>

                    <!-- ── HEADER ── -->
                    <tr>
                        <td bgcolor="#18060c" style="background-color:#18060c;padding:40px 40px 34px;text-align:center;">

                            <!-- Brand Pill -->
                            <table cellpadding="0" cellspacing="0" border="0" align="center">
                                <tr>
                                    <td bgcolor="#3b0a16" style="background-color:#3b0a16;border:1px solid #f59e0b;border-radius:24px;padding:7px 20px;text-align:center;">
                                        <span style="color:#fbbf24;font-size:11px;font-weight:800;letter-spacing:2px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            GS NEXUSPM &bull; GEORGE STEUART GROUP
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Spacer -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr><td style="height:16px;line-height:16px;font-size:1px;">&nbsp;</td></tr>
                            </table>

                            <!-- Accent Bars -->
                            <table cellpadding="0" cellspacing="0" border="0" align="center">
                                <tr>
                                    <td bgcolor="#c3122e" style="background-color:#c3122e;width:40px;height:3px;border-radius:2px;font-size:1px;line-height:1px;">&nbsp;</td>
                                    <td style="width:8px;font-size:1px;">&nbsp;</td>
                                    <td bgcolor="#f59e0b" style="background-color:#f59e0b;width:18px;height:3px;border-radius:2px;font-size:1px;line-height:1px;">&nbsp;</td>
                                </tr>
                            </table>

                            <!-- Spacer -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr><td style="height:16px;line-height:16px;font-size:1px;">&nbsp;</td></tr>
                            </table>

                            <!-- Badge & Title -->
                            <table cellpadding="0" cellspacing="0" border="0" align="center">
                                <tr>
                                    <td bgcolor="#8b0d1f" style="background-color:#8b0d1f;border:1px solid #fca5a5;border-radius:20px;padding:4px 14px;text-align:center;">
                                        <span style="color:#fca5a5;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            ⚠️ GOVERNANCE ALERT — SCHEDULE MODIFIED
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Spacer -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr><td style="height:12px;line-height:12px;font-size:1px;">&nbsp;</td></tr>
                            </table>

                            <h1 style="margin:0 0 6px;color:#ffffff;font-size:24px;font-weight:800;letter-spacing:-0.5px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                Project Timeline Updated
                            </h1>
                            <p style="margin:0;color:#fca5a5;font-size:13px;font-weight:500;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                Changes made by Project Manager <strong style="color:#ffffff;">{{ $editor->name }}</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- ── MAIN CONTENT ── -->
                    <tr>
                        <td bgcolor="#ffffff" style="background-color:#ffffff;padding:36px 40px;">

                            <!-- Project Overview Card -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background-color:#fff5f6;border:1px solid #fecdd3;border-left:4px solid #c3122e;border-radius:14px;overflow:hidden;">
                                <tr>
                                    <td style="padding:20px 22px;">
                                        <span style="display:inline-block;background-color:#c3122e;color:#ffffff;font-size:11px;font-weight:800;padding:3px 9px;border-radius:6px;letter-spacing:0.5px;font-family:monospace;">
                                            {{ $project->code }}
                                        </span>
                                        <h2 style="margin:8px 0 4px;font-size:18px;font-weight:800;color:#1e293b;letter-spacing:-0.3px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            {{ $project->name }}
                                        </h2>
                                        <p style="margin:0;font-size:12px;color:#64748b;font-weight:500;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            Subsidiary: <strong style="color:#334155;">{{ $project->subsidiary->name ?? 'Corporate / Group' }}</strong>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Spacer -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr><td style="height:20px;line-height:20px;font-size:1px;">&nbsp;</td></tr>
                            </table>

                            <!-- Change Description Intro -->
                            <p style="margin:0 0 20px;font-size:14px;line-height:1.6;color:#334155;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                This is an automated governance notification for PMO Admins. The project schedule has been modified by <strong>{{ $editor->name }}</strong> ({{ $editor->email }}). Below are the specific timeline parameter adjustments:
                            </p>

                            <!-- Timeline Comparison Table -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;border-collapse:collapse;">
                                <thead>
                                    <tr style="background-color:#f8fafc;border-bottom:2px solid #e2e8f0;">
                                        <th align="left" style="padding:12px 14px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.5px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">Parameter</th>
                                        <th align="left" style="padding:12px 14px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.5px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">Previous Value</th>
                                        <th align="left" style="padding:12px 14px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.5px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">Updated Value</th>
                                        <th align="center" style="padding:12px 14px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.5px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Start Date Row -->
                                    <tr style="border-bottom:1px solid #e2e8f0;background-color:{{ $startDateChanged ? '#fffbeb' : '#ffffff' }};">
                                        <td style="padding:14px;font-size:13px;font-weight:700;color:#1e293b;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            🛫 Start Date
                                        </td>
                                        <td style="padding:14px;font-size:13px;color:{{ $startDateChanged ? '#94a3b8' : '#334155' }};{{ $startDateChanged ? 'text-decoration:line-through;' : '' }}font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            {{ $oldStartDate ? \Carbon\Carbon::parse($oldStartDate)->format('M d, Y') : 'Not Set' }}
                                        </td>
                                        <td style="padding:14px;font-size:13px;font-weight:{{ $startDateChanged ? '700' : '500' }};color:{{ $startDateChanged ? '#b45309' : '#334155' }};font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            {{ $newStartDate ? \Carbon\Carbon::parse($newStartDate)->format('M d, Y') : 'Not Set' }}
                                        </td>
                                        <td align="center" style="padding:14px;">
                                            @if($startDateChanged)
                                                <span style="display:inline-block;background-color:#fef3c7;color:#92400e;border:1px solid #fde68a;border-radius:6px;font-size:10px;font-weight:800;padding:2px 8px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                    Changed
                                                </span>
                                            @else
                                                <span style="display:inline-block;background-color:#f1f5f9;color:#64748b;border-radius:6px;font-size:10px;font-weight:600;padding:2px 8px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                    Unchanged
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Deadline Row -->
                                    <tr style="background-color:{{ $deadlineChanged ? '#fef2f2' : '#ffffff' }};">
                                        <td style="padding:14px;font-size:13px;font-weight:700;color:#1e293b;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            🎯 Deadline
                                        </td>
                                        <td style="padding:14px;font-size:13px;color:{{ $deadlineChanged ? '#94a3b8' : '#334155' }};{{ $deadlineChanged ? 'text-decoration:line-through;' : '' }}font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            {{ $oldDeadline ? \Carbon\Carbon::parse($oldDeadline)->format('M d, Y') : 'Not Set' }}
                                        </td>
                                        <td style="padding:14px;font-size:13px;font-weight:{{ $deadlineChanged ? '700' : '500' }};color:{{ $deadlineChanged ? '#b91c1c' : '#334155' }};font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                            {{ $newDeadline ? \Carbon\Carbon::parse($newDeadline)->format('M d, Y') : 'Not Set' }}
                                        </td>
                                        <td align="center" style="padding:14px;">
                                            @if($deadlineChanged)
                                                <span style="display:inline-block;background-color:#fee2e2;color:#991b1b;border:1px solid #fecaca;border-radius:6px;font-size:10px;font-weight:800;padding:2px 8px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                    Changed
                                                </span>
                                            @else
                                                <span style="display:inline-block;background-color:#f1f5f9;color:#64748b;border-radius:6px;font-size:10px;font-weight:600;padding:2px 8px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                    Unchanged
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            @if(!empty($varianceText))
                            <!-- Spacer -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr><td style="height:16px;line-height:16px;font-size:1px;">&nbsp;</td></tr>
                            </table>

                            <!-- Variance Highlight Box -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background-color:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:10px;">
                                <tr>
                                    <td style="padding:14px 18px;font-size:12px;color:#166534;font-weight:700;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                        📊 Variance Impact: {{ $varianceText }}
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Spacer -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr><td style="height:28px;line-height:28px;font-size:1px;">&nbsp;</td></tr>
                            </table>

                            <!-- ── 100% BULLETPROOF CTA BUTTON ── -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto;">
                                            <tr>
                                                <td align="center" bgcolor="#c3122e" style="background-color:#c3122e;border-radius:12px;padding:16px 36px;text-align:center;">
                                                    <a href="{{ route('projects.show', $project->id) }}" target="_blank" style="color:#ffffff !important;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:15px;font-weight:800;text-decoration:none;display:inline-block;white-space:nowrap;letter-spacing:0.3px;">
                                                        Open Project Workspace &nbsp;&rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- ── FOOTER ── -->
                    <tr>
                        <td bgcolor="#f8fafc" style="background-color:#f8fafc;border-top:1px solid #e2e8f0;border-radius:0 0 20px 20px;padding:26px 40px;text-align:center;">
                            <p style="margin:0 0 6px;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:1px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                George Steuart Group &bull; Corporate PMO Governance Suite
                            </p>
                            <p style="margin:0;color:#94a3b8;font-size:11px;color:#94a3b8;line-height:1.5;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
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
