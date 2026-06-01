@extends('layouts.admin')
@section('title', __('messages.rep_meta_list_title'))
@section('page_title_key', 'sb_reports')
@section('content')
<div class="table-warp" style="padding: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: var(--text); margin: 0; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-file-medical" style="color: var(--accent);"></i>
            @if(isset($specificUser))
                {{ __('messages.rep_header_for_user') }}: <span style="color: #38bdf8;">{{ $specificUser->name }}</span>
            @else
                {{ __('messages.rep_header_all_reports') }}
            @endif
        </h2>

        <a href="{{ route('admin.get.users') }}" class="btn-theme-back" style="color: var(--text-muted); text-decoration: none; font-size: 14px; background: var(--surface-2); padding: 8px 15px; border-radius: 6px; border: 1px solid var(--border); transition: all 0.2s;">
            <i class="fa-solid fa-arrow-left"></i> {{ __('messages.btn_back_to_users') }}
        </a>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ route('admin.reports.create') }}" 
           style="background: var(--accent); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: bold; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            <i class="fa-solid fa-plus"></i>
            {{ __('messages.btn_create_new_report') }}
        </a>

        @if($reports->count() > 0)
        <a href="{{ route('admin.reports.download.pdf', request()->query()) }}" 
           style="background: #ef4444; color: white; padding: 10px 18px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            <i class="fa-solid fa-file-pdf"></i>
            {{ __('messages.btn_bulk_download_pdf') }}
        </a>
        @endif
    </div>

    {{-- System Status Alerts Toast Notifications --}}
    @if(session('success'))
        <div id="alert-msg" style="background: rgba(6, 95, 70, 0.15); color: #34d399; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #059669;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="alert-msg" style="background: rgba(127, 29, 29, 0.15); color: #f87171; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #b91c1c;">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <script>
        setTimeout(function() {
            var msg = document.getElementById('alert-msg');
            if(msg) msg.style.display = 'none';
        }, 5000);
    </script>

    <div style="background: var(--surface); border-radius: 12px; overflow: hidden; border: 1px solid var(--border); transition: background-color 0.3s, border-color 0.3s;">
        <table class="admin-table" style="width: 100%; border-collapse: collapse; color: var(--text);">
            <thead>
                <tr style="background: var(--surface-2); text-align: left; border-bottom: 1px solid var(--border);">
                    <th style="padding: 15px; color: var(--text);">{{ __('messages.th_date_invoice') }}</th>
                    <th style="padding: 15px; color: var(--text);">{{ __('messages.th_user_info') }}</th>
                    <th style="padding: 15px; color: var(--text);">{{ __('messages.th_tests_hierarchy') }}</th>
                    <th style="padding: 15px; text-align: center; color: var(--text);">{{ __('messages.th_result') }}</th>
                    <th style="padding: 15px; text-align: center; color: var(--text);">{{ __('messages.th_status') }}</th>
                    <th style="padding: 15px; text-align: right; color: var(--text);">{{ __('messages.th_actions') }}</th>
                </tr>
            </thead>

            <tbody>
                {{-- Grouping reports by inv_code to secure structural view uniformity --}}
                @forelse($reports->groupBy('inv_code') as $invCode => $group)
                    @php $firstReport = $group->first(); @endphp
                    <tr class="table-row-hover" style="border-bottom: 1px solid var(--border); vertical-align: top; transition: background-color 0.2s;">
                        
                        <td style="padding: 15px;">
                            <div style="font-weight: bold; color: var(--text);">{{ $firstReport->created_at->format('d M, Y') }}</div>
                            <small style="background: var(--surface-2); border: 1px solid var(--border); padding: 2px 6px; border-radius: 4px; color: #38bdf8; display: inline-block; margin-top: 4px;">{{ $invCode }}</small>
                        </td>

                        <td style="padding: 15px;">
                            <div style="font-weight: 500; color: var(--text);">{{ $firstReport->user->name ?? __('messages.lbl_not_available') }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $firstReport->user->email ?? '' }}</div>
                        </td>

                        <td style="padding: 15px;">
                            @foreach($group as $item)
                                <div style="margin-bottom: 8px; padding-bottom: 5px; border-bottom: 1px dashed var(--border);">
                                    <span style="color: var(--text-muted); font-size: 12px;">{{ $item->biomarkerCategory->title ?? __('messages.lbl_not_available') }}</span> <br>
                                    <i class="fa-solid fa-chevron-right" style="font-size: 10px; color: var(--accent);"></i> 
                                    <span style="color: var(--text); font-size: 13px;">{{ $item->biomarkerSubcategory->title ?? __('messages.lbl_not_available') }}</span>
                                </div>
                            @endforeach
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            @foreach($group as $item)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #22c55e;">{{ $item->value }}</strong> 
                                    <small style="color: var(--text-muted);">{{ $item->unit }}</small>
                                </div>
                            @endforeach
                        </td>

                        <td style="padding: 15px; text-align: center; vertical-align: middle;">
                            <span class="badge {{ $firstReport->status ? 'status-active-badge' : 'status-inactive-badge' }}" style="padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block;">
                                {{ $firstReport->status ? __('messages.status_active') : __('messages.status_inactive') }}
                            </span>
                        </td>

                        <td style="padding: 15px; text-align: right; vertical-align: middle;">
                            <div style="display: inline-flex; justify-content: flex-end; gap: 14px; align-items: center;">
                                
                                {{-- Dispatch Report Email --}}
                                <form action="{{ route('admin.reports.send.email') }}" method="POST" class="d-inline send-email-form" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="inv_code" value="{{ $invCode }}">
                                    <button type="submit" title="{{ __('messages.tip_send_email') }}" 
                                            style="background: none; border: none; color: #38bdf8; cursor: pointer; padding: 0; display: flex; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="fa-solid fa-envelope" style="font-size: 18px;"></i>
                                    </button>
                                </form>

                                {{-- Single Sheet PDF Generation Print --}}
                                <a href="{{ route('admin.reports.download.pdf', ['inv_code' => $invCode]) }}" title="{{ __('messages.tip_download_pdf') }}" style="color: #fbbf24; display: inline-flex; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class="fa-solid fa-print" style="font-size: 18px;"></i>
                                </a>
                                
                                {{-- Destructive Delete Sequence Control --}}
                                <form action="#" method="POST" onsubmit="return confirm('{{ __('messages.js_confirm_delete_invoice') }}');" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="{{ __('messages.btn_remove') }}" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0; display: inline-flex; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="fa-solid fa-trash" style="font-size: 17px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 50px; color: var(--text-muted);">
                            <i class="fa-regular fa-folder-open" style="font-size: 44px; display: block; margin-bottom: 12px; color: var(--text-muted); opacity: 0.6;"></i>
                            {{ __('messages.lbl_no_reports_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper" style="margin-top: 25px;">
        {{ $reports->appends(request()->query())->links() }}
    </div>

</div>

<style>
    .table-row-hover:hover {
        background: var(--surface-2) !important;
        opacity: 0.95;
    }
    
    .btn-theme-back:hover {
        background: var(--surface) !important;
        color: var(--text) !important;
        border-color: var(--accent) !important;
    }

    .status-active-badge {
        background: rgba(6, 95, 70, 0.2);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .status-inactive-badge {
        background: rgba(127, 29, 29, 0.2);
        color: #f87171;
        border: 1px solid rgba(248, 113, 113, 0.3);
    }
</style>
@endsection