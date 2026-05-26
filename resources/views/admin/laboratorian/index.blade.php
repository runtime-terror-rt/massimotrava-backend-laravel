@extends('layouts.admin')
@section('title', __('messages.lab_users_meta_title'))
@section('page_title_key', 'sb_laboratorian')
@section('content')
{{-- Action Header Block --}}
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h2 style="color: white; font-weight: 600; margin-bottom: 0;">{{ __('messages.lab_users_header') }}</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLabUserModal">
        <i class="fa-solid fa-plus me-1"></i> {{ __('messages.btn_add_laboratorian') }}
    </button>
</div>

{{-- Success and General Error Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: #10b981; color: white; border: none;">
        {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="on" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: #ef4444; color: white; border: none;">
        {{ session('error') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="on" aria-label="Close"></button>
    </div>
@endif

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
            @forelse($labUsers as $user)
            <tr>
                <td>
                    @if($user->image)
                        <img src="{{ asset('storage/' . $user->image) }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div class="td-avatar" style="background: #334155; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
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
                        {{ $user->status ? __('messages.status_active') : __('messages.status_inactive') }}
                    </span>
                </td>
                <td style="text-align: center;">
                    <div class="action-btns" style="display: flex; justify-content: center;">
                        <button onclick="confirmDelete({{ $user->id }})" class="btn btn-sm btn-outline-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.lab-users.destroy', $user->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #64748b; padding: 20px;">
                    {{ __('messages.no_users_found') }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- System Pagination Controls Grid --}}
    <div class="pagination-wrapper" style="margin-top: 20px;">
        {{ $labUsers->links() }}
    </div>
</div>
@endsection


@push('modals')
<div class="modal fade" id="createLabUserModal" tabindex="-1" aria-labelledby="createLabUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: #1e293b; color: white; border: 1px solid #334155;">
            <div class="modal-header" style="border-bottom: 1px solid #334155;">
                <h5 class="modal-title" id="createLabUserModalLabel">{{ __('messages.modal_title_add_laboratorian') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.lab-users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" style="color: #cbd5e1;">{{ __('messages.lbl_assign_lab') }} <span class="text-danger">*</span></label>
                            <select name="lab_id" class="form-select text-white @error('lab_id') is-invalid @enderror" style="background: #0f172a; color: white; border: 1px solid #334155;" required>
                                <option value="" style="background: #1e293b; color: #cbd5e1;">-- {{ __('messages.opt_select_lab') }} --</option>
                                
                                @foreach($labs as $lab)
                                    <option value="{{ $lab->id }}" {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                                        {{ $lab->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lab_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Name Input Area --}}
                        <div class="col-md-6">
                            <label class="form-label" style="color: #cbd5e1;">{{ __('messages.lbl_full_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" style="background: #0f172a; color: white; border: 1px solid #334155;" placeholder="John Doe" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Email Address --}}
                        <div class="col-md-6">
                            <label class="form-label" style="color: #cbd5e1;">{{ __('messages.lbl_email_address') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" style="background: #0f172a; color: white; border: 1px solid #334155;" placeholder="email@example.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Security Credentials Layer --}}
                        <div class="col-md-6">
                            <label class="form-label" style="color: #cbd5e1;">{{ __('messages.lbl_password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" style="background: #0f172a; color: white; border: 1px solid #334155;" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="color: #cbd5e1;">{{ __('messages.lbl_confirm_password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" style="background: #0f172a; color: white; border: 1px solid #334155;" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #334155;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
    function confirmDelete(id) {
        if (confirm("{{ __('messages.js_alert_delete_laboratorian') }}")) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        @if($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('createLabUserModal'));
            myModal.show();
        @endif
    });
</script>
@endpush