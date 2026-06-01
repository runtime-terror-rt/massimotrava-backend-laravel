@extends('layouts.admin')
@section('title', __('messages.create_content_meta_title'))
@section('page_title_key', 'sb_contents')

@section('content')
<div class="container-fluid px-4 py-4 d-flex flex-column align-items-center" style="background-color: var(--bg); min-height: 100vh; color: var(--text); transition: background-color 0.3s, color 0.3s;">
    
    {{-- Top Utility Bar --}}
    <div class="w-100 mb-3 d-flex justify-content-start" style="max-width: 800px;">
        <a href="{{ route('admin.contents.index') }}" class="btn px-3 py-2 text-sm d-inline-flex align-items-center gap-2" style="background: var(--surface); border: 1px solid var(--border); color: var(--text); border-radius: 8px; font-family: 'DM Sans', sans-serif; transition: all 0.2s;">
            <i class="fa-solid fa-arrow-left text-xs" style="color: var(--text-muted);"></i> 
            <span>{{ __('messages.btn_back_content_list') }}</span>
        </a>
    </div>

    {{-- Content Card Wrapper --}}
    <div class="w-100 card border-0 p-4 shadow-sm mb-5" style="max-width: 800px; background: var(--surface); border-radius: 12px; border: 1px solid var(--border) !important; font-family: 'DM Sans', sans-serif; transition: background-color 0.3s, border-color 0.3s;">
        
        {{-- Header Section --}}
        <div class="mb-4 pb-3" style="border-bottom: 1px solid var(--border);">
            <h1 class="h4 font-weight-bold mb-1" style="font-family: 'Syne', sans-serif; color: var(--text);">
                {{ __('messages.create_content_header') }}
            </h1>
            <p class="text-sm mb-0" style="color: var(--text-muted);">
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
                <label for="type_switcher" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                    {{ __('messages.lbl_asset_type') }} <span class="text-danger">*</span>
                </label>
                <select name="type" id="type_switcher" class="form-select form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;">
                    <option value="post" {{ old('type') == 'post' ? 'selected' : '' }} style="background: var(--surface); color: var(--text);">📄 {{ __('messages.opt_type_post') }}</option>
                    <option value="video" {{ old('type') == 'video' ? 'selected' : '' }} style="background: var(--surface); color: var(--text);">🎥 {{ __('messages.opt_type_video') }}</option>
                </select>
                @error('type') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Headline Title --}}
            <div class="col-12">
                <label for="title" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                    {{ __('messages.lbl_headline_title') }} <span class="text-danger">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                       placeholder="{{ __('messages.ph_headline_title') }}" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;">
                @error('title') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Article Body Container (Conditional) --}}
            <div class="col-12" id="post_meta_container">
                <label for="body" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                    {{ __('messages.lbl_content_body') }}
                </label>
                <textarea name="body" id="body" rows="8" 
                          placeholder="{{ __('messages.ph_content_body') }}" class="form-control form-input" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;"></textarea>
                @error('body') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Video URL Container (Conditional) --}}
            <div class="col-12 d-none" id="video_meta_container">
                <label for="video_url" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                    {{ __('messages.lbl_video_url') }}
                </label>
                <input type="text" name="video_url" id="video_url" value="{{ old('video_url') }}" 
                       placeholder="https://www.youtube.com/watch?v=..." class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;">
                @error('video_url') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Queue Pipeline Status --}}
            <div class="col-md-6">
                <label for="status" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                    {{ __('messages.lbl_queue_status') }} <span class="text-danger">*</span>
                </label>
                <select name="status" id="status" class="form-select form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;">
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }} style="background: var(--surface); color: var(--text);">🚀 {{ __('messages.opt_status_publish_now') }}</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }} style="background: var(--surface); color: var(--text);">📁 {{ __('messages.opt_status_hold_draft') }}</option>
                </select>
                @error('status') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            {{-- Video Duration --}}
            <div class="col-md-6">
                <label for="duration" class="form-label font-weight-bold mb-2" style="font-size: 14px; color: var(--text);">
                    {{ __('messages.lbl_video_duration') }} <small style="color: var(--text-muted);">({{ __('messages.lbl_optional') }})</small>
                </label>
                <input type="number" name="duration" id="duration" value="{{ old('duration') }}" placeholder="e.g., 180" class="form-control form-input py-2.5" style="background-color: var(--surface-2); border: 1px solid var(--border); color: var(--text); outline: none;">
                @error('duration') <span class="text-danger d-block text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="col-12 pt-3 mt-4 d-flex justify-content-end gap-2" style="border-top: 1px solid var(--border);">
                <a href="{{ route('admin.contents.index') }}" class="btn px-4 py-2" style="background: var(--bg); border: 1px solid var(--border); color: var(--text-muted); border-radius: 6px; transition: all 0.2s;">
                    {{ __('messages.btn_cancel') }}
                </a>
                <button type="submit" class="btn px-4 py-2 text-white font-weight-bold" style="background: var(--accent); border-radius: 6px; border: none; cursor: pointer; transition: background-color 0.2s;">
                    {{ __('messages.btn_save_content') }}
                </button>
            </div>
        </form>
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
</style>

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