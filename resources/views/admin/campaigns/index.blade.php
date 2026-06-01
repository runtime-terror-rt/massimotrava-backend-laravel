@extends('layouts.admin')
@section('title', __('messages.campaign_meta_title'))
@section('page_title_key', 'sb_campaigns')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: var(--bg); min-height: 100vh; color: var(--text); transition: background-color 0.3s, color 0.3s;">
    
    {{-- Top Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold mb-1" style="font-family: 'Syne', sans-serif; color: var(--text);">
                {{ __('messages.campaign_header') }}
            </h1>
            <p class="text-sm mb-0" style="color: var(--text-muted); font-family: 'DM Sans', sans-serif;">
                {{ __('messages.campaign_subheader') }}
            </p>
        </div>
        {{-- Trigger Button for Modal --}}
        <button type="button" class="btn px-4 py-2 text-white font-weight-bold" data-bs-toggle="modal" data-bs-target="#createCampaignModal" style="background: var(--accent); border-radius: 8px; font-family: 'DM Sans', sans-serif; border: none;">
            <i class="fa-solid fa-plus me-2"></i> {{ __('messages.btn_launch_campaign') }}
        </button>
    </div>

    {{-- Session Success Alert --}}
    @if(session('success'))
        <div id="alert-msg" class="alert text-emerald-400 border-0 mb-4" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; border-radius: 8px;">
            <i class="fa-regular fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Session Error Alert (Validation Fallback or Store Errors) --}}
    @if(session('error') || $errors->any())
        <div class="alert text-rose-400 border-0 mb-4 text-sm" style="background: rgba(244, 63, 94, 0.1); border: 1px solid rgba(244, 63, 94, 0.2) !important; border-radius: 8px;">
            <i class="fa-solid fa-circle-exclamation me-2"></i> 
            @if(session('error'))
                {{ session('error') }}
            @else
                {{ __('messages.validation_error_pills') ?? 'Please check the form configurations below.' }}
            @endif
        </div>
    @endif

    {{-- Data Table Card --}}
    <div class="card border-0 overflow-hidden shadow-sm" style="background: var(--surface); border-radius: 12px; border: 1px solid var(--border) !important; font-family: 'DM Sans', sans-serif; transition: background-color 0.3s, border-color 0.3s;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="color: var(--text);">
                <thead style="background: rgba(15, 23, 42, 0.05);">
                    <tr style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border);">
                        <th class="px-4 py-3 border-0">{{ __('messages.th_campaign_info') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_status_state') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_target_url') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_timeline_schedule') }}</th>
                    </tr>
                </thead>
                <tbody style="border-top: none;">
                    @forelse($campaigns as $row)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td class="px-4 py-3">
                                <span class="d-block font-weight-medium mb-0" style="font-size: 15px; color: var(--text);">{{ $row->title }}</span>
                                <small style="color: var(--text-muted);">{{ $row->slug }}</small>
                            </td>
                            <td class="px-4 py-3">
                                @if($row->status === 'active')
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 20px; font-weight: 500;">
                                        🟢 {{ __('messages.badge_active') }}
                                    </span>
                                @elseif($row->status === 'paused')
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.2); border-radius: 20px; font-weight: 500;">
                                        🟡 {{ __('messages.badge_paused') }}
                                    </span>
                                @else
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(148, 163, 184, 0.1); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.2); border-radius: 20px; font-weight: 500;">
                                        📁 {{ __('messages.badge_draft') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ $row->action_url ?? '#' }}" target="_blank" class="text-info text-decoration-none text-sm">
                                    {{ Str::limit($row->action_url ?? __('messages.url_none'), 30) }}
                                </a>
                            </td>
                            <td class="px-4 py-3 style="color: var(--text-muted); font-size: 12px; font-family: monospace;">
                                {{ $row->start_date ? $row->start_date->format('M d') : __('messages.timeline_immediate') }} - 
                                {{ $row->end_date ? $row->end_date->format('M d, Y') : __('messages.timeline_infinite') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-5 text-center" style="font-size: 14px; color: var(--text-muted);">
                                <i class="fa-solid fa-bullhorn d-block mb-2 fs-3" style="color: var(--text-muted);"></i> 
                                {{ __('messages.no_campaigns_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Campaign Creation Modal Integration --}}
<div class="modal fade" id="createCampaignModal" tabindex="-1" aria-labelledby="createCampaignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 p-2 shadow-lg" style="background: var(--surface); border-radius: 12px; border: 1px solid var(--border) !important; font-family: 'DM Sans', sans-serif;">
            
            {{-- Modal Header --}}
            <div class="modal-header pb-3 border-0 d-flex flex-column align-items-start position-relative" style="border-bottom: 1px solid var(--border) !important;">
                <h1 class="modal-title h4 font-weight-bold mb-1" id="createCampaignModalLabel" style="font-family: 'Syne', sans-serif; color: var(--text);">
                    {{ __('messages.create_campaign_header') }}
                </h1>
                <p class="text-sm mb-0 text-start" style="color: var(--text-muted);">
                    {{ __('messages.create_campaign_subheader') }}
                </p>
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--close-btn-filter, none); color: var(--text); shadow: none; outline: none;"></button>
            </div>

            {{-- Modal Body Form --}}
            <div class="modal-body py-4">
                <form action="{{ route('admin.campaigns.store') }}" method="POST" id="modal_campaign_form" class="row g-4">
                    @csrf

                    {{-- Campaign Title --}}
                    <div class="col-12">
                        <label for="title" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                            {{ __('messages.lbl_campaign_title') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                               placeholder="{{ __('messages.ph_campaign_title') }}" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;" required>
                        @error('title') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Redirect URL --}}
                    <div class="col-12">
                        <label for="action_url" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                            {{ __('messages.lbl_action_url') }}
                        </label>
                        <input type="url" name="action_url" id="action_url" value="{{ old('action_url') }}" 
                               placeholder="https://vyralabs.health/promo" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;">
                        @error('action_url') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label for="description" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                            {{ __('messages.lbl_campaign_description') }}
                        </label>
                        <textarea name="description" id="description" rows="3" 
                                  placeholder="{{ __('messages.ph_campaign_description') }}" class="form-control form-input" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;">{{ old('description') }}</textarea>
                    </div>

                    {{-- Start Time --}}
                    <div class="col-md-6">
                        <label for="start_date" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                            {{ __('messages.lbl_start_date') }}
                        </label>
                        <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;">
                        @error('start_date') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    {{-- End Time --}}
                    <div class="col-md-6">
                        <label for="end_date" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                            {{ __('messages.lbl_end_date') }}
                        </label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;">
                        @error('end_date') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Initial Status --}}
                    <div class="col-12">
                        <label for="status" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                            {{ __('messages.lbl_pipeline_status') }} <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="status" class="form-select form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }} style="background: var(--surface); color: var(--text);">🚀 {{ __('messages.opt_status_active') }}</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }} style="background: var(--surface); color: var(--text);">📁 {{ __('messages.opt_status_draft') }}</option>
                            <option value="paused" {{ old('status') == 'paused' ? 'selected' : '' }} style="background: var(--surface); color: var(--text);">⏸️ {{ __('messages.opt_status_paused') }}</option>
                        </select>
                        @error('status') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                    </div>
                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2" style="border-top: 1px solid var(--border) !important; padding-top: 1rem !important;">
                <button type="button" class="btn px-4 py-2" data-bs-dismiss="modal" style="background: var(--bg); border: 1px solid var(--border); color: var(--text-muted); border-radius: 6px; transition: all 0.2s;">
                    {{ __('messages.btn_cancel') }}
                </button>
                <button type="submit" form="modal_campaign_form" class="btn px-4 py-2 text-white font-weight-bold" style="background: var(--accent); border-radius: 6px; border: none; cursor: pointer; transition: background-color 0.2s;">
                    {{ __('messages.btn_deploy_campaign') }}
                </button>
            </div>

        </div>
    </div>
</div>

<style>
    .form-input {
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-input:focus {
        background-color: var(--surface-2) !important;
        color: var(--text) !important;
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
    }
    .form-input::placeholder {
        color: var(--text-muted);
        opacity: 0.6;
    }
    .form-select, .form-control {
        box-shadow: none;
    }
    .btn-close:focus {
        box-shadow: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Smooth auto fade-out handling for alerts
        setTimeout(function() {
            var msg = document.getElementById('alert-msg');
            if(msg) msg.style.display = 'none';
        }, 4000);

        // Validation Error Auto-open Modal Feature
        @if($errors->any())
            var campaignModal = new bootstrap.Modal(document.getElementById('createCampaignModal'));
            campaignModal.show();
        @endif
    });
</script>
@endsection