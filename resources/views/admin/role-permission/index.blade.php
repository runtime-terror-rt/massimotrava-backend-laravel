@extends('layouts.admin')

@section('title', 'Role & Permission Matrix')

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
        <h2 style="color: white; font-weight: 600; margin-bottom: 5px;">Role & Permission Manager</h2>
        <p style="color: #94a3b8; margin: 0; font-size: 14px;">Establish system hierarchies and control resource access maps.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px; position: sticky; top: 20px;">
            <h4 style="color: white; margin-bottom: 20px; font-weight: 600;">
                {{ $selectedRole ? 'Edit Role: '.ucwords(str_replace('-', ' ', $selectedRole->name)) : 'Establish New Role' }}
            </h4>
            
            <form action="{{ route('admin.role-permission.save') }}" method="POST">
                @csrf
                @if($selectedRole)
                    <input type="hidden" name="id" value="{{ $selectedRole->id }}">
                @endif

                <div class="mb-3">
                    <label class="form-label" style="color: #cbd5e1; font-size: 14px;">Role Technical Slug / Title</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $selectedRole->name ?? '') }}" placeholder="e.g. lab-manager" {{ $selectedRole && $selectedRole->name === 'admin' ? 'readonly' : '' }} required>
                </div>

                <div class="mb-4">
                    <label class="form-label" style="color: #cbd5e1; font-size: 14px; display: block; margin-bottom: 10px; font-weight: 500;">
                        Bind Capabilities (Permissions)
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
                    <button type="submit" class="btn btn-primary w-100">{{ $selectedRole ? 'Update Configuration' : 'Deploy Role' }}</button>
                    @if($selectedRole)
                        <a href="{{ route('admin.role-permission.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
                        <th style="width: 25%;">Role Entity</th>
                        <th style="width: 60%;">Allowed Privileges</th>
                        <th style="width: 15%; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>
                            <strong style="color: white; font-weight: 600; text-transform: capitalize;">
                                {{ str_replace(['-', '_'], ' ', $role->name) }}
                            </strong>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">slug: {{ $role->name }}</div>
                        </td>
                        <td>
                            @forelse($role->permissions as $perm)
                                <span class="permission-badge">
                                    {{ ucwords(str_replace(['-', '_'], ' ', $perm->name)) }}
                                </span>
                            @empty
                                <span class="text-muted small" style="font-style: italic; color:#475569 !important;">No permissions assigned yet.</span>
                            @endforelse
                        </td>
                        <td style="text-align: center;">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.role-permission.index', ['id' => $role->id]) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if($role->name !== 'admin')
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $role->id }})">
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
            Swal.fire({ icon: 'success', title: 'Action Registered', text: "{{ session('success') }}", showConfirmButton: false, timer: 2000, timerProgressBar: true });
        @endif
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Revoke Role?',
            text: "All relationships linked with this entity will face total removal!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, drop role!'
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById('delete-form-' + id).submit(); }
        });
    }
</script>
@endsection