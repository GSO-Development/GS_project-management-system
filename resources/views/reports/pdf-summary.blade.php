<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>NexusPM Executive Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1e293b; }
        .header { margin-bottom: 20px; border-b: 2px solid #3730a3; padding-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; color: #3730a3; }
        .subtitle { font-size: 11px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #2e1318; color: #ffffff; padding: 8px; text-align: left; font-size: 10px; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) { background: #f8fafc; }
        .badge { font-size: 9px; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">NexusPM Executive Portfolio Report</div>
        <div class="subtitle">Generated on {{ date('F d, Y') }} • Confidential Internal Document</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Project Name</th>
                <th>Subsidiary</th>
                <th>Manager</th>
                <th>Status</th>
                <th>Progress</th>
                <th>Budget (LKR)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $p)
                <tr>
                    <td><strong>{{ $p->code }}</strong></td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->subsidiary->name ?? '-' }}</td>
                    <td>{{ $p->projectManager->name ?? '-' }}</td>
                    <td>{{ $p->status->label() }}</td>
                    <td>{{ $p->overall_progress }}%</td>
                    <td>LKR {{ number_format($p->estimated_budget, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        NexusPM Enterprise Project Management Application — Page 1 of 1
    </div>
</body>
</html>
