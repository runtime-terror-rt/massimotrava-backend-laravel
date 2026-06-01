@extends('layouts.admin')
@section('page_title_key', 'sb_sub_category')
@section('content')
<div style="padding: 20px;">
    @if(session('success'))
        <div id="alert-msg" style="background: rgba(6, 95, 70, 0.15); color: #34d399; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #059669;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2.5fr; gap: 25px;">
        
        {{-- Left Column: Form Section --}}
        <div style="background: var(--surface); padding: 25px; border-radius: 12px; border: 1px solid var(--border); height: fit-content; position: sticky; top: 20px; transition: background-color 0.3s, border-color 0.3s;">
            <h3 id="form-title" style="color: var(--text); margin-top: 0; margin-bottom: 20px; font-size: 18px; font-weight: 600;">
                {{ __('messages.add_new_subcategory') }}
            </h3>
            
            <form action="{{ route('admin.biomarker-subcategory.store') }}" method="POST" id="subcategory-form">
                @csrf
                <input type="hidden" name="id" id="subcategory_id">

                <div style="margin-bottom: 15px;">
                    <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.select_main_category') }}
                    </label>
                    <select name="biomarker_category_id" id="biomarker_category_id" 
                            style="width: 100%; background: var(--surface-2); color: var(--text); border: 1px solid var(--border); padding: 11px; border-radius: 8px; outline: none; transition: all 0.2s;" required
                            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                        <option value="" style="background: var(--surface); color: var(--text);">-- {{ __('messages.select_category_placeholder') }} --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" style="background: var(--surface); color: var(--text);">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.subcategory_title') }}
                    </label>
                    <input type="text" name="title" id="title" 
                           style="width: 100%; background: var(--surface-2); color: var(--text); border: 1px solid var(--border); padding: 11px; border-radius: 8px; outline: none; transition: all 0.2s;" 
                           placeholder="e.g. Vitamin D" required
                           onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 15px;">
                    <div>
                        <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">
                            {{ __('messages.min_range') }}
                        </label>
                        <input type="number" step="0.01" name="min_range" id="min_range" 
                               style="width: 100%; background: var(--surface-2); color: var(--text); border: 1px solid var(--border); padding: 11px; border-radius: 8px; outline: none; transition: all 0.2s;" required
                               onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                    <div>
                        <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">
                            {{ __('messages.max_range') }}
                        </label>
                        <input type="number" step="0.01" name="max_range" id="max_range" 
                               style="width: 100%; background: var(--surface-2); color: var(--text); border: 1px solid var(--border); padding: 11px; border-radius: 8px; outline: none; transition: all 0.2s;" required
                               onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.unit') }}
                    </label>
                    <input type="text" name="unit" id="unit" 
                           style="width: 100%; background: var(--surface-2); color: var(--text); border: 1px solid var(--border); padding: 11px; border-radius: 8px; outline: none; transition: all 0.2s;" 
                           placeholder="e.g. ng/mL" required
                           onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.description') }}
                    </label>
                    <textarea name="description" id="description" rows="3" 
                              style="width: 100%; background: var(--surface-2); color: var(--text); border: 1px solid var(--border); padding: 11px; border-radius: 8px; outline: none; transition: all 0.2s; resize: vertical;"
                              onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="flex: 1; background: var(--accent); color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: bold; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        <i class="fa-solid fa-save"></i> {{ __('messages.save_data') }}
                    </button>
                    <button type="button" onclick="resetSubForm()" style="background: var(--surface-2); color: var(--text); border: 1px solid var(--border); padding: 12px; border-radius: 8px; cursor: pointer; font-weight: 500; transition: background-color 0.2s;">
                        {{ __('messages.reset') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Right Column: Table Section --}}
        <div style="background: var(--surface); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; height: fit-content; transition: background-color 0.3s, border-color 0.3s;">
            <table class="admin-table" style="width: 100%; border-collapse: collapse; color: var(--text); font-size: 14px;">
                <thead>
                    <tr style="background: var(--surface-2); text-align: left; border-bottom: 1px solid var(--border);">
                        <th style="padding: 15px; color: var(--text);">{{ __('messages.table_subcategory') }}</th>
                        <th style="padding: 15px; color: var(--text);">{{ __('messages.table_category') }}</th>
                        <th style="padding: 15px; color: var(--text);">{{ __('messages.table_range_unit') }}</th>
                        <th style="padding: 15px; text-align: right; color: var(--text);">{{ __('messages.table_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subcategories as $sub)
                    <tr class="table-row-hover" style="border-bottom: 1px solid var(--border); transition: background-color 0.2s;">
                        <td style="padding: 15px;">
                            <div style="font-weight: bold; color: var(--text);">{{ $sub->title }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ Str::limit($sub->description, 40) }}</div>
                        </td>
                        <td style="padding: 15px; vertical-align: middle;">
                            <span style="background: var(--surface-2); color: var(--text); padding: 5px 12px; border-radius: 20px; font-size: 12px; border: 1px solid var(--border); font-weight: 500; display: inline-block;">
                                {{ $sub->category->title ?? __('messages.not_available') }}
                            </span>
                        </td>
                        <td style="padding: 15px; color: var(--accent); font-weight: 600; vertical-align: middle;">
                            {{ $sub->min_range }} - {{ $sub->max_range }} <small style="color: var(--text-muted); font-weight: normal; margin-left: 2px;">{{ $sub->unit }}</small>
                        </td>
                        <td style="padding: 15px; text-align: right; vertical-align: middle;">
                            <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 12px;">
                                <button onclick='editSubcategory(@json($sub))' style="background: none; border: none; color: #fbbf24; cursor: pointer; padding: 0; display: inline-flex; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'" title="{{ __('messages.edit') }}">
                                    <i class="fa-solid fa-pen-to-square" style="font-size: 16px;"></i>
                                </button>
                                <form action="{{ route('admin.biomarker-subcategory.delete', $sub->id) }}" method="POST" style="margin: 0; display: inline-flex;" onsubmit="return confirm('{{ __('messages.delete_subcategory_confirm') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0; display: inline-flex; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'" title="{{ __('messages.delete') }}">
                                        <i class="fa-solid fa-trash" style="font-size: 16px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 40px; text-align: center; color: var(--text-muted);">
                            <i class="fa-regular fa-folder-open" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.6;"></i>
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
    const langEditTitle = "{{ __('messages.edit_subcategory_prefix') }}";
    const langAddTitle = "{{ __('messages.add_new_subcategory') }}";

    function editSubcategory(sub) {
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

<style>
    .table-row-hover:hover {
        background: var(--surface-2) !important;
    }
</style>
@endsection