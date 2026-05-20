@extends('layouts.admin')

@section('title', 'Unified Content Pipeline - Massimotrava')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0f172a; min-h: 100vh; color: #f1f5f9;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">Unified Content Pipeline</h1>
            <p class="text-sm" style="color: #94a3b8; font-family: 'DM Sans', sans-serif;">Manage all your Posts and Videos seamlessly inside a single repository streams.</p>
        </div>
        <a href="{{ route('admin.contents.create') }}" class="btn px-4 py-2 text-white font-weight-bold" style="background: #6366f1; border-radius: 8px; font-family: 'DM Sans', sans-serif; border: none;">
            <i class="fa-solid fa-plus me-2"></i> Create New Content
        </a>
    </div>

    @if(session('success'))
        <div class="alert text-emerald-400 border-0 mb-4" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; border-radius: 8px;">
            <i class="fa-regular fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="d-flex gap-2 mb-4 pb-2" style="border-bottom: 1px solid #334155; font-family: 'DM Sans', sans-serif;">
        <a href="{{ route('admin.contents.index') }}" class="btn text-decoration-none px-3 py-2 text-sm" style="{{ !request('type') ? 'background: #1e293b; color: #fff;' : 'color: #94a3b8;' }} border-radius: 6px;">All Stream</a>
        <a href="{{ route('admin.contents.index', ['type' => 'post']) }}" class="btn text-decoration-none px-3 py-2 text-sm" style="{{ request('type') == 'post' ? 'background: #1e293b; color: #fff;' : 'color: #94a3b8;' }} border-radius: 6px;">
            <i class="fa-regular fa-file-lines me-2"></i> Articles / Posts
        </a>
        <a href="{{ route('admin.contents.index', ['type' => 'video']) }}" class="btn text-decoration-none px-3 py-2 text-sm" style="{{ request('type') == 'video' ? 'background: #1e293b; color: #fff;' : 'color: #94a3b8;' }} border-radius: 6px;">
            <i class="fa-regular fa-file-video me-2"></i> Video Library
        </a>
    </div>

    <div class="card border-0 overflow-hidden shadow-lg" style="background: #1e293b; border-radius: 12px; border: 1px solid #334155 !important; font-family: 'DM Sans', sans-serif;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="color: #cbd5e1;">
                <thead style="background: rgba(15, 23, 42, 0.4);">
                    <tr style="color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #334155;">
                        <th class="px-4 py-3 border-0">Content Title</th>
                        <th class="px-4 py-3 border-0">Resource Type</th>
                        <th class="px-4 py-3 border-0">Author</th>
                        <th class="px-4 py-3 border-0">Status State</th>
                        <th class="px-4 py-3 border-0">Date Log</th>
                    </tr>
                </thead>
                <tbody style="border-top: none;">
                    @forelse($feed as $item)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);">
                            <td class="px-4 py-3">
                                <span class="text-white d-block font-weight-medium mb-0" style="font-size: 15px;">{{ $item->title }}</span>
                                <small class="text-slate-500 font-mono" style="font-size: 11px; color: #64748b;">{{ $item->slug }}</small>
                            </td>
                            <td class="px-4 py-3">
                                @if($item->type === 'post')
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(56, 189, 248, 0.1); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 20px; font-weight: 500;">📄 Post</span>
                                @else
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.2); border-radius: 20px; font-weight: 500;">🎥 Video</span>
                                @endif
                            </td>
                            <td class="px-4 py-3" style="color: #94a3b8;">
                                {{ $item->user->name ?? 'Admin User' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="{{ $item->status === 'published' ? 'text-emerald-400 font-weight-medium' : 'text-muted' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-mono" style="color: #64748b; font-size: 12px; font-family: monospace;">
                                {{ $item->created_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-5 text-center text-muted" style="font-size: 14px;">
                                <i class="fa-solid fa-folder-open d-block mb-2 fs-3 text-slate-600"></i> No active content records streamed yet into the repository index view.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-end">
        {{ $feed->appends(request()->query())->links() }}
    </div>
</div>
@endsection