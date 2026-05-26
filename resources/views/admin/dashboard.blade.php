@extends('layouts.admin')
@section('title', __('messages.dashboard_title', ['default' => 'Dashboard']))

@section('content')
<div class="content">
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome back, Admin! Here is the latest system overview.</p>
        </div>
    </div>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; margin-bottom: 30px;">
    
        {{-- Card 1: Total Transactions --}}
        <div class="stat-card" style="background: #111622; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 20px; border-top: 3px solid #6366f1;">
            <div class="stat-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <div class="stat-icon" style="background: rgba(99, 102, 241, 0.1); color: #818cf8; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="stat-trend trend-up" style="background: rgba(16, 185, 129, 0.08); color: #10b981; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 500; font-family: 'Inter', system-ui, sans-serif;">
                    <i class="fa-solid fa-arrow-trend-up" style="font-size: 9px; margin-right: 4px;"></i> 12.4%
                </div>
            </div>
            <div class="stat-value" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, system-ui, sans-serif !important; font-size: 26px; font-weight: 400 !important; color: #ffffff !important; letter-spacing: -0.3px; margin: 0 0 4px 0; line-height: 1.2; -webkit-font-smoothing: antialiased;">
                ${{ number_format((float)($totalTransactions ?? 0), 2) }}
            </div>
            <div class="stat-label" style="font-size: 12px; color: #64748b; font-weight: 400; font-family: 'Inter', system-ui, sans-serif;">Total Transactions</div>
        </div>

        {{-- Card 2: Total Users --}}
        <div class="stat-card" style="background: #111622; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 20px; border-top: 3px solid #ec4899;">
            <div class="stat-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <div class="stat-icon" style="background: rgba(236, 72, 153, 0.1); color: #f472b6; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-trend trend-up" style="background: rgba(16, 185, 129, 0.08); color: #10b981; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 500; font-family: 'Inter', system-ui, sans-serif;">
                    <i class="fa-solid fa-arrow-trend-up" style="font-size: 9px; margin-right: 4px;"></i> 8.1%
                </div>
            </div>
            <div class="stat-value" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, system-ui, sans-serif !important; font-size: 26px; font-weight: 400 !important; color: #ffffff !important; letter-spacing: -0.3px; margin: 0 0 4px 0; line-height: 1.2; -webkit-font-smoothing: antialiased;">
                {{ $totalUsers ?? 0 }}
            </div>
            <div class="stat-label" style="font-size: 12px; color: #64748b; font-weight: 400; font-family: 'Inter', system-ui, sans-serif;">Total Users</div>
        </div>

        {{-- Card 3: Total Labs --}}
        <div class="stat-card" style="background: #111622; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 20px; border-top: 3px solid #10b981;">
            <div class="stat-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #34d399; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                    <i class="fa-solid fa-flask"></i>
                </div>
                <div class="stat-trend trend-up" style="background: rgba(16, 185, 129, 0.08); color: #10b981; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 500; font-family: 'Inter', system-ui, sans-serif;">
                    <i class="fa-solid fa-arrow-trend-up" style="font-size: 9px; margin-right: 4px;"></i> 5.3%
                </div>
            </div>
            <div class="stat-value" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, system-ui, sans-serif !important; font-size: 26px; font-weight: 400 !important; color: #ffffff !important; letter-spacing: -0.3px; margin: 0 0 4px 0; line-height: 1.2; -webkit-font-smoothing: antialiased;">
                {{ $totalLabs ?? 0 }}
            </div>
            <div class="stat-label" style="font-size: 12px; color: #64748b; font-weight: 400; font-family: 'Inter', system-ui, sans-serif;">Total Labs</div>
        </div>

        {{-- Card 4: Total Generated Reports --}}
        <div class="stat-card" style="background: #111622; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 20px; border-top: 3px solid #f59e0b;">
            <div class="stat-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #fbbf24; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                    <i class="fa-solid fa-file-medical"></i>
                </div>
                <div class="stat-trend trend-down" style="background: rgba(239, 68, 68, 0.08); color: #f87171; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 500; font-family: 'Inter', system-ui, sans-serif;">
                    <i class="fa-solid fa-arrow-trend-down" style="font-size: 9px; margin-right: 4px;"></i> 2.7%
                </div>
            </div>
            <div class="stat-value" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, system-ui, sans-serif !important; font-size: 26px; font-weight: 400 !important; color: #ffffff !important; letter-spacing: -0.3px; margin: 0 0 4px 0; line-height: 1.2; -webkit-font-smoothing: antialiased;">
                {{ $totalGeneratedReports ?? 0 }}
            </div>
            <div class="stat-label" style="font-size: 12px; color: #64748b; font-weight: 400; font-family: 'Inter', system-ui, sans-serif;">Total Generated Reports</div>
        </div>

    </div>

    {{-- Main Content Section --}}
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Recent Generated Reports</h3>
                <p class="card-subtitle">Showing the latest reports generated across the system</p>
            </div>
            <div class="card-actions">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-ghost">
                    <i class="fa-solid fa-eye"></i> View All
                </a>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Kit & Activation</th>
                        <th>Generated Date</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReports as $report)
                        <tr>
                            <td>
                                <div class="td-user">
                                    <div class="td-avatar">
                                        {{ strtoupper(substr($report->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="td-name">{{ $report->user->name ?? 'N/A' }}</div>
                                        <div class="td-email">{{ $report->user->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-weight: 500; color: var(--text);">
                                    {{ $report->kit->activation_code ?? 'No Code' }}
                                </span>
                                <div style="font-size: 11px; color: var(--text-muted);">
                                    INV: {{ $report->kit->inv_code ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                {{ $report->created_at->format('M d, Y') }}
                                <div style="font-size: 11px; color: var(--text-muted);">
                                    {{ $report->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-active">
                                    <span class="badge-dot"></span> Active
                                </span>
                            </td>
                            <td>
                                <div class="action-btns" style="justify-content: flex-end;">
                                    <a href="{{-- route('admin.reports.edit', $report->id) --}}" class="action-btn edit" title="Edit Report">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="{{-- route('admin.reports.show', $report->id) --}}" class="action-btn" title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                <i class="fa-solid fa-folder-open" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                                No recent biomarker reports found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection