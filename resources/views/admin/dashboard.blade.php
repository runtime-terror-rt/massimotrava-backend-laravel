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

    <div class="stats-grid">
        {{-- Card 1: Total Transactions --}}
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="stat-trend trend-up">
                    <i class="fa-solid fa-arrow-trend-up"></i> 12.4%
                </div>
            </div>
            <div class="stat-value">{{ $totalTransactions }}</div>
            <div class="stat-label">Total Transactions</div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-trend trend-up">
                    <i class="fa-solid fa-arrow-trend-up"></i> 8.1%
                </div>
            </div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-label">Total Users</div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-flask"></i>
                </div>
                <div class="stat-trend trend-up">
                    <i class="fa-solid fa-arrow-trend-up"></i> 5.3%
                </div>
            </div>
            <div class="stat-value">{{ number_format($totalLabs) }}</div>
            <div class="stat-label">Total Labs</div>
        </div>

        {{-- Card 4: Total Generated Reports --}}
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-file-medical"></i>
                </div>
                <div class="stat-trend trend-down">
                    <i class="fa-solid fa-arrow-trend-down"></i> 2.7%
                </div>
            </div>
            <div class="stat-value">{{ number_format($totalGeneratedReports) }}</div>
            <div class="stat-label">Total Generated Reports</div>
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