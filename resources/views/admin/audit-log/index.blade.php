@extends('layouts.admin')
@section('title', 'Security & Audit Logs')

@push('styles')
    <style>
        /* Table and Container Wrappers */
        .audit-card {
            background: var(--surface, #ffffff);
            border: 1px solid var(--border, #e2e8f0);
            border-radius: 12px;
            padding: 20px;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .audit-table-wrap {
            background: var(--surface, #ffffff);
            border: 1px solid var(--border, #e2e8f0);
            border-radius: 12px;
            padding: 15px;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .audit-table th {
            color: var(--text-muted, #64748b);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border, #e2e8f0);
            padding: 12px 8px;
        }
        .audit-table td {
            color: var(--text, #334155);
            border-bottom: 1px solid var(--border, #e2e8f0);
            padding: 14px 8px;
        }

        /* Filter Elements Inputs */
        .audit-input {
            background-color: var(--bg, #f8fafc) !important;
            border: 1px solid var(--border, #e2e8f0) !important;
            color: var(--text, #1e293b) !important;
        }
        .audit-input:focus {
            border-color: var(--accent, #6366f1) !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        }

        /* Modals Architecture Adjustments */
        .audit-modal-content {
            background: var(--surface, #ffffff) !important;
            color: var(--text, #1e293b) !important;
            border: 1px solid var(--border, #e2e8f0) !important;
            border-radius: 12px !important;
        }
        .audit-modal-header {
            border-bottom: 1px solid var(--border, #e2e8f0) !important;
        }
        .audit-code-frame {
            background: var(--bg, #f8fafc) !important;
            padding: 12px;
            border-radius: 6px;
            max-height: 300px;
            overflow-y: auto;
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4 py-2" style="color: var(--text, #1e293b);">
    
    {{-- Header Section --}}
    <div class="header-action d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 font-weight-bold mb-1" style="color: var(--text, #1e293b); font-family: 'Syne', sans-serif;">Security & Audit Trail</h2>
            <p class="mb-0 text-sm" style="color: var(--text-muted, #64748b); font-family: 'DM Sans', sans-serif;">Real-time ecosystem activity ledger & security forensics log.</p>
        </div>
    </div>

    <div style="font-family: 'DM Sans', sans-serif;">
        {{-- Filter Widget Panel --}}
        <div class="audit-card shadow-sm mb-4">
            <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control audit-input py-2" value="{{ request('search') }}" placeholder="Search by User, Model Name or Event...">
                </div>
                <div class="col-md-4">
                    <select name="event" class="form-select audit-input py-2">
                        <option value="">-- All Dynamic Events --</option>
                        <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created (Data Registry)</option>
                        <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated (Data Mutation)</option>
                        <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted (Purged Entity)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn text-white font-weight-medium w-100 py-2" style="background: var(--accent, #6366f1); border: none;">
                        <i class="fa-solid fa-filter me-1"></i> Apply Matrix
                    </button>
                </div>
            </form>
        </div>

        {{-- Data Table Grid Layer --}}
        <div class="audit-table-wrap shadow-sm"> 
            <div class="table-responsive">
                <table class="table audit-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Timestamp</th>
                            <th style="width: 20%;">Operator (User)</th>
                            <th style="width: 15%;">Action Matrix</th>
                            <th style="width: 20%;">Target Entity Model</th>
                            <th style="width: 15%;">Data Changes Payload</th>
                            <th style="width: 15%;">Network Footprint</th>
                        </tr>
                    </thead>
                    <tbody style="border-top: none;">
                        @forelse($logs as $log)
                        <tr>
                            <td class="text-mono" style="font-size: 13px; color: var(--text-muted, #64748b);">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text, #1e293b);">{{ $log->user->name ?? 'System Automated' }}</div>
                                <div style="font-size: 11px; color: var(--text-muted, #64748b);">ID: {{ $log->user_id ?? 'N/A' }}</div>
                            </td>
                            <td>
                                @if($log->event === 'created')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1.5" style="border-radius: 6px; font-weight: 600; text-transform: uppercase; font-size: 10px;">Created</span>
                                @elseif($log->event === 'updated')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1.5" style="border-radius: 6px; font-weight: 600; text-transform: uppercase; font-size: 10px;">Updated</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1.5" style="border-radius: 6px; font-weight: 600; text-transform: uppercase; font-size: 10px;">Deleted</span>
                                @endif
                            </td>
                            <td>
                                <div style="color: var(--accent, #6366f1); font-weight: 500;">{{ class_basename($log->auditable_type) }}</div>
                                <div style="font-size: 11px; color: var(--text-muted, #64748b);">Record ID: {{ $log->auditable_id ?? 'N/A' }}</div>
                            </td>
                            <td>
                                {{-- Payload Modal Trigger Button --}}
                                <button type="button" class="btn btn-sm btn-outline-secondary" style="font-size: 12px; padding: 4px 10px; border-radius: 6px; border-color: var(--border);" onclick="viewPayload({{ $log->id }}, {{ json_encode($log->old_values) }}, {{ json_encode($log->new_values) }})">
                                    <i class="fa-solid fa-database me-1"></i> View Changes
                                </button>
                            </td>
                            <td>
                                <div class="text-mono" style="font-size: 12px; color: var(--text, #334155);"><i class="fa-solid fa-network-wired me-1" style="color: var(--text-muted);"></i>{{ $log->ip_address }}</div>
                                <div style="font-size: 10px; color: var(--text-muted, #64748b); max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->user_agent }}">{{ $log->user_agent }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color: var(--text-muted, #64748b);">
                                <i class="fa-solid fa-shield-halved mb-2 fs-3" style="color: var(--text-muted);"></i><br>No system logs recorded for this specific filter matrix.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper d-flex justify-content-between align-items-center mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Payload Inspect Modal Boot --}}
<div class="modal fade" id="payloadInspectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="audit-modal-content modal-content">
            <div class="audit-modal-header modal-header">
                <h5 class="modal-title font-weight-bold" style="color: var(--text); font-family: 'Syne', sans-serif;"><i class="fa-solid fa-code text-indigo me-2"></i>Data Payload Inspector</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="font-family: 'DM Sans', sans-serif;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-danger mb-2" style="font-size: 13px;">Before Mutation (Old State)</h6>
                        <pre id="oldValuesFrame" class="audit-code-frame" style="color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.15);"></pre>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-success mb-2" style="font-size: 13px;">After Mutation (New State)</h6>
                        <pre id="newValuesFrame" class="audit-code-frame" style="color: #16a34a; border: 1px solid rgba(22, 163, 74, 0.15);"></pre>
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