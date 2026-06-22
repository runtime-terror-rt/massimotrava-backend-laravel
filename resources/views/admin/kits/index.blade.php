@extends('layouts.admin')

@section('title', __('messages.kit_management') . ' - Massimotrava')
@section('page_title_key', 'sb_kit_manager')
@section('content')
<div class="container-fluid px-4 py-4" style="background-color: var(--surface-2); min-height: 100vh; color: var(--text); transition: background-color 0.3s, color 0.3s;">
    
    {{-- Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2">
        <div>
            <h1 class="h3 font-weight-bold mb-1" style="font-family: 'Syne', sans-serif; color: var(--text);">
                {{ __('messages.kit_management') }}
            </h1>
            <p class="text-sm mb-0" style="color: var(--text-muted); font-family: 'DM Sans', sans-serif;">
                {{ __('messages.kit_subtitle') }}
            </p>
        </div>
        
        {{-- Activation Form --}}
        <form action="{{ route('admin.kits.activate') }}" method="POST" class="d-flex gap-2">
            @csrf
            <input type="text" name="activation_code" placeholder="{{ __('messages.enter_activation_code') }}" required 
                   class="form-control px-3 py-2 text-sm" 
                   style="min-width: 220px; background: var(--surface); border: 1px solid var(--border); color: var(--text); transition: border-color 0.2s; outline: none;"
                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
            
            <button type="submit" class="btn text-white font-weight-bold text-sm px-4" style="background: var(--accent); border: none; border-radius: 6px; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                <i class="fa-solid fa-key me-1"></i> {{ __('messages.activate_kit') }}
            </button>
        </form>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert border-0 mb-4" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-3); border: 1px solid rgba(16, 185, 129, 0.2) !important;">
            <i class="fa-regular fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Main Data Table Card --}}
    <div class="card border-0 overflow-hidden shadow-sm" style="background: var(--surface); border-radius: 12px; border: 1px solid var(--border) !important; font-family: 'DM Sans', sans-serif; transition: background-color 0.3s, border-color 0.3s;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="color: var(--text);">
                <thead style="background: var(--surface-2);">
                    <tr style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border);">
                        <th class="px-4 py-3 border-0" style="color: var(--text);">{{ __('messages.activation_code') }}</th>
                        <th class="px-4 py-3 border-0" style="color: var(--text);">{{ __('messages.invoice_code') }}</th>
                        
                        {{-- শুধু অ্যাডমিন হলে ইউজার ইনফো কলামের হেডার দেখাবে --}}
                        @if($isAdmin)
                            <th class="px-4 py-3 border-0" style="color: var(--text);">{{ __('messages.user') }}</th>
                        @endif
                        
                        <th class="px-4 py-3 border-0" style="color: var(--text);">{{ __('messages.status') }}</th>
                        <th class="px-4 py-3 border-0 text-center" style="color: var(--text);">{{ __('messages.action') }}</th>
                    </tr>
                </thead>
                <tbody style="border-top: none;">
                    @forelse($kits as $kit)
                        <tr class="table-row-hover" style="border-bottom: 1px solid var(--border); transition: background-color 0.2s;">
                            
                            {{-- Activation Code --}}
                            <td class="px-4 py-3 font-weight-bold" style="color: var(--accent);">
                                {{ $kit->activation_code }}
                            </td>
                            
                            {{-- Invoice Code --}}
                            <td class="px-4 py-3">
                                <code style="color: var(--text-muted); font-family: monospace; background: var(--surface-2); padding: 2px 6px; border-radius: 4px;">{{ $kit->inv_code }}</code>
                            </td>
                            
                            {{-- User Column (শুধু অ্যাডমিনদের জন্য দৃশ্যমান) --}}
                            @if($isAdmin)
                                <td class="px-4 py-3">
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-medium mb-0" style="color: var(--text);">{{ $kit->user->name ?? __('messages.not_available') }}</span>
                                        <small style="color: var(--text-muted); font-size: 11px;">{{ $kit->user->email ?? '' }}</small>
                                    </div>
                                </td>
                            @endif
                            
                            {{-- Status Badge --}}
                            <td class="px-4 py-3">
                                @if($kit->status)
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-3); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 20px;">
                                        ● {{ __('messages.status_active') }}
                                    </span>
                                @else
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(244, 63, 94, 0.1); color: #f43f5e; border: 1px solid rgba(244, 63, 94, 0.2); border-radius: 20px;">
                                        ● {{ __('messages.status_inactive') }}
                                    </span>
                                @endif
                            </td>
                            
                            {{-- Action Column Content --}}
                            <td class="px-4 py-3 text-center">
                                @if($isAdmin)
                                    {{-- Admin Action: Delete Button --}}
                                    <form action="{{ route('admin.kits.destroy', $kit->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirm') }}')" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-0 border-0 bg-transparent" style="color: #f43f5e; transition: transform 0.2s; display: inline-flex; align-items: center;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
                                            <i class="fa-solid fa-trash-can" style="font-size: 16px;"></i>
                                        </button>
                                    </form>
                                @else
                                    {{-- User Action: Premium View Button --}}
                                    <a href="{{-- route('admin.kits.show', $kit->id) --}}" class="btn btn-sm text-white px-3 py-1" style="background: var(--surface-2); border: 1px solid var(--border); border-radius: 6px; font-size: 12px; font-weight: 500; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px;" onmouseover="this.style.background='var(--accent)'; this.style.borderColor='var(--accent)';" onmouseout="this.style.background='var(--surface-2)'; this.style.borderColor='var(--border)';">
                                        <i class="fa-regular fa-eye" style="font-size: 13px;"></i> {{ __('messages.view') ?? 'View' }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        {{-- Empty State (Dynamic Column Span) --}}
                        <tr>
                            <td colspan="{{ $isAdmin ? 5 : 4 }}" class="px-4 py-5 text-center" style="font-size: 14px; color: var(--text-muted);">
                                <i class="fa-solid fa-box-open d-block mb-2 fs-3" style="color: var(--text-muted); opacity: 0.7;"></i> 
                                {{ __('messages.no_kits_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination Links --}}
    <div class="mt-4 d-flex justify-content-end">
        {{ $kits->appends(request()->query())->links() }}
    </div>
</div>

<style>
    .table-row-hover:hover {
        background-color: var(--surface-2) !important;
    }
</style>
@endsection