@extends('layouts.admin')
@section('title', __('messages.dashboard_title', ['default' => 'Dashboard']))

@section('content')
<div class="content">
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('messages.dashboard_title') }}</h1>
            <p class="page-subtitle">{{ __('messages.welcome_back', ['name' => auth()->user()->name]) }}</p>
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
            <div class="stat-value"> {{ $totalTransactions }}</div>
            <div class="stat-label">{{ __('messages.total_transactions') }}</div>
        </div>

        {{-- Card 2: Total Users --}}
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
            <div class="stat-label">{{ __('messages.total_users') }}</div>
        </div>

        {{-- Card 3: Total Labs --}}
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
            <div class="stat-label">{{ __('messages.total_labs') }}</div>
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
            <div class="stat-label">{{ __('messages.total_generated_reports') }}</div>
        </div>
    </div>

    {{-- Main Content Section --}}
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">{{ __('messages.recent_generated_reports') }}</h3>
                <p class="card-subtitle">{{ __('messages.recent_reports_subtitle') }}</p>
            </div>
            <div class="card-actions">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-ghost">
                    <i class="fa-solid fa-eye"></i> {{ __('messages.view_all') }}
                </a>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('messages.customer') }}</th>
                        <th>{{ __('messages.kit_activation') }}</th>
                        <th>{{ __('messages.generated_date') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th style="text-align: right;">{{ __('messages.actions') }}</th>
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
                                        <div class="td-name">{{ $report->user->name ?? __('messages.not_available_short') }}</div>
                                        <div class="td-email">{{ $report->user->email ?? __('messages.not_available_short') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-weight: 500; color: var(--text);">
                                    {{ $report->kit->activation_code ?? __('messages.no_code') }}
                                </span>
                                <div style="font-size: 11px; color: var(--text-muted);">
                                    {{ __('messages.inv') }}: {{ $report->kit->inv_code ?? __('messages.not_available_short') }}
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
                                    <span class="badge-dot"></span> {{ __('messages.status_active') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btns" style="justify-content: flex-end;">
                                    <a href="{{ route('admin.reports.edit', $report->inv_code) }}" class="action-btn edit" title="{{ __('messages.edit_report') }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="{{ route('admin.reports.show', $report->id) }}" class="action-btn" title="{{ __('messages.view_details') }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                <i class="fa-solid fa-folder-open" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                                {{ __('messages.no_reports_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection