@extends('admin.layouts.app')

@section('title', __('messages.pickup_requests'))

@section('content')
<div class="content-wrapper">

    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">{{ __('messages.pickup_requests') }}</h4>
            <p class="text-muted mb-0" style="font-size:13px;">
                ইউজার যেসব sample pickup schedule করেছে সেগুলো এখান থেকে assign ও ট্র্যাক করুন
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
            <option value="">সব স্ট্যাটাস</option>
            <option value="requested" {{ request('status')=='requested'?'selected':'' }}>Requested</option>
            <option value="assigned" {{ request('status')=='assigned'?'selected':'' }}>Assigned</option>
            <option value="collected" {{ request('status')=='collected'?'selected':'' }}>Collected</option>
            <option value="delivered_to_lab" {{ request('status')=='delivered_to_lab'?'selected':'' }}>Delivered to Lab</option>
            <option value="failed" {{ request('status')=='failed'?'selected':'' }}>Failed</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> ফিল্টার</button>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Kit Code</th>
                        <th>Plan</th>
                        <th>Preferred Date/Time</th>
                        <th>Address</th>
                        <th>Courier</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pickups as $pickup)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $pickup->kit->user->name ?? 'N/A' }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ $pickup->contact_phone }}</div>
                            </td>
                            <td>{{ $pickup->kit->kit_code ?? '-' }}</td>
                            <td>{{ $pickup->kit->plan->name ?? '-' }}</td>
                            <td>
                                {{ optional($pickup->preferred_date)->format('d M Y') }}
                                <div class="text-muted" style="font-size:12px;">{{ $pickup->preferred_time_slot }}</div>
                            </td>
                            <td style="max-width:200px;font-size:13px;">{{ Str::limit($pickup->pickup_address, 60) }}</td>
                            <td>
                                @if($pickup->assigned_courier_name)
                                    {{ $pickup->assigned_courier_name }}
                                    <div class="text-muted" style="font-size:12px;">{{ $pickup->assigned_courier_phone }}</div>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $pBadge = [
                                        'requested' => 'secondary', 'assigned' => 'primary', 'collected' => 'info',
                                        'delivered_to_lab' => 'success', 'failed' => 'danger', 'cancelled' => 'dark',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $pBadge[$pickup->status] ?? 'secondary' }}">
                                    {{ str_replace('_',' ', ucfirst($pickup->status)) }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if($pickup->status === 'requested')
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $pickup->id }}">
                                        <i class="fa-solid fa-truck"></i> Assign
                                    </button>
                                @elseif($pickup->status === 'assigned')
                                    <form method="POST" action="{{ route('admin.pickup.collected', $pickup->id) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-info text-white">
                                            <i class="fa-solid fa-check"></i> Collected
                                        </button>
                                    </form>
                                @elseif($pickup->status === 'collected')
                                    <form method="POST" action="{{ route('admin.pickup.deliveredToLab', $pickup->id) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-success">
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

                        {{-- Assign Modal --}}
                        <div class="modal fade" id="assignModal{{ $pickup->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.pickup.assign', $pickup->id) }}">
                                        @csrf @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">কুরিয়ার Assign করুন</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Courier Name</label>
                                                <input type="text" name="assigned_courier_name" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Courier Phone</label>
                                                <input type="text" name="assigned_courier_phone" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Note</label>
                                                <textarea name="admin_notes" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                                            <button type="submit" class="btn btn-primary">Assign করুন</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

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
                                            <label class="form-label">কারণ</label>
                                            <textarea name="failure_reason" class="form-control" rows="2" required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                                            <button type="submit" class="btn btn-danger">Fail করুন</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">কোনো pickup request পাওয়া যায়নি।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $pickups->links() }}</div>

</div>
@endsection