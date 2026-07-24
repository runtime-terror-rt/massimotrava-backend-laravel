@extends('layouts.admin')

@section('page_title_key', 'sb_pickup')

@section('content')

<style>
*{box-sizing:border-box}
.pr{padding:4px 0}

/* Top bar */
.pr-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.pr-title{font-family:'Syne',sans-serif;font-size:24px;font-weight:800;letter-spacing:-0.03em;color:var(--text)}
.pr-sub{font-size:13px;color:var(--text-muted);margin-top:3px}
.pr-btn{display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:700;padding:11px 22px;border-radius:12px;border:none;cursor:pointer;background:linear-gradient(135deg,#10b981,#06b6d4);color:#000;transition:all .2s}
.pr-btn:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(16,185,129,0.3)}

/* Alert */
.alert-banner{background:rgba(34,211,238,0.04);border:1px solid rgba(34,211,238,0.18);border-radius:14px;padding:13px 20px;display:flex;align-items:center;gap:10px;font-size:13px;color:var(--text-muted);margin-bottom:22px}
.alert-banner i{color:#22d3ee;font-size:14px;flex-shrink:0}
.alert-banner b{color:var(--text)}

/* Stats strip */
.stat-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:24px}
.scard{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:18px 20px;transition:border-color .2s}
.scard:hover{border-color:var(--accent)}
.scard-label{font-size:10.5px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px;display:flex;align-items:center;gap:6px}
.scard-val{font-family:'Syne',sans-serif;font-size:30px;font-weight:900;line-height:1}
.scard-sub{font-size:11.5px;margin-top:5px;color:var(--text-muted)}

/* Table card */
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:20px;overflow:hidden}
.table-head-bar{display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid var(--border);flex-wrap:wrap;gap:10px}
.th-label{font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text)}
.filter-row{display:flex;gap:8px;flex-wrap:wrap}
.ftab{font-size:12px;font-weight:600;padding:5px 14px;border-radius:20px;cursor:pointer;border:1px solid var(--border);color:var(--text-muted);background:transparent;transition:all .2s;text-decoration:none}
.ftab:hover{color:var(--text);border-color:var(--accent)}
.ftab.active{background:rgba(34,211,238,0.1);color:#22d3ee;border-color:rgba(34,211,238,0.25)}

/* Table */
.tbl-wrap{overflow-x:auto}
.tbl{width:100%;border-collapse:collapse;min-width:700px}
.tbl th{font-size:10.5px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--text-muted);padding:12px 24px;text-align:left;border-bottom:1px solid var(--border);white-space:nowrap}
.tbl td{padding:15px 24px;font-size:13px;color:var(--text);border-bottom:1px solid var(--border);vertical-align:middle}
.tbl tr:last-child td{border-bottom:none}
.tbl tr:hover td{background:var(--surface-2)}

/* Kit cell */
.kit-cell{display:flex;align-items:center;gap:12px}
.kit-icon{width:38px;height:38px;border-radius:10px;background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.2);display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
.kit-name{font-size:13.5px;font-weight:600;color:var(--text)}
.kit-id{font-size:11px;color:var(--text-muted);margin-top:2px}

/* Badges */
.badge{font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px;display:inline-flex;align-items:center;gap:5px;white-space:nowrap}
.b-pending{background:rgba(245,158,11,0.08);color:#f59e0b;border:1px solid rgba(245,158,11,0.2)}
.b-scheduled{background:rgba(99,102,241,0.08);color:#a78bfa;border:1px solid rgba(99,102,241,0.2)}
.b-collected{background:rgba(16,185,129,0.08);color:#10b981;border:1px solid rgba(16,185,129,0.2)}
.b-cancelled{background:rgba(239,68,68,0.08);color:#f87171;border:1px solid rgba(239,68,68,0.2)}

/* Action buttons */
.act-row{display:flex;gap:6px;flex-wrap:wrap}
.act-btn{font-size:11.5px;font-weight:600;padding:5px 12px;border-radius:8px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:5px;transition:all .2s;text-decoration:none}
.ab-primary{background:rgba(34,211,238,0.1);color:#22d3ee;border:1px solid rgba(34,211,238,0.2)}
.ab-primary:hover{background:rgba(34,211,238,0.2)}
.ab-danger{background:rgba(239,68,68,0.08);color:#f87171;border:1px solid rgba(239,68,68,0.15)}
.ab-danger:hover{background:rgba(239,68,68,0.15)}
.ab-ghost{background:var(--surface-2);color:var(--text-muted);border:1px solid var(--border)}
.ab-ghost:hover{background:var(--surface);color:var(--text)}

/* Empty state */
.empty-state{padding:60px 24px;text-align:center}
.empty-icon{font-size:40px;margin-bottom:14px;opacity:0.4}
.empty-title{font-size:15px;font-weight:600;color:var(--text);margin-bottom:6px}
.empty-sub{font-size:13px;color:var(--text-muted)}

@media(max-width:900px){.stat-strip{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.stat-strip{grid-template-columns:1fr 1fr}.pr-topbar{flex-direction:column;align-items:flex-start}}
</style>

<div class="pr">

    {{-- Top bar --}}
    <div class="pr-topbar">
        <div>
            <div class="pr-title">Pickup Requests</div>
            <div class="pr-sub">Manage your home kit collection requests</div>
        </div>
        <button type="button" class="pr-btn" onclick="openPickupModal()">
            <i class="fa-solid fa-plus"></i> New Pickup Request
        </button>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert-banner" style="border-color:rgba(16,185,129,0.25);margin-bottom:16px">
            <i class="fa-solid fa-circle-check" style="color:#10b981"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-banner" style="border-color:rgba(239,68,68,0.25);margin-bottom:16px">
            <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Alert info --}}
    <div class="alert-banner">
        <i class="fa-solid fa-circle-info"></i>
        <span><b>Note:</b> Pickup requests must be placed at least 24 hours in advance. Our courier will collect your sample within the selected time slot.</span>
    </div>

    {{-- Stats --}}
    <div class="stat-strip">
        <div class="scard">
            <div class="scard-label"><i class="fa-solid fa-box" style="color:#22d3ee"></i> Total</div>
            <div class="scard-val" style="color:#22d3ee">{{ $stats['total'] ?? 0 }}</div>
            <div class="scard-sub">All requests</div>
        </div>
        <div class="scard">
            <div class="scard-label"><i class="fa-solid fa-clock" style="color:#f59e0b"></i> Pending</div>
            <div class="scard-val" style="color:#f59e0b">{{ $stats['pending'] ?? 0 }}</div>
            <div class="scard-sub">Awaiting schedule</div>
        </div>
        <div class="scard">
            <div class="scard-label"><i class="fa-solid fa-calendar-check" style="color:#a78bfa"></i> Scheduled</div>
            <div class="scard-val" style="color:#a78bfa">{{ $stats['scheduled'] ?? 0 }}</div>
            <div class="scard-sub">Upcoming pickup</div>
        </div>
        <div class="scard">
            <div class="scard-label"><i class="fa-solid fa-circle-check" style="color:#10b981"></i> Collected</div>
            <div class="scard-val" style="color:#10b981">{{ $stats['collected'] ?? 0 }}</div>
            <div class="scard-sub">Sent to lab</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-head-bar">
            <div class="th-label">📦 All Requests</div>
            <div class="filter-row">
                <a href="?status=" class="ftab {{ !request('status') ? 'active' : '' }}">All</a>
                <a href="?status=pending" class="ftab {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
                <a href="?status=scheduled" class="ftab {{ request('status') === 'scheduled' ? 'active' : '' }}">Scheduled</a>
                <a href="?status=collected" class="ftab {{ request('status') === 'collected' ? 'active' : '' }}">Collected</a>
                <a href="?status=cancelled" class="ftab {{ request('status') === 'cancelled' ? 'active' : '' }}">Cancelled</a>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Kit</th>
                        <th>Pickup Date</th>
                        <th>Time Slot</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pickupRequests ?? [] as $req)
                    <tr>
                        <td>
                            <div class="kit-cell">
                                <div class="kit-icon">{{ $req->kit_icon ?? '🧬' }}</div>
                                <div>
                                    <div class="kit-name">{{ $req->kit_name ?? 'Test Kit' }}</div>
                                    <div class="kit-id">#KIT-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $req->pickup_date ? \Carbon\Carbon::parse($req->pickup_date)->format('M d, Y') : '—' }}</td>
                        <td>{{ $req->time_slot ?? '—' }}</td>
                        <td style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $req->address ?? '—' }}</td>
                        <td>
                            @php
                                $badgeClass = match($req->status) {
                                    'pending'   => 'b-pending',
                                    'scheduled' => 'b-scheduled',
                                    'collected' => 'b-collected',
                                    'cancelled' => 'b-cancelled',
                                    default     => 'b-pending',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">● {{ ucfirst($req->status) }}</span>
                        </td>
                        <td>
                            <div class="act-row">
                                @if($req->status === 'pending')
                                    <button type="button" class="act-btn ab-primary" onclick="openPickupRescheduleModal({{ $req->id }})">
                                        <i class="fa-solid fa-calendar-plus"></i> Schedule
                                    </button>
                                    <form action="{{ route('user.pickup.cancel', $req->id) }}" method="POST" onsubmit="return confirm('Cancel this request?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="act-btn ab-danger">
                                            <i class="fa-solid fa-xmark"></i> Cancel
                                        </button>
                                    </form>
                                @elseif($req->status === 'scheduled')
                                    <button type="button" class="act-btn ab-primary" onclick="openPickupRescheduleModal({{ $req->id }}, '{{ $req->pickup_date }}', '{{ $req->time_slot }}')">
                                        <i class="fa-solid fa-calendar-pen"></i> Reschedule
                                    </button>
                                    <form action="{{ route('user.pickup.cancel', $req->id) }}" method="POST" onsubmit="return confirm('Cancel this request?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="act-btn ab-danger">
                                            <i class="fa-solid fa-xmark"></i> Cancel
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('user.pickup.show', $req->id) }}" class="act-btn ab-ghost">
                                        <i class="fa-solid fa-eye"></i> View Details
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">📦</div>
                                <div class="empty-title">No pickup requests yet</div>
                                <div class="empty-sub">Click "New Pickup Request" to schedule your first home kit collection.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('modals')

<style>
.pu-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.75);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 999999;
    align-items: center;
    justify-content: center;
}
.pu-modal-overlay.open {
    display: flex;
}
.pu-modal {
    background: #111827;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 28px 32px;
    width: 90%;
    max-width: 480px;
    position: relative;
    box-shadow: 0 25px 60px rgba(0,0,0,0.6);
}
.pu-modal-title{font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:#fff;margin-bottom:20px}
.pu-modal-close{position:absolute;top:16px;right:16px;background:none;border:none;color:#64748b;font-size:18px;cursor:pointer;line-height:1}
.pu-modal-close:hover{color:#f1f5f9}
.pu-form-group{margin-bottom:18px}
.pu-form-label{font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;display:block}
.pu-form-input{width:100%;padding:11px 14px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;color:#f1f5f9;font-size:13.5px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s}
.pu-form-input:focus{border-color:rgba(34,211,238,0.4)}
.pu-form-input option {
    background: #1e293b;
    color: #f1f5f9;
}
.pu-form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.pu-modal-footer{display:flex;justify-content:flex-end;gap:10px;margin-top:24px;padding-top:18px;border-top:1px solid rgba(255,255,255,0.05)}
.pu-btn-cancel{padding:10px 20px;border-radius:10px;border:1px solid rgba(255,255,255,0.1);background:transparent;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer}
.pu-btn-submit{padding:10px 24px;border-radius:10px;border:none;background:linear-gradient(135deg,#10b981,#06b6d4);color:#000;font-size:13px;font-weight:800;cursor:pointer}
</style>

{{-- New Request Modal --}}
<div class="pu-modal-overlay" id="newRequestModal">
    <div class="pu-modal">
        <button type="button" class="pu-modal-close" onclick="closePickupModal()">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="pu-modal-title">📦 New Pickup Request</div>

        <form action="{{ route('user.pickup.store') }}" method="POST">
            @csrf
            <div class="pu-form-group">
                <label class="pu-form-label">Kit</label>
                <select name="kit_id" class="pu-form-input" required>
                    <option value="">Select an activated kit</option>
                    @forelse($activatedKits as $kit)
                        <option value="{{ $kit->id }}">
                            {{ $kit->activation_code }}
                            @if($kit->inv_code) — Inv: {{ $kit->inv_code }} @endif
                        </option>
                    @empty
                        <option value="" disabled>No activated kits available</option>
                    @endforelse
                </select>
                @if($activatedKits->isEmpty())
                    <small class="d-block mt-1">
                        You have no activated kits yet. Activate a kit first before scheduling a pickup.
                    </small>
                @endif
            </div>
            <div class="pu-form-row">
                <div class="pu-form-group" style="margin-bottom:0">
                    <label class="pu-form-label">Preferred Date</label>
                    <input type="date" name="pickup_date" class="pu-form-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                </div>
                <div class="pu-form-group" style="margin-bottom:0">
                    <label class="pu-form-label">Time Slot</label>
                    <select name="time_slot" class="pu-form-input" required>
                        <option value="">Select slot</option>
                        <option value="08:00 – 10:00 AM">08:00 – 10:00 AM</option>
                        <option value="10:00 – 12:00 PM">10:00 – 12:00 PM</option>
                        <option value="12:00 – 02:00 PM">12:00 – 02:00 PM</option>
                        <option value="02:00 – 04:00 PM">02:00 – 04:00 PM</option>
                        <option value="04:00 – 06:00 PM">04:00 – 06:00 PM</option>
                    </select>
                </div>
            </div>
            <div class="pu-form-group" style="margin-top:18px">
                <label class="pu-form-label">Pickup Address</label>
                <input type="text" name="address" class="pu-form-input" placeholder="Enter your full address" required value="{{ auth()->user()->address ?? '' }}">
            </div>
            <div class="pu-form-group">
                <label class="pu-form-label">Notes (Optional)</label>
                <textarea name="notes" class="pu-form-input" rows="2" placeholder="Any special instructions for the courier…" style="resize:vertical"></textarea>
            </div>
            <div class="pu-modal-footer">
                <button type="button" class="pu-btn-cancel" onclick="closePickupModal()">Cancel</button>
                <button type="submit" class="pu-btn-submit"><i class="fa-solid fa-paper-plane" style="margin-right:6px"></i>Submit Request</button>
            </div>
        </form>
    </div>
</div>

{{-- Reschedule Modal --}}
<div class="pu-modal-overlay" id="rescheduleModal">
    <div class="pu-modal">
        <button type="button" class="pu-modal-close" onclick="closePickupRescheduleModal()">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="pu-modal-title">📅 Reschedule Pickup</div>
        <form id="rescheduleForm" method="POST">
            @csrf @method('PATCH')
            <div class="pu-form-row">
                <div class="pu-form-group" style="margin-bottom:0">
                    <label class="pu-form-label">New Date</label>
                    <input type="date" name="pickup_date" value="pickup_date" id="rescheduleDate" class="pu-form-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                </div>
                <div class="pu-form-group" style="margin-bottom:0">
                    <label class="pu-form-label">Time Slot</label>
                    <select name="time_slot" id="rescheduleSlot" class="pu-form-input" required>
                        <option value="">Select slot</option>
                        <option value="08:00 – 10:00 AM">08:00 – 10:00 AM</option>
                        <option value="10:00 – 12:00 PM">10:00 – 12:00 PM</option>
                        <option value="12:00 – 02:00 PM">12:00 – 02:00 PM</option>
                        <option value="02:00 – 04:00 PM">02:00 – 04:00 PM</option>
                        <option value="04:00 – 06:00 PM">04:00 – 06:00 PM</option>
                    </select>
                </div>
            </div>
            <div class="pu-modal-footer">
                <button type="button" class="pu-btn-cancel" onclick="closePickupRescheduleModal()">Cancel</button>
                <button type="submit" class="pu-btn-submit"><i class="fa-solid fa-calendar-check" style="margin-right:6px"></i>Confirm</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPickupModal() {
        document.getElementById('newRequestModal').classList.add('open');
    }
    function closePickupModal() {
        document.getElementById('newRequestModal').classList.remove('open');
    }
    function openPickupRescheduleModal(id, date, slot) {
        var form = document.getElementById('rescheduleForm');
        var url = "{{ route('user.pickup.reschedule', ':id') }}";
        form.action = url.replace(':id', id); 
        
        if (date) document.getElementById('rescheduleDate').value = date;
        if (slot) document.getElementById('rescheduleSlot').value = slot;
        document.getElementById('rescheduleModal').classList.add('open');
    }
    function closePickupRescheduleModal() {
        document.getElementById('rescheduleModal').classList.remove('open');
    }

    document.getElementById('newRequestModal').addEventListener('click', function(e) {
        if (e.target === this) closePickupModal();
    });
    document.getElementById('rescheduleModal').addEventListener('click', function(e) {
        if (e.target === this) closePickupRescheduleModal();
    });
</script>

@endpush