@extends('layouts.admin')
@section('title', __('messages.pipeline_meta_title'))
@section('page_title_key', 'sb_contents')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: var(--bg); min-height: 100vh; color: var(--text); transition: background-color 0.3s, color 0.3s;">
    
    {{-- Top Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold mb-1" style="font-family: 'Syne', sans-serif; color: var(--text);">
                {{ __('messages.pipeline_header') }}
            </h1>
            <p class="text-sm mb-0" style="color: var(--text-muted); font-family: 'DM Sans', sans-serif;">
                {{ __('messages.pipeline_subheader') }}
            </p>
        </div>
        {{-- Trigger Button for Modal --}}
        <button type="button" class="btn px-4 py-2 text-white font-weight-bold" data-bs-toggle="modal" data-bs-target="#createContentModal" style="background: var(--accent); border-radius: 8px; font-family: 'DM Sans', sans-serif; border: none;">
            <i class="fa-solid fa-plus me-2"></i> {{ __('messages.btn_create_content') }}
        </button>
    </div>

    {{-- Session Success Notification Alert --}}
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

    {{-- Stream Filter Tab Navigation --}}
    <div class="d-flex gap-2 mb-4 pb-2" style="border-bottom: 1px solid var(--border); font-family: 'DM Sans', sans-serif;">
        <a href="{{ route('admin.contents.index') }}" class="btn text-decoration-none px-3 py-2 text-sm" style="{{ !request('type') ? 'background: var(--surface-2); color: var(--text); border: 1px solid var(--border);' : 'color: var(--text-muted);' }} border-radius: 6px;">
            {{ __('messages.tab_all_stream') }}
        </a>
        <a href="{{ route('admin.contents.index', ['type' => 'post']) }}" class="btn text-decoration-none px-3 py-2 text-sm" style="{{ request('type') == 'post' ? 'background: var(--surface-2); color: var(--text); border: 1px solid var(--border);' : 'color: var(--text-muted);' }} border-radius: 6px;">
            <i class="fa-regular fa-file-lines me-2"></i> {{ __('messages.tab_articles_posts') }}
        </a>
        <a href="{{ route('admin.contents.index', ['type' => 'video']) }}" class="btn text-decoration-none px-3 py-2 text-sm" style="{{ request('type') == 'video' ? 'background: var(--surface-2); color: var(--text); border: 1px solid var(--border);' : 'color: var(--text-muted);' }} border-radius: 6px;">
            <i class="fa-regular fa-file-video me-2"></i> {{ __('messages.tab_video_library') }}
        </a>
    </div>

    {{-- Data Pipeline Grid Card Table --}}
    <div class="card border-0 overflow-hidden shadow-sm" style="background: var(--surface); border-radius: 12px; border: 1px solid var(--border) !important; font-family: 'DM Sans', sans-serif;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="color: var(--text);">
                <thead style="background: rgba(15, 23, 42, 0.05);">
                    <tr style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border);">
                        <th class="px-4 py-3 border-0">{{ __('messages.th_content_title') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_resource_type') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_author') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_status_state') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_date_log') }}</th>
                    </tr>
                </thead>
                <tbody style="border-top: none;">
                    @forelse($feed as $item)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td class="px-4 py-3">
                                <span class="d-block font-weight-medium mb-0" style="font-size: 15px; color: var(--text);">{{ $item->title }}</span>
                                <small class="font-mono" style="font-size: 11px; color: var(--text-muted);">{{ $item->slug }}</small>
                            </td>
                            <td class="px-4 py-3">
                                @if($item->type === 'post')
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(56, 189, 248, 0.1); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 20px; font-weight: 500;">
                                        📄 {{ __('messages.badge_post') }}
                                    </span>
                                @else
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.2); border-radius: 20px; font-weight: 500;">
                                        🎥 {{ __('messages.badge_video') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3" style="color: var(--text-muted);">
                                {{ $item->user->name ?? __('messages.default_author') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="{{ $item->status === 'published' ? 'text-emerald-400 font-weight-medium' : 'text-muted' }}">
                                    @if($item->status === 'published')
                                        {{ __('messages.status_published') }}
                                    @elseif($item->status === 'draft')
                                        {{ __('messages.status_draft') }}
                                    @else
                                        {{ ucfirst($item->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3" style="color: var(--text-muted); font-size: 12px; font-family: monospace;">
                                {{ $item->created_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-5 text-center" style="font-size: 14px; color: var(--text-muted);">
                                <i class="fa-solid fa-folder-open d-block mb-2 fs-3" style="color: var(--text-muted);"></i> 
                                {{ __('messages.no_pipeline_contents_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Render Appended Pagination Streams Links --}}
    <div class="mt-4 d-flex justify-content-end">
        {{ $feed->appends(request()->query())->links() }}
    </div>
</div>

{{-- Creation Modal Integration --}}
<div class="modal fade" id="createContentModal" tabindex="-1" aria-labelledby="createContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 p-2 shadow-lg" style="background: var(--surface); border-radius: 12px; border: 1px solid var(--border) !important; font-family: 'DM Sans', sans-serif;">
            
            {{-- Modal Header --}}
            <div class="modal-header pb-3 border-0 d-flex flex-column align-items-start position-relative" style="border-bottom: 1px solid var(--border) !important;">
                <h1 class="modal-title h4 font-weight-bold mb-1" id="createContentModalLabel" style="font-family: 'Syne', sans-serif; color: var(--text);">
                    {{ __('messages.create_content_header') }}
                </h1>
                <p class="text-sm mb-0 text-start" style="color: var(--text-muted);">
                    {{ __('messages.create_content_subheader') }}
                </p>
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--close-btn-filter, none); color: var(--text); shadow: none; outline: none;"></button>
            </div>

            {{-- Modal Body Form --}}
            <div class="modal-body py-4">
                <form action="{{ route('admin.contents.store') }}" method="POST" id="modal_content_form" class="row g-4">
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
                        <textarea name="body" id="body" rows="6" 
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
                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2" style="border-top: 1px solid var(--border) !important; padding-top: 1rem !important;">
                <button type="button" class="btn px-4 py-2" data-bs-dismiss="modal" style="background: var(--bg); border: 1px solid var(--border); color: var(--text-muted); border-radius: 6px; transition: all 0.2s;">
                    {{ __('messages.btn_cancel') }}
                </button>
                <button type="submit" form="modal_content_form" class="btn px-4 py-2 text-white font-weight-bold" style="background: var(--accent); border-radius: 6px; border: none; cursor: pointer; transition: background-color 0.2s;">
                    {{ __('messages.btn_save_content') }}
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
    /* Modal Close Button Adjustment for Dark/Light alignment */
    .btn-close:focus {
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

        toggleInputLayers(switcher.value);

        switcher.addEventListener('change', function(e) {
            toggleInputLayers(e.target.value);
        });

        // Smooth auto fade-out handling for success alert
        setTimeout(function() {
            var msg = document.getElementById('alert-msg');
            if(msg) msg.style.display = 'none';
        }, 4000);

        // Validation Error Auto-open Modal Feature
        @if($errors->any())
            var createModal = new bootstrap.Modal(document.getElementById('createContentModal'));
            createModal.show();
        @endif
    });
</script>
@endsection