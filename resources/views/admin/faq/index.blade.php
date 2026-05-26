@extends('layouts.admin')
@section('title', __('messages.faq_meta_title'))
@section('page_title_key', 'sb_faq')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .swal2-popup {
            background: var(--surface, #1e293b) !important;
            color: #ffffff !important;
            border: 1px solid var(--border, #334155) !important;
            border-radius: 12px !important;
        }
        .swal2-title {
            color: #ffffff !important;
        }
        .swal2-html-container {
            color: #94a3b8 !important;
        }
    </style>
@endpush

@section('content')
{{-- Header --}}
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <div>
        <h2 style="color: white; font-weight: 600; margin-bottom: 5px;">{{ __('messages.faq_header') }}</h2>
        <p style="color: #94a3b8; margin: 0; font-size: 14px;">{{ __('messages.faq_subheader') }}</p>
    </div>
</div>

<div class="row g-4">
    {{-- Form Container (Sticky Side) --}}
    <div class="col-md-4">
        <div class="card" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px; position: sticky; top: 20px;">
            <h4 style="color: white; margin-bottom: 20px; font-weight: 600;">
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
                    <label class="form-label" style="color: #cbd5e1; font-size: 14px;">{{ __('messages.lbl_faq_question') }}</label>
                    <input type="text" name="question" class="form-control" 
                           value="{{ old('question', $selectedFaq->question ?? '') }}" 
                           placeholder="{{ __('messages.ph_faq_question') }}" required>
                    @error('question') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                </div>

                {{-- Answer Textarea --}}
                <div class="mb-3">
                    <label class="form-label" style="color: #cbd5e1; font-size: 14px;">{{ __('messages.lbl_faq_answer') }}</label>
                    <textarea name="answer" class="form-control" rows="5" 
                              placeholder="{{ __('messages.ph_faq_answer') }}" required>{{ old('answer', $selectedFaq->answer ?? '') }}</textarea>
                    @error('answer') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                </div>

                {{-- Status Select --}}
                <div class="mb-4">
                    <label class="form-label" style="color: #cbd5e1; font-size: 14px;">{{ __('messages.lbl_faq_status') }}</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ old('is_active', $selectedFaq->is_active ?? 1) == 1 ? 'selected' : '' }}>{{ __('messages.opt_faq_active') }}</option>
                        <option value="0" {{ old('is_active', $selectedFaq->is_active ?? 1) == 0 ? 'selected' : '' }}>{{ __('messages.opt_faq_inactive') }}</option>
                    </select>
                    @error('is_active') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                </div>

                {{-- Form Actions Buttons --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        {{ $selectedFaq ? __('messages.btn_faq_update') : __('messages.btn_faq_save') }}
                    </button>
                    @if($selectedFaq)
                        <a href="{{ route('admin.faq.index') }}" class="btn btn-outline-secondary">
                            {{ __('messages.btn_faq_cancel') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Grid Data List Table Container --}}
    <div class="col-md-8">
        <div class="table-wrap" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 10px;">
            <table class="table align-middle" style="margin: 0;">
                <thead>
                    <tr>
                        <th style="width: 8%;">{{ __('messages.th_faq_id') }}</th>
                        <th style="width: 30%;">{{ __('messages.th_faq_question') }}</th>
                        <th style="width: 37%;">{{ __('messages.th_faq_answer') }}</th>
                        <th style="width: 10%;">{{ __('messages.th_faq_status') }}</th>
                        <th style="width: 15%; text-align: center;">{{ __('messages.th_faq_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                    <tr>
                        <td>#{{ $faq->id }}</td>
                        <td style="font-weight: 500; color: white;">{{ $faq->question }}</td>
                        <td style="color: #94a3b8; font-size: 13px;">{{ Str::limit($faq->answer, 80, '...') }}</td>
                        <td>
                            <a href="{{ route('admin.faq.toggle', $faq->id) }}" style="text-decoration: none;">
                                <span class="badge {{ $faq->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $faq->is_active ? __('messages.badge_faq_active') : __('messages.badge_faq_inactive') }}
                                </span>
                            </a>
                        </td>
                        <td style="text-align: center;">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.faq.index', ['id' => $faq->id]) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $faq->id }})">
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
                        <td colspan="5" class="text-center py-4" style="color: #94a3b8;">
                            {{ __('messages.no_faqs_found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
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

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: "{{ __('messages.swal_error_title') }}",
                text: "{{ session('error') }}",
                confirmButtonColor: '#3085d6'
            });
        @endif
    });

    function confirmDelete(id) {
        Swal.fire({
            title: "{{ __('messages.swal_delete_title') }}",
            text: "{{ __('messages.swal_delete_text') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
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