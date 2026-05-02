@extends('layouts.admin')

@section('content')
<div class="table-warp" style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: white; margin: 0;">Kit Management</h2>
        
        {{-- কিট অ্যাক্টিভেট করার ছোট ফর্ম --}}
        <form action="{{ route('admin.kits.activate') }}" method="POST" style="display: flex; gap: 10px;">
            @csrf
            <input type="text" name="activation_code" placeholder="Enter Activation Code" required 
                   style="background: #0f172a; border: 1px solid #334155; color: white; padding: 8px 15px; border-radius: 6px;">
            <button type="submit" style="background: #6366f1; color: white; border: none; padding: 8px 20px; border-radius: 6px; cursor: pointer;">
                Activate Kit
            </button>
        </form>
    </div>

    @if(session('success'))
        <div style="background: rgba(34, 197, 94, 0.2); color: #4ade80; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <table class="admin-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 2px solid #334155;">
                <th style="padding: 12px;">Activation Code</th>
                <th>Invoice Code</th>
                <th>User</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kits as $kit)
            <tr style="border-bottom: 1px solid #1e293b;">
                <td style="padding: 15px; color: #6366f1; font-weight: bold;">{{ $kit->activation_code }}</td>
                <td><code style="color: #94a3b8;">{{ $kit->inv_code }}</code></td>
                <td>
                    <div style="display: flex; flex-direction: column;">
                        <span style="color: white;">{{ $kit->user->name ?? 'N/A' }}</span>
                        <small style="color: #64748b;">{{ $kit->user->email ?? '' }}</small>
                    </div>
                </td>
                <td>
                    <span style="padding: 4px 10px; border-radius: 20px; font-size: 11px; background: {{ $kit->status ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $kit->status ? '#4ade80' : '#f87171' }}; border: 1px solid {{ $kit->status ? '#22c55e' : '#ef4444' }};">
                        {{ $kit->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <form action="{{ route('admin.kits.destroy', $kit->id) }}" method="POST" onsubmit="return confirm('Delete this kit?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $kits->links() }}
    </div>
</div>
@endsection