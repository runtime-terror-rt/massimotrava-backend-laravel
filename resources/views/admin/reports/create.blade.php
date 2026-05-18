@extends('layouts.admin')
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
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.reports.index') }}" 
       style="display: inline-flex; align-items: center; gap: 8px; color: #94a3b8; text-decoration: none; font-size: 14px; background: #0f172a; padding: 10px 18px; border-radius: 8px; border: 1px solid #334155; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);"
       onmouseover="this.style.color='#f8fafc'; this.style.borderColor='#6366f1'; this.style.background='#1e293b';" 
       onmouseout="this.style.color='#94a3b8'; this.style.borderColor='#334155'; this.style.background='#0f172a';">
        <i class="fa-solid fa-arrow-left-long"></i> 
        <span style="font-weight: 500;">Back to Report Lists</span>
    </a>
</div>
<div class="report-container" style="max-width: 1100px; margin: 30px auto; padding: 30px; background: #1e293b; border-radius: 12px; color: white; font-family: sans-serif;">
    <h2 style="margin-bottom: 25px; font-size: 24px;">Add Biomarker Report</h2>

    <form action="{{ route('admin.reports.store') }}" method="POST">
        @csrf

        <!-- User & Kit Selection -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 20px;">
            <div>
                <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">Select User</label>
                <select name="user_id" id="user_id_select" style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 12px; border-radius: 8px;" required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">Select Kit</label>
                <select name="kit_id" id="kit_id_select" style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 12px; border-radius: 8px;" required>
                    <option value="">-- First Select a User --</option>
                </select>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #334155; margin: 30px 0;">

        <div id="categories-master-container">
        </div>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <button type="button" id="add-category-btn" style="background: #6366f1; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: bold;">
                <i class="fa fa-plus"></i> Add New Category Block
            </button>
        </div>

        <button type="submit" style="width: 100%; background: #22c55e; color: white; border: none; padding: 15px; border-radius: 10px; font-weight: bold; font-size: 16px; cursor: pointer; margin-top: 30px;">
            Save All Categories & Reports
        </button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let catIdx = 0;

        $('#user_id_select').on('change', function() {
            let userId = $(this).val();
            let $kitSelect = $('#kit_id_select');
            $kitSelect.html('<option value="">Searching kits...</option>');
            if(userId) {
                $.get("{{ route('admin.get-user-kits') }}", { user_id: userId }, function(data) {
                    $kitSelect.empty().append('<option value="">-- Select Kit --</option>');
                    data.forEach(kit => {
                        $kitSelect.append(`<option value="${kit.id}">${kit.activation_code} [${kit.inv_code}]</option>`);
                    });
                });
            }
        });

        $('#add-category-btn').on('click', function() {
            let categoryHtml = `
            <div class="category-block" data-cat-id="${catIdx}" style="background: #1e293b; border: 1px solid #475569; padding: 20px; border-radius: 12px; margin-bottom: 25px; position: relative;">
                <button type="button" class="remove-category-btn" style="position: absolute; right: 15px; top: 50px; background: #ef4444; color: white; border: none; border-radius: 5px; cursor: pointer; padding: 5px 10px;">Remove</button>
                
                <div style="margin-bottom: 20px; width: 80%;">
                    <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">Select Biomarker Category</label>
                    <select name="categories[${catIdx}][id]" class="category-select" style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 12px; border-radius: 8px;" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="subcategories-container" style="margin-left: 20px; border-left: 2px solid #334155; padding-left: 20px;">
                
                </div>

                <button type="button" class="add-sub-btn" style="margin-top: 10px; background: #38bdf8; color: #0f172a; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: bold; display:none;">
                    + Add Subcategory Value
                </button>
            </div>`;
            
            $('#categories-master-container').append(categoryHtml);
            catIdx++;
        });

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
                        alert('No subcategories found.');
                    }
                });
            } else {
                $addBtn.hide();
            }
        });

        $(document).on('click', '.add-sub-btn', function() {
            let $block = $(this).closest('.category-block');
            let currentCatIdx = $block.data('cat-id');
            let data = $block.data('sub-data');
            let $subContainer = $block.find('.subcategories-container');
            addSubRow(currentCatIdx, $subContainer, data);
        });

        function addSubRow(cIdx, container, subData) {
            let subIdx = container.find('.subcategory-row').length;
            let options = subData.map(sub => `<option value="${sub.id}">${sub.title} (${sub.unit})</option>`).join('');
            
            let html = `
            <div class="subcategory-row" style="display: grid; grid-template-columns: 1fr 1fr 40px; gap: 15px; margin-bottom: 10px; align-items: center;">
                <div>
                    <select name="categories[${cIdx}][reports][${subIdx}][subcategory_id]" style="width: 100%; background: #1e293b; color: white; border: 1px solid #334155; padding: 10px; border-radius: 6px;" required>
                        ${options}
                    </select>
                </div>
                <div>
                    <input type="number" step="0.01" name="categories[${cIdx}][reports][${subIdx}][value]" style="width: 100%; background: #1e293b; color: white; border: 1px solid #334155; padding: 10px; border-radius: 6px;" placeholder="Value" required>
                </div>
                <button type="button" class="remove-sub-btn" style="background: transparent; color: #ef4444; border: none; cursor: pointer; font-size: 18px;">
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