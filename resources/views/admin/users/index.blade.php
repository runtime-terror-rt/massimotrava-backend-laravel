@extends('layouts.admin') 

@section('content')
    <div class="table-warp"> 
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Status</th>
                    <th style="display: flex; gap: 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        @if($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: #334155; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-user" style="font-size: 14px; color: #94a3b8;"></i>
                            </div>
                        @endif
                    </td>

                    <td>{{ $user->name ?? 'N/A' }}</td>

                    <td>{{ $user->email }}</td>

                    <td>{{ $user->phone ?? 'N/A' }}</td>

                    <td>{{ $user->age ?? 'N/A' }}</td>

                    <td>
                        <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $user->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <td>
                        <div style="display: flex; gap: 10px;">
                            <td>
                                <div style="display: flex; gap: 10px;">
                                    <a href="{{ route('admin.reports.index', ['user_id' => $user->id]) }}" 
                                    class="btn-show" 
                                    style="background: #6366f1; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 12px;">
                                        <i class="fa-solid fa-file-medical"></i> Show Report
                                    </a>
                                    
                                    <button type="button" onclick="confirmDelete({{ $user->id }})" style="background:none; border:none;">
                                        <i class="fa-solid fa-trash" style="color: #ef4444;"></i>
                                    </button>

                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                                
                            </td>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrapper" style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            })
        }
    </script>
@endsection