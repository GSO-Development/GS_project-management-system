<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Project Assignment – GS Project Management</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">

<!-- Hidden preheader -->
<div style="display:none;max-height:0;overflow:hidden;font-size:1px;color:#f1f5f9;">
    You have been assigned to project: {{ $project->name }} — GS Project Management
</div>

<!-- Outer table -->
<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#f1f5f9">
<tr><td align="center" style="padding:40px 16px;">

<!-- Container -->
<table cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px;">

    <!-- ── HEADER ── -->
    <tr>
        <td bgcolor="#0f172a" style="background-color:#0f172a;border-radius:16px 16px 0 0;padding:48px 48px 40px;text-align:center;">

            <!-- Brand pill -->
            <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin-bottom:28px;">
                <tr>
                    <td bgcolor="#1e3a5f" style="background-color:#1e3a5f;border-radius:10px;padding:10px 20px;">
                        <span style="color:#93c5fd;font-size:11px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">GS PROJECT MANAGEMENT</span>
                    </td>
                </tr>
            </table>

            <!-- Accent bars -->
            <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin-bottom:28px;">
                <tr>
                    <td bgcolor="#2563eb" style="background-color:#2563eb;width:48px;height:3px;border-radius:2px;"></td>
                    <td style="width:8px;"></td>
                    <td bgcolor="#60a5fa" style="background-color:#60a5fa;width:20px;height:3px;border-radius:2px;"></td>
                </tr>
            </table>

            <!-- Title -->
            <p style="margin:0 0 8px;color:#ffffff;font-size:28px;font-weight:700;letter-spacing:-0.5px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">Project Assignment</p>
            <p style="margin:0;color:#93c5fd;font-size:14px;font-weight:400;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">You have been assigned to a new project</p>

        </td>
    </tr>

    <!-- ── NOTICE BAND ── -->
    <tr>
        <td bgcolor="#1d4ed8" style="background-color:#1d4ed8;padding:13px 48px;text-align:center;">
            <p style="margin:0;color:#ffffff;font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                Action Required &nbsp;&mdash;&nbsp; Review Your Assignment
            </p>
        </td>
    </tr>

    <!-- ── BODY ── -->
    <tr>
        <td bgcolor="#ffffff" style="background-color:#ffffff;padding:48px;">

            <!-- Greeting -->
            <p style="margin:0 0 6px;color:#0f172a;font-size:21px;font-weight:700;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">Hello, {{ $assignee->name }}</p>
            <p style="margin:0 0 36px;color:#475569;font-size:15px;line-height:1.75;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                You have been assigned to a new project in the
                <strong style="color:#0f172a;">GS Project Management System</strong>.
                Please review the details below and log in to get started.
            </p>

            <!-- ── PROJECT CARD ── -->
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:32px;">
                <tr>
                    <td bgcolor="#f8faff" style="background-color:#f8faff;border:1px solid #dbeafe;border-left:4px solid #2563eb;border-radius:12px;padding:28px;">

                        <!-- Code badge -->
                        <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:14px;">
                            <tr>
                                <td bgcolor="#dbeafe" style="background-color:#dbeafe;border-radius:20px;padding:4px 14px;">
                                    <span style="color:#1d4ed8;font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">{{ $project->code }}</span>
                                </td>
                            </tr>
                        </table>

                        <!-- Project name -->
                        <p style="margin:0 0 6px;color:#0f172a;font-size:19px;font-weight:700;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">{{ $project->name }}</p>

                        @if($project->description)
                        <p style="margin:0 0 24px;color:#64748b;font-size:14px;line-height:1.6;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">{{ Str::limit($project->description, 160) }}</p>
                        @else
                        <p style="margin:0 0 24px;font-size:1px;">&nbsp;</p>
                        @endif

                        <!-- Detail rows (1-column for max compatibility) -->
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">

                            <tr>
                                <td width="49%" bgcolor="#ffffff" style="background-color:#ffffff;border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;vertical-align:top;">
                                    <p style="margin:0 0 3px;color:#94a3b8;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">Start Date</p>
                                    <p style="margin:0;color:#0f172a;font-size:14px;font-weight:600;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : 'TBD' }}</p>
                                </td>
                                <td width="2%"></td>
                                <td width="49%" bgcolor="#ffffff" style="background-color:#ffffff;border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;vertical-align:top;">
                                    <p style="margin:0 0 3px;color:#94a3b8;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">Deadline</p>
                                    <p style="margin:0;color:#0f172a;font-size:14px;font-weight:600;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">{{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('M d, Y') : 'TBD' }}</p>
                                </td>
                            </tr>

                            <tr><td colspan="3" style="height:8px;"></td></tr>

                            <tr>
                                <td width="49%" bgcolor="#ffffff" style="background-color:#ffffff;border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;vertical-align:top;">
                                    <p style="margin:0 0 3px;color:#94a3b8;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">Status</p>
                                    <p style="margin:0;color:#0f172a;font-size:14px;font-weight:600;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">{{ ucfirst(str_replace('_', ' ', $project->status?->value ?? 'Planning')) }}</p>
                                </td>
                                <td width="2%"></td>
                                <td width="49%" bgcolor="#ffffff" style="background-color:#ffffff;border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;vertical-align:top;">
                                    <p style="margin:0 0 3px;color:#94a3b8;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">Subsidiary</p>
                                    <p style="margin:0;color:#0f172a;font-size:14px;font-weight:600;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">{{ $project->subsidiary?->name ?? 'N/A' }}</p>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>



            <!-- ── CTA BUTTON ── -->
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:32px;">
                <tr>
                    <td align="center">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td bgcolor="#c3122e" style="background-color:#c3122e;border-radius:8px;">
                                    <a href="{{ url('/projects/' . $project->id) }}"
                                       style="display:inline-block;color:#ffffff;font-size:15px;font-weight:700;text-decoration:none;padding:16px 44px;border-radius:8px;letter-spacing:0.3px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                        {{ $role === 'lead' ? 'Accept & Open Project Workspace' : 'View Project Workspace' }} &nbsp;&rarr;
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
                    <td bgcolor="#f8fafc" style="background-color:#f8fafc;border:1px solid #e2e8f0;border-left:3px solid #64748b;border-radius:8px;padding:18px 22px;">
                        <p style="margin:0;color:#475569;font-size:13px;line-height:1.75;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                            <strong style="color:#1e293b;">Next Steps:</strong>&nbsp;
                            Log in to the GS Project Management System, navigate to your project dashboard, and review your assigned tasks. Keep your task status updated to keep the team informed.
                        </p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    <!-- ── FOOTER ── -->
    <tr>
        <td bgcolor="#f8fafc" style="background-color:#f8fafc;border-top:1px solid #e2e8f0;border-radius:0 0 16px 16px;padding:28px 48px;">
            <p style="margin:0 0 4px;color:#94a3b8;font-size:12px;text-align:center;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                This notification was sent by <strong style="color:#64748b;">GS Project Management System</strong>
            </p>
            <p style="margin:0;color:#cbd5e1;font-size:11px;text-align:center;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
                If you received this in error, please contact your administrator &nbsp;&bull;&nbsp; &copy; {{ date('Y') }} GS Project Management
            </p>
        </td>
    </tr>

    <tr><td style="height:32px;"></td></tr>

</table>
<!-- /Container -->

</td></tr>
</table>

</body>
</html>
