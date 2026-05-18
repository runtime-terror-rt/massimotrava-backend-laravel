@extends('layouts.admin')

@section('title', 'Privacy Policy Management')

@section('content')
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <div>
        <h2 style="color: white; font-weight: 600; margin-bottom: 5px;">Privacy Policy</h2>
        <p style="color: #94a3b8; margin: 0; font-size: 14px;">Update and manage your application privacy terms.</p>
    </div>
</div>

<div class="card mb-5" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px;">
    <form action="{{ route('admin.privacy-policy.save') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $selectedPolicy->id }}">
        
        <div class="row g-4">
            <div class="col-md-8">
                <label class="form-label" style="color: #cbd5e1;">Policy Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $selectedPolicy->title) }}" placeholder="e.g. Privacy Policy" required>
            </div>

            <div class="col-md-4">
                <label class="form-label" style="color: #cbd5e1;">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ old('is_active', $selectedPolicy->is_active) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $selectedPolicy->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <hr style="border-color: var(--border); margin-top: 40px;">

            <div class="col-12">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h4 style="color: white; margin: 0;">Policy Sections</h4>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">
                        <i class="fa-solid fa-plus"></i> Add New Section
                    </button>
                </div>

                <div id="policy-items-container">
                    @php
                        // Correctly handling content array
                        $items = old('items', $selectedPolicy->content ?? [['heading' => '', 'content' => '']]);
                    @endphp

                    @foreach($items as $index => $item)
                    <div class="policy-item-row" style="background: rgba(255,255,255,0.03); padding: 20px; border-radius: 8px; margin-bottom: 15px; border: 1px solid var(--border); position: relative;">
                        <button type="button" class="btn-close btn-close-white" style="position: absolute; top: 10px; right: 10px; font-size: 12px;" onclick="removeItem(this)"></button>
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; color: #94a3b8;">Section Heading</label>
                            <input type="text" name="items[{{ $index }}][heading]" class="form-control" value="{{ $item['heading'] ?? '' }}" required>
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 13px; color: #94a3b8;">Section Content</label>
                            <textarea name="items[{{ $index }}][content]" class="form-control" rows="4" required>{{ $item['content'] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-5">Save Changes</button>
            </div>
        </div>
    </form>
</div>

<div class="table-wrap mt-4">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Created At</th>
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($policies as $policy)
            <tr>
                <td>#{{ $policy->id }}</td>
                <td>{{ $policy->title }}</td>
                <td>
                    <span class="badge {{ $policy->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $policy->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>{{ $policy->created_at->format('d M, Y') }}</td>
                <td style="text-align: center;">
                    <a href="{{ route('admin.privacy-policy.index', ['id' => $policy->id]) }}" class="action-btn edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection