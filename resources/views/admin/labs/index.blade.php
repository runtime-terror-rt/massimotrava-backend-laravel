@extends('layouts.admin')

@section('title', 'Laboratories List')

@section('content')
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <div>
        <h2 style="color: white; font-weight: 600; margin-bottom: 5px;">Laboratories</h2>
        <p style="color: #94a3b8; margin: 0; font-size: 14px;">Manage all registered laboratory facilities.</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openLabModal()">
        <i class="fa-solid fa-plus"></i> Add New Laboratory
    </button>
</div>

<div class="table-wrap"> 
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Lab Name</th>
                <th>Email & Phone</th>
                <th>Location (City)</th>
                <th>Full Address</th>
                <th>Status</th>
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($labs as $lab)
            <tr>
                <td style="color: #6366f1; font-weight: 600;">#{{ $lab->id }}</td>
                <td class="td-name">
                    <div style="font-weight: 600; color: white;">{{ $lab->name }}</div>
                    <div style="font-size: 11px; color: #64748b;">Code: {{ $lab->postal_code }}</div>
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
                        {{ $lab->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td style="text-align: center;">
                    <div class="action-btns" style="justify-content: center;">
                        <button class="action-btn edit" title="Edit" onclick="openLabModal({{ json_encode($lab) }})">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button onclick="confirmDelete({{ $lab->id }})" class="action-btn delete" title="Delete">
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
    
    <div class="pagination-wrapper">
        {{ $labs->links() }}
    </div>
</div>
@endsection

@push('modals')
<!-- Laboratory Modal (Unified for Create & Update) -->
<div class="modal fade" id="labModal" tabindex="-1" aria-labelledby="labModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labModalLabel">Register New Laboratory</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.labs.store') }}" method="POST" id="labForm">
                @csrf
                <!-- Hidden ID field: thakle Update, na thakle Create -->
                <input type="hidden" name="id" id="lab_id">
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Laboratory Name</label>
                            <input type="text" name="name" id="lab_name" class="form-control" placeholder="e.g. Massimo Lab Center" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="contact_email" id="lab_email" class="form-control" placeholder="lab@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" id="lab_phone" class="form-control" placeholder="+39..." required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Street Address</label>
                            <input type="text" name="street_address" id="lab_address" class="form-control" placeholder="Via Roma, 123" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" id="lab_city" class="form-control" placeholder="Rome" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Province (Code)</label>
                            <input type="text" name="province" id="lab_province" class="form-control" maxlength="2" placeholder="RM" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" id="lab_postal_code" class="form-control" maxlength="10" placeholder="00100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" id="lab_country" class="form-control" value="Italy">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" id="lab_status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">Save Laboratory</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this laboratory? This may affect associated users.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    function openLabModal(lab = null) {
        const modal = new bootstrap.Modal(document.getElementById('labModal'));
        const form = document.getElementById('labForm');
        const title = document.getElementById('labModalLabel');
        const submitBtn = document.getElementById('submitBtn');

        if (lab) {
            // Edit Mode
            title.innerText = 'Edit Laboratory';
            submitBtn.innerText = 'Update Laboratory';
            
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
            // Create Mode
            title.innerText = 'Register New Laboratory';
            submitBtn.innerText = 'Save Laboratory';
            
            form.reset(); // Reset form data
            document.getElementById('lab_id').value = ''; // Ensure ID is empty
            document.getElementById('lab_country').value = 'Italy'; // Default value
        }

        modal.show();
    }
</script>
@endpush