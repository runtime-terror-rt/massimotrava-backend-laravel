@extends('layouts.admin')
@section('title', __('messages.rep_meta_list_title'))

@section('content')
<div class="table-warp" style="padding: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: white; margin: 0;">
            <i class="fa-solid fa-file-medical" style="color: #6366f1; margin-right: 10px;"></i>
            @if(isset($specificUser))
                {{ __('messages.rep_header_for_user') }}: <span style="color: #38bdf8;">{{ $specificUser->name }}</span>
            @else
                {{ __('messages.rep_header_all_reports') }}
            @endif
        </h2>

        <a href="{{ route('admin.get.users') }}" style="color: #94a3b8; text-decoration: none; font-size: 14px; background: #0f172a; padding: 8px 15px; border-radius: 6px; border: 1px solid #334155; transition: background 0.2s;" onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
            <i class="fa-solid fa-arrow-left"></i> {{ __('messages.btn_back_to_users') }}
        </a>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ route('admin.reports.create') }}" 
           style="background: #6366f1; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: bold; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
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
        <div id="alert-msg" style="background: #065f46; color: #34d399; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #059669;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="alert-msg" style="background: #7f1d1d; color: #f87171; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #b91c1c;">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <script>
        setTimeout(function() {
            var msg = document.getElementById('alert-msg');
            if(msg) msg.style.display = 'none';
        }, 5000);
    </script>

    <div style="background: #1e293b; border-radius: 12px; overflow: hidden; border: 1px solid #334155;">
        <table class="admin-table" style="width: 100%; border-collapse: collapse; color: #e2e8f0;">
            <thead>
                <tr style="background: #0f172a; text-align: left;">
                    <th style="padding: 15px;">{{ __('messages.th_date_invoice') }}</th>
                    <th style="padding: 15px;">{{ __('messages.th_user_info') }}</th>
                    <th style="padding: 15px;">{{ __('messages.th_tests_hierarchy') }}</th>
                    <th style="padding: 15px; text-align: center;">{{ __('messages.th_result') }}</th>
                    <th style="padding: 15px; text-align: center;">{{ __('messages.th_status') }}</th>
                    <th style="padding: 15px; text-align: right;">{{ __('messages.th_actions') }}</th>
                </tr>
            </thead>

            <tbody>
                {{-- Grouping reports by inv_code to secure structural view uniformity --}}
                @forelse($reports->groupBy('inv_code') as $invCode => $group)
                    @php $firstReport = $group->first(); @endphp
                    <tr style="border-bottom: 1px solid #334155; vertical-align: top;">
                        
                        <td style="padding: 15px;">
                            <div style="font-weight: bold; color: #f8fafc;">{{ $firstReport->created_at->format('d M, Y') }}</div>
                            <small style="background: #334155; padding: 2px 6px; border-radius: 4px; color: #38bdf8;">{{ $invCode }}</small>
                        </td>

                        <td style="padding: 15px;">
                            <div style="font-weight: 500;">{{ $firstReport->user->name ?? __('messages.lbl_not_available') }}</div>
                            <div style="font-size: 12px; color: #94a3b8;">{{ $firstReport->user->email ?? '' }}</div>
                        </td>

                        <td style="padding: 15px;">
                            @foreach($group as $item)
                                <div style="margin-bottom: 8px; padding-bottom: 5px; border-bottom: 1px dashed #334155; last-child: border:none;">
                                    <span style="color: #94a3b8; font-size: 12px;">{{ $item->biomarkerCategory->title ?? __('messages.lbl_not_available') }}</span> <br>
                                    <i class="fa-solid fa-chevron-right" style="font-size: 10px; color: #6366f1;"></i> 
                                    <span style="color: #e2e8f0;">{{ $item->biomarkerSubcategory->title ?? __('messages.lbl_not_available') }}</span>
                                </div>
                            @endforeach
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            @foreach($group as $item)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #22c55e;">{{ $item->value }}</strong> 
                                    <small style="color: #94a3b8;">{{ $item->unit }}</small>
                                </div>
                            @endforeach
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <span class="badge {{ $firstReport->status ? 'bg-success' : 'bg-danger' }}" style="padding: 5px 10px; border-radius: 20px; font-size: 11px;">
                                {{ $firstReport->status ? __('messages.status_active') : __('messages.status_inactive') }}
                            </span>
                        </td>

                        <td style="padding: 15px; text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
                                
                                {{-- Dispatch Dispatch Report Email --}}
                                <form action="{{ route('admin.reports.send.email') }}" method="POST" class="d-inline send-email-form" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="inv_code" value="{{ $invCode }}">
                                    <button type="submit" title="{{ __('messages.tip_send_email') }}" 
                                            style="background: none; border: none; color: #38bdf8; cursor: pointer; padding: 0; display: flex; align-items: center;">
                                        <i class="fa-solid fa-envelope" style="font-size: 18px;"></i>
                                    </button>
                                </form>

                                {{-- Single Sheet PDF Generation Print --}}
                                <a href="{{ route('admin.reports.download.pdf', ['inv_code' => $invCode]) }}" title="{{ __('messages.tip_download_pdf') }}" style="color: #fbbf24; display: flex; align-items: center;">
                                    <i class="fa-solid fa-print style="font-size: 18px;"></i>
                                </a>
                                
                                {{-- Destructive Delete Sequence Control --}}
                                <form action="#" method="POST" onsubmit="return confirm('{{ __('messages.js_confirm_delete_invoice') }}');" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="{{ __('messages.btn_remove') }}" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0; display: flex; align-items: center;">
                                        <i class="fa-solid fa-trash" style="font-size: 17px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="fa-regular fa-folder-open" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                            {{ __('messages.lbl_no_reports_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper" style="margin-top: 20px;">
        {{ $reports->appends(request()->query())->links() }}
    </div>

</div>

<style>
    .admin-table tbody tr:hover {
        background: #0f172a80;
    }
    .badge { display: inline-block; font-weight: bold; }
    .bg-success { background: #065f46; color: #34d399; }
    .bg-danger { background: #7f1d1d; color: #f87171; }
</style>
@endsection