@extends('layouts.admin')

@section('content')
<div style="max-width: 850px; margin: 30px auto; padding: 0 20px; font-family: 'Segoe UI', Arial, sans-serif;">

    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.25); overflow: hidden;">

        <div style="height: 5px; background: linear-gradient(90deg, #4338ca, #6366f1, #818cf8);"></div>

        {{-- Header --}}
        <div style="padding: 32px 40px 24px 40px; display:flex; justify-content:space-between; align-items:flex-start; flex-wrap: wrap; gap: 20px; border-bottom: 1px solid #334155;">
            <div style="display:flex; align-items:center; gap:14px;">
                <img src="{{ asset('images/logo.avif') }}" alt="Vyralabs" style="height:42px; width:auto; object-fit:contain;">
                <div>
                    <h1 style="margin:0; font-size:20px; font-weight:800; color:#fff;">Vyralabs</h1>
                    <p style="margin:2px 0 0 0; font-size:12px; color:#818cf8; font-weight:600; letter-spacing:0.4px;">{{ __('messages.pdf_subtitle') }}</p>
                </div>
            </div>

            <div style="text-align:right; font-size:12px; color:#94a3b8;">
                <p style="margin:0;"><strong style="color:#e2e8f0;">{{ __('messages.pdf_lbl_date') }}:</strong> {{ $mainReport->created_at->format('d M Y') }}</p>
                <p style="margin:0;"><strong style="color:#e2e8f0;">{{ __('messages.pdf_lbl_time') }}:</strong> {{ $mainReport->created_at->format('h:i A') }}</p>
                <p style="margin:0;"><strong style="color:#e2e8f0;">{{ __('messages.pdf_lbl_report_id') }}:</strong> {{ $mainReport->inv_code }}</p>
            </div>
        </div>

        {{-- Patient info --}}
        <div style="padding: 24px 40px; background:#17233a; border-bottom: 1px solid #334155;">
            <table style="width:100%;">
                <tr>
                    <td style="color:#64748b; font-weight:700; font-size:12px; width:150px; padding:4px 0;">{{ __('messages.pdf_lbl_patient_name') }}:</td>
                    <td style="color:#fff; font-weight:700; font-size:14px; padding:4px 0;">{{ $specificUser->name ?? __('messages.lbl_not_available') }}</td>
                    <td style="color:#64748b; font-weight:700; font-size:12px; width:120px; padding:4px 0;">{{ __('messages.lbl_select_kit') }} ID:</td>
                    <td style="color:#e2e8f0; font-size:13px; padding:4px 0;">{{ $mainReport->kit->activation_code ?? __('messages.lbl_not_available') }}</td>
                </tr>
                <tr>
                    <td style="color:#64748b; font-weight:700; font-size:12px; padding:4px 0;">{{ __('messages.th_user_info') }} (Email):</td>
                    <td style="color:#cbd5e1; font-size:13px; padding:4px 0;">{{ $specificUser->email ?? __('messages.lbl_not_available') }}</td>
                    <td style="color:#64748b; font-weight:700; font-size:12px; padding:4px 0;">{{ __('messages.th_date_invoice') }}:</td>
                    <td style="padding:4px 0;"><span style="background:#334155; color:#e2e8f0; padding:2px 8px; border-radius:4px; font-family:monospace; font-size:11px;">{{ $mainReport->inv_code }}</span></td>
                </tr>
            </table>
        </div>

        {{-- Categories --}}
        <div style="padding: 30px 40px;">
            @forelse($reports->groupBy('biomarker_category_id') as $categoryId => $group)
                <div style="margin-bottom: 26px; border: 1px solid #334155; border-radius: 10px; overflow: hidden;">

                    <div style="background: linear-gradient(90deg, #4338ca, #6366f1); padding: 12px 18px;">
                        <h3 style="margin:0; font-size:13px; font-weight:700; color:#fff;">
                            {{ $group->first()->biomarkerCategory->title ?? __('messages.opt_select_category') }}
                        </h3>
                    </div>

                    <table style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background:#17233a;">
                                <th style="text-align:left; padding: 10px 18px; font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; border-bottom:1px solid #334155;">{{ __('messages.pdf_th_test_name') }}</th>
                                <th style="text-align:center; padding: 10px 18px; font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; border-bottom:1px solid #334155;">{{ __('messages.th_result') }}</th>
                                <th style="text-align:center; padding: 10px 18px; font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; border-bottom:1px solid #334155;">{{ __('messages.pdf_th_unit') }}</th>
                                <th style="text-align:center; padding: 10px 18px; font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; border-bottom:1px solid #334155;">{{ __('messages.th_status') }}</th>
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
                                    <td style="padding: 13px 18px; font-size:13px; font-weight:600; color:#e2e8f0;">
                                        {{ optional($subcat)->title ?? __('messages.lbl_not_available') }}
                                    </td>
                                    <td style="padding: 13px 18px; font-size:14px; font-weight:800; color:#fff; text-align:center; font-family: monospace;">
                                        {{ $value }}
                                    </td>
                                    <td style="padding: 13px 18px; font-size:12px; color:#94a3b8; text-align:center;">
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
                </div>
            @empty
                <p style="text-align:center; color:#64748b; padding: 40px 0;">{{ __('messages.lbl_no_reports_found') }}</p>
            @endforelse
        </div>

        {{-- Footer --}}
        <div style="padding: 22px 40px; background:#17233a; border-top: 1px solid #334155;">
            <p style="margin:0 0 4px 0; font-size:11px; color:#94a3b8; font-weight:700;">{{ __('messages.pdf_footer_confidential') }}</p>
            <p style="margin:0 0 16px 0; font-size:11px; color:#64748b; line-height:1.6;">{{ __('messages.pdf_footer_disclaimer') }}</p>
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap: wrap; gap: 12px;">
                <p style="margin:0; font-size:11px; color:#64748b;">© {{ date('Y') }} VyraLabs</p>
                <div style="display:flex; gap:10px;" class="no-print">
                    <button onclick="window.print()" style="background:#273548; border:1px solid #334155; color:#e2e8f0; padding: 9px 18px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;">
                        <i class="fa-solid fa-print"></i> {{ __('messages.pdf_page_numbering', ['current'=>1,'total'=>1]) ? 'Print / Save PDF' : '' }}
                    </button>
                    <a href="{{ route('user.dashboard.index') }}" style="background:#6366f1; color:#fff; text-decoration:none; padding: 9px 18px; border-radius:8px; font-size:12px; font-weight:600;">
                        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {
        header, .topbar, .sidebar, .no-print { display: none !important; }
        body { background: #0f172a !important; }
    }
</style>
@endsection