@extends('layouts.admin')
@section('title', __('messages.newsletter'))
@section('page_title_key', 'newsletter')
@section('content')
<div class="container-fluid">

  <div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="page-title">{{ __('messages.newsletter') }}</h2>
      <p  class="page-subtitle" style="color: var(--text-muted); margin-0;">{{ __('messages.newsletter_desc') }}</p>
    </div>
    <a href="{{ route('admin.newsletter.export') }}" class="btn btn-primary">
      <i class="fa-solid fa-file-arrow-down"></i> {{ __('messages.export_csv') }}
    </a>
  </div>

  {{-- Stats --}}
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="stat-card p-3 border rounded">
        <div class="stat-label">{{ __('messages.total_subscribers') }}</div>
        <div class="fs-3 fw-bold">{{ $stats['total'] }}</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card p-3 border rounded">
        <div class="stat-label">{{ __('messages.active') }}</div>
        <div class="fs-3 fw-bold text-success">{{ $stats['active'] }}</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card p-3 border rounded">
        <div class="stat-label">{{ __('messages.unsubscribed') }}</div>
        <div class="fs-3 fw-bold text-danger">{{ $stats['inactive'] }}</div>
      </div>
    </div>
  </div>

  {{-- Filters --}}
  <form method="GET" action="{{ route('admin.newsletter.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('messages.search_placeholder') }}">
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">{{ __('messages.all_statuses') }}</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('messages.unsubscribed') }}</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100">{{ __('messages.filter') }}</button>
    </div>
  </form>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- Table --}}
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>{{ __('messages.email') }}</th>
          <th>{{ __('messages.locale') }}</th>
          <th>{{ __('messages.source') }}</th>
          <th>{{ __('messages.status') }}</th>
          <th>{{ __('messages.subscribed') }}</th>
          <th class="text-end">{{ __('messages.actions') }}</th>
        </tr>
      </thead>
      <tbody>
        @forelse($subscribers as $subscriber)
          <tr>
            <td>{{ $subscriber->email }}</td>
            <td><span class="badge bg-light text-dark text-uppercase">{{ $subscriber->locale ?? '—' }}</span></td>
            <td>{{ $subscriber->source ?? '—' }}</td>
            <td>
              @if($subscriber->is_active)
                <span class="badge bg-success">{{ __('messages.active') }}</span>
              @else
                <span class="badge bg-secondary">{{ __('messages.unsubscribed') }}</span>
              @endif
            </td>
            <td>{{ $subscriber->created_at->format('M d, Y') }}</td>
            <td class="text-end">
              <form action="{{ route('admin.newsletter.toggle', $subscriber) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                  {{ $subscriber->is_active ? __('messages.unsubscribed') : __('messages.reactivate') }}
                </button>
              </form>
              <form action="{{ route('admin.newsletter.destroy', $subscriber) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Remove this subscriber permanently?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('messages.delete') }}</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">{{ __('messages.no_subscribers') }}</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $subscribers->links() }}

</div>
@endsection