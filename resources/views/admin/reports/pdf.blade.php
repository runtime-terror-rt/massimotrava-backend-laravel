<!DOCTYPE html>
<html>
<head>
    <title>Medical Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
            line-height: 1.6;
        }

        /* HEADER */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .meta {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-top: 5px;
        }

        /* PATIENT BOX */
        .patient-box {
            border: 1px solid #000;
            padding: 10px;
            margin: 15px 0;
        }

        .patient-box div {
            margin-bottom: 4px;
        }

        /* SECTION TITLE */
        .section-title {
            margin-top: 20px;
            font-weight: bold;
            font-size: 13px;
            border-left: 4px solid #000;
            padding-left: 8px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
        }

        /* STATUS */
        .normal {
            color: green;
            font-weight: bold;
        }

        .abnormal {
            color: red;
            font-weight: bold;
        }

        /* FOOTER */
        .footer {
            margin-top: 25px;
            font-size: 10px;
            text-align: center;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>

<body>

{{-- HEADER --}}
<div class="header">
    <h1>Biomarker Medical Report</h1>
    <div class="meta">
        Generated: {{ date('d M Y, h:i A') }}
    </div>
</div>

{{-- PATIENT INFO (SAFE VERSION) --}}
<div class="patient-box">

    <div>
        <strong>Patient Name:</strong>
        {{ $specificUser->name ?? ($reports->first()->user->name ?? 'N/A') }}
    </div>

    <div>
        <strong>Email:</strong>
        {{ $specificUser->email ?? ($reports->first()->user->email ?? 'N/A') }}
    </div>

    <div>
        <strong>Report Type:</strong> Biomarker Analysis
    </div>

</div>

{{-- SECTION --}}
<div class="section-title">Test Results</div>

<table>
    <thead>
        <tr>
            <th>Inv Code</th>
            <th>Subcategory</th>
            <th>Value</th>
            <th>Unit</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
    @forelse($reports as $report)
        <tr>
            <td>{{ $report->inv_code }}</td>

            <td>
                {{ optional($report->biomarkerSubcategory)->title ?? 'N/A' }}
            </td>

            <td><strong>{{ $report->value }}</strong></td>

            <td>{{ $report->unit ?? '-' }}</td>

            <td>
                @if($report->status)
                    <span class="normal">NORMAL</span>
                @else
                    <span class="abnormal">ABNORMAL</span>
                @endif
            </td>

            <td>{{ $report->created_at->format('d M Y') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6">No reports found</td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    <div style="margin-bottom:5px;">
        Confidential Health Report
    </div>
    <div style="font-size:10px;">
        This document is system generated and does not require a physical signature.
        All information is intended for medical/clinical reference purposes only.
    </div>
</div>

</body>
</html>