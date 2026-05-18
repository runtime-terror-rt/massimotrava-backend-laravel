@extends('layouts.admin')

@section('content')
<div class="table-warp" style="padding: 20px;">

    <!-- HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: white; margin: 0;">
            <i class="fa-solid fa-file-medical" style="color: #6366f1; margin-right: 10px;"></i>
            @if(isset($specificUser))
                Reports for: <span style="color: #38bdf8;">{{ $specificUser->name }}</span>
            @else
                All Biomarker Reports
            @endif
        </h2>

        <a href="{{ route('admin.get.users') }}" style="color: #94a3b8; text-decoration: none; font-size: 14px; background: #0f172a; padding: 8px 15px; border-radius: 6px; border: 1px solid #334155;">
            <i class="fa-solid fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <!-- ACTION BAR -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ route('admin.reports.create') }}" 
           style="background: #6366f1; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: bold; transition: 0.3s;">
            <i class="fa-solid fa-plus"></i>
            Create New Report
        </a>

        @if($reports->count() > 0)
        <a href="{{ route('admin.reports.download.pdf', request()->query()) }}" 
           style="background: #ef4444; color: white; padding: 10px 18px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500;">
            <i class="fa-solid fa-file-pdf"></i>
            Bulk Download PDF
        </a>
        @endif
    </div>
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
    <!-- TABLE -->
    <div style="background: #1e293b; border-radius: 12px; overflow: hidden; border: 1px solid #334155;">
        <table class="admin-table" style="width: 100%; border-collapse: collapse; color: #e2e8f0;">
            <thead>
                <tr style="background: #0f172a; text-align: left;">
                    <th style="padding: 15px;">Date & Invoice</th>
                    <th style="padding: 15px;">User Info</th>
                    <th style="padding: 15px;">Tests (Category > Subcategory)</th>
                    <th style="padding: 15px; text-align: center;">Result</th>
                    <th style="padding: 15px; text-align: center;">Status</th>
                    <th style="padding: 15px; text-align: right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                {{-- Grouping reports by inv_code for better visibility --}}
                @forelse($reports->groupBy('inv_code') as $invCode => $group)
                    @php $firstReport = $group->first(); @endphp
                    <tr style="border-bottom: 1px solid #334155; vertical-align: top;">
                        <!-- Date & Invoice -->
                        <td style="padding: 15px;">
                            <div style="font-weight: bold; color: #f8fafc;">{{ $firstReport->created_at->format('d M, Y') }}</div>
                            <small style="background: #334155; padding: 2px 6px; border-radius: 4px; color: #38bdf8;">{{ $invCode }}</small>
                        </td>

                        <!-- User Info -->
                        <td style="padding: 15px;">
                            <div style="font-weight: 500;">{{ $firstReport->user->name ?? 'N/A' }}</div>
                            <div style="font-size: 12px; color: #94a3b8;">{{ $firstReport->user->email ?? '' }}</div>
                        </td>

                        <!-- Nested Tests -->
                        <td style="padding: 15px;">
                            @foreach($group as $item)
                                <div style="margin-bottom: 8px; padding-bottom: 5px; border-bottom: 1px dashed #334155; last-child: border:none;">
                                    <span style="color: #94a3b8; font-size: 12px;">{{ $item->biomarkerCategory->title ?? 'N/A' }}</span> <br>
                                    <i class="fa-solid fa-chevron-right" style="font-size: 10px; color: #6366f1;"></i> 
                                    <span style="color: #e2e8f0;">{{ $item->biomarkerSubcategory->title ?? 'N/A' }}</span>
                                </div>
                            @endforeach
                        </td>

                        <!-- Results -->
                        <td style="padding: 15px; text-align: center;">
                            @foreach($group as $item)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #22c55e;">{{ $item->value }}</strong> 
                                    <small style="color: #94a3b8;">{{ $item->unit }}</small>
                                </div>
                            @endforeach
                        </td>

                        <!-- Status -->
                        <td style="padding: 15px; text-align: center;">
                            <span class="badge {{ $firstReport->status ? 'bg-success' : 'bg-danger' }}" style="padding: 5px 10px; border-radius: 20px; font-size: 11px;">
                                {{ $firstReport->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td style="padding: 15px; text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: 10px; align-items: center;">
                                
                                <form action="{{ route('admin.reports.send.email') }}" method="POST" class="d-inline send-email-form">
                                    @csrf
                                    <input type="hidden" name="inv_code" value="{{ $invCode }}">
                                    <button type="submit" title="Send Email to User" 
                                            style="background: none; border: none; color: #38bdf8; cursor: pointer; padding: 0;">
                                        <i class="fa-solid fa-envelope" style="font-size: 18px;"></i>
                                    </button>
                                </form>

                                <a href="{{ route('admin.reports.download.pdf', ['inv_code' => $invCode]) }}" title="Download Invoice PDF" style="color: #fbbf24;">
                                    <i class="fa-solid fa-print text-lg"></i>
                                </a>
                                
                                <form action="#" method="POST" onsubmit="return confirm('Delete this entire invoice?');" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="fa-regular fa-folder-open" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                            No reports found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
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