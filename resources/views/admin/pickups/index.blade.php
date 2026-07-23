@extends('layouts.admin')

@section('title', __('messages.pickup_requests'))

@section('content')
<div class="content-wrapper">

    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">{{ __('messages.pickup_requests') }}</h4>
            <p class="text-muted mb-0" style="font-size:13px;">
                Assign and track sample pickup schedules created by users from here
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="filter-bar mb-3 d-flex gap-2">
        <select name="status" class="form-select" style="max-width:200px;">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="scheduled" {{ request('status')=='scheduled'?'selected':'' }}>Scheduled</option>
            <option value="collected" {{ request('status')=='collected'?'selected':'' }}>Collected</option>
            <option value="delivered_to_lab" {{ request('status')=='delivered_to_lab'?'selected':'' }}>Delivered to Lab</option>
            <option value="failed" {{ request('status')=='failed'?'selected':'' }}>Failed</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filter</button>
        @if(request('status'))
            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Clear</a>
        @endif
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Kit Name</th>
                        <th>Pickup Date & Time</th>
                        <th>Address</th>
                        <th>Notes</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pickups as $pickup)
                        <tr>
                            <td>
                                {{-- Direct Relationship Check --}}
                                <div class="fw-semibold">{{ $pickup->user->name ?? 'User #'.$pickup->user_id }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ $pickup->user->email ?? '' }}</div>
                            </td>
                            <td>{{ $pickup->kit_name ?? '-' }}</td>
                            <td>
                                {{-- Database Column Name: pickup_date --}}
                                {{ \Carbon\Carbon::parse($pickup->pickup_date)->format('d M Y') }}
                                <div class="text-muted" style="font-size:12px;">{{ $pickup->time_slot }}</div>
                            </td>
                            <td style="max-width:200px;font-size:13px;">{{ Str::limit($pickup->address, 60) }}</td>
                            <td>{{ $pickup->notes ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $pBadge = [
                                        'pending'   => 'warning', 
                                        'scheduled' => 'primary', 
                                        'collected' => 'info',
                                        'delivered_to_lab' => 'success', 
                                        'failed'    => 'danger', 
                                        'cancelled' => 'dark',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $pBadge[$pickup->status] ?? 'secondary' }}">
                                    {{ str_replace('_',' ', ucfirst($pickup->status)) }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if(in_array($pickup->status, ['pending', 'scheduled']))
                                    <form method="POST" action="{{ route('admin.pickup.collected', $pickup->id) }}">
                                        @csrf 
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-info text-white">
                                            Collected
                                        </button>
                                    </form>
                                @elseif($pickup->status === 'collected')
                                    <form method="POST" action="{{ route('admin.pickup.delivered-to-lab', $pickup->id) }}" class="d-inline">
                                        @csrf 
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fa-solid fa-flask"></i> Delivered to Lab
                                        </button>
                                    </form>
                                @endif

                                @if(!in_array($pickup->status, ['delivered_to_lab', 'failed', 'cancelled']))
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#failModal{{ $pickup->id }}">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>

                        {{-- Fail Modal --}}
                        <div class="modal fade" id="failModal{{ $pickup->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.pickup.failed', $pickup->id) }}">
                                        @csrf @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Pickup Failed</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label">Reason</label>
                                            <textarea name="failure_reason" class="form-control" rows="2" required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Mark as Failed</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No pickup requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $pickups->links() }}</div>

</div>
@endsection