@extends('layouts.admin')
@section('title', __('messages.privacy_meta_title'))

@section('content')
{{-- Header Grid Module --}}
<div class="header-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <div>
        <h2 style="color: white; font-weight: 600; margin-bottom: 5px;">{{ __('messages.privacy_header') }}</h2>
        <p style="color: #94a3b8; margin: 0; font-size: 14px;">{{ __('messages.privacy_subheader') }}</p>
    </div>
</div>

<div class="card mb-5" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px;">
    <form action="{{ route('admin.privacy-policy.save') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $selectedPolicy->id }}">
        
        <div class="row g-4">
            {{-- Title Input Segment --}}
            <div class="col-md-8">
                <label class="form-label" style="color: #cbd5e1;">{{ __('messages.lbl_policy_title') }} <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $selectedPolicy->title) }}" placeholder="{{ __('messages.ph_policy_title') }}" required>
            </div>

            {{-- Status Config Select --}}
            <div class="col-md-4">
                <label class="form-label" style="color: #cbd5e1;">{{ __('messages.lbl_policy_status') }}</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ old('is_active', $selectedPolicy->is_active) == 1 ? 'selected' : '' }}>{{ __('messages.status_active') }}</option>
                    <option value="0" {{ old('is_active', $selectedPolicy->is_active) == 0 ? 'selected' : '' }}>{{ __('messages.status_inactive') }}</option>
                </select>
            </div>

            <hr style="border-color: var(--border); margin-top: 40px;">

            {{-- Nested Repeater Sections Block --}}
            <div class="col-12">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h4 style="color: white; margin: 0;">{{ __('messages.lbl_policy_sections_heading') }}</h4>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">
                        <i class="fa-solid fa-plus me-1"></i> {{ __('messages.btn_add_section') }}
                    </button>
                </div>

                <div id="policy-items-container">
                    @php
                        $items = old('items', $selectedPolicy->content ?? [['heading' => '', 'content' => '']]);
                    @endphp

                    @foreach($items as $index => $item)
                    <div class="policy-item-row" style="background: rgba(255,255,255,0.03); padding: 20px; border-radius: 8px; margin-bottom: 15px; border: 1px solid var(--border); position: relative;">
                        <button type="button" class="btn-close btn-close-white" style="position: absolute; top: 10px; right: 10px; font-size: 12px;" onclick="removeItem(this)"></button>
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 13px; color: #94a3b8;">{{ __('messages.lbl_section_heading') }} <span class="text-danger">*</span></label>
                            <input type="text" name="items[{{ $index }}][heading]" class="form-control" value="{{ $item['heading'] ?? '' }}" required>
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 13px; color: #94a3b8;">{{ __('messages.lbl_section_content') }} <span class="text-danger">*</span></label>
                            <textarea name="items[{{ $index }}][content]" class="form-control" rows="4" required>{{ $item['content'] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-5">{{ __('messages.btn_save_changes') }}</button>
            </div>
        </div>
    </form>
</div>

{{-- History / Archive Log Grid Matrix --}}
<div class="table-wrap mt-4">
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('messages.th_policy_id') }}</th>
                <th>{{ __('messages.th_policy_title') }}</th>
                <th>{{ __('messages.th_policy_status') }}</th>
                <th>{{ __('messages.th_policy_created') }}</th>
                <th style="text-align: center;">{{ __('messages.th_policy_action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($policies as $policy)
            <tr>
                <td>#{{ $policy->id }}</td>
                <td>{{ $policy->title }}</td>
                <td>
                    <span class="badge {{ $policy->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $policy->is_active ? __('messages.status_active') : __('messages.status_inactive') }}
                    </span>
                </td>
                <td>{{ $policy->created_at ? $policy->created_at->format('d M, Y') : '' }}</td>
                <td style="text-align: center;">
                    <a href="{{ route('admin.privacy-policy.index', ['id' => $policy->id]) }}" class="action-btn edit" title="{{ __('messages.btn_title_edit') }}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    // System Repeater Metrics Tracking Configuration Matrix
    let itemIndex = {{ count((array)$items) }};
    
    // JS Injection Object for UI Multi-language Adaptations
    const localeRepeater = {
        labelHeading: "{{ __('messages.lbl_section_heading') }}",
        labelContent: "{{ __('messages.lbl_section_content') }}",
        phHeading: "{{ __('messages.ph_section_heading') }}",
        phContent: "{{ __('messages.ph_section_content') }}",
        alertMinSection: "{{ __('messages.js_alert_min_policy_section') }}"
    };

    function addItem() {
        const container = document.getElementById('policy-items-container');
        const html = `
            <div class="policy-item-row" style="background: rgba(255,255,255,0.03); padding: 20px; border-radius: 8px; margin-bottom: 15px; border: 1px solid var(--border); position: relative;">
                <button type="button" class="btn-close btn-close-white" style="position: absolute; top: 10px; right: 10px; font-size: 12px;" onclick="removeItem(this)"></button>
                <div class="mb-3">
                    <label class="form-label" style="font-size: 13px; color: #94a3b8;">${localeRepeater.labelHeading} <span class="text-danger">*</span></label>
                    <input type="text" name="items[${itemIndex}][heading]" class="form-control" placeholder="${localeRepeater.phHeading}" required>
                </div>
                <div>
                    <label class="form-label" style="font-size: 13px; color: #94a3b8;">${localeRepeater.labelContent} <span class="text-danger">*</span></label>
                    <textarea name="items[${itemIndex}][content]" class="form-control" rows="4" placeholder="${localeRepeater.phContent}" required></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        itemIndex++;
    }

    function removeItem(btn) {
        const rows = document.querySelectorAll('.policy-item-row');
        if (rows.length > 1) {
            btn.closest('.policy-item-row').remove();
        } else {
            alert(localeRepeater.alertMinSection);
        }
    }
</script>
@endpush