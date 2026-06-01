@extends('layouts.admin') 
@section('page_title_key', 'sb_users')
@section('content')
    <div class="table-warp" style="padding: 20px;"> 
        @if(session('success'))
            <div id="alert-msg" style="background: rgba(6, 95, 70, 0.15); color: #34d399; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #059669;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div style="background: var(--surface); border-radius: 12px; overflow: hidden; border: 1px solid var(--border); transition: background-color 0.3s, border-color 0.3s;">
            <table class="admin-table" style="width: 100%; border-collapse: collapse; color: var(--text);">
                <thead>
                    <tr style="background: var(--surface-2); text-align: left; border-bottom: 1px solid var(--border);">
                        <th style="padding: 15px; color: var(--text);">{{ __('messages.th_image') }}</th>
                        <th style="padding: 15px; color: var(--text);">{{ __('messages.th_name') }}</th>
                        <th style="padding: 15px; color: var(--text);">{{ __('messages.th_email') }}</th>
                        <th style="padding: 15px; color: var(--text);">{{ __('messages.th_phone') }}</th>
                        <th style="padding: 15px; color: var(--text);">{{ __('messages.th_age') }}</th>
                        <th style="padding: 15px; color: var(--text);">{{ __('messages.th_status') }}</th>
                        <th style="padding: 15px; text-align: right; color: var(--text);">{{ __('messages.th_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="table-row-hover" style="border-bottom: 1px solid var(--border); transition: background-color 0.2s;">
                        <td style="padding: 15px;">
                            @if($user->image)
                                <img src="{{ asset('storage/' . $user->image) }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border);">
                            @else
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--surface-2); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-user" style="font-size: 14px; color: var(--text-muted);"></i>
                                </div>
                            @endif
                        </td>

                        <td style="padding: 15px; font-weight: bold; color: var(--text);">{{ $user->name ?? __('messages.not_available') }}</td>
                        <td style="padding: 15px; color: var(--text);">{{ $user->email }}</td>
                        <td style="padding: 15px; color: var(--text);">{{ $user->phone ?? __('messages.not_available') }}</td>
                        <td style="padding: 15px; color: var(--text);">{{ $user->age ?? __('messages.not_available') }}</td>

                        <td style="padding: 15px; vertical-align: middle;">
                            <span class="badge {{ $user->status ? 'status-active-badge' : 'status-inactive-badge' }}" 
                                  style="padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block;">
                                {{ $user->status ? __('messages.status_active') : __('messages.status_inactive') }}
                            </span>
                        </td>

                        <td style="padding: 15px; text-align: right; vertical-align: middle;">
                            <div style="display: flex; gap: 14px; justify-content: flex-end; align-items: center;">
                                <a href="{{ route('admin.reports.index', ['user_id' => $user->id]) }}" 
                                   class="btn-show" 
                                   style="background: var(--accent); color: white; padding: 7px 14px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                    <i class="fa-solid fa-file-medical"></i> {{ __('messages.btn_show_report') }}
                                </a>
                                
                                <button type="button" onclick="confirmDelete({{ $user->id }})" style="background:none; border:none; cursor: pointer; padding: 0; display: inline-flex; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class="fa-solid fa-trash" style="color: #ef4444; font-size: 17px;"></i>
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
                        <td colspan="7" style="padding: 50px; text-align: center; color: var(--text-muted);">
                            <i class="fa-regular fa-folder-open" style="font-size: 44px; display: block; margin-bottom: 12px; opacity: 0.6;"></i>
                            {{ __('messages.no_users_found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="pagination-wrapper" style="margin-top: 25px;">
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
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            
            Swal.fire({
                title: swalTitle,
                text: swalText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--accent, #6366f1)',
                cancelButtonColor: '#ef4444',
                confirmButtonText: swalConfirmBtn,
                cancelButtonText: swalCancelBtn,
                background: isDark ? '#161b27' : '#ffffff',
                color: isDark ? '#e2e8f0' : '#1e293b'
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

    <style>
        .table-row-hover:hover {
            background: var(--surface-2) !important;
        }

        .status-active-badge {
            background: rgba(34, 197, 94, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .status-inactive-badge {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, 0.3);
        }
    </style>
@endsection