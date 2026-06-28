@extends('layouts.admin')

@section('title', 'Pickup Requests')

@section('content')

<style>
*{box-sizing:border-box}
.pr{padding:4px 0}

.pr-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.pr-title{font-family:'Syne',sans-serif;font-size:24px;font-weight:800;letter-spacing:-0.03em;color:#fff}
.pr-sub{font-size:13px;color:#64748b;margin-top:3px}

/* Stats */
.stat-strip{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:24px}
.scard{background:var(--surface);border:1px solid rgba(255,255,255,0.06);border-radius:16px;padding:18px 20px;transition:border-color .2s}
.scard:hover{border-color:rgba(255,255,255,0.12)}
.scard-label{font-size:10.5px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#64748b;margin-bottom:8px}
.scard-val{font-family:'Syne',sans-serif;font-size:30px;font-weight:900;line-height:1}
.scard-sub{font-size:11.5px;margin-top:5px;color:#64748b}

/* Table card */
.table-card{background:var(--surface);border:1px solid rgba(255,255,255,0.06);border-radius:20px;overflow:hidden}
.table-head-bar{display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid rgba(255,255,255,0.05);flex-wrap:wrap;gap:12px}
.th-label{font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:#fff}

/* Search */
.search-wrap{position:relative}
.search-input{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:9px 14px 9px 36px;color:#f1f5f9;font-size:13px;outline:none;width:220px;font-family:'Inter',sans-serif;transition:border-color .2s}
.search-input:focus{border-color:rgba(34,211,238,0.35)}
.search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#64748b;font-size:13px}

.filter-row{display:flex;gap:8px;flex-wrap:wrap}
.ftab{font-size:12px;font-weight:600;padding:5px 14px;border-radius:20px;cursor:pointer;border:1px solid rgba(255,255,255,0.08);color:#64748b;background:transparent;transition:all .2s;text-decoration:none}
.ftab:hover{color:#fff;border-color:rgba(255,255,255,0.2)}
.ftab.active{background:rgba(34,211,238,0.1);color:#22d3ee;border-color:rgba(34,211,238,0.25)}

/* Table */
.tbl-wrap{overflow-x:auto}
.tbl{width:100%;border-collapse:collapse;min-width:900px}
.tbl th{font-size:10.5px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#475569;padding:12px 20px;text-align:left;border-bottom:1px solid rgba(255,255,255,0.04);white-space:nowrap}
.tbl td{padding:14px 20px;font-size:13px;color:#cbd5e1;border-bottom:1px solid rgba(255,255,255,0.03);vertical-align:middle}
.tbl tr:last-child td{border-bottom:none}
.tbl tr:hover td{background:rgba(255,255,255,0.02)}

/* User cell */
.user-cell{display:flex;align-items:center;gap:10px}
.user-av{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a78bfa);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}
.user-name{font-size:13px;font-weight:600;color:#f1f5f9}
.user-email{font-size:11px;color:#64748b;margin-top:1px}

.kit-cell{display:flex;align-items:center;gap:10px}
.kit-icon{width:34px;height:34px;border-radius:9px;background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.2);display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
.kit-name{font-size:13px;font-weight:600;color:#f1f5f9}
.kit-id{font-size:11px;color:#64748b;margin-top:1px}

/* Badges */
.badge{font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px;display:inline-flex;align-items:center;gap:5px;white-space:nowrap}
.b-pending{background:rgba(245,158,11,0.08);color:#f59e0b;border:1px solid rgba(245,158,11,0.2)}
.b-scheduled{background:rgba(99,102,241,0.08);color:#a78bfa;border:1px solid rgba(99,102,241,0.2)}
.b-collected{background:rgba(16,185,129,0.08);color:#10b981;border:1px solid rgba(16,185,129,0.2)}
.b-cancelled{background:rgba(239,68,68,0.08);color:#f87171;border:1px solid rgba(239,68,68,0.2)}

/* Action buttons */
.act-row{display:flex;gap:6px;flex-wrap:wrap}
.act-btn{font-size:11.5px;font-weight:600;padding:5px 12px;border-radius:8px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:5px;transition:all .2s;text-decoration:none}
.ab-green{background:rgba(16,185,129,0.1);color:#10b981;border:1px solid rgba(16,185,129,0.2)}
.ab-green:hover{background:rgba(16,185,129,0.2)}
.ab-violet{background:rgba(167,139,250,0.1);color:#a78bfa;border:1px solid rgba(167,139,250,0.2)}
.ab-violet:hover{background:rgba(167,139,250,0.2)}
.ab-danger{background:rgba(239,68,68,0.08);color:#f87171;border:1px solid rgba(239,68,68,0.15)}
.ab-danger:hover{background:rgba(239,68,68,0.15)}
.ab-ghost{background:rgba(255,255,255,0.04);color:#94a3b8;border:1px solid rgba(255,255,255,0.08)}
.ab-ghost:hover{background:rgba(255,255,255,0.08);color:#f1f5f9}

/* Empty state */
.empty-state{padding:60px 24px;text-align:center}
.empty-icon{font-size:40px;margin-bottom:14px;opacity:0.4}
.empty-title{font-size:15px;font-weight:600;color:#f1f5f9;margin-bottom:6px}
.empty-sub{font-size:13px;color:#64748b}

@media(max-width:1024px){.stat-strip{grid-template-columns:repeat(3,1fr)}}
@media(max-width:600px){.stat-strip{grid-template-columns:1fr 1fr}}
</style>

<div class="pr">

    {{-- Top bar --}}
    <div class="pr-topbar">
        <div>
            <div class="pr-title">Pickup Requests</div>
            <div class="pr-sub">Manage and schedule all user kit collection requests</div>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);color:#10b981;padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:13px;display:flex;align-items:center;gap:8px">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="stat-strip">
        <div class="scard">
            <div class="scard-label">Total</div>
            <div class="scard-val" style="color:#22d3ee">{{ $stats['total'] ?? 0 }}</div>
            <div class="scard-sub">All requests</div>
        </div>
        <div class="scard">
            <div class="scard-label">Pending</div>
            <div class="scard-val" style="color:#f59e0b">{{ $stats['pending'] ?? 0 }}</div>
            <div class="scard-sub">Needs scheduling</div>
        </div>
        <div class="scard">
            <div class="scard-label">Scheduled</div>
            <div class="scard-val" style="color:#a78bfa">{{ $stats['scheduled'] ?? 0 }}</div>
            <div class="scard-sub">Upcoming</div>
        </div>
        <div class="scard">
            <div class="scard-label">Collected</div>
            <div class="scard-val" style="color:#10b981">{{ $stats['collected'] ?? 0 }}</div>
            <div class="scard-sub">Sent to lab</div>
        </div>
        <div class="scard">
            <div class="scard-label">Cancelled</div>
            <div class="scard-val" style="color:#f87171">{{ $stats['cancelled'] ?? 0 }}</div>
            <div class="scard-sub">By user/admin</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-head-bar">
            <div class="th-label">📦 All Requests</div>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" class="search-input" id="searchInput" placeholder="Search user or kit…" oninput="filterTable()">
                </div>
                <div class="filter-row">
                    <a href="?status=" class="ftab {{ !request('status') ? 'active' : '' }}">All</a>
                    <a href="?status=pending" class="ftab {{ request('status')==='pending' ? 'active' : '' }}">Pending</a>
                    <a href="?status=scheduled" class="ftab {{ request('status')==='scheduled' ? 'active' : '' }}">Scheduled</a>
                    <a href="?status=collected" class="ftab {{ request('status')==='collected' ? 'active' : '' }}">Collected</a>
                    <a href="?status=cancelled" class="ftab {{ request('status')==='cancelled' ? 'active' : '' }}">Cancelled</a>
                </div>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="tbl" id="mainTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
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
                        <td style="color:#475569;font-size:12px">{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="user-cell">
                                <div class="user-av">{{ strtoupper(substr($req->user->name ?? 'U', 0, 1)) }}</div>
                                <div>
                                    <div class="user-name">{{ $req->user->name ?? '—' }}</div>
                                    <div class="user-email">{{ $req->user->email ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
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
                        <td style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $req->address ?? '—' }}</td>
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
                                @if(in_array($req->status, ['pending','scheduled']))
                                    <button class="act-btn ab-violet" onclick="openAdminScheduleModal({{ $req->id }}, '{{ $req->pickup_date }}', '{{ $req->time_slot }}')">
                                        <i class="fa-solid fa-calendar-pen"></i> Schedule
                                    </button>
                                    <form action="{{ route('admin.pickup.collect', $req->id) }}" method="POST" onsubmit="return confirm('Mark as collected?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="act-btn ab-green">
                                            <i class="fa-solid fa-circle-check"></i> Collected
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.pickup.cancel', $req->id) }}" method="POST" onsubmit="return confirm('Cancel this request?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="act-btn ab-danger">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.pickup.show', $req->id) }}" class="act-btn ab-ghost">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-icon">📦</div>
                                <div class="empty-title">No pickup requests found</div>
                                <div class="empty-sub">Requests will appear here once users submit them.</div>
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
/* Bootstrap এর .modal class এর সাথে conflict এড়াতে আলাদা prefix */
.adm-modal-overlay {
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
.adm-modal-overlay.open { display: flex; }
.adm-modal {
    background: #111827;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 20px;
    padding: 28px 32px;
    width: 100%;
    max-width: 460px;
    position: relative;
    box-shadow: 0 25px 60px rgba(0,0,0,0.6);
}
.adm-modal-title { font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:#fff;margin-bottom:20px }
.adm-modal-close { position:absolute;top:16px;right:16px;background:none;border:none;color:#64748b;font-size:18px;cursor:pointer;line-height:1 }
.adm-modal-close:hover { color:#f1f5f9 }
.adm-form-group { margin-bottom:18px }
.adm-form-label { font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;display:block }
.adm-form-input { width:100%;padding:11px 14px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;color:#f1f5f9;font-size:13.5px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s }
.adm-form-input:focus { border-color:rgba(34,211,238,0.4) }
.adm-form-input option { background:#1e293b; color:#f1f5f9 }
.adm-form-row { display:grid;grid-template-columns:1fr 1fr;gap:14px }
.adm-modal-footer { display:flex;justify-content:flex-end;gap:10px;margin-top:24px;padding-top:18px;border-top:1px solid rgba(255,255,255,0.05) }
.adm-btn-cancel { padding:10px 20px;border-radius:10px;border:1px solid rgba(255,255,255,0.1);background:transparent;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer }
.adm-btn-submit { padding:10px 24px;border-radius:10px;border:none;background:linear-gradient(135deg,#10b981,#06b6d4);color:#000;font-size:13px;font-weight:800;cursor:pointer }
</style>

{{-- Admin Schedule Modal --}}
<div class="adm-modal-overlay" id="adminScheduleModal">
    <div class="adm-modal">
        <button class="adm-modal-close" onclick="closeAdminScheduleModal()">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="adm-modal-title">📅 Schedule Pickup</div>
        <form id="adminScheduleForm" method="POST">
            @csrf @method('PATCH')
            <div class="adm-form-row">
                <div class="adm-form-group" style="margin-bottom:0">
                    <label class="adm-form-label">Pickup Date</label>
                    <input type="date" name="pickup_date" id="admSchedDate" class="adm-form-input" required>
                </div>
                <div class="adm-form-group" style="margin-bottom:0">
                    <label class="adm-form-label">Time Slot</label>
                    <select name="time_slot" id="admSchedSlot" class="adm-form-input" required>
                        <option value="">Select slot</option>
                        <option value="08:00 – 10:00 AM">08:00 – 10:00 AM</option>
                        <option value="10:00 – 12:00 PM">10:00 – 12:00 PM</option>
                        <option value="12:00 – 02:00 PM">12:00 – 02:00 PM</option>
                        <option value="02:00 – 04:00 PM">02:00 – 04:00 PM</option>
                        <option value="04:00 – 06:00 PM">04:00 – 06:00 PM</option>
                    </select>
                </div>
            </div>
            <div class="adm-form-group" style="margin-top:18px">
                <label class="adm-form-label">Courier Notes (Optional)</label>
                <textarea name="admin_notes" class="adm-form-input" rows="2" placeholder="Notes for the courier…" style="resize:vertical"></textarea>
            </div>
            <div class="adm-modal-footer">
                <button type="button" class="adm-btn-cancel" onclick="closeAdminScheduleModal()">Cancel</button>
                <button type="submit" class="adm-btn-submit">
                    <i class="fa-solid fa-calendar-check" style="margin-right:6px"></i>Confirm Schedule
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAdminScheduleModal(id, date, slot) {
    var form = document.getElementById('adminScheduleForm');
    form.action = '/admin/pickup/' + id + '/schedule';
    if (date) document.getElementById('admSchedDate').value = date;
    if (slot) document.getElementById('admSchedSlot').value = slot;
    document.getElementById('adminScheduleModal').classList.add('open');
}
function closeAdminScheduleModal() {
    document.getElementById('adminScheduleModal').classList.remove('open');
}
document.getElementById('adminScheduleModal').addEventListener('click', function(e) {
    if (e.target === this) closeAdminScheduleModal();
});

// Live search
function filterTable() {
    var q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#mainTable tbody tr').forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
@endpush