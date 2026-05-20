@extends('layouts.admin')
@section('title', 'Launch New Campaign - Massimotrava')

@section('content')
<div class="container-fluid px-4 py-4 d-flex justify-content-center" style="background-color: #0f172a; min-h: 100vh; color: #f1f5f9;">
    <div class="w-100 card border-0 p-4 shadow-lg mb-5" style="max-width: 800px; background: #1e293b; border-radius: 12px; border: 1px solid #334155 !important; font-family: 'DM Sans', sans-serif;">
        
        <div class="mb-4 pb-3" style="border-bottom: 1px solid #334155;">
            <h1 class="h4 font-weight-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">Launch New Campaign Banner</h1>
            <p class="text-sm mb-0" style="color: #94a3b8;">Publish active marketing sliders, notification anchors, or platform offers.</p>
        </div>

        @if(session('error'))
            <div class="alert text-rose-400 border-0 mb-4 text-sm" style="background: rgba(244, 63, 94, 0.1); border: 1px solid rgba(244, 63, 94, 0.2) !important;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.campaigns.store') }}" method="POST" class="row g-4">
            @csrf

            <div class="col-12">
                <label for="title" class="form-label text-slate-300 font-weight-bold mb-2">Campaign Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="e.g., Ramadan Special 20% Off on Biomarker Kits" class="form-control py-2.5">
                @error('title') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
            </div>

            <div class="col-12">
                <label for="action_url" class="form-label text-slate-300 font-weight-bold mb-2">Redirect Action URL Link</label>
                <input type="url" name="action_url" id="action_url" value="{{ old('action_url') }}" placeholder="https://biovue.com/kits/promo" class="form-control py-2.5">
                @error('action_url') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
            </div>

            <div class="col-12">
                <label for="description" class="form-label text-slate-300 font-weight-bold mb-2">Short Campaign Brief</label>
                <textarea name="description" id="description" rows="3" placeholder="Summary notes regarding targeted user baselines..." class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="col-md-6">
                <label for="start_date" class="form-label text-slate-300 font-weight-bold mb-2">Start Time Schedule</label>
                <input type="datetime-local" name="start_date" id="start_date" class="form-control py-2.5">
            </div>

            <div class="col-md-6">
                <label for="end_date" class="form-label text-slate-300 font-weight-bold mb-2">Expiration Timeline</label>
                <input type="datetime-local" name="end_date" id="end_date" class="form-control py-2.5">
            </div>

            <div class="col-12">
                <label for="status" class="form-label text-slate-300 font-weight-bold mb-2">Initial Pipeline Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select py-2.5">
                    <option value="active">🚀 Active Immediately</option>
                    <option value="draft">📁 Safe Draft Mode</option>
                    <option value="paused">⏸️ Keep Paused</option>
                </select>
            </div>

            <div class="col-12 pt-3 mt-4 d-flex justify-content-end gap-2" style="border-top: 1px solid #334155;">
                <a href="{{ route('admin.campaigns.index') }}" class="btn px-4 py-2" style="background: #0f172a; border: 1px solid #334155; color: #cbd5e1;">Cancel</a>
                <button type="submit" class="btn px-4 py-2 text-white font-weight-bold" style="background: #6366f1; border: none; border-radius: 6px;">Deploy Campaign Banner</button>
            </div>
        </form>
    </div>
</div>
@endsection