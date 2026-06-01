@extends('layouts.admin')

@section('title', __('messages.role_meta_title'))
@section('page_title_key', 'sb_role_permission')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* SweetAlert2 Theme Adjustments to match Light Theme Design */
        .swal2-popup { 
            background: var(--surface, #ffffff) !important; 
            color: var(--text, #1e293b) !important; 
            border: 1px solid var(--border, #e2e8f0) !important; 
            border-radius: 12px !important;
        }
        .swal2-title { 
            color: var(--text, #1e293b) !important; 
        }
        .swal2-html-container {
            color: var(--text-muted, #64748b) !important;
        }

        /* Permission Badges Style for Light Mode */
        .permission-badge { 
            background: rgba(99, 102, 241, 0.08); 
            color: var(--accent, #6366f1); 
            border: 1px solid rgba(99, 102, 241, 0.18); 
            padding: 5px 10px; 
            border-radius: 6px; 
            font-size: 11px; 
            display: inline-block; 
            margin: 3px; 
            font-weight: 500; 
            text-transform: capitalize; 
        }

        /* Sticky Forms & Table Elements */
        .role-sticky-card {
            background: var(--surface, #ffffff); 
            border: 1px solid var(--border, #e2e8f0); 
            border-radius: 12px; 
            padding: 24px; 
            position: sticky; 
            top: 20px;
            transition: all 0.3s ease;
        }
        .role-table-wrap {
            background: var(--surface, #ffffff); 
            border: 1px solid var(--border, #e2e8f0); 
            border-radius: 12px; 
            padding: 12px;
            transition: all 0.3s ease;
        }
        .role-table th {
            color: var(--text-muted, #64748b);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border, #e2e8f0);
            padding: 12px 8px;
        }
        .role-table td {
            color: var(--text, #334155);
            border-bottom: 1px solid var(--border, #e2e8f0);
            padding: 14px 8px;
        }

        /* Capabilities / Permission Checkbox Scroll Box */
        .permission-scroll-container {
            max-height: 260px; 
            overflow-y: auto; 
            background: var(--bg, #f8fafc); 
            padding: 15px; 
            border-radius: 8px; 
            border: 1px solid var(--border, #e2e8f0);
        }
        .permission-item-box {
            display: flex; 
            align-items: center; 
            gap: 10px; 
            background: var(--surface, #ffffff); 
            padding: 8px 12px; 
            border-radius: 6px; 
            border: 1px solid var(--border, #e2e8f0);
            transition: background-color 0.2s;
        }
        .permission-item-box:hover {
            background: rgba(99, 102, 241, 0.03);
        }

        /* Form Controls Override */
        .role-input {
            background-color: var(--surface, #ffffff) !important;
            border: 1px solid var(--border, #e2e8f0) !important;
            color: var(--text, #1e293b) !important;
        }
        .role-input:focus {
            border-color: var(--accent, #6366f1) !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        }
        .role-input[readonly] {
            background-color: var(--bg, #f1f5f9) !important;
            color: var(--text-muted, #94a3b8) !important;
            cursor: not-allowed;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4 py-2" style="color: var(--text, #1e293b);">
    
    {{-- Header Section --}}
    <div class="header-action d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 font-weight-bold mb-1" style="color: var(--text, #1e293b); font-family: 'Syne', sans-serif;">
                {{ __('messages.role_header_title') }}
            </h2>
            <p class="mb-0 text-sm" style="color: var(--text-muted, #64748b); font-family: 'DM Sans', sans-serif;">
                {{ __('messages.role_header_subtitle') }}
            </p>
        </div>
    </div>

    <div class="row g-4" style="font-family: 'DM Sans', sans-serif;">
        {{-- Form Container (Sticky Side) --}}
        <div class="col-md-4">
            <div class="role-sticky-card shadow-sm">
                <h4 class="h5 font-weight-bold mb-4" style="color: var(--text, #1e293b); font-family: 'Syne', sans-serif;">
                    @if($selectedRole)
                        {{ __('messages.role_form_edit') }}: {{ ucwords(str_replace('-', ' ', $selectedRole->name)) }}
                    @else
                        {{ __('messages.role_form_create') }}
                    @endif
                </h4>
                
                <form action="{{ route('admin.role-permission.save') }}" method="POST">
                    @csrf
                    @if($selectedRole)
                        <input type="hidden" name="id" value="{{ $selectedRole->id }}">
                    @endif

                    {{-- Role Slug Input --}}
                    <div class="mb-3">
                        <label class="form-label font-weight-medium mb-2" style="color: var(--text, #334155); font-size: 14px;">
                            {{ __('messages.role_label_slug') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control role-input py-2.5" 
                               value="{{ old('name', $selectedRole->name ?? '') }}" 
                               placeholder="e.g. lab-manager" 
                               {{ $selectedRole && $selectedRole->name === 'admin' ? 'readonly' : '' }} required>
                    </div>

                    {{-- Capabilities Checkbox Scrollable List --}}
                    <div class="mb-4">
                        <label class="form-label font-weight-medium mb-2" style="color: var(--text, #334155); font-size: 14px; display: block;">
                            {{ __('messages.role_label_capabilities') }}
                        </label>
                        <div class="permission-scroll-container">
                            <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 8px;">
                                @foreach($permissions as $permission)
                                    <div class="permission-item-box">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" 
                                               value="{{ $permission->id }}" id="perm-{{ $permission->id }}" 
                                               style="cursor: pointer; width: 16px; height: 16px;"
                                            {{ (isset($selectedRole) && $selectedRole->permissions->contains($permission->id)) || (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm-{{ $permission->id }}" style="cursor: pointer; color: var(--text, #334155); font-size: 13px; margin: 0; user-select: none;">
                                            {{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Form Submission Actions --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn text-white w-100 font-weight-medium" style="background: var(--accent, #6366f1); border: none;">
                            {{ $selectedRole ? __('messages.role_btn_update') : __('messages.role_btn_deploy') }}
                        </button>
                        @if($selectedRole)
                            <a href="{{ route('admin.role-permission.index') }}" class="btn btn-outline-secondary px-3" style="border-color: var(--border); color: var(--text-muted);">
                                {{ __('messages.btn_cancel') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Grid Entities Data Table Container --}}
        <div class="col-md-8">
            <div class="role-table-wrap shadow-sm">
                <div class="table-responsive">
                    <table class="table role-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 25%;">{{ __('messages.role_th_entity') }}</th>
                                <th style="width: 60%;">{{ __('messages.role_th_privileges') }}</th>
                                <th style="width: 15%; text-align: center;">{{ __('messages.th_actions') }}</th>
                            </tr>
                        </thead>
                        <tbody style="border-top: none;">
                            @foreach($roles as $role)
                            <tr>
                                <td>
                                    <strong style="color: var(--text, #1e293b); font-weight: 600; text-transform: capitalize;">
                                        {{ str_replace(['-', '_'], ' ', $role->name) }}
                                    </strong>
                                    <div style="font-size: 11px; color: var(--text-muted, #64748b); margin-top: 2px;">
                                        {{ __('messages.role_lbl_slug_prefix') }}: {{ $role->name }}
                                    </div>
                                </td>
                                <td>
                                    @forelse($role->permissions as $perm)
                                        <span class="permission-badge">
                                            {{ ucwords(str_replace(['-', '_'], ' ', $perm->name)) }}
                                        </span>
                                    @empty
                                        <span class="text-muted small" style="font-style: italic; color: var(--text-muted) !important;">
                                            {{ __('messages.role_lbl_no_permissions') }}
                                        </span>
                                    @endforelse
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.role-permission.index', ['id' => $role->id]) }}" class="btn btn-sm btn-outline-info" style="border-radius: 6px;" title="{{ __('messages.btn_edit') }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        @if($role->name !== 'admin')
                                            <button type="button" class="btn btn-sm btn-outline-danger" style="border-radius: 6px;" title="{{ __('messages.btn_remove') }}" onclick="confirmDelete({{ $role->id }})">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $role->id }}" action="{{ route('admin.role-permission.destroy', $role->id) }}" method="POST" style="display:none;">
                                                @csrf @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Success Messaging Flash Session
        @if(session('success'))
            Swal.fire({ 
                icon: 'success', 
                title: "{{ __('messages.swal_title_action_registered') }}", 
                text: "{{ session('success') }}", 
                showConfirmButton: false, 
                timer: 2000, 
                timerProgressBar: true 
            });
        @endif
    });

    // Revocation Drop Dialog Handover
    function confirmDelete(id) {
        Swal.fire({
            title: "{{ __('messages.swal_title_revoke_role') }}",
            text: "{{ __('messages.swal_text_revoke_warning') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: 'var(--text-muted, #64748b)',
            confirmButtonText: "{{ __('messages.swal_btn_confirm_drop') }}",
            cancelButtonText: "{{ __('messages.btn_cancel') }}"
        }).then((result) => {
            if (result.isConfirmed) { 
                document.getElementById('delete-form-' + id).submit(); 
            }
        });
    }
</script>
@endsection