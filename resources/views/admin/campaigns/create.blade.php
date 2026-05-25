@extends('layouts.admin')
@section('title', __('messages.create_campaign_meta_title'))

@section('content')
<div class="container-fluid px-4 py-4 d-flex justify-content-center" style="background-color: #0f172a; min-h: 100vh; color: #f1f5f9;">
    <div class="w-100 card border-0 p-4 shadow-lg mb-5" style="max-width: 800px; background: #1e293b; border-radius: 12px; border: 1px solid #334155 !important; font-family: 'DM Sans', sans-serif;">
        
        {{-- Header Section --}}
        <div class="mb-4 pb-3" style="border-bottom: 1px solid #334155;">
            <h1 class="h4 font-weight-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">
                {{ __('messages.create_campaign_header') }}
            </h1>
            <p class="text-sm mb-0" style="color: #94a3b8;">
                {{ __('messages.create_campaign_subheader') }}
            </p>
        </div>

        {{-- Session Error Alert --}}
        @if(session('error'))
            <div class="alert text-rose-400 border-0 mb-4 text-sm" style="background: rgba(244, 63, 94, 0.1); border: 1px solid rgba(244, 63, 94, 0.2) !important;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Campaign Form --}}
        <form action="{{ route('admin.campaigns.store') }}" method="POST" class="row g-4">
            @csrf

            {{-- Campaign Title --}}
            <div class="col-12">
                <label for="title" class="form-label text-slate-300 font-weight-bold mb-2">
                    {{ __('messages.lbl_campaign_title') }} <span class="text-danger">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                       placeholder="{{ __('messages.ph_campaign_title') }}" class="form-control py-2.5" required>
                @error('title') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
            </div>

            {{-- Redirect URL --}}
            <div class="col-12">
                <label for="action_url" class="form-label text-slate-300 font-weight-bold mb-2">
                    {{ __('messages.lbl_action_url') }}
                </label>
                <input type="url" name="action_url" id="action_url" value="{{ old('action_url') }}" 
                       placeholder="https://massimotrava.com/promo" class="form-control py-2.5">
                @error('action_url') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div class="col-12">
                <label for="description" class="form-label text-slate-300 font-weight-bold mb-2">
                    {{ __('messages.lbl_campaign_description') }}
                </label>
                <textarea name="description" id="description" rows="3" 
                          placeholder="{{ __('messages.ph_campaign_description') }}" class="form-control">{{ old('description') }}</textarea>
            </div>

            {{-- Start Time --}}
            <div class="col-md-6">
                <label for="start_date" class="form-label text-slate-300 font-weight-bold mb-2">
                    {{ __('messages.lbl_start_date') }}
                </label>
                <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" class="form-control py-2.5">
                @error('start_date') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
            </div>

            {{-- End Time --}}
            <div class="col-md-6">
                <label for="end_date" class="form-label text-slate-300 font-weight-bold mb-2">
                    {{ __('messages.lbl_end_date') }}
                </label>
                <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" class="form-control py-2.5">
                @error('end_date') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
            </div>

            {{-- Initial Status --}}
            <div class="col-12">
                <label for="status" class="form-label text-slate-300 font-weight-bold mb-2">
                    {{ __('messages.lbl_pipeline_status') }} <span class="text-danger">*</span>
                </label>
                <select name="status" id="status" class="form-select py-2.5">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>🚀 {{ __('messages.opt_status_active') }}</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📁 {{ __('messages.opt_status_draft') }}</option>
                    <option value="paused" {{ old('status') == 'paused' ? 'selected' : '' }}>⏸️ {{ __('messages.opt_status_paused') }}</option>
                </select>
                @error('status') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="col-12 pt-3 mt-4 d-flex justify-content-end gap-2" style="border-top: 1px solid #334155;">
                <a href="{{ route('admin.campaigns.index') }}" class="btn px-4 py-2" style="background: #0f172a; border: 1px solid #334155; color: #cbd5e1;">
                    {{ __('messages.btn_cancel') }}
                </a>
                <button type="submit" class="btn px-4 py-2 text-white font-weight-bold" style="background: #6366f1; border: none; border-radius: 6px;">
                    {{ __('messages.btn_deploy_campaign') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection