@extends('layouts.admin')

@section('title', 'Dashboard — Massimotrava Admin')
@section('page_title', 'Dashboard')

@section('content')

  {{-- ===== PAGE HEADER ===== --}}
  <div class="page-header">
    <div>
      <h1 class="page-title">Overview</h1>
      <p class="page-subtitle">{{ now()->format('l, d F Y') }} — Welcome back, {{ auth()->user()->name ?? 'Admin' }} 👋</p>
    </div>
    <div class="page-actions">
      <button class="btn btn-ghost"><i class="fa-solid fa-sliders"></i> Filter</button>
      <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Report</button>
    </div>
  </div>

  {{-- ===== STAT CARDS ===== --}}
  @include('admin.dashboard._stats', ['stats' => $stats])

  {{-- ===== MAIN GRID ===== --}}
  <div class="main-grid">

    {{-- Transactions Table --}}
    @include('admin.dashboard._transactions', ['transactions' => $transactions])

    {{-- Side Column --}}
    <div class="side-col">
      @include('admin.dashboard._activity', ['activities' => $activities])
      @include('admin.dashboard._top_products', ['topProducts' => $topProducts])
    </div>

  </div>

@endsection

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
