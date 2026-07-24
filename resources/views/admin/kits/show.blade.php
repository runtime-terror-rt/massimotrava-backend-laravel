@extends('admin.layouts.app')

@section('title', __('messages.kit_manager'))

@section('content')
<div class="content-wrapper">

    <a href="{{ route('admin.kits.index') }}" class="btn btn-sm btn-light mb-3">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- User Info Card --}}
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between flex-wrap">
            <div>
                <h5 class="mb-1">{{ $subscription->user->name }}</h5>
                <div class="text-muted">{{ $subscription->user->email }}</div>
            </div>
            <div class="text-end">
                <span class="badge bg-info-subtle text-info fs-6">{{ $subscription->plan->name }}</span>
                <div class="text-muted mt-1" style="font-size:13px;">
                    Kit Limit: {{ $subscription->plan->kit_limit ?? 'Unlimited' }} |
                    বাকি: {{ $subscription->remainingKits() ?? 'Unlimited' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Kit History Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Kit History</strong>
            @if($subscription->status === 'active')
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addKitModal">
                    <i class="fa-solid fa-plus"></i> + Add New Kit
                </button>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Activation Code</th>
                        <th>Invoice Code</th>
                        <th>Status</th>
                        <th>Courier</th>
                        <th>Tracking No.</th>
                        <th>Shipped</th>
                        <th>Delivered</th>
                        <th>Activated</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscription->kits as $kit)
                        <tr>
                            <td class="fw-semibold">{{ $kit->activation_code }}</td>
                            <td><code>{{ $kit->inv_code ?? '-' }}</code></td>
                            <td>
                                @php
                                    $badgeMap = [
                                        'processing' => 'warning', 'shipped' => 'primary',
                                        'delivered' => 'info', 'activated' => 'success',
                                        'pickup_scheduled' => 'warning', 'pickup_assigned' => 'primary',
                                        'sample_collected' => 'info', 'received_at_lab' => 'secondary',
                                        'processing_at_lab' => 'secondary', 'results_ready' => 'success',
                                        'completed' => 'dark', 'cancelled' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $badgeMap[$kit->status] ?? 'secondary' }}">
                                    {{ str_replace('_', ' ', ucfirst($kit->status)) }}
                                </span>
                            </td>
                            <td>{{ $kit->courier_name ?? '-' }}</td>
                            <td>{{ $kit->tracking_number ?? '-' }}</td>
                            <td>{{ optional($kit->shipped_at)->format('d M Y') ?? '-' }}</td>
                            <td>{{ optional($kit->delivered_at)->format('d M Y') ?? '-' }}</td>
                            <td>{{ optional($kit->activated_at)->format('d M Y') ?? '-' }}</td>
                            <td class="text-end">
                                {{-- Only dispatch-side statuses are editable here.
                                     "activated" is set exclusively via the user's
                                     activateKit() flow, which validates ownership
                                     and generates inv_code — it must not be
                                     settable from this generic status dropdown. --}}
                                @if(!in_array($kit->status, ['activated', 'completed', 'cancelled']))
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#statusModal{{ $kit->id }}">
                                        <i class="fa-solid fa-pen"></i> Status
                                    </button>
                                @else
                                    <span class="text-muted" style="font-size:12px;">-</span>
                                @endif
                            </td>
                        </tr>

                        @if(!in_array($kit->status, ['activated', 'completed', 'cancelled']))
                        {{-- Status Update Modal --}}
                        <div class="modal fade" id="statusModal{{ $kit->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.kits.updateDispatchStatus', $kit->id) }}">
                                        @csrf @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $kit->activation_code }} — Status Updata</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="processing" {{ $kit->status=='processing'?'selected':'' }}>Processing</option>
                                                    <option value="shipped" {{ $kit->status=='shipped'?'selected':'' }}>Shipped</option>
                                                    <option value="delivered" {{ $kit->status=='delivered'?'selected':'' }}>Delivered</option>
                                                    <option value="cancelled" {{ $kit->status=='cancelled'?'selected':'' }}>Cancelled</option>
                                                </select>
                                                <div class="form-text">
                                                    "Activated" is set automatically when the user scans/enters the code — not selectable here.
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Courier Name</label>
                                                <input type="text" name="courier_name" class="form-control" value="{{ $kit->courier_name }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tracking Number</label>
                                                <input type="text" name="tracking_number" class="form-control" value="{{ $kit->tracking_number }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4"></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Kit Modal — dispatches a kit against THIS subscription --}}
    <div class="modal fade" id="addKitModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.kits.dispatch') }}">
                    @csrf
                    <input type="hidden" name="user_subscription_id" value="{{ $subscription->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">+ Add New Kit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Activation Code <span class="text-danger">*</span></label>
                            <input type="text" name="activation_code" class="form-control" required
                                   placeholder="Scan or type the code printed on the kit box">
                            <div class="form-text">Must match the manufacturer QR/code on the physical box exactly.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Courier Name</label>
                            <input type="text" name="courier_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Admin Note</label>
                            <textarea name="admin_notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">+ Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection