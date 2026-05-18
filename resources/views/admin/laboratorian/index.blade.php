@extends('layouts.admin')

@section('content')
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h2 style="color: white; font-weight: 600;">Laboratorians List</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLabUserModal">
        <i class="fa-solid fa-plus"></i> Add New Laboratorian
    </button>
</div>

<div class="admin-table" style="width: 100%; border-collapse: collapse;"> 
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Lab</th>
                <th>Status</th>
                <th style="text-align: center;">Action</th>
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
                <td class="td-name">{{ $user->name ?? 'N/A' }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? 'N/A' }}</td>
                <td><span style="color: #3b82f6;">{{ $user->lab->name ?? 'No Lab Assigned' }}</span></td> 
                <td>
                    <span class="badge {{ $user->status ? 'badge-active' : 'badge-inactive' }}">
                        <span class="badge-dot"></span>
                        {{ $user->status ? 'Active' : 'Inactive' }}
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
    <div class="pagination-wrapper">
        {{ $labUsers->links() }}
    </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="createLabUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Laboratorian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.lab-users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Assign Lab</label>
                            <select name="lab_id" class="form-select" required>
                                <option value="">-- Select Lab --</option>
                                    @foreach($labs as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Laboratorian</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this laboratorian?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush