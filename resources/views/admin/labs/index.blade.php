@extends('layouts.admin')
@section('title', __('messages.labs_meta_title'))

@section('content')
{{-- Action Header Block --}}
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <div>
        <h2 style="color: white; font-weight: 600; margin-bottom: 5px;">{{ __('messages.labs_header') }}</h2>
        <p style="color: #94a3b8; margin: 0; font-size: 14px;">{{ __('messages.labs_subheader') }}</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openLabModal()">
        <i class="fa-solid fa-plus me-1"></i> {{ __('messages.btn_add_laboratory') }}
    </button>
</div>

{{-- Data Grid Table Container --}}
<div class="table-wrap"> 
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('messages.th_lab_id') }}</th>
                <th>{{ __('messages.th_lab_name') }}</th>
                <th>{{ __('messages.th_lab_contact') }}</th>
                <th>{{ __('messages.th_lab_location') }}</th>
                <th>{{ __('messages.th_lab_address') }}</th>
                <th>{{ __('messages.th_lab_status') }}</th>
                <th style="text-align: center;">{{ __('messages.th_lab_action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($labs as $lab)
            <tr>
                <td style="color: #6366f1; font-weight: 600;">#{{ $lab->id }}</td>
                <td class="td-name">
                    <div style="font-weight: 600; color: white;">{{ $lab->name }}</div>
                    <div style="font-size: 11px; color: #64748b;">{{ __('messages.lbl_lab_code') }}: {{ $lab->postal_code }}</div>
                </td>
                <td>
                    <div style="font-size: 13px;">{{ $lab->contact_email }}</div>
                    <div style="font-size: 12px; color: #94a3b8;">{{ $lab->phone }}</div>
                </td>
                <td>
                    <div style="color: #cbd5e1;">{{ $lab->city }}</div>
                    <div style="font-size: 11px; color: #64748b;">{{ $lab->province }}, {{ $lab->country }}</div>
                </td>
                <td style="max-width: 250px; white-space: normal; font-size: 12px; color: #94a3b8;">
                    {{ $lab->street_address }}
                </td>
                <td>
                    <span class="badge {{ $lab->status ? 'badge-active' : 'badge-inactive' }}">
                        <span class="badge-dot"></span>
                        {{ $lab->status ? __('messages.status_active') : __('messages.status_inactive') }}
                    </span>
                </td>
                <td style="text-align: center;">
                    <div class="action-btns" style="justify-content: center;">
                        <button class="action-btn edit" title="{{ __('messages.btn_title_edit') }}" onclick="openLabModal({{ json_encode($lab) }})">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button onclick="confirmDelete({{ $lab->id }})" class="action-btn delete" title="{{ __('messages.btn_title_delete') }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                    <form id="delete-form-{{ $lab->id }}" action="{{ route('admin.labs.destroy', $lab->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{-- Pagination Index Streams --}}
    <div class="pagination-wrapper">
        {{ $labs->links() }}
    </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="labModal" tabindex="-1" aria-labelledby="labModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labModalLabel">{{ __('messages.modal_title_register_lab') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.labs.store') }}" method="POST" id="labForm">
                @csrf
                <input type="hidden" name="id" id="lab_id">
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">{{ __('messages.lbl_lab_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="lab_name" class="form-control" placeholder="{{ __('messages.ph_lab_name') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.lbl_lab_email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="contact_email" id="lab_email" class="form-control" placeholder="lab@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.lbl_lab_phone') }} <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="lab_phone" class="form-control" placeholder="+39..." required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('messages.lbl_lab_address') }} <span class="text-danger">*</span></label>
                            <input type="text" name="street_address" id="lab_address" class="form-control" placeholder="Via Roma, 123" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.lbl_lab_city') }} <span class="text-danger">*</span></label>
                            <input type="text" name="city" id="lab_city" class="form-control" placeholder="Rome" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.lbl_lab_province') }} <span class="text-danger">*</span></label>
                            <input type="text" name="province" id="lab_province" class="form-control" maxlength="2" placeholder="RM" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.lbl_lab_postal_code') }} <span class="text-danger">*</span></label>
                            <input type="text" name="postal_code" id="lab_postal_code" class="form-control" maxlength="10" placeholder="00100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.lbl_lab_country') }}</label>
                            <input type="text" name="country" id="lab_country" class="form-control" value="Italy">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.lbl_lab_status') }}</label>
                            <select name="status" id="lab_status" class="form-select">
                                <option value="1">{{ __('messages.opt_faq_active') }}</option>
                                <option value="0">{{ __('messages.opt_faq_inactive') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                        {{ __('messages.btn_modal_cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                        {{ __('messages.btn_modal_save_lab') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Localized Strings Storage Pipeline Matrix for Javascript Injection
    const localeLabels = {
        titleCreate: "{{ __('messages.modal_title_register_lab') }}",
        titleEdit: "{{ __('messages.modal_title_edit_lab') }}",
        btnSave: "{{ __('messages.btn_modal_save_lab') }}",
        btnUpdate: "{{ __('messages.btn_modal_update_lab') }}",
        confirmDeleteMsg: "{{ __('messages.js_alert_delete_laboratory') }}"
    };

    function confirmDelete(id) {
        if (confirm(localeLabels.confirmDeleteMsg)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    function openLabModal(lab = null) {
        const modal = new bootstrap.Modal(document.getElementById('labModal'));
        const form = document.getElementById('labForm');
        const title = document.getElementById('labModalLabel');
        const submitBtn = document.getElementById('submitBtn');

        if (lab) {
            // Edit Mode Dynamic Injection
            title.innerText = localeLabels.titleEdit;
            submitBtn.innerText = localeLabels.btnUpdate;
            
            document.getElementById('lab_id').value = lab.id;
            document.getElementById('lab_name').value = lab.name;
            document.getElementById('lab_email').value = lab.contact_email;
            document.getElementById('lab_phone').value = lab.phone;
            document.getElementById('lab_address').value = lab.street_address;
            document.getElementById('lab_city').value = lab.city;
            document.getElementById('lab_province').value = lab.province;
            document.getElementById('lab_postal_code').value = lab.postal_code;
            document.getElementById('lab_country').value = lab.country || 'Italy';
            document.getElementById('lab_status').value = lab.status;
        } else {
            // Create Mode Reset Pipeline
            title.innerText = localeLabels.titleCreate;
            submitBtn.innerText = localeLabels.btnSave;
            
            form.reset(); 
            document.getElementById('lab_id').value = ''; 
            document.getElementById('lab_country').value = 'Italy'; 
        }

        modal.show();
    }
</script>
@endpush