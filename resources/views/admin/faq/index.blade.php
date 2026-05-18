@extends('layouts.admin')

@section('title', 'FAQ Management')

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
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <div>
        <h2 style="color: white; font-weight: 600; margin-bottom: 5px;">Frequently Asked Questions (FAQs)</h2>
        <p style="color: #94a3b8; margin: 0; font-size: 14px;">Create, update, and manage system FAQs seamlessly.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px; position: sticky; top: 20px;">
            <h4 style="color: white; margin-bottom: 20px; font-weight: 600;">
                {{ $selectedFaq ? 'Edit FAQ #'.$selectedFaq->id : 'Create New FAQ' }}
            </h4>
            
            <form action="{{ route('admin.faq.store') }}" method="POST">
                @csrf
                @if($selectedFaq)
                    <input type="hidden" name="id" value="{{ $selectedFaq->id }}">
                @endif
                
                <div class="mb-3">
                    <label class="form-label" style="color: #cbd5e1; font-size: 14px;">Question</label>
                    <input type="text" name="question" class="form-control" value="{{ old('question', $selectedFaq->question ?? '') }}" placeholder="e.g. What is Vyralabs?" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: #cbd5e1; font-size: 14px;">Answer</label>
                    <textarea name="answer" class="form-control" rows="5" placeholder="Provide a detailed answer..." required>{{ old('answer', $selectedFaq->answer ?? '') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label" style="color: #cbd5e1; font-size: 14px;">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ old('is_active', $selectedFaq->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $selectedFaq->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        {{ $selectedFaq ? 'Update FAQ' : 'Save FAQ' }}
                    </button>
                    @if($selectedFaq)
                        <a href="{{ route('admin.faq.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-wrap" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 10px;">
            <table class="table align-middle" style="margin: 0;">
                <thead>
                    <tr>
                        <th style="width: 8%;">ID</th>
                        <th style="width: 30%;">Question</th>
                        <th style="width: 37%;">Answer</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%; text-align: center;">Actions</th>
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
                                    {{ $faq->is_active ? 'Active' : 'Inactive' }}
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
                        <td colspan="5" class="text-center py-4" style="color: #94a3b8;">No FAQs found.</td>
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
                title: 'Success!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3085d6'
            });
        @endif
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this data trace!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection