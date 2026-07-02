<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('messages.pdf_title') }}</title>
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
            width: 110px; /* Slight buffer for translated text lengths */
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

    {{-- HEADER MODULE --}}
    <table class="header-table">
        <tr>
            <td style="border:none;">
                <div class="header-title">Massimotrava Hospital Limited</div>
                <div style="color: #6366f1; font-weight: bold;">{{ __('messages.pdf_subtitle') }}</div>
            </td>
            <td class="header-meta" style="border:none;">
                <strong>{{ __('messages.pdf_lbl_date') }}:</strong> {{ date('d M Y') }}<br>
                <strong>{{ __('messages.pdf_lbl_time') }}:</strong> {{ date('h:i A') }}<br>
                <strong>{{ __('messages.pdf_lbl_report_id') }}:</strong> {{ $reports->first()->inv_code ?? __('messages.lbl_not_available') }}
            </td>
        </tr>
    </table>

    {{-- PATIENT DATA COORDINATES --}}
    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="label">{{ __('messages.pdf_lbl_patient_name') }}:</td>
                <td style="font-weight: bold; font-size: 14px;">{{ $specificUser->name ?? ($reports->first()->user->name ?? __('messages.lbl_not_available')) }}</td>
                <td class="label">{{ __('messages.lbl_select_kit') }} ID:</td>
                <td>{{ $reports->first()->kit->activation_code ?? __('messages.lbl_not_available') }}</td>
            </tr>
            <tr>
                <td class="label">{{ __('messages.th_user_info') }} (Email):</td>
                <td>{{ $specificUser->email ?? ($reports->first()->user->email ?? __('messages.lbl_not_available')) }}</td>
                <td class="label">{{ __('messages.th_date_invoice') }}:</td>
                <td><span class="inv-badge">{{ $reports->first()->inv_code ?? __('messages.lbl_not_available') }}</span></td>
            </tr>
        </table>
    </div>

    {{-- RESULTS SEGMENTED BY CATEGORY NODES --}}
    @forelse($reports->groupBy('biomarker_category_id') as $categoryId => $group)
        <div class="category-title">
            {{ $group->first()->biomarkerCategory->title ?? __('messages.opt_select_category') }}
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 45%;">{{ __('messages.pdf_th_test_name') }}</th>
                    <th style="width: 20%; text-align: center;">{{ __('messages.th_result') }}</th>
                    <th style="width: 15%; text-align: center;">{{ __('messages.pdf_th_unit') }}</th>
                    <th style="width: 20%; text-align: center;">{{ __('messages.th_status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group as $report)
                    @php
                        $subcat = $report->biomarkerSubcategory;
                        $min = $subcat->min_range ?? null;
                        $max = $subcat->max_range ?? null;
                        $value = $report->value;

                        if (is_null($min) || is_null($max)) {
                            $statusLabel = 'N/A';
                            $statusColor = '#94a3b8';
                            $statusBg = 'rgba(148,163,184,0.12)';
                        } elseif ($value < $min) {
                            $statusLabel = 'LOW';
                            $statusColor = '#fbbf24';
                            $statusBg = 'rgba(251,191,36,0.12)';
                        } elseif ($value > $max) {
                            $statusLabel = 'HIGH';
                            $statusColor = '#f87171';
                            $statusBg = 'rgba(239,68,68,0.12)';
                        } else {
                            $statusLabel = 'OPTIMAL';
                            $statusColor = '#34d399';
                            $statusBg = 'rgba(16,185,129,0.12)';
                        }
                    @endphp
                    <tr style="border-bottom: 1px solid #273548;">
                        <td style="padding: 13px 18px; font-size:13px; font-weight:600; color:#393b3d;">
                            {{ optional($subcat)->title ?? __('messages.lbl_not_available') }}
                        </td>
                        <td style="padding: 13px 18px; font-size:14px; font-weight:800; color:#393b3d; text-align:center; font-family: monospace;">
                            {{ $value }}
                        </td>
                        <td style="padding: 13px 18px; font-size:12px; color:#393b3d; text-align:center;">
                            {{ $report->unit ?? '-' }}
                        </td>
                        <td style="padding: 13px 18px; text-align:center;">
                            <span style="background:{{ $statusBg }}; color:{{ $statusColor }}; font-size:11px; font-weight:700; padding: 3px 10px; border-radius:999px; display:inline-block;">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <div style="text-align:center; padding: 50px; color: #94a3b8;">
            {{ __('messages.lbl_no_reports_found') }}
        </div>
    @endforelse

    {{-- FOOTER LAYER --}}
    <div class="footer">
        <div style="margin-bottom: 5px; color: #475569; font-weight: bold;">
            {{ __('messages.pdf_footer_confidential') }}
        </div>
        <div>
            {{ __('messages.pdf_footer_disclaimer') }}
        </div>
        <div style="margin-top: 5px;">
            {{ __('messages.pdf_page_numbering', ['current' => 1, 'total' => 1]) }}
        </div>
    </div>

</body>
</html>