@extends('layouts.admin')
@section('title', __('messages.campaign_meta_title'))
@section('page_title_key', 'sb_campaigns')
@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0f172a; min-h: 100vh; color: #f1f5f9;">
    
    {{-- Top Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">
                {{ __('messages.campaign_header') }}
            </h1>
            <p class="text-sm" style="color: #94a3b8; font-family: 'DM Sans', sans-serif;">
                {{ __('messages.campaign_subheader') }}
            </p>
        </div>
        <a href="{{ route('admin.campaigns.create') }}" class="btn px-4 py-2 text-white font-weight-bold" style="background: #6366f1; border-radius: 8px; border: none;">
            <i class="fa-solid fa-plus me-2"></i> {{ __('messages.btn_launch_campaign') }}
        </a>
    </div>

    {{-- Session Success Alert --}}
    @if(session('success'))
        <div id="alert-msg" class="alert text-emerald-400 border-0 mb-4" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Data Table Card --}}
    <div class="card border-0 overflow-hidden shadow-lg" style="background: #1e293b; border-radius: 12px; border: 1px solid #334155 !important; font-family: 'DM Sans', sans-serif;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="color: #cbd5e1;">
                <thead style="background: rgba(15, 23, 42, 0.4);">
                    <tr style="color: #94a3b8; font-size: 11px; text-transform: uppercase; border-bottom: 1px solid #334155;">
                        <th class="px-4 py-3 border-0">{{ __('messages.th_campaign_info') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_status_state') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_target_url') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_timeline_schedule') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $row)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);">
                            <td class="px-4 py-3">
                                <span class="text-white d-block font-weight-medium" style="font-size: 15px;">{{ $row->title }}</span>
                                <small style="color: #64748b;">{{ $row->slug }}</small>
                            </td>
                            <td class="px-4 py-3">
                                @if($row->status === 'active')
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2);">
                                        🟢 {{ __('messages.badge_active') }}
                                    </span>
                                @elseif($row->status === 'paused')
                                    <span class="badge" style="background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.2);">
                                        🟡 {{ __('messages.badge_paused') }}
                                    </span>
                                @else
                                    <span class="badge" style="background: rgba(148, 163, 184, 0.1); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.2);">
                                        📁 {{ __('messages.badge_draft') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ $row->action_url ?? '#' }}" target="_blank" class="text-info text-decoration-none text-sm">
                                    {{ Str::limit($row->action_url ?? __('messages.url_none'), 30) }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-muted style="font-size: 12px; font-family: monospace;">
                                {{ $row->start_date ? $row->start_date->format('M d') : __('messages.timeline_immediate') }} - 
                                {{ $row->end_date ? $row->end_date->format('M d, Y') : __('messages.timeline_infinite') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-5 text-center text-muted">
                                <i class="fa-solid fa-bullhorn d-block mb-2 fs-3 style="color: #475569;"></i> 
                                {{ __('messages.no_campaigns_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Auto-hide alert messaging after 4 seconds smoothly
    setTimeout(function() {
        var msg = document.getElementById('alert-msg');
        if(msg) msg.style.display = 'none';
    }, 4000);
</script>
@endsection