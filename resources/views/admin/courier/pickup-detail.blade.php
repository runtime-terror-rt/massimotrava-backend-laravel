@extends('layouts.admin')

@section('title', 'Pickup Request Detail')

@section('content')

<style>
.pd { padding: 4px 0 }

/* Back */
.pd-back { display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#64748b;text-decoration:none;margin-bottom:22px;transition:color .2s }
.pd-back:hover { color:#f1f5f9 }

/* Header card */
.pd-header { background:var(--surface);border:1px solid rgba(255,255,255,0.06);border-radius:20px;padding:28px 32px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px }
.pd-header-left { display:flex;align-items:center;gap:16px }
.pd-kit-icon { width:56px;height:56px;border-radius:14px;background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.2);display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0 }
.pd-kit-name { font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:#fff }
.pd-kit-id { font-size:12px;color:#64748b;margin-top:3px }
.pd-header-right { display:flex;align-items:center;gap:10px;flex-wrap:wrap }

/* Badges */
.badge { font-size:12px;font-weight:700;padding:6px 16px;border-radius:20px;display:inline-flex;align-items:center;gap:6px;white-space:nowrap }
.b-pending   { background:rgba(245,158,11,0.08);color:#f59e0b;border:1px solid rgba(245,158,11,0.2) }
.b-scheduled { background:rgba(99,102,241,0.08);color:#a78bfa;border:1px solid rgba(99,102,241,0.2) }
.b-collected { background:rgba(16,185,129,0.08);color:#10b981;border:1px solid rgba(16,185,129,0.2) }
.b-cancelled { background:rgba(239,68,68,0.08);color:#f87171;border:1px solid rgba(239,68,68,0.2) }

/* Grid */
.pd-grid { display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px }
.pd-card { background:var(--surface);border:1px solid rgba(255,255,255,0.06);border-radius:18px;padding:24px 28px }
.pd-card-title { font-size:11px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#475569;margin-bottom:18px;display:flex;align-items:center;gap:8px }
.pd-card-title i { font-size:12px }

/* Info rows */
.info-row { display:flex;align-items:flex-start;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.04) }
.info-row:last-child { border-bottom:none;padding-bottom:0 }
.info-row:first-child { padding-top:0 }
.info-label { font-size:12px;color:#64748b;font-weight:500;flex-shrink:0;margin-right:12px }
.info-val { font-size:13px;color:#f1f5f9;font-weight:600;text-align:right }

/* User card */
.user-block { display:flex;align-items:center;gap:14px;margin-bottom:18px;padding-bottom:18px;border-bottom:1px solid rgba(255,255,255,0.05) }
.user-av { width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a78bfa);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;flex-shrink:0 }
.user-name { font-size:15px;font-weight:700;color:#f1f5f9 }
.user-email { font-size:12px;color:#64748b;margin-top:2px }

/* Notes box */
.notes-box { background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:14px 16px;font-size:13px;color:#94a3b8;line-height:1.6;min-height:60px }

/* Timeline */
.timeline { list-style:none;padding:0;margin:0 }
.tl-item { display:flex;gap:14px;padding-bottom:18px;position:relative }
.tl-item:last-child { padding-bottom:0 }
.tl-item:not(:last-child)::before { content:'';position:absolute;left:13px;top:28px;bottom:0;width:1px;background:rgba(255,255,255,0.06) }
.tl-dot { width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;margin-top:2px }
.tl-dot-cyan   { background:rgba(34,211,238,0.1);color:#22d3ee;border:1px solid rgba(34,211,238,0.2) }
.tl-dot-violet { background:rgba(167,139,250,0.1);color:#a78bfa;border:1px solid rgba(167,139,250,0.2) }
.tl-dot-green  { background:rgba(16,185,129,0.1);color:#10b981;border:1px solid rgba(16,185,129,0.2) }
.tl-dot-red    { background:rgba(239,68,68,0.08);color:#f87171;border:1px solid rgba(239,68,68,0.15) }
.tl-dot-gray   { background:rgba(255,255,255,0.04);color:#64748b;border:1px solid rgba(255,255,255,0.08) }
.tl-title { font-size:13px;font-weight:600;color:#f1f5f9;margin-bottom:3px }
.tl-sub { font-size:11.5px;color:#64748b }

/* Action bar */
.pd-actions { background:var(--surface);border:1px solid rgba(255,255,255,0.06);border-radius:18px;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px }
.pd-actions-label { font-size:13px;color:#64748b }
.pd-actions-btns { display:flex;gap:10px;flex-wrap:wrap }
.act-btn { font-size:13px;font-weight:600;padding:9px 18px;border-radius:10px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:all .2s;text-decoration:none }
.ab-violet { background:rgba(167,139,250,0.1);color:#a78bfa;border:1px solid rgba(167,139,250,0.2) }
.ab-violet:hover { background:rgba(167,139,250,0.2) }
.ab-green  { background:rgba(16,185,129,0.1);color:#10b981;border:1px solid rgba(16,185,129,0.2) }
.ab-green:hover  { background:rgba(16,185,129,0.2) }
.ab-danger { background:rgba(239,68,68,0.08);color:#f87171;border:1px solid rgba(239,68,68,0.15) }
.ab-danger:hover { background:rgba(239,68,68,0.15) }

@media(max-width:768px){.pd-grid{grid-template-columns:1fr}.pd-header{flex-direction:column;align-items:flex-start}}
</style>

<div class="pd">

    {{-- Back --}}
    <a href="{{ route('admin.pickup.index') }}" class="pd-back">
        <i class="fa-solid fa-arrow-left"></i> Back to All Requests
    </a>

    {{-- Flash --}}
    @if(session('success'))
        <div style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);color:#10b981;padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:13px;display:flex;align-items:center;gap:8px">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="pd-header">
        <div class="pd-header-left">
            <div class="pd-kit-icon">{{ $req->kit_icon ?? '🧬' }}</div>
            <div>
                <div class="pd-kit-name">{{ $req->kit_name ?? 'Test Kit' }}</div>
                <div class="pd-kit-id">#KIT-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }} · Created {{ $req->created_at->format('M d, Y') }}</div>
            </div>
        </div>
        <div class="pd-header-right">
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
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="pd-grid">

        {{-- Pickup Info --}}
        <div class="pd-card">
            <div class="pd-card-title"><i class="fa-solid fa-box"></i> Pickup Details</div>
            <div class="info-row">
                <span class="info-label">Pickup Date</span>
                <span class="info-val">{{ $req->pickup_date ? \Carbon\Carbon::parse($req->pickup_date)->format('D, M d, Y') : '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Time Slot</span>
                <span class="info-val">{{ $req->time_slot ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Address</span>
                <span class="info-val" style="max-width:220px">{{ $req->address ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Submitted</span>
                <span class="info-val">{{ $req->created_at->format('M d, Y · h:i A') }}</span>
            </div>
            @if($req->updated_at && $req->updated_at != $req->created_at)
            <div class="info-row">
                <span class="info-label">Last Updated</span>
                <span class="info-val">{{ $req->updated_at->format('M d, Y · h:i A') }}</span>
            </div>
            @endif
        </div>

        {{-- User Info --}}
        <div class="pd-card">
            <div class="pd-card-title"><i class="fa-solid fa-user"></i> User Information</div>
            <div class="user-block">
                <div class="user-av">{{ strtoupper(substr($req->user->name ?? 'U', 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ $req->user->name ?? '—' }}</div>
                    <div class="user-email">{{ $req->user->email ?? '—' }}</div>
                </div>
            </div>
            <div class="info-row">
                <span class="info-label">Phone</span>
                <span class="info-val">{{ $req->user->phone ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Member Since</span>
                <span class="info-val">{{ $req->user->created_at ? $req->user->created_at->format('M Y') : '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Requests</span>
                <span class="info-val">{{ $req->user->pickup_requests_count ?? \App\Models\PickupRequest::where('user_id', $req->user_id)->count() }}</span>
            </div>
        </div>

        {{-- Notes --}}
        <div class="pd-card">
            <div class="pd-card-title"><i class="fa-solid fa-note-sticky"></i> User Notes</div>
            <div class="notes-box">{{ $req->notes ?? 'No special instructions provided.' }}</div>

            @if($req->admin_notes)
            <div class="pd-card-title" style="margin-top:18px"><i class="fa-solid fa-shield-halved"></i> Admin Notes</div>
            <div class="notes-box" style="border-color:rgba(167,139,250,0.15);background:rgba(167,139,250,0.04)">{{ $req->admin_notes }}</div>
            @endif
        </div>

        {{-- Timeline --}}
        <div class="pd-card">
            <div class="pd-card-title"><i class="fa-solid fa-timeline"></i> Status Timeline</div>
            <ul class="timeline">
                <li class="tl-item">
                    <div class="tl-dot tl-dot-cyan"><i class="fa-solid fa-plus"></i></div>
                    <div>
                        <div class="tl-title">Request Submitted</div>
                        <div class="tl-sub">{{ $req->created_at->format('M d, Y · h:i A') }}</div>
                    </div>
                </li>

                @if(in_array($req->status, ['scheduled','collected','cancelled']))
                <li class="tl-item">
                    <div class="tl-dot {{ $req->status === 'cancelled' ? 'tl-dot-red' : 'tl-dot-violet' }}">
                        <i class="fa-solid {{ $req->status === 'cancelled' ? 'fa-xmark' : 'fa-calendar-check' }}"></i>
                    </div>
                    <div>
                        <div class="tl-title">{{ $req->status === 'cancelled' ? 'Request Cancelled' : 'Pickup Scheduled' }}</div>
                        <div class="tl-sub">{{ $req->updated_at->format('M d, Y · h:i A') }}</div>
                    </div>
                </li>
                @endif

                @if($req->status === 'collected')
                <li class="tl-item">
                    <div class="tl-dot tl-dot-green"><i class="fa-solid fa-circle-check"></i></div>
                    <div>
                        <div class="tl-title">Sample Collected</div>
                        <div class="tl-sub">Sent to laboratory</div>
                    </div>
                </li>
                @endif

                @if(in_array($req->status, ['pending','scheduled']))
                <li class="tl-item">
                    <div class="tl-dot tl-dot-gray"><i class="fa-solid fa-flask"></i></div>
                    <div>
                        <div class="tl-title" style="color:#475569">Awaiting Collection</div>
                        <div class="tl-sub">Pending pickup</div>
                    </div>
                </li>
                @endif
            </ul>
        </div>

    </div>

    {{-- Action Bar --}}
    @if(in_array($req->status, ['pending','scheduled']))
    <div class="pd-actions">
        <div class="pd-actions-label">
            <i class="fa-solid fa-bolt" style="color:#22d3ee;margin-right:6px"></i>
            Admin Actions
        </div>
        <div class="pd-actions-btns">
            <button class="act-btn ab-violet" onclick="openAdminSchedModal({{ $req->id }}, '{{ $req->pickup_date }}', '{{ $req->time_slot }}')">
                <i class="fa-solid fa-calendar-pen"></i> Schedule / Reschedule
            </button>
            <form action="{{ route('admin.pickup.collect', $req->id) }}" method="POST" onsubmit="return confirm('Mark as collected?')">
                @csrf @method('PATCH')
                <button type="submit" class="act-btn ab-green">
                    <i class="fa-solid fa-circle-check"></i> Mark Collected
                </button>
            </form>
            <form action="{{ route('admin.pickup.cancel', $req->id) }}" method="POST" onsubmit="return confirm('Cancel this pickup request?')">
                @csrf @method('PATCH')
                <button type="submit" class="act-btn ab-danger">
                    <i class="fa-solid fa-xmark"></i> Cancel Request
                </button>
            </form>
        </div>
    </div>
    @endif

</div>

@endsection

@push('modals')
<style>
.adm-det-overlay {
    display:none;position:fixed;inset:0;width:100vw;height:100vh;
    background:rgba(0,0,0,0.75);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
    z-index:999999;align-items:center;justify-content:center;
}
.adm-det-overlay.open { display:flex }
.adm-det-modal {
    background:#111827;border:1px solid rgba(255,255,255,0.08);border-radius:20px;
    padding:28px 32px;width:100%;max-width:460px;position:relative;
    box-shadow:0 25px 60px rgba(0,0,0,0.6);
}
.adm-det-title { font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:#fff;margin-bottom:20px }
.adm-det-close { position:absolute;top:16px;right:16px;background:none;border:none;color:#64748b;font-size:18px;cursor:pointer }
.adm-det-close:hover { color:#f1f5f9 }
.adm-det-fg { margin-bottom:18px }
.adm-det-label { font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;display:block }
.adm-det-input { width:100%;padding:11px 14px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;color:#f1f5f9;font-size:13.5px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s }
.adm-det-input:focus { border-color:rgba(34,211,238,0.4) }
.adm-det-input option { background:#1e293b;color:#f1f5f9 }
.adm-det-row { display:grid;grid-template-columns:1fr 1fr;gap:14px }
.adm-det-footer { display:flex;justify-content:flex-end;gap:10px;margin-top:24px;padding-top:18px;border-top:1px solid rgba(255,255,255,0.05) }
.adm-det-cancel { padding:10px 20px;border-radius:10px;border:1px solid rgba(255,255,255,0.1);background:transparent;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer }
.adm-det-submit { padding:10px 24px;border-radius:10px;border:none;background:linear-gradient(135deg,#10b981,#06b6d4);color:#000;font-size:13px;font-weight:800;cursor:pointer }
</style>

<div class="adm-det-overlay" id="admDetSchedModal">
    <div class="adm-det-modal">
        <button class="adm-det-close" onclick="closeAdminSchedModal()"><i class="fa-solid fa-xmark"></i></button>
        <div class="adm-det-title">📅 Schedule Pickup</div>
        <form id="admDetSchedForm" method="POST">
            @csrf @method('PATCH')
            <div class="adm-det-row">
                <div class="adm-det-fg" style="margin-bottom:0">
                    <label class="adm-det-label">Pickup Date</label>
                    <input type="date" name="pickup_date" id="admDetDate" class="adm-det-input" required>
                </div>
                <div class="adm-det-fg" style="margin-bottom:0">
                    <label class="adm-det-label">Time Slot</label>
                    <select name="time_slot" id="admDetSlot" class="adm-det-input" required>
                        <option value="">Select slot</option>
                        <option value="08:00 – 10:00 AM">08:00 – 10:00 AM</option>
                        <option value="10:00 – 12:00 PM">10:00 – 12:00 PM</option>
                        <option value="12:00 – 02:00 PM">12:00 – 02:00 PM</option>
                        <option value="02:00 – 04:00 PM">02:00 – 04:00 PM</option>
                        <option value="04:00 – 06:00 PM">04:00 – 06:00 PM</option>
                    </select>
                </div>
            </div>
            <div class="adm-det-fg" style="margin-top:18px">
                <label class="adm-det-label">Courier Notes (Optional)</label>
                <textarea name="admin_notes" class="adm-det-input" rows="2" placeholder="Notes for the courier…" style="resize:vertical"></textarea>
            </div>
            <div class="adm-det-footer">
                <button type="button" class="adm-det-cancel" onclick="closeAdminSchedModal()">Cancel</button>
                <button type="submit" class="adm-det-submit">
                    <i class="fa-solid fa-calendar-check" style="margin-right:6px"></i>Confirm Schedule
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAdminSchedModal(id, date, slot) {
    var form = document.getElementById('admDetSchedForm');
    form.action = '/admin/pickup/' + id + '/schedule';
    if (date) document.getElementById('admDetDate').value = date;
    if (slot) document.getElementById('admDetSlot').value = slot;
    document.getElementById('admDetSchedModal').classList.add('open');
}
function closeAdminSchedModal() {
    document.getElementById('admDetSchedModal').classList.remove('open');
}
document.getElementById('admDetSchedModal').addEventListener('click', function(e) {
    if (e.target === this) closeAdminSchedModal();
});
</script>
@endpush