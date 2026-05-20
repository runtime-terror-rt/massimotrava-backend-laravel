@extends('layouts.admin')
@section('title', 'Marketing Campaigns - Massimotrava')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0f172a; min-h: 100vh; color: #f1f5f9;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">Marketing Campaigns</h1>
            <p class="text-sm" style="color: #94a3b8; font-family: 'DM Sans', sans-serif;">Promote mobile app banners, promotional kits, or active target metrics.</p>
        </div>
        <a href="{{ route('admin.campaigns.create') }}" class="btn px-4 py-2 text-white font-weight-bold" style="background: #6366f1; border-radius: 8px; border: none;">
            <i class="fa-solid fa-plus me-2"></i> Launch New Campaign
        </a>
    </div>

    @if(session('success'))
        <div class="alert text-emerald-400 border-0 mb-4" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 overflow-hidden shadow-lg" style="background: #1e293b; border-radius: 12px; border: 1px solid #334155 !important; font-family: 'DM Sans', sans-serif;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="color: #cbd5e1;">
                <thead style="background: rgba(15, 23, 42, 0.4);">
                    <tr style="color: #94a3b8; font-size: 11px; text-transform: uppercase; border-bottom: 1px solid #334155;">
                        <th class="px-4 py-3 border-0">Campaign Info</th>
                        <th class="px-4 py-3 border-0">Status State</th>
                        <th class="px-4 py-3 border-0">Target URL</th>
                        <th class="px-4 py-3 border-0">Timeline Schedule</th>
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
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2);">🟢 Active</span>
                                @elseif($row->status === 'paused')
                                    <span class="badge" style="background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.2);">🟡 Paused</span>
                                @else
                                    <span class="badge" style="background: rgba(148, 163, 184, 0.1); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.2);">📁 Draft</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ $row->action_url ?? '#' }}" target="_blank" class="text-info text-decoration-none text-sm">{{ Str::limit($row->action_url ?? 'None Specified', 30) }}</a>
                            </td>
                            <td class="px-4 py-3 text-muted text-xs font-mono" style="font-size: 12px;">
                                {{ $row->start_date ? $row->start_date->format('M d') : 'Immediate' }} - {{ $row->end_date ? $row->end_date->format('M d, Y') : 'Infinite' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-5 text-center text-muted">
                                <i class="fa-solid fa-bullhorn d-block mb-2 fs-3 text-slate-600"></i> No campaigns listed yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection