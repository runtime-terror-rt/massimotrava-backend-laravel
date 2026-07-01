@extends('layouts.admin')
@section('title', __('messages.reviews_meta_title'))
@section('page_title_key', 'sb_reviews')
@section('content')

{{-- Action Header Block --}}
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h2 style="color: white; font-weight: 600; margin-bottom: 0;">{{ __('messages.reviews_header') }}</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReviewModal">
        <i class="fa-solid fa-plus me-1"></i> {{ __('messages.btn_add_review') }}
    </button>
</div>

{{-- Success and General Error Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: #10b981; color: white; border: none;">
        {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: #ef4444; color: white; border: none;">
        {{ session('error') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Data Table Pipeline Index --}}
<div class="admin-table" style="width: 100%; border-collapse: collapse;"> 
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('messages.th_author_image') }}</th>
                <th>{{ __('messages.th_author_name') }}</th>
                <th>{{ __('messages.th_rating') }}</th>
                <th>{{ __('messages.th_review_text') }}</th>
                <th>{{ __('messages.th_verified_status') }}</th>
                <th style="text-align: center;">{{ __('messages.th_status') }}</th> {{-- এই কলামটি স্ট্যাটাসের জন্য --}}
                <th style="text-align: center;">{{ __('messages.th_action') }}</th> {{-- এই কলামটি ডিলিট বাটনের জন্য --}}
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td>
                    @if($review->author_image)
                        <img src="{{ asset('images/reviews/' . $review->author_image) }}" alt="Author" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div class="td-avatar" style="background: #334155; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    @endif
                </td>
                <td class="td-name">{{ $review->author_name ?? __('messages.lbl_not_available') }}</td>
                <td>
                    <span class="text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                        @endfor
                    </span>
                </td>
                <td>{{ Str::limit($review->text, 50) }}</td>
                <td>
                    <span class="badge {{ $review->is_verified ? 'bg-success' : 'bg-secondary' }}">
                        {{ $review->is_verified ? __('messages.verified') : __('messages.not_verified') }}
                    </span>
                </td> 
                <td style="text-align: center; vertical-align: middle;">
                    <form action="{{ route('admin.reviews.toggle-status', $review->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-sm" style="border: none; background: transparent; padding: 0;">
                            <span class="badge {{ $review->status ? 'badge-active' : 'badge-inactive' }}" style="cursor: pointer;">
                                {{ $review->status ? __('messages.status_active') : __('messages.status_inactive') }}
                            </span>
                        </button>
                    </form>
                </td>

                <td style="text-align: center; vertical-align: middle;">
                    <div class="action-btns" style="display: flex; justify-content: center;">
                        <button onclick="confirmDelete({{ $review->id }})" class="btn btn-sm btn-outline-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                    <form id="delete-form-{{ $review->id }}" action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #64748b; padding: 20px;">
                    {{ __('messages.no_reviews_found') }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- System Pagination Controls Grid --}}
    <div class="pagination-wrapper" style="margin-top: 20px;">
        {{-- $reviews->links() --}}
    </div>
</div>
@endsection


@push('modals')
{{-- Create Review Modal --}}
<div class="modal fade" id="createReviewModal" tabindex="-1" aria-labelledby="createReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: #1e293b; color: white; border: 1px solid #334155;">
            <div class="modal-header" style="border-bottom: 1px solid #334155;">
                <h5 class="modal-title" id="createReviewModalLabel">{{ __('messages.modal_title_add_review') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.reviews.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        
                        {{-- Author Name --}}
                        <div class="col-md-6">
                            <label class="form-label" style="color: #cbd5e1;">{{ __('messages.lbl_author_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="author_name" value="{{ old('author_name') }}" class="form-control @error('author_name') is-invalid @enderror" style="background: #0f172a; color: white; border: 1px solid #334155;" placeholder="Client Name" required>
                            @error('author_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Rating Selection --}}
                        <div class="col-md-6">
                            <label class="form-label" style="color: #cbd5e1;">{{ __('messages.lbl_rating') }} <span class="text-danger">*</span></label>
                            <select name="rating" class="form-select text-white @error('rating') is-invalid @enderror" style="background: #0f172a; color: white; border: 1px solid #334155;" required>
                                <option value="" style="background: #1e293b; color: #cbd5e1;">-- {{ __('messages.opt_select_rating') }} --</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} {{ __('messages.stars') }}</option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Author Image --}}
                        <div class="col-md-6">
                            <label class="form-label" style="color: #cbd5e1;">{{ __('messages.lbl_author_image') }}</label>
                            <input type="file" name="author_image" class="form-control @error('author_image') is-invalid @enderror" style="background: #0f172a; color: white; border: 1px solid #334155;">
                            @error('author_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Is Verified Checkbox --}}
                        <div class="col-md-6 d-flex align-items-center" style="margin-top: 45px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_verified" value="1" id="is_verified" {{ old('is_verified') ? 'checked' : '' }}>
                                <label class="form-check-input-label" for="is_verified" style="color: #cbd5e1; margin-left: 10px;">
                                    {{ __('messages.lbl_mark_as_verified') }}
                                </label>
                            </div>
                        </div>

                        {{-- Review Text --}}
                        <div class="col-12">
                            <label class="form-label" style="color: #cbd5e1;">{{ __('messages.lbl_review_text') }} <span class="text-danger">*</span></label>
                            <textarea name="text" rows="4" class="form-control @error('text') is-invalid @enderror" style="background: #0f172a; color: white; border: 1px solid #334155;" placeholder="{{ __('messages.placeholder_write_review') }}" required>{{ old('text') }}</textarea>
                            @error('text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #334155;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('messages.btn_modal_cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ __('messages.btn_modal_create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm("{{ __('messages.js_alert_delete_review') }}")) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        @if($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('createReviewModal'));
            myModal.show();
        @endif
    });
</script>
@endpush