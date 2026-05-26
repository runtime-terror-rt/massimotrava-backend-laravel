@extends('layouts.admin')
@section('page_title_key', 'sb_sub_category')
@section('content')
<div style="padding: 20px;">
    @if(session('success'))
        <div id="alert-msg" style="background: #065f46; color: #34d399; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #059669;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2.5fr; gap: 25px;">
        
        {{-- Left Column: Form Section --}}
        <div style="background: #1e293b; padding: 25px; border-radius: 12px; border: 1px solid #334155; height: fit-content; position: sticky; top: 20px;">
            <h3 id="form-title" style="color: white; margin-top: 0; margin-bottom: 20px; font-size: 18px;">
                {{ __('messages.add_new_subcategory') }}
            </h3>
            
            <form action="{{ route('admin.biomarker-subcategory.store') }}" method="POST" id="subcategory-form">
                @csrf
                <input type="hidden" name="id" id="subcategory_id">

                <div style="margin-bottom: 15px;">
                    <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.select_main_category') }}
                    </label>
                    <select name="biomarker_category_id" id="biomarker_category_id" style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 10px; border-radius: 8px;" required>
                        <option value="">-- {{ __('messages.select_category_placeholder') }} --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.subcategory_title') }}
                    </label>
                    <input type="text" name="title" id="title" style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 10px; border-radius: 8px;" placeholder="e.g. Vitamin D" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                    <div>
                        <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">
                            {{ __('messages.min_range') }}
                        </label>
                        <input type="number" step="0.01" name="min_range" id="min_range" style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 10px; border-radius: 8px;" required>
                    </div>
                    <div>
                        <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">
                            {{ __('messages.max_range') }}
                        </label>
                        <input type="number" step="0.01" name="max_range" id="max_range" style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 10px; border-radius: 8px;" required>
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.unit') }}
                    </label>
                    <input type="text" name="unit" id="unit" style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 10px; border-radius: 8px;" placeholder="e.g. ng/mL" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.description') }}
                    </label>
                    <textarea name="description" id="description" rows="3" style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 10px; border-radius: 8px;"></textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="flex: 1; background: #38bdf8; color: #0f172a; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: bold;">
                        <i class="fa-solid fa-save"></i> {{ __('messages.save_data') }}
                    </button>
                    <button type="button" onclick="resetSubForm()" style="background: #334155; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer;">
                        {{ __('messages.reset') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Right Column: Table Section --}}
        <div style="background: #1e293b; border-radius: 12px; border: 1px solid #334155; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; color: #e2e8f0; font-size: 14px;">
                <thead>
                    <tr style="background: #0f172a; text-align: left;">
                        <th style="padding: 15px;">{{ __('messages.table_subcategory') }}</th>
                        <th style="padding: 15px;">{{ __('messages.table_category') }}</th>
                        <th style="padding: 15px;">{{ __('messages.table_range_unit') }}</th>
                        <th style="padding: 15px; text-align: right;">{{ __('messages.table_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subcategories as $sub)
                    <tr style="border-bottom: 1px solid #334155;">
                        <td style="padding: 15px;">
                            <div style="font-weight: bold;">{{ $sub->title }}</div>
                            <div style="font-size: 12px; color: #94a3b8;">{{ Str::limit($sub->description, 40) }}</div>
                        </td>
                        <td style="padding: 15px;">
                            <span style="background: #334155; padding: 4px 10px; border-radius: 20px; font-size: 12px;">
                                {{ $sub->category->title ?? __('messages.not_available') }}
                            </span>
                        </td>
                        <td style="padding: 15px; color: #38bdf8;">
                            {{ $sub->min_range }} - {{ $sub->max_range }} <small style="color: #94a3b8;">{{ $sub->unit }}</small>
                        </td>
                        <td style="padding: 15px; text-align: right;">
                            <button onclick='editSubcategory(@json($sub))' style="background: none; border: none; color: #fbbf24; cursor: pointer; margin-right: 10px;" title="{{ __('messages.edit') }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('admin.biomarker-subcategory.delete', $sub->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('messages.delete_subcategory_confirm') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;" title="{{ __('messages.delete') }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 30px; text-align: center; color: #94a3b8;">
                            {{ __('messages.no_subcategories_found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // 🎯 JavaScript Localization variables passing from Laravel Config
    const langEditTitle = "{{ __('messages.edit_subcategory_prefix') }}";
    const langAddTitle = "{{ __('messages.add_new_subcategory') }}";

    function editSubcategory(sub) {
        // ডাইনামিক ভাষা অনুযায়ী ফর্মের শিরোনাম পরিবর্তন হবে
        document.getElementById('form-title').innerText = langEditTitle + ' ' + sub.title;
        
        document.getElementById('subcategory_id').value = sub.id;
        document.getElementById('biomarker_category_id').value = sub.biomarker_category_id;
        document.getElementById('title').value = sub.title;
        document.getElementById('min_range').value = sub.min_range;
        document.getElementById('max_range').value = sub.max_range;
        document.getElementById('unit').value = sub.unit;
        document.getElementById('description').value = sub.description;

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetSubForm() {
        document.getElementById('form-title').innerText = langAddTitle;
        document.getElementById('subcategory-form').reset();
        document.getElementById('subcategory_id').value = '';
    }

    setTimeout(function() {
        var msg = document.getElementById('alert-msg');
        if(msg) msg.style.display = 'none';
    }, 4000);
</script>
@endsection