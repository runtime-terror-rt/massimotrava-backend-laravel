@extends('layouts.admin')

@section('content')
<div class="table-warp" style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: white;">
            @if(isset($specificUser))
                Reports for: {{ $specificUser->name }}
            @else
                All Biomarker Reports
            @endif
        </h2>
        <a href="{{route('admin.get.users')}}" style="color: #94a3b8; text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Back to Users
        </a>
    </div>
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.reports.create') }}" 
        style="background: #6366f1; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; transition: 0.3s;">
            <i class="fa-solid fa-plus"></i>
            Create Report
        </a>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
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
                <td>{{$report->user->name}}</td>
                <td><small>{{ $report->inv_code }}</small></td>
                <td>{{ $report->biomarkerSubcategory->title ?? 'N/A' }}</td>
                <td><strong>{{ $report->value }}</strong></td>
                <td>{{ $report->unit ?? '-' }}</td>
                <td>
                    <span class="badge {{ $report->status ? 'bg-success' : 'bg-danger' }}">
                        {{ $report->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>{{ $report->created_at->format('d M, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">No reports found for this user.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $reports->appends(request()->query())->links() }} 
        {{-- appends(request()->query()) না দিলে ২য় পেজে গেলে user_id ফিল্টার হারিয়ে যাবে --}}
    </div>
</div>
@endsection