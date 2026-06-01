@extends('layouts.admin')
@section('page_title_key', 'sb_category')
@section('content')
<div style="padding: 20px;">
    @if(session('success'))
        <div style="background: rgba(6, 95, 70, 0.15); color: #34d399; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #059669;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        
        {{-- Left Column: Form Section --}}
        <div style="background: var(--surface); padding: 25px; border-radius: 12px; border: 1px solid var(--border); height: fit-content; transition: background-color 0.3s, border-color 0.3s;">
            <h3 id="form-title" style="color: var(--text); margin-top: 0; font-weight: 600;">
                {{ __('messages.add_new_category') }}
            </h3>
            
            <form action="{{ route('admin.categories.store') }}" method="POST" id="category-form">
                @csrf
                <input type="hidden" name="id" id="category_id">

                <div style="margin-bottom: 15px;">
                    <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.category_title') }}
                    </label>
                    <input type="text" name="title" id="title" class="form-control" 
                           style="width: 100%; background: var(--surface-2); color: var(--text); border: 1px solid var(--border); padding: 12px; border-radius: 8px; outline: none; transition: all 0.2s;" 
                           placeholder="e.g. Vitamins & Minerals" required
                           onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                    @error('title') <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="color: var(--text-muted); display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.description_optional') }}
                    </label>
                    <textarea name="description" id="description" rows="4" 
                              style="width: 100%; background: var(--surface-2); color: var(--text); border: 1px solid var(--border); padding: 12px; border-radius: 8px; outline: none; transition: all 0.2s; resize: vertical;" 
                              placeholder="{{ __('messages.describe_category_placeholder') }}"
                              onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="flex: 1; background: var(--accent); color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: bold; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        <i class="fa-solid fa-save"></i> {{ __('messages.save_category') }}
                    </button>
                    <button type="button" onclick="resetForm()" style="background: var(--surface-2); color: var(--text); border: 1px solid var(--border); padding: 12px; border-radius: 8px; cursor: pointer; font-weight: 500; transition: background-color 0.2s;">
                        {{ __('messages.reset') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Right Column: Table Section --}}
        <div style="background: var(--surface); border-radius: 12px; overflow: hidden; border: 1px solid var(--border); height: fit-content; transition: background-color 0.3s, border-color 0.3s;">
            <table class="admin-table" style="width: 100%; border-collapse: collapse; color: var(--text);">
                <thead>
                    <tr style="background: var(--surface-2); text-align: left; border-bottom: 1px solid var(--border);">
                        <th style="padding: 15px; color: var(--text);">{{ __('messages.table_title') }}</th>
                        <th style="padding: 15px; color: var(--text);">{{ __('messages.table_description') }}</th>
                        <th style="padding: 15px; text-align: right; color: var(--text);">{{ __('messages.table_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr class="table-row-hover" style="border-bottom: 1px solid var(--border); transition: background-color 0.2s;">
                        <td style="padding: 15px; font-weight: bold; color: var(--text);">{{ $category->title }}</td>
                        <td style="padding: 15px; color: var(--text-muted);">{{ Str::limit($category->description, 50) }}</td>
                        <td style="padding: 15px; text-align: right; vertical-align: middle;">
                            <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 12px;">
                                <button onclick="editCategory({{ json_encode($category) }})" 
                                        style="background: none; border: none; color: #38bdf8; cursor: pointer; padding: 0; display: inline-flex; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'" title="{{ __('messages.edit') }}">
                                    <i class="fa-solid fa-pen-to-square" style="font-size: 16px;"></i>
                                </button>
                                <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_category_confirm') }}');" style="margin: 0; display: inline-flex;">
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
                        <td colspan="3" style="padding: 40px; text-align: center; color: var(--text-muted);">
                            <i class="fa-regular fa-folder-open" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.6;"></i>
                            {{ __('messages.no_categories_found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    // 🎯 Live Translation sync hooks for Javascript context
    const langEditTitle = "{{ __('messages.edit_category_prefix') }}";
    const langAddTitle = "{{ __('messages.add_new_category') }}";

    function editCategory(category) {
        document.getElementById('form-title').innerText = langEditTitle + ' ' + category.title;
        
        document.getElementById('category_id').value = category.id;
        document.getElementById('title').value = category.title;
        document.getElementById('description').value = category.description;
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        document.getElementById('form-title').innerText = langAddTitle;
        document.getElementById('category-form').reset();
        document.getElementById('category_id').value = '';
    }
</script>

<style>
    .table-row-hover:hover {
        background: var(--surface-2) !important;
    }
</style>
@endsection