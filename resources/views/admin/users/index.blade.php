@extends('layouts.admin') 

@section('content')
    <div class="table-warp" style="padding: 20px;"> 
        @if(session('success'))
            <div id="alert-msg" style="background: #065f46; color: #34d399; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #059669;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <table class="admin-table" style="width: 100%; border-collapse: collapse; color: #e2e8f0;">
            <thead>
                <tr style="background: #0f172a; text-align: left;">
                    <th style="padding: 15px;">{{ __('messages.th_image') }}</th>
                    <th style="padding: 15px;">{{ __('messages.th_name') }}</th>
                    <th style="padding: 15px;">{{ __('messages.th_email') }}</th>
                    <th style="padding: 15px;">{{ __('messages.th_phone') }}</th>
                    <th style="padding: 15px;">{{ __('messages.th_age') }}</th>
                    <th style="padding: 15px;">{{ __('messages.th_status') }}</th>
                    <th style="padding: 15px; text-align: right;">{{ __('messages.th_action') }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- 🎯 ফিক্স: এখানে @foreach এর জায়গায় @forelse হবে --}}
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid #334155;">
                    <td style="padding: 15px;">
                        @if($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: #334155; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-user" style="font-size: 14px; color: #94a3b8;"></i>
                            </div>
                        @endif
                    </td>

                    <td style="padding: 15px; font-weight: bold;">{{ $user->name ?? __('messages.not_available') }}</td>
                    <td style="padding: 15px;">{{ $user->email }}</td>
                    <td style="padding: 15px;">{{ $user->phone ?? __('messages.not_available') }}</td>
                    <td style="padding: 15px;">{{ $user->age ?? __('messages.not_available') }}</td>

                    <td style="padding: 15px;">
                        <span class="badge" 
                              style="padding: 4px 10px; border-radius: 20px; font-size: 11px; background: {{ $user->status ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $user->status ? '#4ade80' : '#f87171' }}; border: 1px solid {{ $user->status ? '#22c55e' : '#ef4444' }};">
                            {{ $user->status ? __('messages.status_active') : __('messages.status_inactive') }}
                        </span>
                    </td>

                    <td style="padding: 15px; text-align: right;">
                        <div style="display: flex; gap: 10px; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('admin.reports.index', ['user_id' => $user->id]) }}" 
                               class="btn-show" 
                               style="background: #6366f1; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-file-medical"></i> {{ __('messages.btn_show_report') }}
                            </a>
                            
                            <button type="button" onclick="confirmDelete({{ $user->id }})" style="background:none; border:none; cursor: pointer;">
                                <i class="fa-solid fa-trash" style="color: #ef4444; font-size: 16px;"></i>
                            </button>

                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 30px; text-align: center; color: #94a3b8;">
                        {{ __('messages.no_users_found') }}
                    </td>
                </tr>
                {{-- 🎯 ফিক্স: এখানে @endforelse দিয়ে লুপটি শেষ হবে --}}
                @endforelse
            </tbody>
        </table>
        
        <div class="pagination-wrapper" style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    </div>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const swalTitle = "{{ __('messages.swal_delete_title') }}";
        const swalText = "{{ __('messages.swal_delete_text') }}";
        const swalConfirmBtn = "{{ __('messages.swal_delete_confirm_btn') }}";
        const swalCancelBtn = "{{ __('messages.swal_delete_cancel_btn') }}";

        function confirmDelete(userId) {
            Swal.fire({
                title: swalTitle,
                text: swalText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#ef4444',
                confirmButtonText: swalConfirmBtn,
                cancelButtonText: swalCancelBtn
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            })
        }

        setTimeout(function() {
            var msg = document.getElementById('alert-msg');
            if(msg) msg.style.display = 'none';
        }, 4000);
    </script>
@endsection