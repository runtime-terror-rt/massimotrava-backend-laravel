@extends('layouts.admin')
@section('title', __('messages.faq_meta_title'))
@section('page_title_key', 'sb_faq')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* SweetAlert2 Theme Adjustments to match Global Design */
        .swal2-popup {
            background: var(--surface, #ffffff) !important;
            color: var(--text, #1e293b) !important;
            border: 1px solid var(--border, #e2e8f0) !important;
            border-radius: 12px !important;
        }
        .swal2-title {
            color: var(--text, #1e293b) !important;
        }
        .swal2-html-container {
            color: var(--text-muted, #64748b) !important;
        }
        
        /* Custom Badge Styles for FAQ state */
        .badge-active {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }
        .badge-inactive {
            background-color: rgba(148, 163, 184, 0.1);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.2);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        /* Sticky Layout & Table Typography */
        .faq-sticky-card {
            background: var(--surface, #ffffff);
            border: 1px solid var(--border, #e2e8f0);
            border-radius: 12px;
            padding: 24px;
            position: sticky;
            top: 20px;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .faq-table-wrap {
            background: var(--surface, #ffffff);
            border: 1px solid var(--border, #e2e8f0);
            border-radius: 12px;
            padding: 12px;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .faq-table th {
            color: var(--text-muted, #64748b);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border, #e2e8f0);
            padding: 12px 8px;
        }
        .faq-table td {
            color: var(--text, #334155);
            border-bottom: 1px solid var(--border, #e2e8f0);
            padding: 14px 8px;
        }
        
        /* Input & Elements Focus Overrides */
        .faq-input {
            background-color: var(--bg, #f8fafc) !important;
            border: 1px solid var(--border, #e2e8f0) !important;
            color: var(--text, #1e293b) !important;
        }
        .faq-input:focus {
            border-color: var(--accent, #6366f1) !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4 py-2" style="color: var(--text, #1e293b);">
    
    {{-- Header Section --}}
    <div class="header-action d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 font-weight-bold mb-1" style="color: var(--text, #1e293b); font-family: 'Syne', sans-serif;">
                {{ __('messages.faq_header') }}
            </h2>
            <p class="mb-0 text-sm" style="color: var(--text-muted, #64748b); font-family: 'DM Sans', sans-serif;">
                {{ __('messages.faq_subheader') }}
            </p>
        </div>
    </div>

    <div class="row g-4" style="font-family: 'DM Sans', sans-serif;">
        {{-- Form Container (Sticky Side) --}}
        <div class="col-md-4">
            <div class="faq-sticky-card shadow-sm">
                <h4 class="h5 font-weight-bold mb-4" style="color: var(--text, #1e293b); font-family: 'Syne', sans-serif;">
                    @if($selectedFaq)
                        {{ __('messages.faq_title_edit', ['id' => $selectedFaq->id]) }}
                    @else
                        {{ __('messages.faq_title_create') }}
                    @endif
                </h4>
                
                <form action="{{ route('admin.faq.store') }}" method="POST">
                    @csrf
                    @if($selectedFaq)
                        <input type="hidden" name="id" value="{{ $selectedFaq->id }}">
                    @endif
                    
                    {{-- Question Input --}}
                    <div class="mb-3">
                        <label class="form-label font-weight-medium mb-2" style="color: var(--text, #334155); font-size: 14px;">
                            {{ __('messages.lbl_faq_question') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="question" class="form-control faq-input py-2.5" 
                               value="{{ old('question', $selectedFaq->question ?? '') }}" 
                               placeholder="{{ __('messages.ph_faq_question') }}" required>
                        @error('question') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Answer Textarea --}}
                    <div class="mb-3">
                        <label class="form-label font-weight-medium mb-2" style="color: var(--text, #334155); font-size: 14px;">
                            {{ __('messages.lbl_faq_answer') }} <span class="text-danger">*</span>
                        </label>
                        <textarea name="answer" class="form-control faq-input" rows="5" 
                                  placeholder="{{ __('messages.ph_faq_answer') }}" required>{{ old('answer', $selectedFaq->answer ?? '') }}</textarea>
                        @error('answer') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Status Select --}}
                    <div class="mb-4">
                        <label class="form-label font-weight-medium mb-2" style="color: var(--text, #334155); font-size: 14px;">
                            {{ __('messages.lbl_faq_status') }}
                        </label>
                        <select name="is_active" class="form-select faq-input py-2.5">
                            <option value="1" {{ old('is_active', $selectedFaq->is_active ?? 1) == 1 ? 'selected' : '' }}>{{ __('messages.opt_faq_active') }}</option>
                            <option value="0" {{ old('is_active', $selectedFaq->is_active ?? 1) == 0 ? 'selected' : '' }}>{{ __('messages.opt_faq_inactive') }}</option>
                        </select>
                        @error('is_active') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Form Actions Buttons --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn text-white w-100 font-weight-medium" style="background: var(--accent, #6366f1); border: none;">
                            {{ $selectedFaq ? __('messages.btn_faq_update') : __('messages.btn_faq_save') }}
                        </button>
                        @if($selectedFaq)
                            <a href="{{ route('admin.faq.index') }}" class="btn btn-outline-secondary px-3" style="border-color: var(--border); color: var(--text-muted);">
                                {{ __('messages.btn_faq_cancel') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Grid Data List Table Container --}}
        <div class="col-md-8">
            <div class="faq-table-wrap shadow-sm">
                <div class="table-responsive">
                    <table class="table faq-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 10%;">{{ __('messages.th_faq_id') }}</th>
                                <th style="width: 32%;">{{ __('messages.th_faq_question') }}</th>
                                <th style="width: 35%;">{{ __('messages.th_faq_answer') }}</th>
                                <th style="width: 10%;">{{ __('messages.th_faq_status') }}</th>
                                <th style="width: 13%; text-align: center;">{{ __('messages.th_faq_actions') }}</th>
                            </tr>
                        </thead>
                        <tbody style="border-top: none;">
                            @forelse($faqs as $faq)
                            <tr>
                                <td class="text-mono" style="font-size: 13px; color: var(--text-muted);">#{{ $faq->id }}</td>
                                <td style="font-weight: 500; color: var(--text, #1e293b);">{{ $faq->question }}</td>
                                <td style="color: var(--text-muted, #64748b); font-size: 13px;">{{ Str::limit($faq->answer, 80, '...') }}</td>
                                <td>
                                    <a href="{{ route('admin.faq.toggle', $faq->id) }}" class="text-decoration-none d-inline-block">
                                        <span class="badge {{ $faq->is_active ? 'badge-active' : 'badge-inactive' }}">
                                            {{ $faq->is_active ? __('messages.badge_faq_active') : __('messages.badge_faq_inactive') }}
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.faq.index', ['id' => $faq->id]) }}" class="btn btn-sm btn-outline-info" style="border-radius: 6px;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-danger" style="border-radius: 6px;" onclick="confirmDelete({{ $faq->id }})">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        
                                        <form id="delete-form-{{ $faq->id }}" action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color: var(--text-muted, #64748b); font-size: 14px;">
                                    <i class="fa-solid fa-circle-info d-block mb-2 fs-4" style="color: var(--text-muted);"></i>
                                    {{ __('messages.no_faqs_found') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Success Session Trigger
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: "{{ __('messages.swal_success_title') }}",
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        @endif

        // Error Session Trigger
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: "{{ __('messages.swal_error_title') }}",
                text: "{{ session('error') }}",
                confirmButtonColor: 'var(--accent, #6366f1)'
            });
        @endif
    });

    // Delete Confirmation handler
    function confirmDelete(id) {
        Swal.fire({
            title: "{{ __('messages.swal_delete_title') }}",
            text: "{{ __('messages.swal_delete_text') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: 'var(--text-muted, #64748b)',
            confirmButtonText: "{{ __('messages.swal_delete_confirm_btn') }}",
            cancelButtonText: "{{ __('messages.swal_delete_cancel_btn') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection