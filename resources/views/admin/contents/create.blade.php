@extends('layouts.admin')
@section('title', __('messages.create_content_meta_title'))
@section('page_title_key', 'sb_contents')
@section('content')
<div class="container-fluid px-4 py-4 d-flex justify-content-center" style="background-color: #0f172a; min-h: 100vh; color: #f1f5f9;">
    <div class="w-100 card border-0 p-4 shadow-lg mb-5" style="max-width: 800px; background: #1e293b; border-radius: 12px; border: 1px solid #334155 !important; font-family: 'DM Sans', sans-serif;">
        
        {{-- Header Section --}}
        <div class="mb-4 pb-3" style="border-bottom: 1px solid #334155;">
            <h1 class="h4 font-weight-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">
                {{ __('messages.create_content_header') }}
            </h1>
            <p class="text-sm mb-0" style="color: #94a3b8;">
                {{ __('messages.create_content_subheader') }}
            </p>
        </div>

        {{-- Session Error Alert --}}
        @if(session('error'))
            <div class="alert text-rose-400 border-0 mb-4 text-sm" style="background: rgba(244, 63, 94, 0.1); border: 1px solid rgba(244, 63, 94, 0.2) !important;">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Content Store Form --}}
        <form action="{{ route('admin.contents.store') }}" method="POST" class="row g-4">
            @csrf

            {{-- Asset Type Switcher --}}
            <div class="col-12">
                <label for="type_switcher" class="form-label text-slate-300 font-weight-bold mb-2" style="font-size: 14px;">
                    {{ __('messages.lbl_asset_type') }} <span class="text-danger">*</span>
                </label>
                <select name="type" id="type_switcher" class="form-select py-2.5">
                    <option value="post" {{ old('type') == 'post' ? 'selected' : '' }}>📄 {{ __('messages.opt_type_post') }}</option>
                    <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>🎥 {{ __('messages.opt_type_video') }}</option>
                </select>
                @error('type') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Headline Title --}}
            <div class="col-12">
                <label for="title" class="form-label text-slate-300 font-weight-bold mb-2" style="font-size: 14px;">
                    {{ __('messages.lbl_headline_title') }} <span class="text-danger">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                       placeholder="{{ __('messages.ph_headline_title') }}" class="form-control py-2.5">
                @error('title') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Article Body Container (Conditional) --}}
            <div class="col-12" id="post_meta_container">
                <label for="body" class="form-label text-slate-300 font-weight-bold mb-2" style="font-size: 14px;">
                    {{ __('messages.lbl_content_body') }}
                </label>
                <textarea name="body" id="body" rows="8" 
                          placeholder="{{ __('messages.ph_content_body') }}" class="form-control">{{ old('body') }}</textarea>
                @error('body') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Video URL Container (Conditional) --}}
            <div class="col-12 d-none" id="video_meta_container">
                <label for="video_url" class="form-label text-slate-300 font-weight-bold mb-2" style="font-size: 14px;">
                    {{ __('messages.lbl_video_url') }}
                </label>
                <input type="text" name="video_url" id="video_url" value="{{ old('video_url') }}" 
                       placeholder="https://www.youtube.com/watch?v=..." class="form-control py-2.5">
                @error('video_url') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Queue Pipeline Status --}}
            <div class="col-md-6">
                <label for="status" class="form-label text-slate-300 font-weight-bold mb-2" style="font-size: 14px;">
                    {{ __('messages.lbl_queue_status') }} <span class="text-danger">*</span>
                </label>
                <select name="status" id="status" class="form-select py-2.5">
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>🚀 {{ __('messages.opt_status_publish_now') }}</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📁 {{ __('messages.opt_status_hold_draft') }}</option>
                </select>
                @error('status') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            {{-- Video Duration --}}
            <div class="col-md-6">
                <label for="duration" class="form-label text-slate-300 font-weight-bold mb-2" style="font-size: 14px;">
                    {{ __('messages.lbl_video_duration') }} <small style="color: #64748b;">({{ __('messages.lbl_optional') }})</small>
                </label>
                <input type="number" name="duration" id="duration" value="{{ old('duration') }}" placeholder="e.g., 180" class="form-control py-2.5">
                @error('duration') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="col-12 pt-3 mt-4 d-flex justify-content-end gap-2" style="border-top: 1px solid #334155;">
                <a href="{{ route('admin.contents.index') }}" class="btn px-4 py-2" style="background: #0f172a; border: 1px solid #334155; color: #cbd5e1; border-radius: 6px;">
                    {{ __('messages.btn_cancel') }}
                </a>
                <button type="submit" class="btn px-4 py-2 text-white font-weight-bold" style="background: #6366f1; border-radius: 6px; border: none;">
                    {{ __('messages.btn_save_content') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const switcher = document.getElementById('type_switcher');
        const postBox  = document.getElementById('post_meta_container');
        const videoBox = document.getElementById('video_meta_container');

        function toggleInputLayers(type) {
            if (type === 'video') {
                videoBox.classList.remove('d-none');
                postBox.classList.add('d-none');
            } else {
                postBox.classList.remove('d-none');
                videoBox.classList.add('d-none');
            }
        }

        // Run execution mapping validation on early DOM computation states
        toggleInputLayers(switcher.value);

        // Track active interaction variations
        switcher.addEventListener('change', function(e) {
            toggleInputLayers(e.target.value);
        });
    });
</script>
@endsection