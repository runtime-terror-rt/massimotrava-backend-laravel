@extends('layouts.admin')
@section('page_title_key', 'schedule_retest')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: var(--bg); min-height: 100vh; color: var(--text); transition: background-color 0.3s, color 0.3s;">
    
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold mb-1" style="font-family: 'Syne', sans-serif; color: var(--text);">
                My Retest Schedule
            </h1>
            <p class="text-sm mb-0" style="color: var(--text-muted); font-family: 'DM Sans', sans-serif;">
                Schedule and manage your next biomarker retest session tracker blocks.
            </p>
        </div>
        <button type="button" class="btn px-4 py-2 text-white font-weight-bold" data-bs-toggle="modal" data-bs-target="#createRetestModal" style="background: var(--accent); border-radius: 8px; font-family: 'DM Sans', sans-serif; border: none;">
            <i class="fa-solid fa-calendar-plus me-2"></i> Schedule New Retest
        </button>
    </div>

    {{-- Session Notifications --}}
    @if(session('success'))
        <div id="alert-msg" class="alert text-emerald-400 border-0 mb-4" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; border-radius: 8px;">
            <i class="fa-regular fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="alert text-rose-400 border-0 mb-4 text-sm" style="background: rgba(244, 63, 94, 0.1); border: 1px solid rgba(244, 63, 94, 0.2) !important; border-radius: 8px;">
            <i class="fa-solid fa-circle-exclamation me-2"></i> 
            @if(session('error'))
                {{ session('error') }}
            @else
                Something went wrong. Please check your inputs and try again.
            @endif
        </div>
    @endif

    {{-- Schedules Table --}}
    <div class="card border-0 overflow-hidden shadow-sm" style="background: var(--surface); border-radius: 12px; border: 1px solid var(--border) !important; font-family: 'DM Sans', sans-serif;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="color: var(--text);">
                <thead style="background: rgba(15, 23, 42, 0.05);">
                    <tr style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border);">
                        <th class="px-4 py-3 border-0">Kit Details</th>
                        <th class="px-4 py-3 border-0">Invitation Code</th>
                        <th class="px-4 py-3 border-0">Scheduled Timeline</th>
                        <th class="px-4 py-3 border-0">My Notes</th>
                        <th class="px-4 py-3 border-0">Status</th>
                        <th class="px-4 py-3 border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody style="border-top: none;">
                    @forelse($feed as $item)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td class="px-4 py-3">
                                <span class="badge px-2.5 py-1.5" style="background: rgba(56, 189, 248, 0.1); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 20px; font-weight: 500;">
                                    📦 {{ $item->kit->name ?? "Kit ID: {$item->kit_id}" }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono" style="color: var(--text-muted); font-size: 13px;">
                                {{ $item->original_inv_code ?? '---' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="d-block font-weight-medium" style="font-size: 13px;">
                                    📅 {{ \Carbon\Carbon::parse($item->retest_date)->format('M d, Y') }}
                                </span>
                                <small class=" font-mono" style="color: var(--text-muted); font-size: 11px;">
                                    🕒 {{ $item->retest_time ? \Carbon\Carbon::parse($item->retest_time)->format('h:i A') : 'No time slot' }}
                                </small>
                            </td>
                            <td class="px-4 py-3 text-sm text-truncate" style="max-width: 200px; color: var(--text-muted);">
                                {{ $item->user_notes ?? 'No notes added' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($item->status == 1)
                                    <span class="text-emerald-400 font-weight-medium text-sm">Active Queue</span>
                                @else
                                    <span class="text-muted text-sm">Suspended</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Edit Button --}}
                                    <button type="button" class="btn btn-sm p-1.5 px-2.5" style="background: rgba(34, 211, 238, 0.1); color: #22d3ee; border: 1px solid rgba(34, 211, 238, 0.2); border-radius: 6px; font-size: 12px; display: inline-flex; align-items: center;" onclick="openEditRetestModal({{ json_encode($item) }})">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    {{-- Cancel/Delete Button --}}
                                    <form action="{{ route('user.retests.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this retest schedule?');" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm p-1.5 px-2.5" style="background: rgba(239, 68, 68, 0.08); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.15); border-radius: 6px; font-size: 12px; display: inline-flex; align-items: center;">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-5 text-center" style="font-size: 14px; color: var(--text-muted);">
                                <i class="fa-solid fa-folder-open d-block mb-2 fs-3"></i> You have not scheduled any retest sessions yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-end">
        {{ $feed->appends(request()->query())->links() }}
    </div>
</div>

{{-- ==================== USER CREATION MODAL ==================== --}}
<div class="modal fade" id="createRetestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 p-2 shadow-lg" style="background: var(--surface); border-radius: 12px; border: 1px solid var(--border) !important; font-family: 'DM Sans', sans-serif;">
            <div class="modal-header pb-3 border-0 d-flex flex-column align-items-start position-relative" style="border-bottom: 1px solid var(--border) !important;">
                <h1 class="modal-title h4 font-weight-bold mb-1" style="font-family: 'Syne', sans-serif; color: var(--text);">Schedule a Retest</h1>
                <p class="text-sm mb-0 text-start" style="color: var(--text-muted);">Pick your test kit and choose a preferred date log.</p>
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal" style="filter: var(--close-btn-filter, none); color: var(--text); outline: none; shadow: none;"></button>
            </div>

            <div class="modal-body py-4">
                <form action="{{ route('user.retests.store') }}" method="POST" id="create_retest_form" class="row g-4">
                    @csrf
                    <div class="col-12">
                        <label class="form-label font-weight-bold mb-2" style="font-size:14px; color:var(--text);">Select Kit <span class="text-danger">*</span></label>
                        <select name="kit_id" class="form-select form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline:none;" required>
                            <option value="">-- Choose Your Kit --</option>
                            @foreach($kits as $kit)
                                <option value="{{ $kit->id }}" {{ old('kit_id') == $kit->id ? 'selected' : '' }}>
                                    📦 Code: {{ $kit->inv_code ?? $kit->activation_code }} (ID: {{ $kit->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label font-weight-bold mb-2" style="font-size:14px; color:var(--text);">Original Invitation Code (Optional)</label>
                        <input type="text" name="original_inv_code" value="{{ old('original_inv_code') }}" placeholder="e.g., INV-99801" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline:none;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold mb-2" style="font-size:14px; color:var(--text);">Retest Date <span class="text-danger">*</span></label>
                        <input type="date" name="retest_date" value="{{ old('retest_date') }}" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline:none;" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold mb-2" style="font-size:14px; color:var(--text);">Preferred Time Slot (Optional)</label>
                        <input type="time" name="retest_time" value="{{ old('retest_time') }}" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline:none;">
                    </div>

                    <div class="col-12">
                        <label class="form-label font-weight-bold mb-2" style="font-size:14px; color:var(--text);">Notes / Symptoms / Special Instructions</label>
                        <textarea name="user_notes" rows="4" placeholder="Any information you want to share with us..." class="form-control form-input" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline:none;">{{ old('user_notes') }}</textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2" style="border-top: 1px solid var(--border) !important; padding-top: 1rem !important;">
                <button type="button" class="btn px-4 py-2" data-bs-dismiss="modal" style="background: var(--bg); border: 1px solid var(--border); color: var(--text-muted); border-radius: 6px;">Cancel</button>
                <button type="submit" form="create_retest_form" class="btn px-4 py-2 text-white font-weight-bold" style="background: var(--accent); border-radius: 6px; border: none;">Confirm Schedule</button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== USER EDIT MODAL ==================== --}}
<div class="modal fade" id="editRetestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 p-2 shadow-lg" style="background: var(--surface); border-radius: 12px; border: 1px solid var(--border) !important; font-family: 'DM Sans', sans-serif;">
            <div class="modal-header pb-3 border-0 d-flex flex-column align-items-start position-relative" style="border-bottom: 1px solid var(--border) !important;">
                <h1 class="modal-title h4 font-weight-bold mb-1" style="font-family: 'Syne', sans-serif; color: var(--text);">📝 Modify Retest Details</h1>
                <p class="text-sm mb-0 text-start" style="color: var(--text-muted);">Change your appointment timeline configuration blocks.</p>
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal" style="filter: var(--close-btn-filter, none); color: var(--text); outline: none; shadow: none;"></button>
            </div>

            <div class="modal-body py-4">
                <form action="" method="POST" id="edit_retest_form" class="row g-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="col-12">
                        <label class="form-label font-weight-bold mb-2" style="font-size:14px; color:var(--text);">Select Kit <span class="text-danger">*</span></label>
                        <select name="kit_id" id="edit_kit_id" class="form-select form-input py-2.5" style="background-color: var(--surface-2) !important; border: 1px solid var(--border); color: var(--text) !important; outline:none;" required>
                            <option value="" style="background-color: #1e293b; color: #f1f5f9;">-- Choose Your Kit --</option>
                            
                            @if(isset($kits) && $kits->count() > 0)
                                @foreach($kits as $kit)
                                    <option value="{{ $kit->id }}" style="background-color: #1e293b !important; color: #f1f5f9 !important;">
                                        📦 Code: {{ $kit->inv_code ?? $kit->activation_code }} (ID: {{ $kit->id }})
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled style="background-color: #1e293b; color: #f87171;">⚠️ No active kits found for your account</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label font-weight-bold mb-2" style="font-size:14px; color:var(--text);">Original Invitation Code</label>
                        <input type="text" name="original_inv_code" id="edit_original_inv_code" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline:none;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold mb-2" style="font-size:14px; color:var(--text);">Retest Date <span class="text-danger">*</span></label>
                        <input type="date" name="retest_date" id="edit_retest_date" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline:none;" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold mb-2" style="font-size:14px; color:var(--text);">Preferred Time Slot</label>
                        <input type="time" name="retest_time" id="edit_retest_time" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline:none;">
                    </div>

                    <div class="col-12">
                        <label class="form-label font-weight-bold mb-2" style="font-size:14px; color:var(--text);">My Notes Notice</label>
                        <textarea name="user_notes" id="edit_user_notes" rows="4" class="form-control form-input" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline:none;"></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2" style="border-top: 1px solid var(--border) !important; padding-top: 1rem !important;">
                <button type="button" class="btn px-4 py-2" data-bs-dismiss="modal" style="background: var(--bg); border: 1px solid var(--border); color: var(--text-muted); border-radius: 6px;">Cancel</button>
                <button type="submit" form="edit_retest_form" class="btn px-4 py-2 text-white font-weight-bold" style="background: var(--accent); border-radius: 6px; border: none;">Update Changes</button>
            </div>
        </div>
    </div>
</div>

<style>
    .form-input { transition: border-color 0.2s, box-shadow 0.2s; }
    .form-input:focus {
        background-color: var(--surface-2) !important;
        color: var(--text) !important;
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
    }
    .form-select, .form-control, select { box-shadow: none; color: var(--text) !important; }
    .btn-close:focus { box-shadow: none; }

    select,
    .form-select, 
    .form-input {
        color: var(--text) !important;
        background-color: var(--surface-2) !important;
    }

    select option,
    .form-select option {
        background-color: #1e293b !important; 
        color: #f1f5f9 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function() {
            var msg = document.getElementById('alert-msg');
            if(msg) msg.style.display = 'none';
        }, 4000);
    });

    function openEditRetestModal(item) {
        const form = document.getElementById('edit_retest_form');
        form.action = `/user/retests/${item.id}`;

        document.getElementById('edit_kit_id').value = item.kit_id;
        document.getElementById('edit_original_inv_code').value = item.original_inv_code || '';
        document.getElementById('edit_retest_date').value = item.retest_date;
        
        if (item.retest_time) {
            document.getElementById('edit_retest_time').value = item.retest_time.substring(0, 5);
        } else {
            document.getElementById('edit_retest_time').value = '';
        }

        document.getElementById('edit_user_notes').value = item.user_notes || '';

        var editModal = new bootstrap.Modal(document.getElementById('editRetestModal'));
        editModal.show();
    }
</script>
@endsection