@extends('layouts.admin')

@section('title', __('messages.role_meta_title'))
@section('page_title_key', 'sb_role_permission')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .swal2-popup { background: var(--surface, #1e293b) !important; color: #fff !important; border: 1px solid var(--border, #334155) !important; }
        .swal2-title { color: #fff !important; }
        .permission-badge { background: rgba(99, 102, 241, 0.15); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.3); padding: 4px 10px; border-radius: 6px; font-size: 11px; display: inline-block; margin: 3px; font-weight: 500; text-transform: capitalize; }
    </style>
@endpush

@section('content')
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <div>
        <h2 style="color: white; font-weight: 600; margin-bottom: 5px;">{{ __('messages.role_header_title') }}</h2>
        <p style="color: #94a3b8; margin: 0; font-size: 14px;">{{ __('messages.role_header_subtitle') }}</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px; position: sticky; top: 20px;">
            <h4 style="color: white; margin-bottom: 20px; font-weight: 600;">
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

                <div class="mb-3">
                    <label class="form-label" style="color: #cbd5e1; font-size: 14px;">{{ __('messages.role_label_slug') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $selectedRole->name ?? '') }}" placeholder="e.g. lab-manager" {{ $selectedRole && $selectedRole->name === 'admin' ? 'readonly' : '' }} required>
                </div>

                <div class="mb-4">
                    <label class="form-label" style="color: #cbd5e1; font-size: 14px; display: block; margin-bottom: 10px; font-weight: 500;">
                        {{ __('messages.role_label_capabilities') }}
                    </label>
                    <div class="permission-scroll-container" style="max-height: 250px; overflow-y: auto; background: #0f172a; padding: 15px; border-radius: 8px; border: 1px solid #1e293b;">
                        <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 10px;">
                            @foreach($permissions as $permission)
                                <div class="permission-item-box" style="display: flex; align-items: center; gap: 10px; background: #1e293b; padding: 8px 12px; border-radius: 6px; border: 1px solid #334155;">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm-{{ $permission->id }}" style="cursor: pointer; width: 16px; height: 16px; background-color: #0f172a; border-color: #475569;"
                                        {{ (isset($selectedRole) && $selectedRole->permissions->contains($permission->id)) || (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm-{{ $permission->id }}" style="cursor: pointer; color: #cbd5e1; font-size: 13px; margin: 0; user-select: none;">
                                        {{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        {{ $selectedRole ? __('messages.role_btn_update') : __('messages.role_btn_deploy') }}
                    </button>
                    @if($selectedRole)
                        <a href="{{ route('admin.role-permission.index') }}" class="btn btn-outline-secondary">
                            {{ __('messages.btn_cancel') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-wrap" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 10px;">
            <table class="table align-middle" style="margin: 0;">
                <thead>
                    <tr>
                        <th style="width: 25%;">{{ __('messages.role_th_entity') }}</th>
                        <th style="width: 60%;">{{ __('messages.role_th_privileges') }}</th>
                        <th style="width: 15%; text-align: center;">{{ __('messages.th_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>
                            <strong style="color: white; font-weight: 600; text-transform: capitalize;">
                                {{ str_replace(['-', '_'], ' ', $role->name) }}
                            </strong>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ __('messages.role_lbl_slug_prefix') }}: {{ $role->name }}</div>
                        </td>
                        <td>
                            @forelse($role->permissions as $perm)
                                <span class="permission-badge">
                                    {{ ucwords(str_replace(['-', '_'], ' ', $perm->name)) }}
                                </span>
                            @empty
                                <span class="text-muted small" style="font-style: italic; color:#475569 !important;">
                                    {{ __('messages.role_lbl_no_permissions') }}
                                </span>
                            @endforelse
                        </td>
                        <td style="text-align: center;">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.role-permission.index', ['id' => $role->id]) }}" class="btn btn-sm btn-outline-info" title="{{ __('messages.btn_edit') }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if($role->name !== 'admin')
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="{{ __('messages.btn_remove') }}" onclick="confirmDelete({{ $role->id }})">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
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

    function confirmDelete(id) {
        Swal.fire({
            title: "{{ __('messages.swal_title_revoke_role') }}",
            text: "{{ __('messages.swal_text_revoke_warning') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
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