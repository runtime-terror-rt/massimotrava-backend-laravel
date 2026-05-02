@extends('layouts.admin')

@section('content')
<div class="report-container" style="max-width: 1100px; margin: 30px auto; padding: 30px; background: #1e293b; border-radius: 12px; color: white; font-family: sans-serif;">
    <h2 style="margin-bottom: 25px; font-size: 24px;">Add Biomarker Report</h2>

    <form action="{{ route('admin.reports.store') }}" method="POST">
        @csrf

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

        <div style="margin-bottom: 30px;">
            <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">Biomarker Category</label>
            <select name="biomarker_category_id" id="category_id_select" style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 12px; border-radius: 8px;" required>
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
        </div>

        <h4 style="margin-bottom: 15px; font-size: 18px; border-top: 1px solid #334155; padding-top: 20px;">Enter Biomarker Values</h4>
        
        <div id="report-rows-container">
            <!-- Rows will be injected here dynamically -->
        </div>

        <div style="margin-top: 15px;">
            <button type="button" class="add-row-btn" id="main_add_btn" style="background: #6366f1; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; display: none;">
                <i class="fa fa-plus"></i> Add Another Subcategory
            </button>
        </div>

        <button type="submit" style="width: 100%; background: #22c55e; color: white; border: none; padding: 15px; border-radius: 10px; font-weight: bold; font-size: 16px; cursor: pointer; margin-top: 20px;">
            Save All Reports
        </button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let subcategoriesData = []; // এখানে সিলেক্টেড ক্যাটাগরির সাবক্যাটাগরি সেভ থাকবে
        let rowIdx = 0;

        // AJAX: ইউজারের কিট লোড করা
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

        // AJAX: ক্যাটাগরি অনুযায়ী সাবক্যাটাগরি লোড করা
        $('#category_id_select').on('change', function() {
            let categoryId = $(this).val();
            $('#report-rows-container').empty(); // নতুন ক্যাটাগরি নিলে আগের রো মুছে যাবে
            rowIdx = 0;

            if(categoryId) {
                $.get("{{ route('admin.get-subcategories') }}", { category_id: categoryId }, function(data) {
                    subcategoriesData = data;
                    if(data.length > 0) {
                        $('#main_add_btn').show();
                        addNewRow(); // প্রথম রো অটোমেটিক যোগ হবে
                    } else {
                        $('#main_add_btn').hide();
                        alert('No subcategories found for this category.');
                    }
                });
            } else {
                $('#main_add_btn').hide();
            }
        });

        // নতুন রো যোগ করার ফাংশন
        function addNewRow() {
            let options = subcategoriesData.map(sub => `<option value="${sub.id}">${sub.title} (${sub.unit})</option>`).join('');
            
            let html = `
            <div class="report-row" style="display: grid; grid-template-columns: 1fr 1fr 50px; gap: 15px; margin-bottom: 15px; align-items: center; background: #0f172a; padding: 15px; border-radius: 10px; border: 1px solid #334155;">
                <div>
                    <select name="reports[${rowIdx}][subcategory_id]" style="width: 100%; background: #1e293b; color: white; border: 1px solid #334155; padding: 10px; border-radius: 6px;" required>
                        ${options}
                    </select>
                </div>
                <div>
                    <input type="number" step="0.01" name="reports[${rowIdx}][value]" style="width: 100%; background: #1e293b; color: white; border: 1px solid #334155; padding: 10px; border-radius: 6px;" placeholder="Result Value" required>
                </div>
                <button type="button" class="remove-row-btn" style="background: #ef4444; color: white; border: none; width: 40px; height: 40px; border-radius: 8px; cursor: pointer;">
                    <i class="fa fa-trash"></i>
                </button>
            </div>`;
            
            $('#report-rows-container').append(html);
            rowIdx++;
        }

        $('#main_add_btn').on('click', addNewRow);

        $(document).on('click', '.remove-row-btn', function() {
            $(this).closest('.report-row').remove();
        });
    });
</script>
@endsection