<!DOCTYPE html>
<html>
<head>
    <title>Biomarker Medical Report</title>
    <style>
        @page { margin: 40px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* HEADER */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-title {
            font-size: 22px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
        }
        .header-meta {
            text-align: right;
            font-size: 10px;
            color: #64748b;
        }

        /* PATIENT INFO BOX */
        .info-section {
            width: 100%;
            margin-bottom: 25px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 8px;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding: 3px 0;
            border: none;
            text-align: left;
        }
        .label {
            color: #64748b;
            font-weight: bold;
            width: 100px;
        }

        /* REPORT TABLES */
        .category-title {
            background: #4f46e5;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 4px;
            margin-top: 20px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #e2e8f0;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        /* STATUS BADGE */
        .status {
            font-size: 10px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 12px;
            text-align: center;
            display: inline-block;
        }
        .normal { background: #dcfce7; color: #166534; }
        .abnormal { background: #fee2e2; color: #991b1b; }

        /* FOOTER */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }

        .inv-badge {
            background: #e2e8f0;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 10px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td style="border:none;">
                <div class="header-title">Massimotrava Hospital Limited</div>
                <div style="color: #6366f1; font-weight: bold;">Biomarker Analysis Report</div>
            </td>
            <td class="header-meta" style="border:none;">
                <strong>Date:</strong> {{ date('d M Y') }}<br>
                <strong>Time:</strong> {{ date('h:i A') }}<br>
                <strong>Report ID:</strong> {{ $reports->first()->inv_code ?? 'N/A' }}
            </td>
        </tr>
    </table>

    {{-- PATIENT INFO --}}
    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="label">Patient Name:</td>
                <td style="font-weight: bold; font-size: 14px;">{{ $specificUser->name ?? ($reports->first()->user->name ?? 'N/A') }}</td>
                <td class="label">Kit ID:</td>
                <td>{{ $reports->first()->kit->activation_code ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td>{{ $specificUser->email ?? ($reports->first()->user->email ?? 'N/A') }}</td>
                <td class="label">Invoice:</td>
                <td><span class="inv-badge">{{ $reports->first()->inv_code ?? 'N/A' }}</span></td>
            </tr>
        </table>
    </div>

    {{-- RESULTS GROUPED BY CATEGORY --}}
    @forelse($reports->groupBy('biomarker_category_id') as $categoryId => $group)
        <div class="category-title">
            {{ $group->first()->biomarkerCategory->title ?? 'General Category' }}
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 45%;">Test Name</th>
                    <th style="width: 20%; text-align: center;">Result</th>
                    <th style="width: 15%; text-align: center;">Unit</th>
                    <th style="width: 20%; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group as $report)
                <tr>
                    <td style="color: #1e293b; font-weight: 500;">
                        {{ optional($report->biomarkerSubcategory)->title ?? 'N/A' }}
                    </td>
                    <td style="text-align: center; font-weight: bold; font-size: 13px;">
                        {{ $report->value }}
                    </td>
                    <td style="text-align: center; color: #64748b;">
                        {{ $report->unit ?? '-' }}
                    </td>
                    <td style="text-align: center;">
                        @if($report->status)
                            <span class="status normal">NORMAL</span>
                        @else
                            <span class="status abnormal">ABNORMAL</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <div style="text-align:center; padding: 50px; color: #94a3b8;">
            No records found for this report.
        </div>
    @endforelse

    {{-- FOOTER --}}
    <div class="footer">
        <div style="margin-bottom: 5px; color: #475569; font-weight: bold;">
            Confidential Health Document - For Professional Use Only
        </div>
        <div>
            This is a computer-generated report and does not require a physical signature. 
            The results are based on the biomarker data provided at the time of testing.
        </div>
        <div style="margin-top: 5px;">
            Page 1 of 1
        </div>
    </div>

</body>
</html>