@extends('layouts.admin')

@section('title', __('messages.kit_management') . ' - Massimotrava')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0f172a; min-h: 100vh; color: #f1f5f9;">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2">
        <div>
            <h1 class="h3 font-weight-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">
                {{ __('messages.kit_management') }}
            </h1>
            <p class="text-sm mb-0" style="color: #94a3b8; font-family: 'DM Sans', sans-serif;">
                {{ __('messages.kit_subtitle') }}
            </p>
        </div>
        
        <form action="{{ route('admin.kits.activate') }}" method="POST" class="d-flex gap-2">
            @csrf
            <input type="text" name="activation_code" placeholder="{{ __('messages.enter_activation_code') }}" required 
                   class="form-control px-3 py-2 text-sm" style="min-width: 220px; background: #0f172a; border: 1px solid #334155; color: white;">
            
            <button type="submit" class="btn text-white font-weight-bold text-sm px-4" style="background: #6366f1; border: none; border-radius: 6px;">
                <i class="fa-solid fa-key me-1"></i> {{ __('messages.activate_kit') }}
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert text-emerald-400 border-0 mb-4" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important;">
            <i class="fa-regular fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 overflow-hidden shadow-lg" style="background: #1e293b; border-radius: 12px; border: 1px solid #334155 !important; font-family: 'DM Sans', sans-serif;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="color: #cbd5e1;">
                <thead style="background: rgba(15, 23, 42, 0.4);">
                    <tr style="color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #334155;">
                        <th class="px-4 py-3 border-0">{{ __('messages.activation_code') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.invoice_code') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.user') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.status') }}</th>
                        <th class="px-4 py-3 border-0 text-center">{{ __('messages.action') }}</th>
                    </tr>
                </thead>
                <tbody style="border-top: none;">
                    @forelse($kits as $kit)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);">
                            <td class="px-4 py-3 font-weight-bold" style="color: #6366f1;">
                                {{ $kit->activation_code }}
                            </td>
                            
                            <td class="px-4 py-3">
                                <code style="color: #94a3b8; font-family: monospace;">{{ $kit->inv_code }}</code>
                            </td>
                            
                            <td class="px-4 py-3">
                                <div class="d-flex flex-column">
                                    <span class="text-white font-weight-medium mb-0">{{ $kit->user->name ?? __('messages.not_available') }}</span>
                                    <small style="color: #64748b; font-size: 11px;">{{ $kit->user->email ?? '' }}</small>
                                </div>
                            </td>
                            
                            <td class="px-4 py-3">
                                @if($kit->status)
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 20px;">
                                        ● {{ __('messages.status_active') }}
                                    </span>
                                @else
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(244, 63, 94, 0.1); color: #f43f5e; border: 1px solid rgba(244, 63, 94, 0.2); border-radius: 20px;">
                                        ● {{ __('messages.status_inactive') }}
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('admin.kits.destroy', $kit->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn p-0 border-0 bg-transparent" style="color: #f43f5e; transition: color 0.2s;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-5 text-center text-muted" style="font-size: 14px;">
                                <i class="fa-solid fa-box-open d-block mb-2 fs-3 style-color: #64748b;"></i> 
                                {{ __('messages.no_kits_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-end">
        {{ $kits->appends(request()->query())->links() }}
    </div>
</div>
@endsection