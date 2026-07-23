@extends('layouts.admin')

@section('title', __('messages.subscription'))

@section('content')
<div class="container-fluid py-2 py-md-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
        <h2 class="fw-bold text-white mb-0 fs-3 fs-md-2">{{ __('messages.my_subscription') }}</h2>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0 mb-3 mb-md-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show bg-danger text-white border-0 mb-3 mb-md-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 g-md-4">
        {{-- Current Active Subscription Card --}}
        <div class="col-12 col-lg-5">
            <div class="card border-0 rounded-4 h-100" style="background-color: #111827; border: 1px solid #1f2937 !important;">
                <div class="card-body p-3 p-md-4">
                    <h6 class="text-uppercase fw-semibold mb-3 small" style="color: #9ca3af; letter-spacing: 0.5px;">
                        {{ __('messages.current_active_plan') }}
                    </h6>

                    @if($activeSubscription)
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-3 flex-wrap">
                            <h2 class="fw-bold text-primary mb-0 fs-3 fs-md-2">{{ $activeSubscription->plan->name ?? __('messages.basic') }}</h2>
                            <span class="badge bg-success text-white fs-6 px-3 py-2 rounded-pill">
                                {{ ucfirst($activeSubscription->status) }}
                            </span>
                        </div>

                        <div class="mb-3 mb-md-4">
                            <h3 class="fw-bold text-white mb-0 fs-2">
                                ${{ number_format($activeSubscription->plan->price ?? 0, 2) }}
                                <span class="fs-6 fw-normal" style="color: #9ca3af;">/ {{ strtolower($activeSubscription->plan->billing_cycle ?? __('messages.monthly')) }}</span>
                            </h3>
                        </div>

                        <hr style="border-color: #374151;">

                        <div class="vstack gap-2 gap-md-3 my-3 fs-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="color: #9ca3af;" class="small">{{ __('messages.started_on') }}:</span>
                                <span class="fw-medium text-white small fs-md-6">{{ \Carbon\Carbon::parse($activeSubscription->starts_at)->format('d M, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="color: #9ca3af;" class="small">{{ __('messages.renews_expires_on') }}:</span>
                                <span class="fw-medium text-danger small fs-md-6">{{ \Carbon\Carbon::parse($activeSubscription->ends_at)->format('d M, Y') }}</span>
                            </div>
                        </div>

                    @else
                        <div class="text-center py-4 py-md-5">
                            <div class="mb-3">
                                <i class="fa-solid fa-box-open fa-2x fa-md-3x" style="color: #4b5563;"></i>
                            </div>
                            <h6 class="fw-bold text-white mb-2">{{ __('messages.no_active_subscription_found') }}</h6>
                            <p class="small mb-4 text-break" style="color: #9ca3af;">{{ __('messages.no_active_subscription_desc') }}</p>
                            <a href="{{ route('home.index') }}#pricing" class="btn btn-primary rounded-pill px-4 btn-sm fs-md-6">
                                {{ __('messages.choose_a_plan') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Payment History --}}
        <div class="col-12 col-lg-7">
            <div class="card border-0 rounded-4 h-100" style="background-color: #111827; border: 1px solid #1f2937 !important;">
                <div class="card-body p-3 p-md-4">
                    <h6 class="text-uppercase fw-semibold mb-3 small" style="color: #9ca3af; letter-spacing: 0.5px;">
                        {{ __('messages.billing_payment_history') }}
                    </h6>

                    <div class="table-responsive">
                        <table class="table align-middle text-nowrap mb-0" style="color: #e5e7eb;">
                            <thead>
                                <tr style="background-color: #1f2937; border-color: #374151;">
                                    <th class="py-2 py-md-3 small" style="color: #9ca3af;">{{ __('messages.date') }}</th>
                                    <th class="py-2 py-md-3 small" style="color: #9ca3af;">{{ __('messages.amount') }}</th>
                                    <th class="py-2 py-md-3 small" style="color: #9ca3af;">{{ __('messages.status') }}</th>
                                    <th class="py-2 py-md-3 small" style="color: #9ca3af;">{{ __('messages.payment_method') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr style="border-color: #1f2937;">
                                        <td class="small fs-md-6">{{ $payment->created_at->format('d M, Y') }}</td>
                                        <td class="fw-bold text-white small fs-md-6">${{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                                        <td>
                                            @if($payment->payment_status === 'succeeded')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded small">{{ __('messages.paid') }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded small">{{ ucfirst($payment->payment_status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-capitalize small fs-md-6">{{ $payment->payment_method ?? 'Card' }}</td>
                                    </tr>
                                @empty
                                    <tr style="border-color: #1f2937;">
                                        <td colspan="4" class="text-center py-4 small" style="color: #9ca3af;">{{ __('messages.no_payment_record_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 overflow-auto">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection