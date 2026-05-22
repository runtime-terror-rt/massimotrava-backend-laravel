@extends('layouts.admin')

@section('content')
<div style="padding: 20px;">
    @if(session('success'))
        <div style="background: #065f46; color: #34d399; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #059669;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        
        {{-- Left Column: Form Section --}}
        <div style="background: #1e293b; padding: 25px; border-radius: 12px; border: 1px solid #334155; height: fit-content;">
            <h3 id="form-title" style="color: white; margin-top: 0;">
                {{ __('messages.add_new_category') }}
            </h3>
            
            <form action="{{ route('admin.categories.store') }}" method="POST" id="category-form">
                @csrf
                <input type="hidden" name="id" id="category_id">

                <div style="margin-bottom: 15px;">
                    <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.category_title') }}
                    </label>
                    <input type="text" name="title" id="title" class="form-control" 
                           style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 12px; border-radius: 8px;" 
                           placeholder="e.g. Vitamins & Minerals" required>
                    @error('title') <small style="color: #ef4444;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="color: #94a3b8; display: block; margin-bottom: 8px; font-size: 14px;">
                        {{ __('messages.description_optional') }}
                    </label>
                    <textarea name="description" id="description" rows="4" 
                              style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 12px; border-radius: 8px;" 
                              placeholder="{{ __('messages.describe_category_placeholder') }}"></textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="flex: 1; background: #6366f1; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: bold;">
                        <i class="fa-solid fa-save"></i> {{ __('messages.save_category') }}
                    </button>
                    <button type="button" onclick="resetForm()" style="background: #334155; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer;">
                        {{ __('messages.reset') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Right Column: Table Section --}}
        <div style="background: #1e293b; border-radius: 12px; overflow: hidden; border: 1px solid #334155;">
            <table style="width: 100%; border-collapse: collapse; color: #e2e8f0;">
                <thead>
                    <tr style="background: #0f172a; text-align: left;">
                        <th style="padding: 15px;">{{ __('messages.table_title') }}</th>
                        <th style="padding: 15px;">{{ __('messages.table_description') }}</th>
                        <th style="padding: 15px; text-align: right;">{{ __('messages.table_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr style="border-bottom: 1px solid #334155;">
                        <td style="padding: 15px; font-weight: bold;">{{ $category->title }}</td>
                        <td style="padding: 15px; color: #94a3b8;">{{ Str::limit($category->description, 50) }}</td>
                        <td style="padding: 15px; text-align: right;">
                            <button onclick="editCategory({{ json_encode($category) }})" 
                                    style="background: none; border: none; color: #38bdf8; cursor: pointer; margin-right: 10px;" title="{{ __('messages.edit') }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_category_confirm') }}');" style="display: inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;" title="{{ __('messages.delete') }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="padding: 20px; text-align: center; color: #94a3b8;">
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
@endsection