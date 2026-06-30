@extends('layouts.admin')

@section('title', 'Pickup Request Detail')

@section('content')

<style>
.pd { padding: 4px 0 }

.pd-back { display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#64748b;text-decoration:none;margin-bottom:22px;transition:color .2s }
.pd-back:hover { color:#f1f5f9 }

/* Header card */
.pd-header { background:var(--surface);border:1px solid rgba(255,255,255,0.06);border-radius:20px;padding:28px 32px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px }
.pd-header-left { display:flex;align-items:center;gap:16px }
.pd-kit-icon { width:56px;height:56px;border-radius:14px;background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.2);display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0 }
.pd-kit-name { font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:#fff }
.pd-kit-id { font-size:12px;color:#64748b;margin-top:3px }

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
.ab-danger { background:rgba(239,68,68,0.08);color:#f87171;border:1px solid rgba(239,68,68,0.15) }
.ab-danger:hover { background:rgba(239,68,68,0.15) }

/* Info alert */
.info-alert { background:rgba(34,211,238,0.04);border:1px solid rgba(34,211,238,0.15);border-radius:12px;padding:12px 16px;font-size:13px;color:#94a3b8;display:flex;align-items:center;gap:10px;margin-bottom:20px }
.info-alert i { color:#22d3ee;flex-shrink:0 }

@media(max-width:768px){.pd-grid{grid-template-columns:1fr}.pd-header{flex-direction:column;align-items:flex-start}}
</style>
@php
    $statusMap = [
        'pending'   => 'messages.status_pending',
        'scheduled' => 'messages.status_scheduled',
        'collected' => 'messages.status_collected',
        'cancelled' => 'messages.status_cancelled',
    ];
    $currentStatusKey = $statusMap[$req->status] ?? 'messages.status_pending';
@endphp
<div class="pd">

    {{-- Back --}}
    <a href="{{ route('user.pickup.index') }}" class="pd-back">
        <i class="fa-solid fa-arrow-left"></i> {{ __('messages.back_to_my_requests') }}
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
                <div class="pd-kit-name">{{ $req->kit_name ?? __('messages.test_kit') }}</div>
                <div class="pd-kit-id">#KIT-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }} · {{ __('messages.submitted_on') }} {{ $req->created_at->format('M d, Y') }}</div>
            </div>
        </div>
        @php
            $badgeClass = match($req->status) {
                'pending'   => 'b-pending',
                'scheduled' => 'b-scheduled',
                'collected' => 'b-collected',
                'cancelled' => 'b-cancelled',
                default     => 'b-pending',
            };
        @endphp
        <span class="badge {{ $badgeClass }}">● {{ __($currentStatusKey) }}</span>
    </div>

    {{-- Scheduled info alert --}}
    @if($req->status === 'scheduled' && $req->pickup_date)
    <div class="info-alert">
        <i class="fa-solid fa-calendar-check"></i>
        <span>
            {{ __('messages.pickup_scheduled_alert_prefix') }} 
            <strong style="color:#e2e8f0">{{ \Carbon\Carbon::parse($req->pickup_date)->format('D, M d, Y') }}</strong> 
            {{ __('messages.pickup_scheduled_alert_middle') }} 
            <strong style="color:#e2e8f0">{{ $req->time_slot }}</strong>. 
            {{ __('messages.pickup_scheduled_alert_suffix') }}
        </span>
    </div>
    @endif

    {{-- Grid --}}
    <div class="pd-grid">

        {{-- Pickup Details --}}
        <div class="pd-card">
            <div class="pd-card-title"><i class="fa-solid fa-box"></i> {{ __('messages.pickup_details') }}</div>
            <div class="info-row">
                <span class="info-label">{{ __('messages.kit_type') }}</span>
                <span class="info-val">{{ $req->kit_name ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('messages.pickup_date') }}</span>
                <span class="info-val">{{ $req->pickup_date ? \Carbon\Carbon::parse($req->pickup_date)->format('D, M d, Y') : '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('messages.time_slot') }}</span>
                <span class="info-val">{{ $req->time_slot ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('messages.address') }}</span>
                <span class="info-val" style="max-width:220px">{{ $req->address ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('messages.submitted') }}</span>
                <span class="info-val">{{ $req->created_at->format('M d, Y · h:i A') }}</span>
            </div>
            @if($req->updated_at && $req->updated_at->ne($req->created_at))
            <div class="info-row">
                <span class="info-label">{{ __('messages.last_updated') }}</span>
                <span class="info-val">{{ $req->updated_at->format('M d, Y · h:i A') }}</span>
            </div>
            @endif
        </div>

        {{-- Timeline --}}
        <div class="pd-card">
            <div class="pd-card-title"><i class="fa-solid fa-timeline"></i> {{ __('messages.request_timeline') }}</div>
            <ul class="timeline">
                <li class="tl-item">
                    <div class="tl-dot tl-dot-cyan"><i class="fa-solid fa-plus"></i></div>
                    <div>
                        <div class="tl-title">{{ __('messages.tl_submitted_title') }}</div>
                        <div class="tl-sub">{{ $req->created_at->format('M d, Y · h:i A') }}</div>
                    </div>
                </li>

                @if(in_array($req->status, ['scheduled', 'collected', 'cancelled']))
                <li class="tl-item">
                    @if($req->status === 'cancelled')
                        <div class="tl-dot tl-dot-red"><i class="fa-solid fa-xmark"></i></div>
                        <div>
                            <div class="tl-title">{{ __('messages.tl_cancelled_title') }}</div>
                            <div class="tl-sub">{{ $req->updated_at->format('M d, Y · h:i A') }}</div>
                        </div>
                    @else
                        <div class="tl-dot tl-dot-violet"><i class="fa-solid fa-calendar-check"></i></div>
                        <div>
                            <div class="tl-title">{{ __('messages.tl_scheduled_title') }}</div>
                            <div class="tl-sub">
                                {{ $req->pickup_date ? \Carbon\Carbon::parse($req->pickup_date)->format('M d, Y') : '' }}
                                @if($req->time_slot) · {{ $req->time_slot }} @endif
                            </div>
                        </div>
                    @endif
                </li>
                @endif

                @if($req->status === 'collected')
                <li class="tl-item">
                    <div class="tl-dot tl-dot-green"><i class="fa-solid fa-circle-check"></i></div>
                    <div>
                        <div class="tl-title">{{ __('messages.tl_collected_title') }}</div>
                        <div class="tl-sub">{{ __('messages.tl_collected_sub') }}</div>
                    </div>
                </li>
                @endif

                @if(in_array($req->status, ['pending', 'scheduled']))
                <li class="tl-item">
                    <div class="tl-dot tl-dot-gray"><i class="fa-solid fa-flask"></i></div>
                    <div>
                        <div class="tl-title" style="color:#475569">{{ __('messages.tl_awaiting_title') }}</div>
                        <div class="tl-sub">{{ __('messages.tl_awaiting_sub') }}</div>
                    </div>
                </li>
                @endif
            </ul>
        </div>

        {{-- Notes --}}
        <div class="pd-card">
            <div class="pd-card-title"><i class="fa-solid fa-note-sticky"></i> {{ __('messages.your_notes') }}</div>
            <div class="notes-box">{{ $req->notes ?? __('messages.no_special_instructions') }}</div>

            @if($req->admin_notes)
            <div class="pd-card-title" style="margin-top:20px"><i class="fa-solid fa-headset"></i> {{ __('messages.message_from_team') }}</div>
            <div class="notes-box" style="border-color:rgba(34,211,238,0.15);background:rgba(34,211,238,0.03);color:#e2e8f0">
                {{ $req->admin_notes }}
            </div>
            @endif
        </div>

        {{-- What's Next --}}
        <div class="pd-card">
            <div class="pd-card-title"><i class="fa-solid fa-circle-info"></i> {{ __('messages.whats_next_title') }}</div>
            @if($req->status === 'pending')
                <p style="font-size:13px;color:#94a3b8;line-height:1.7;margin:0">{{ __('messages.wn_pending_text') }}</p>
            @elseif($req->status === 'scheduled')
                <p style="font-size:13px;color:#94a3b8;line-height:1.7;margin:0">{{ __('messages.wn_scheduled_text') }}</p>
            @elseif($req->status === 'collected')
                <p style="font-size:13px;color:#94a3b8;line-height:1.7;margin:0">{{ __('messages.wn_collected_text') }}</p>
            @elseif($req->status === 'cancelled')
                <p style="font-size:13px;color:#94a3b8;line-height:1.7;margin:0">{{ __('messages.wn_cancelled_text') }}</p>
            @endif
        </div>

    </div>

    {{-- Action Bar (only for pending/scheduled) --}}
    @if(in_array($req->status, ['pending', 'scheduled']))
    <div class="pd-actions">
        <div class="pd-actions-label">
            <i class="fa-solid fa-sliders" style="color:#22d3ee;margin-right:6px"></i>
            {{ __('messages.manage_this_request') }}
        </div>
        <div class="pd-actions-btns">
            <button class="act-btn ab-violet" onclick="openUserReschedModal({{ $req->id }}, '{{ $req->pickup_date }}', '{{ $req->time_slot }}')">
                <i class="fa-solid fa-calendar-pen"></i> {{ __('messages.reschedule') }}
            </button>
            <form action="{{ route('user.pickup.cancel', $req->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.cancel_pickup_confirm') }}')">
                @csrf @method('PATCH')
                <button type="submit" class="act-btn ab-danger">
                    <i class="fa-solid fa-xmark"></i> {{ __('messages.cancel_request') }}
                </button>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection

@push('modals')
    <style>
        .usr-det-overlay {
            display:none;position:fixed;inset:0;width:100vw;height:100vh;
            background:rgba(0,0,0,0.75);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
            z-index:999999;align-items:center;justify-content:center;
        }
        .usr-det-overlay.open { display:flex }
        .usr-det-modal {
            background:#111827;border:1px solid rgba(255,255,255,0.08);border-radius:20px;
            padding:28px 32px;width:100%;max-width:460px;position:relative;
            box-shadow:0 25px 60px rgba(0,0,0,0.6);
        }
        .usr-det-title { font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:#fff;margin-bottom:20px }
        .usr-det-close { position:absolute;top:16px;right:16px;background:none;border:none;color:#64748b;font-size:18px;cursor:pointer }
        .usr-det-close:hover { color:#f1f5f9 }
        .usr-det-fg { margin-bottom:18px }
        .usr-det-label { font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;display:block }
        .usr-det-input { width:100%;padding:11px 14px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;color:#f1f5f9;font-size:13.5px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s }
        .usr-det-input:focus { border-color:rgba(34,211,238,0.4) }
        .usr-det-input option { background:#1e293b;color:#f1f5f9 }
        .usr-det-row { display:grid;grid-template-columns:1fr 1fr;gap:14px }
        .usr-det-footer { display:flex;justify-content:flex-end;gap:10px;margin-top:24px;padding-top:18px;border-top:1px solid rgba(255,255,255,0.05) }
        .usr-det-cancel { padding:10px 20px;border-radius:10px;border:1px solid rgba(255,255,255,0.1);background:transparent;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer }
        .usr-det-submit { padding:10px 24px;border-radius:10px;border:none;background:linear-gradient(135deg,#10b981,#06b6d4);color:#000;font-size:13px;font-weight:800;cursor:pointer }
    </style>

    <div class="usr-det-overlay" id="usrDetReschedModal">
        <div class="usr-det-modal">
            <button class="usr-det-close" onclick="closeUserReschedModal()"><i class="fa-solid fa-xmark"></i></button>
            <div class="usr-det-title">📅 {{ __('messages.modal_reschedule_title') }}</div>
            <form id="usrDetReschedForm" method="POST">
                @csrf @method('PATCH')
                <div class="usr-det-row">
                    <div class="usr-det-fg" style="margin-bottom:0">
                        <label class="usr-det-label">{{ __('messages.modal_new_date') }}</label>
                        <input type="date" name="pickup_date" id="usrDetDate" class="usr-det-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    <div class="usr-det-fg" style="margin-bottom:0">
                        <label class="usr-det-label">{{ __('messages.modal_time_slot') }}</label>
                        <select name="time_slot" id="usrDetSlot" class="usr-det-input" required>
                            <option value="">{{ __('messages.modal_select_slot') }}</option>
                            <option value="08:00 – 10:00 AM">08:00 – 10:00 AM</option>
                            <option value="10:00 – 12:00 PM">10:00 – 12:00 PM</option>
                            <option value="12:00 – 02:00 PM">12:00 – 02:00 PM</option>
                            <option value="02:00 – 04:00 PM">02:00 – 04:00 PM</option>
                            <option value="04:00 – 06:00 PM">04:00 – 06:00 PM</option>
                        </select>
                    </div>
                </div>
                <div class="usr-det-footer">
                    <button type="button" class="usr-det-cancel" onclick="closeUserReschedModal()">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="usr-det-submit">
                        <i class="fa-solid fa-calendar-check" style="margin-right:6px"></i>{{ __('messages.confirm') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endpush

<script>
function openUserReschedModal(id, date, slot) {
    var form = document.getElementById('usrDetReschedForm');
    form.action = '/user/pickup/' + id + '/reschedule';
    if (date) document.getElementById('usrDetDate').value = date;
    if (slot) document.getElementById('usrDetSlot').value = slot;
    document.getElementById('usrDetReschedModal').classList.add('open');
}
function closeUserReschedModal() {
    document.getElementById('usrDetReschedModal').classList.remove('open');
}
document.getElementById('usrDetReschedModal').addEventListener('click', function(e) {
    if (e.target === this) closeUserReschedModal();
});
</script>