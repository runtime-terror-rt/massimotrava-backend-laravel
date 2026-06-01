@extends('layouts.admin') {{-- Replace with your actual admin layout path --}}

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="card shadow-sm border-0 mb-4" style="background: var(--surface); border-radius: 12px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1" style="color: var(--text-main); font-weight: 600;">Biomarker Report Details</h4>
                    <p class="text-muted mb-0">Batch Invoice Track ID: <strong class="text-primary">{{ $mainReport->inv_code }}</strong></p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary px-3 me-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                    <a href="{{ route('admin.reports.edit', $mainReport->inv_code) }}" class="btn btn-warning px-3 text-dark">
                        <i class="fas fa-edit me-1"></i> Edit Batch
                    </a>
                </div>
            </div>

            <hr class="my-4" style="opacity: 0.1;">

            <div class="row g-3">
                <div class="col-sm-6 col-md-3">
                    <span class="text-muted d-block small text-uppercase">Patient / User</span>
                    <strong style="color: var(--text-main);">{{ $mainReport->user->name ?? 'N/A' }}</strong>
                    <span class="text-muted d-block small">{{ $mainReport->user->email ?? '' }}</span>
                </div>
                <div class="col-sm-6 col-md-3">
                    <span class="text-muted d-block small text-uppercase">Assigned Kit</span>
                    <strong style="color: var(--text-main);">{{ $mainReport->kit->name ?? 'N/A' }}</strong>
                </div>
                <div class="col-sm-6 col-md-3">
                    <span class="text-muted d-block small text-uppercase">Generated At</span>
                    <strong style="color: var(--text-main);">{{ $mainReport->created_at->format('M d, Y h:i A') }}</strong>
                </div>
                <div class="col-sm-6 col-md-3">
                    <span class="text-muted d-block small text-uppercase">Status</span>
                    @if($mainReport->status == 1)
                        <span class="badge bg-success-subtle text-success px-2.5 py-1">Active / Verified</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1">Draft</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="background: var(--surface); border-radius: 12px;">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <h5 class="mb-0" style="color: var(--text-main); font-weight: 600;">Recorded Test Values Matrix</h5>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="border-color: rgba(0,0,0,0.05);">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4" style="width: 30%;">Category</th>
                            <th scope="col" style="width: 40%;">Subcategory / Biomarker Marker</th>
                            <th scope="col" class="text-end pe-4" style="width: 30%;">Result Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Group the collection by category name or ID to cleanly merge cells visually
                            $groupedReports = $reports->groupBy('biomarker_category_id');
                        @endphp

                        @forelse($groupedReports as $catId => $items)
                            @foreach($items as $index => $item)
                                <tr>
                                    @if($index === 0)
                                        <td rowspan="{{ $items->count() }}" class="fw-semibold align-top pt-3 ps-4" style="color: var(--text-main); border-right: 1px solid rgba(0,0,0,0.03);">
                                            <i class="fas fa-folder me-2 text-muted small"></i>
                                            {{ $item->biomarkerCategory->name ?? 'Uncategorized' }}
                                        </td>
                                    @endif

                                    <td>
                                        <span class="d-block fw-medium" style="color: var(--text-main);">
                                            {{ $item->biomarkerSubcategory->name ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td class="text-end pe-4 fw-bold text-dark">
                                        <span class="bg-light px-3 py-1.5 rounded-pill d-inline-block">
                                            {{ $item->value }} 
                                            @if($item->unit)
                                                <small class="text-muted fw-normal ms-1">{{ $item->unit }}</small>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="fas fa-exclamation-circle fa-2x mb-3 d-block"></i>
                                    No entry values found inside this biomarker report packet structure.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection