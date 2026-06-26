@extends('layouts.admin')
@section('title', __('messages.sb_dashboard') ?? 'Health Dashboard')
@section('page_title_key', 'sb_dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/vyralabs-all.css') }}">
@endpush

@section('content')
<div class="hd-wrap">
    
    <div class="hd-hero">
        <div class="hd-hero-left">
            <h2>Welcome Back, Dashboard Premium</h2>
            <p>Your performance statistics are up to date.</p>
        </div>
    </div>

    <div class="hd-metrics-grid">
        </div>

    <div class="hd-accordion-item">
        <button class="hd-accordion-btn" onclick="let b = this.nextElementSibling; b.style.display = b.style.display === 'block' ? 'none' : 'block';">
            <span>When will my finalized panel show?</span>
            <i class="fa-solid fa-chevron-down"></i>
        </button>
        <div class="hd-accordion-body" style="display:none;">
            Laboratory analysis requires between 2 to 5 standard processing business days from reception of sample vectors.
        </div>
    </div>

</div>
@endsection