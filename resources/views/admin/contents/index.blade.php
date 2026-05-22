@extends('layouts.admin')
@section('title', __('messages.pipeline_meta_title'))

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0f172a; min-h: 100vh; color: #f1f5f9;">
    
    {{-- Top Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">
                {{ __('messages.pipeline_header') }}
            </h1>
            <p class="text-sm" style="color: #94a3b8; font-family: 'DM Sans', sans-serif;">
                {{ __('messages.pipeline_subheader') }}
            </p>
        </div>
        <a href="{{ route('admin.contents.create') }}" class="btn px-4 py-2 text-white font-weight-bold" style="background: #6366f1; border-radius: 8px; font-family: 'DM Sans', sans-serif; border: none;">
            <i class="fa-solid fa-plus me-2"></i> {{ __('messages.btn_create_content') }}
        </a>
    </div>

    {{-- Session Success Notification Alert --}}
    @if(session('success'))
        <div id="alert-msg" class="alert text-emerald-400 border-0 mb-4" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; border-radius: 8px;">
            <i class="fa-regular fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stream Filter Tab Navigation --}}
    <div class="d-flex gap-2 mb-4 pb-2" style="border-bottom: 1px solid #334155; font-family: 'DM Sans', sans-serif;">
        <a href="{{ route('admin.contents.index') }}" class="btn text-decoration-none px-3 py-2 text-sm" style="{{ !request('type') ? 'background: #1e293b; color: #fff;' : 'color: #94a3b8;' }} border-radius: 6px;">
            {{ __('messages.tab_all_stream') }}
        </a>
        <a href="{{ route('admin.contents.index', ['type' => 'post']) }}" class="btn text-decoration-none px-3 py-2 text-sm" style="{{ request('type') == 'post' ? 'background: #1e293b; color: #fff;' : 'color: #94a3b8;' }} border-radius: 6px;">
            <i class="fa-regular fa-file-lines me-2"></i> {{ __('messages.tab_articles_posts') }}
        </a>
        <a href="{{ route('admin.contents.index', ['type' => 'video']) }}" class="btn text-decoration-none px-3 py-2 text-sm" style="{{ request('type') == 'video' ? 'background: #1e293b; color: #fff;' : 'color: #94a3b8;' }} border-radius: 6px;">
            <i class="fa-regular fa-file-video me-2"></i> {{ __('messages.tab_video_library') }}
        </a>
    </div>

    {{-- Data Pipeline Grid Card Table --}}
    <div class="card border-0 overflow-hidden shadow-lg" style="background: #1e293b; border-radius: 12px; border: 1px solid #334155 !important; font-family: 'DM Sans', sans-serif;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="color: #cbd5e1;">
                <thead style="background: rgba(15, 23, 42, 0.4);">
                    <tr style="color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #334155;">
                        <th class="px-4 py-3 border-0">{{ __('messages.th_content_title') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_resource_type') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_author') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_status_state') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('messages.th_date_log') }}</th>
                    </tr>
                </thead>
                <tbody style="border-top: none;">
                    @forelse($feed as $item)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);">
                            <td class="px-4 py-3">
                                <span class="text-white d-block font-weight-medium mb-0" style="font-size: 15px;">{{ $item->title }}</span>
                                <small class="font-mono" style="font-size: 11px; color: #64748b;">{{ $item->slug }}</small>
                            </td>
                            <td class="px-4 py-3">
                                @if($item->type === 'post')
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(56, 189, 248, 0.1); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 20px; font-weight: 500;">
                                        📄 {{ __('messages.badge_post') }}
                                    </span>
                                @else
                                    <span class="badge px-2.5 py-1.5" style="background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.2); border-radius: 20px; font-weight: 500;">
                                        🎥 {{ __('messages.badge_video') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3" style="color: #94a3b8;">
                                {{ $item->user->name ?? __('messages.default_author') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="{{ $item->status === 'published' ? 'text-emerald-400 font-weight-medium' : 'text-muted' }}">
                                    @if($item->status === 'published')
                                        {{ __('messages.status_published') }}
                                    @elseif($item->status === 'draft')
                                        {{ __('messages.status_draft') }}
                                    @else
                                        {{ ucfirst($item->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3" style="color: #64748b; font-size: 12px; font-family: monospace;">
                                {{ $item->created_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-5 text-center text-muted" style="font-size: 14px;">
                                <i class="fa-solid fa-folder-open d-block mb-2 fs-3" style="color: #475569;"></i> 
                                {{ __('messages.no_pipeline_contents_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Render Appended Pagination Streams Links --}}
    <div class="mt-4 d-flex justify-content-end">
        {{ $feed->appends(request()->query())->links() }}
    </div>
</div>

<script>
    // Smooth auto fade-out handling for alerts logs
    setTimeout(function() {
        var msg = document.getElementById('alert-msg');
        if(msg) msg.style.display = 'none';
    }, 4000);
</script>
@endsection