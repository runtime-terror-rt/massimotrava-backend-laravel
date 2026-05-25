@extends('layouts.admin')
@section('title', 'Security & Audit Logs')

@section('content')
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <div>
        <h2 style="color: white; font-weight: 600; margin-bottom: 5px;">Security & Audit Trail</h2>
        <p style="color: #94a3b8; margin: 0; font-size: 14px;">Real-time ecosystem activity ledger & security forensics log.</p>
    </div>
</div>

{{-- Filter Widget Panel --}}
<div class="card mb-4" style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 20px;">
    <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="row g-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by User, Model Name or Event..." style="background: #0f172a; color: white; border: 1px solid #334155;">
        </div>
        <div class="col-md-4">
            <select name="event" class="form-select" style="background: #0f172a; color: white; border: 1px solid #334155;">
                <option value="">-- All Dynamic Events --</option>
                <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created (Data Registry)</option>
                <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated (Data Mutation)</option>
                <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted (Purged Entity)</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-1"></i> Apply Matrix</button>
        </div>
    </form>
</div>

{{-- Data Table Grid Layer --}}
<div class="admin-table" style="width: 100%; border-collapse: collapse; background: #1e293b; border-radius: 12px; padding: 15px; border: 1px solid #334155;"> 
    <table class="table align-middle" style="margin: 0;">
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Operator (User)</th>
                <th>Action Matrix</th>
                <th>Target Entity Model</th>
                <th>Data Changes Payload</th>
                <th>Network Footprint</th>
            </tr>
        </thead>
        <tbody style="color: #cbd5e1;">
            @forelse($logs as $log)
            <tr>
                <td style="font-family: monospace; font-size: 13px; color: #94a3b8;">
                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                </td>
                <td>
                    <div style="font-weight: 600; color: white;">{{ $log->user->name ?? 'System Automated' }}</div>
                    <div style="font-size: 11px; color: #64748b;">ID: {{ $log->user_id ?? 'N/A' }}</div>
                </td>
                <td>
                    @if($log->event === 'created')
                        <span class="badge bg-success-subtle text-success" style="padding: 5px 10px; border-radius: 6px; font-weight: 600; text-transform: uppercase; font-size: 11px;">Created</span>
                    @elseif($log->event === 'updated')
                        <span class="badge bg-warning-subtle text-warning" style="padding: 5px 10px; border-radius: 6px; font-weight: 600; text-transform: uppercase; font-size: 11px;">Updated</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger" style="padding: 5px 10px; border-radius: 6px; font-weight: 600; text-transform: uppercase; font-size: 11px;">Deleted</span>
                    @endif
                </td>
                <td>
                    <div style="color: #3b82f6; font-weight: 500;">{{ class_basename($log->auditable_type) }}</div>
                    <div style="font-size: 11px; color: #64748b;">Record ID: {{ $log->auditable_id ?? 'N/A' }}</div>
                </td>
                <td>
                    {{-- Payload Modal Trigger Button --}}
                    <button type="button" class="btn btn-sm btn-outline-secondary" style="font-size: 12px; padding: 2px 8px; border-radius: 4px;" onclick="viewPayload({{ $log->id }}, {{ json_encode($log->old_values) }}, {{ json_encode($log->new_values) }})">
                        <i class="fa-solid fa-database me-1"></i> View Changes
                    </button>
                </td>
                <td>
                    <div style="font-size: 12px; font-family: monospace; color: #cbd5e1;"><i class="fa-solid fa-network-wired me-1" style="color:#64748b;"></i>{{ $log->ip_address }}</div>
                    <div style="font-size: 10px; color: #64748b; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->user_agent }}">{{ $log->user_agent }}</div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #64748b; padding: 30px;">
                    <i class="fa-solid fa-shield-halved mb-2" style="font-size: 24px;"></i><br>No system logs recorded for this specific filter matrix.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrapper" style="margin-top: 20px;">
        {{ $logs->links() }}
    </div>
</div>

{{-- Payload Inspect Modal Boot --}}
<div class="modal fade" id="payloadInspectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: #1e293b; color: white; border: 1px solid #334155;">
            <div class="modal-header" style="border-bottom: 1px solid #334155;">
                <h5 class="modal-title"><i class="fa-solid fa-code text-indigo me-2"></i>Data Payload Inspector</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-danger mb-2">Before Mutation (Old State)</h6>
                        <pre id="oldValuesFrame" style="background: #0f172a; padding: 12px; border-radius: 6px; max-height: 300px; overflow-y: auto; color: #f87171; font-size: 12px; border: 1px solid rgba(239, 68, 68, 0.2);"></pre>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success mb-2">After Mutation (New State)</h6>
                        <pre id="newValuesFrame" style="background: #0f172a; padding: 12px; border-radius: 6px; max-height: 300px; overflow-y: auto; color: #4ade80; font-size: 12px; border: 1px solid rgba(74, 222, 128, 0.2);"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function viewPayload(id, oldValues, newValues) {
        document.getElementById('oldValuesFrame').textContent = oldValues ? JSON.stringify(oldValues, null, 4) : '// No previous state recorded';
        document.getElementById('newValuesFrame').textContent = newValues ? JSON.stringify(newValues, null, 4) : '// Entity was entirely purged/deleted';
        
        var inspectModal = new bootstrap.Modal(document.getElementById('payloadInspectModal'));
        inspectModal.show();
    }
</script>
@endpush