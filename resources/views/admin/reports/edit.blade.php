@extends('layouts.admin')
@section('title', isset($report) ? __('messages.rep_meta_title_edit', ['default' => 'Edit Report']) : __('messages.rep_meta_title', ['default' => 'Create Report']))
@section('page_title_key', 'sb_reports')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Select2 থিম অ্যাডাপ্টেশন */
    .select2-container--default .select2-selection--single {
        background-color: var(--surface-input) !important;
        border: 1px solid var(--border) !important;
        height: 48px !important;
        border-radius: 8px !important;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--text-main) !important;
        padding-left: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
    }
    .select2-dropdown {
        background-color: var(--surface-input) !important;
        border: 1px solid var(--border) !important;
        color: var(--text-main) !important;
    }
    .select2-search__field {
        background-color: var(--surface-modal) !important;
        border: 1px solid var(--border) !important;
        color: var(--text-main) !important;
        border-radius: 4px !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--accent) !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: var(--surface-2) !important;
        color: var(--text-main) !important;
    }

    /* গ্লোবাল ফর্ম ও বোতাম ট্রানজিশন ইফেক্ট */
    .report-container {
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .category-block {
        background: var(--surface-modal) !important;
        border: 1px solid var(--border) !important;
    }
    .nav-back-btn {
        color: var(--text-muted);
        background: var(--surface-input);
        border: 1px solid var(--border);
    }
    .nav-back-btn:hover {
        color: var(--text-main);
        border-color: var(--accent);
        background: var(--surface-2);
    }
</style>
@endpush

@if ($errors->any())
    <div style="background: rgba(239, 68, 68, 0.1); color: #f87171; padding: 15px; border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.2); margin-bottom: 25px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('content')
{{-- Action Navigation Header --}}
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.reports.index') }}" class="nav-back-btn"
       style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 14px; padding: 10px 18px; border-radius: 8px; transition: all 0.3s ease; box-shadow: var(--glass-shadow, 0 4px 6px -1px rgba(0, 0, 0, 0.1));">
        <i class="fa-solid fa-arrow-left-long"></i> 
        <span style="font-weight: 500;">{{ __('messages.btn_back_to_list') }}</span>
    </a>
</div>

{{-- Main Form Container --}}
<div class="report-container" style="width: 80%; margin: 30px auto; padding: 30px; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; color: var(--text-main); font-family: sans-serif;">
    <h2 style="margin-bottom: 25px; font-size: 24px; color: var(--text-main);">
        {{ isset($report) ? __('messages.rep_header_edit', ['default' => 'Edit Biomarker Report']) : __('messages.rep_header_add') }}
    </h2>

    <form action="{{ isset($report) ? route('admin.reports.update', $report->id) : route('admin.reports.store') }}" method="POST">
        @csrf
        @if(isset($report))
            @method('PUT')
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 20px;">
            <div>
                <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">{{ __('messages.lbl_select_user') }} <span class="text-danger">*</span></label>
                <select name="user_id" id="user_id_select" class="searchable-user-select" style="width: 100%;" required {{ isset($report) ? 'disabled' : '' }}>
                    <option value="">-- {{ __('messages.opt_select_user') }} --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (old('user_id', $report->user_id ?? '') == $user->id) ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @if(isset($report))
                    <input type="hidden" name="user_id" value="{{ $report->user_id }}">
                @endif
            </div>

            <div>
                <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">{{ __('messages.lbl_select_kit') }} <span class="text-danger">*</span></label>
                <select name="kit_id" id="kit_id_select" style="width: 100%; background: var(--surface-input); color: var(--text-main); border: 1px solid var(--border); padding: 12px; border-radius: 8px;" required {{ isset($report) ? 'disabled' : '' }}>
                    @if(isset($report))
                        <option value="{{ $report->kit_id }}" selected>{{ $report->kit->activation_code ?? 'Selected Kit' }} [{{ $report->kit->inv_code ?? 'N/A' }}]</option>
                    @else
                        <option value="">-- {{ __('messages.opt_first_select_user') }} --</option>
                    @endif
                </select>
                @if(isset($report))
                    <input type="hidden" name="kit_id" value="{{ $report->kit_id }}">
                @endif
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border); margin: 30px 0;">

        {{-- Dynamic Repeater Target Injection Point --}}
        <div id="categories-master-container">
            @if(isset($report) && isset($reportDetails))
                @foreach($reportDetails as $catKey => $detailGroup)
                    <div class="category-block" data-cat-id="{{ $catKey }}" style="border: 1px solid var(--border); padding: 20px; border-radius: 12px; margin-bottom: 25px; position: relative;">
                        <button type="button" class="remove-category-btn" style="position: absolute; right: 15px; top: 55px; background: #ef4444; color: white; border: none; border-radius: 5px; cursor: pointer; padding: 5px 12px; font-size: 13px;">{{ __('messages.btn_remove') }}</button>
                        
                        <div style="margin-bottom: 20px; width: 80%;">
                            <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">{{ __('messages.lbl_select_biomarker_category') }} <span class="text-danger">*</span></label>
                            <select name="categories[{{ $catKey }}][id]" class="category-select" style="width: 100%; background: var(--surface-input); color: var(--text-main); border: 1px solid var(--border); padding: 12px; border-radius: 8px;" required>
                                <option value="">-- {{ __('messages.opt_select_category') }} --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $detailGroup['category_id'] ? 'selected' : '' }}>{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="subcategories-container" style="margin-left: 20px; border-left: 2px solid var(--border); padding-left: 20px;">
                            @foreach($detailGroup['subcategories'] as $subKey => $subDetail)
                                <div class="subcategory-row" style="display: grid; grid-template-columns: 1fr 1fr 40px; gap: 15px; margin-bottom: 10px; align-items: center;">
                                    <div>
                                        <select name="categories[{{ $catKey }}][reports][{{ $subKey }}][subcategory_id]" style="width: 100%; background: var(--surface-input); color: var(--text-main); border: 1px solid var(--border); padding: 10px; border-radius: 6px;" required>
                                            @foreach($detailGroup['all_subs_options'] as $optSub)
                                                <option value="{{ $optSub->id }}" {{ $optSub->id == $subDetail->subcategory_id ? 'selected' : '' }}>{{ $optSub->title }} ({{ $optSub->unit }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <input type="number" step="0.01" name="categories[{{ $catKey }}][reports][{{ $subKey }}][value]" value="{{ $subDetail->value }}" style="width: 100%; background: var(--surface-input); color: var(--text-main); border: 1px solid var(--border); padding: 10px; border-radius: 6px;" placeholder="{{ __('messages.ph_biomarker_value') }}" required>
                                    </div>
                                    <button type="button" class="remove-sub-btn" style="background: transparent; color: #ef4444; border: none; cursor: pointer; font-size: 18px;" title="{{ __('messages.btn_remove') }}">
                                        <i class="fa fa-times-circle"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="add-sub-btn" style="margin-top: 10px; background: #38bdf8; color: #0f172a; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: bold;">
                            + {{ __('messages.btn_add_subcategory_value') }}
                        </button>
                    </div>
                @endforeach
            @endif
        </div>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <button type="button" id="add-category-btn" style="background: var(--accent); color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                <i class="fa fa-plus me-1"></i> {{ __('messages.btn_add_category_block') }}
            </button>
        </div>

        <button type="submit" style="width: 100%; background: #22c55e; color: white; border: none; padding: 15px; border-radius: 10px; font-weight: bold; font-size: 16px; cursor: pointer; margin-top: 30px; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            {{ isset($report) ? __('messages.btn_update_all_reports', ['default' => 'Update Report']) : __('messages.btn_save_all_reports') }}
        </button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.searchable-user-select').select2({
            placeholder: "-- {{ __('messages.opt_select_user') }} --",
            allowClear: true,
            width: '100%'
        });

        // এডিট মোড ক্যাচ করার জন্য এক্সিস্টিং ব্লকের সংখ্যা ট্র্যাক করা
        let catIdx = $('#categories-master-container .category-block').length;

        const localeMatrix = {
            searchingKits: "{{ __('messages.js_searching_kits') }}",
            selectKitDefault: "{{ __('messages.opt_select_kit') }}",
            lblSelectCategory: "{{ __('messages.lbl_select_biomarker_category') }}",
            optSelectCategory: "{{ __('messages.opt_select_category') }}",
            btnRemove: "{{ __('messages.btn_remove') }}",
            btnAddSubValue: "{{ __('messages.btn_add_subcategory_value') }}",
            phValue: "{{ __('messages.ph_biomarker_value') }}",
            alertNoSubs: "{{ __('messages.js_alert_no_subcategories') }}"
        };

        const categoryOptionsHtml = `
            <option value="">-- ${localeMatrix.optSelectCategory} --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ addslashes($category->title) }}</option>
            @endforeach
        `;

        // ইউজার চেঞ্জ ইভেন্ট (শুধুমাত্র ক্রিয়েট মোডে কাজ করবে)
        $('#user_id_select').on('change', function() {
            let userId = $(this).val();
            let $kitSelect = $('#kit_id_select');
            $kitSelect.html(`<option value="">${localeMatrix.searchingKits}</option>`);
            if(userId) {
                $.get("{{ route('admin.get-user-kits') }}", { user_id: userId }, function(data) {
                    $kitSelect.empty().append(`<option value="">-- ${localeMatrix.selectKitDefault} --</option>`);
                    data.forEach(kit => {
                        $kitSelect.append(`<option value="${kit.id}">${kit.activation_code} [${kit.inv_code}]</option>`);
                    });
                });
            } else {
                $kitSelect.html(`<option value="">-- {{ __('messages.opt_first_select_user') }} --</option>`);
            }
        });

        // Add Category Block Layer
        $('#add-category-btn').on('click', function() {
            let categoryHtml = `
            <div class="category-block" data-cat-id="${catIdx}" style="border: 1px solid var(--border); padding: 20px; border-radius: 12px; margin-bottom: 25px; position: relative;">
                <button type="button" class="remove-category-btn" style="position: absolute; right: 15px; top: 55px; background: #ef4444; color: white; border: none; border-radius: 5px; cursor: pointer; padding: 5px 12px; font-size: 13px;">${localeMatrix.btnRemove}</button>
                
                <div style="margin-bottom: 20px; width: 80%;">
                    <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">${localeMatrix.lblSelectCategory} <span class="text-danger">*</span></label>
                    <select name="categories[${catIdx}][id]" class="category-select" style="width: 100%; background: var(--surface-input); color: var(--text-main); border: 1px solid var(--border); padding: 12px; border-radius: 8px;" required>
                        ${categoryOptionsHtml}
                    </select>
                </div>

                <div class="subcategories-container" style="margin-left: 20px; border-left: 2px solid var(--border); padding-left: 20px;"></div>

                <button type="button" class="add-sub-btn" style="margin-top: 10px; background: #38bdf8; color: #0f172a; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: bold; display:none;">
                    + ${localeMatrix.btnAddSubValue}
                </button>
            </div>`;
            
            $('#categories-master-container').append(categoryHtml);
            catIdx++;
        });

        // ক্যাটাগরি চেঞ্জ ইভেন্ট
        $(document).on('change', '.category-select', function() {
            let $select = $(this);
            let categoryId = $select.val();
            let $block = $select.closest('.category-block');
            let $subContainer = $block.find('.subcategories-container');
            let $addBtn = $block.find('.add-sub-btn');
            let currentCatIdx = $block.data('cat-id');

            $subContainer.empty();

            if (categoryId) {
                $.get("{{ route('admin.get-subcategories') }}", { category_id: categoryId }, function(data) {
                    $block.data('sub-data', data);
                    if (data.length > 0) {
                        $addBtn.show();
                        addSubRow(currentCatIdx, $subContainer, data);
                    } else {
                        $addBtn.hide();
                        alert(localeMatrix.alertNoSubs);
                    }
                });
            } else {
                $addBtn.hide();
            }
        });

        // সাবক্যাটাগরি বাটন ক্লিক হ্যান্ডলার
        $(document).on('click', '.add-sub-btn', function() {
            let $block = $(this).closest('.category-block');
            let currentCatIdx = $block.data('cat-id');
            let data = $block.data('sub-data');
            let $subContainer = $block.find('.subcategories-container');
            
            if(data) {
                addSubRow(currentCatIdx, $subContainer, data);
            } else {
                let categoryId = $block.find('.category-select').val();
                $.get("{{ route('admin.get-subcategories') }}", { category_id: categoryId }, function(fetchedData) {
                    $block.data('sub-data', fetchedData);
                    addSubRow(currentCatIdx, $subContainer, fetchedData);
                });
            }
        });

        // ডাইনামিক সাবক্যাটাগরি রো জেনারেশন
        function addSubRow(cIdx, container, subData) {
            let subIdx = container.find('.subcategory-row').length;
            let options = subData.map(sub => `<option value="${sub.id}">${sub.title} (${sub.unit})</option>`).join('');
            
            let html = `
            <div class="subcategory-row" style="display: grid; grid-template-columns: 1fr 1fr 40px; gap: 15px; margin-bottom: 10px; align-items: center;">
                <div>
                    <select name="categories[${cIdx}][reports][${subIdx}][subcategory_id]" style="width: 100%; background: var(--surface-input); color: var(--text-main); border: 1px solid var(--border); padding: 10px; border-radius: 6px;" required>
                        ${options}
                    </select>
                </div>
                <div>
                    <input type="number" step="0.01" name="categories[${cIdx}][reports][${subIdx}][value]" style="width: 100%; background: var(--surface-input); color: var(--text-main); border: 1px solid var(--border); padding: 10px; border-radius: 6px;" placeholder="${localeMatrix.phValue}" required>
                </div>
                <button type="button" class="remove-sub-btn" style="background: transparent; color: #ef4444; border: none; cursor: pointer; font-size: 18px;" title="${localeMatrix.btnRemove}">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>`;
            
            container.append(html);
        }

        $(document).on('click', '.remove-category-btn', function() {
            $(this).closest('.category-block').remove();
        });

        $(document).on('click', '.remove-sub-btn', function() {
            $(this).closest('.subcategory-row').remove();
        });
    });
</script>
@endsection