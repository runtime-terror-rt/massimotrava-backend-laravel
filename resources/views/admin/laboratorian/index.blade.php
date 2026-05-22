@extends('layouts.admin')
@section('title', __('messages.lab_users_meta_title'))

@section('content')
{{-- Action Header Block --}}
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h2 style="color: white; font-weight: 600; margin-bottom: 0;">{{ __('messages.lab_users_header') }}</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLabUserModal">
        <i class="fa-solid fa-plus me-1"></i> {{ __('messages.btn_add_laboratorian') }}
    </button>
</div>

{{-- Data Table Pipeline Index --}}
<div class="admin-table" style="width: 100%; border-collapse: collapse;"> 
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('messages.th_lab_user_image') }}</th>
                <th>{{ __('messages.th_lab_user_name') }}</th>
                <th>{{ __('messages.th_lab_user_email') }}</th>
                <th>{{ __('messages.th_lab_user_phone') }}</th>
                <th>{{ __('messages.th_lab_user_lab') }}</th>
                <th>{{ __('messages.th_lab_user_status') }}</th>
                <th style="text-align: center;">{{ __('messages.th_lab_user_action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($labUsers as $user)
            <tr>
                <td>
                    @if($user->image)
                        <img src="{{ asset('storage/' . $user->image) }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div class="td-avatar" style="background: #334155;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    @endif
                </td>
                <td class="td-name">{{ $user->name ?? __('messages.lbl_not_available') }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? __('messages.lbl_not_available') }}</td>
                <td>
                    <span style="color: #3b82f6;">
                        {{ $user->lab->name ?? __('messages.lbl_no_lab_assigned') }}
                    </span>
                </td> 
                <td>
                    <span class="badge {{ $user->status ? 'badge-active' : 'badge-inactive' }}">
                        <span class="badge-dot"></span>
                        {{ $user->status ? __('messages.status_active') : __('messages.status_inactive') }}
                    </span>
                </td>
                <td style="text-align: center;">
                    <div class="action-btns" style="justify-content: center;">
                        <button onclick="confirmDelete({{ $user->id }})" class="action-btn delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- System Pagination Controls Grid --}}
    <div class="pagination-wrapper">
        {{ $labUsers->links() }}
    </div>
</div>
@endsection

@push('modals')
{{-- Registration Modal Overlay Structure --}}
<div class="modal fade" id="createLabUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.modal_title_add_laboratorian') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.lab-users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        {{-- Dropdown Lab Selection Matrix --}}
                        <div class="col-12">
                            <label class="form-label">{{ __('messages.lbl_assign_lab') }} <span class="text-danger">*</span></label>
                            <select name="lab_id" class="form-select" required>
                                <option value="">-- {{ __('messages.opt_select_lab') }} --</option>
                                @foreach($labs as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Name Input Area --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.lbl_full_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        
                        {{-- Email Address --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.lbl_email_address') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                        </div>
                        
                        {{-- Security Credentials Layer --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.lbl_password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.lbl_confirm_password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                        {{ __('messages.btn_modal_cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ __('messages.btn_modal_create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Prompt confirmation triggers parsing fallback localized script literals
    function confirmDelete(id) {
        if (confirm("{{ __('messages.js_alert_delete_laboratorian') }}")) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush