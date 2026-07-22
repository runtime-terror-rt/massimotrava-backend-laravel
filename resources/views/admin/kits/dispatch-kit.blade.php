{{--
    resources/views/admin/kits/dispatch-kit.blade.php

    এটা একটা standalone section — চাইলে এটাকে সরাসরি
    resources/views/admin/kits/index.blade.php এর ভেতরে @include করে দিন:

        @include('admin.kits.dispatch-kit', ['activeSubscriptions' => $activeSubscriptions])

    $activeSubscriptions ভ্যারিয়েবলটা controller থেকে পাঠাতে হবে, যেমন:

        $activeSubscriptions = \App\Models\UserSubscription::with(['user', 'plan'])
            ->where('status', 'active')
            ->latest()
            ->get();

    Route (routes/web.php বা admin group এ):

        Route::post('/admin/kits/dispatch', [KitController::class, 'dispatchKit'])
            ->name('admin.kits.dispatch');
--}}

<div class="dispatch-kit-section">

    {{-- ============ Success / Error Alerts ============ --}}
    @if(session('success'))
        <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:14px 18px;border-radius:8px;margin-bottom:20px;font-weight:500;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" style="background:#fee2e2;color:#991b1b;padding:14px 18px;border-radius:8px;margin-bottom:20px;font-weight:500;">
            {{ session('error') }}
        </div>
    @endif

    {{-- ============ Header ============ --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div>
            <h3 style="margin:0;font-size:20px;font-weight:700;">Dispatch New Kit</h3>
            <p style="margin:4px 0 0;color:#6b7280;font-size:14px;">Select an active subscription to dispatch a test kit.</p>
        </div>
    </div>

    {{-- ============ Active Subscriptions Table ============ --}}
    <div class="table-responsive" style="overflow-x:auto;border:1px solid #e5e7eb;border-radius:10px;">
        <table style="width:100%;border-collapse:collapse;font-size:14px;">
            <thead>
                <tr style="background:#f9fafb;text-align:left;">
                    <th style="padding:12px 16px;">Subscriber</th>
                    <th style="padding:12px 16px;">Plan</th>
                    <th style="padding:12px 16px;">Kit Usage</th>
                    <th style="padding:12px 16px;">Status</th>
                    <th style="padding:12px 16px;text-align:right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeSubscriptions ?? [] as $subscription)
                    @php
                        $limit = $subscription->plan->kit_limit ?? null;
                        $used  = $subscription->kits_count ?? ($subscription->kits()->where('status', '!=', 'cancelled')->count() ?? 0);
                        $limitReached = !is_null($limit) && $used >= $limit;
                    @endphp
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="padding:12px 16px;">
                            <div style="font-weight:600;">{{ $subscription->user->name ?? 'N/A' }}</div>
                            <div style="color:#6b7280;font-size:12px;">{{ $subscription->user->email ?? '' }}</div>
                        </td>
                        <td style="padding:12px 16px;">{{ $subscription->plan->name ?? 'N/A' }}</td>
                        <td style="padding:12px 16px;">
                            {{ $used }} / {{ $limit ?? '∞' }}
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="background:#d1fae5;color:#065f46;padding:2px 10px;border-radius:999px;font-size:12px;font-weight:600;">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                        <td style="padding:12px 16px;text-align:right;">
                            @if($limitReached)
                                <button type="button" class="btn-disabled" disabled
                                    style="background:#f3f4f6;color:#9ca3af;padding:8px 16px;border-radius:8px;border:none;cursor:not-allowed;font-size:13px;">
                                    Limit Reached
                                </button>
                            @else
                                <button type="button"
                                    class="btn-dispatch"
                                    onclick="openDispatchModal({{ $subscription->id }}, '{{ $subscription->user->name ?? 'N/A' }}', '{{ $subscription->plan->name ?? 'N/A' }}')"
                                    style="background:#4f46e5;color:#fff;padding:8px 16px;border-radius:8px;border:none;cursor:pointer;font-size:13px;font-weight:600;">
                                    + Dispatch Kit
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:24px 16px;text-align:center;color:#9ca3af;">
                            No active subscriptions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ============ Dispatch Kit Modal ============ --}}
    <div id="dispatchKitModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:12px;width:100%;max-width:460px;padding:28px;box-shadow:0 20px 40px rgba(0,0,0,0.2);">

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                <h4 style="margin:0;font-size:18px;font-weight:700;">Dispatch Kit</h4>
                <button type="button" onclick="closeDispatchModal()" style="background:none;border:none;font-size:20px;cursor:pointer;color:#6b7280;">&times;</button>
            </div>

            <p style="margin:0 0 18px;font-size:14px;color:#6b7280;">
                Subscriber: <strong id="modalSubscriberName">-</strong><br>
                Plan: <strong id="modalPlanName">-</strong>
            </p>

            <form action="{{ route('admin.kits.dispatch') }}" method="POST">
                @csrf
                <input type="hidden" name="user_subscription_id" id="modalSubscriptionId" value="">

                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Courier Name (optional)</label>
                    <input type="text" name="courier_name" placeholder="e.g. Pathao, RedX"
                        style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">
                    @error('courier_name')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Admin Notes (optional)</label>
                    <textarea name="admin_notes" rows="3" placeholder="Any internal note about this dispatch..."
                        style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;resize:vertical;"></textarea>
                    @error('admin_notes')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="closeDispatchModal()"
                        style="background:#f3f4f6;color:#374151;padding:10px 18px;border-radius:8px;border:none;cursor:pointer;font-size:14px;font-weight:600;">
                        Cancel
                    </button>
                    <button type="submit"
                        style="background:#4f46e5;color:#fff;padding:10px 18px;border-radius:8px;border:none;cursor:pointer;font-size:14px;font-weight:600;">
                        Confirm Dispatch
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function openDispatchModal(subscriptionId, subscriberName, planName) {
        document.getElementById('modalSubscriptionId').value = subscriptionId;
        document.getElementById('modalSubscriberName').innerText = subscriberName;
        document.getElementById('modalPlanName').innerText = planName;
        document.getElementById('dispatchKitModal').style.display = 'flex';
    }

    function closeDispatchModal() {
        document.getElementById('dispatchKitModal').style.display = 'none';
    }

    document.getElementById('dispatchKitModal')?.addEventListener('click', function (e) {
        if (e.target === this) closeDispatchModal();
    });
</script>