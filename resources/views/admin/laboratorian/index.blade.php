@extends('layouts.admin') 

@section('content')
    <div class="table-warp"> 
        <table >
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($labUsers as $user)
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
                        <td>
                            <button type="button" onclick="confirmDelete({{ $user->id }})" style="background:none; border:none;">
                                <i class="fa-solid fa-trash" style="color: #ef4444;"></i>
                            </button>

                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrapper" style="margin-top: 20px;">
            {{ $labUsers->links() }}
        </div>
    </div>
@endsection