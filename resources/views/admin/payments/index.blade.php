@extends('layouts.admin')
@section('title', __('messages.payments_title', ['default' => 'Subscription Plans']))
@section('page_title_key', 'sb_payments')

@section('content')
<div class="content" style="color: var(--text); transition: color 0.3s;">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.25);color:#10b981;padding:12px 18px;border-radius:8px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:13px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#ef4444;padding:12px 18px;border-radius:8px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:13px;">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#ef4444;padding:12px 18px;border-radius:8px;margin-bottom:20px;font-size:13px;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <ul style="margin:6px 0 0 16px;padding:0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h1 class="page-title" style="color: var(--text); margin-bottom: 4px;">{{ __('messages.sb_payments') }}</h1>
            <p class="page-subtitle" style="color: var(--text-muted); margin-0;">Manage subscription configurations, durations, and system pricing tiers.</p>
        </div>

        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="billing-filter" style="background: var(--surface-2); padding: 4px; border-radius: 8px; border: 1px solid var(--border);">
                <a href="{{ request()->fullUrlWithQuery(['billing' => 'monthly']) }}"
                   class="btn {{ request()->query('billing', 'monthly') === 'monthly' ? 'btn-primary' : 'btn-ghost' }}" style="padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none;">
                     Monthly
                </a>
                <a href="{{ request()->fullUrlWithQuery(['billing' => 'annual']) }}"
                   class="btn {{ request()->query('billing', 'monthly') === 'annual' ? 'btn-primary' : 'btn-ghost' }}" style="padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none;">
                     Annual
                </a>
            </div>

            <button type="button" class="btn btn-primary" onclick="openPlanModal('create')" style="padding: 10px 16px; font-size: 13px; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; background: var(--accent); border: none; color: #fff;">
                <i class="fa-solid fa-plus"></i> Create New Plan
            </button>
        </div>
    </div>

    {{-- Plans Grid --}}
    <div class="stats-grid" style="margin-bottom: 35px; display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        @forelse($plans as $plan)
            <div class="stat-card" style="border-top: 3px solid var(--accent, #6366f1); padding: 20px; background: var(--surface, #161b27); border-radius: 8px; display: flex; flex-direction: column; justify-content: space-between; border: 1px solid var(--border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="background: rgba(99,102,241,0.1); color: var(--accent, #6366f1); width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 14px;">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <span style="background: var(--surface-2); color: var(--text); font-size: 11px; padding: 4px 8px; border-radius: 4px; text-transform: capitalize; border: 1px solid var(--border);">
                            {{ $plan->billing_cycle }}
                        </span>
                    </div>

                    <div style="font-size: 16px; font-weight: 600; color: var(--text); margin-top: 12px;">{{ $plan->name }}</div>
                    <div style="font-size: 26px; font-weight: 700; margin-top: 5px; color: var(--text);">${{ number_format($plan->price, 2) }}</div>

                    <div style="margin-top: 8px; font-size: 11px; color: var(--text-muted); display: flex; gap: 12px; margin-bottom: 15px;">
                        @if($plan->duration)
                            <span><i class="fa-solid fa-clock" style="margin-right: 4px;"></i> {{ $plan->duration }} Days</span>
                        @endif
                        @if($plan->stripe_price_id)
                            <span style="color:#10b981"><i class="fa-brands fa-stripe" style="margin-right: 4px;"></i> Connected</span>
                        @else
                            <span style="color:#ef4444"><i class="fa-solid fa-triangle-exclamation" style="margin-right: 4px;"></i> No Stripe ID</span>
                        @endif
                    </div>
                </div>

                <div style="display: flex; gap: 8px; border-top: 1px solid var(--border); padding-top: 12px; margin-top: 5px;">
                    <button type="button" class="btn btn-ghost" onclick="viewPlanDetails({{ json_encode($plan) }})"
                            style="flex: 1; padding: 6px; font-size: 11px; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; color: var(--text-muted); background: var(--surface-2); border: 1px solid var(--border); cursor: pointer;">
                        <i class="fa-solid fa-eye" style="font-size: 10px;"></i> Show
                    </button>
                    <button type="button" class="btn btn-ghost" onclick="openPlanModal('edit', {{ json_encode($plan) }})"
                            style="flex: 1; padding: 6px; font-size: 11px; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; color: var(--accent, #6366f1); background: rgba(99,102,241,0.05); border: 1px solid rgba(99,102,241,0.1); cursor: pointer;">
                        <i class="fa-solid fa-pen-to-square" style="font-size: 10px;"></i> Edit
                    </button>
                    <button type="button" class="btn btn-ghost" onclick="openDeleteModal('{{ $plan->id }}', '{{ $plan->name }}')"
                            style="flex: 1; padding: 6px; font-size: 11px; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; color: #ef4444; background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.1); cursor: pointer;">
                        <i class="fa-solid fa-trash" style="font-size: 10px;"></i> Delete
                    </button>
                </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted); background: var(--surface); border-radius: 8px; border: 1px solid var(--border);">
                <i class="fa-solid fa-box-open" style="font-size: 32px; margin-bottom: 10px; display: block; opacity: 0.6;"></i>
                No subscription plans found.
            </div>
        @endforelse
    </div>

    {{-- Active Subscriptions Table --}}
    <div class="card" style="background: var(--surface, #161b27); border-radius: 8px; overflow: hidden; border: 1px solid var(--border);">
        <div style="padding: 20px; border-bottom: 1px solid var(--border); background: rgba(0,0,0,0.05);">
            <h3 style="font-size: 16px; font-weight: 600; color: var(--text); margin: 0;">Active User Subscriptions</h3>
        </div>
        <div class="table-wrap">
            <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--surface-2); border-bottom: 1px solid var(--border);">
                        <th style="padding: 12px 20px; color: var(--text); font-size: 13px; font-weight: 600;">Subscriber Name</th>
                        <th style="padding: 12px 20px; color: var(--text); font-size: 13px; font-weight: 600;">Active Plan</th>
                        <th style="padding: 12px 20px; color: var(--text); font-size: 13px; font-weight: 600;">Billing Cycle</th>
                        <th style="padding: 12px 20px; color: var(--text); font-size: 13px; font-weight: 600;">Price</th>
                        <th style="padding: 12px 20px; color: var(--text); font-size: 13px; font-weight: 600;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($userSubscriptions as $sub)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 15px 20px;">
                                <div style="font-weight: 600; color: var(--text);">{{ $sub->user->name ?? 'Unknown' }}</div>
                                <div style="font-size: 12px; color: var(--text-muted);">{{ $sub->user->email ?? 'N/A' }}</div>
                            </td>
                            <td style="padding: 15px 20px; font-weight: 600; color: var(--accent, #6366f1);">{{ $sub->plan->name ?? 'Custom Plan' }}</td>
                            <td style="padding: 15px 20px;">
                                <span style="background: var(--surface-2); color: var(--text); text-transform: capitalize; border: 1px solid var(--border); padding: 4px 8px; border-radius: 4px; font-size: 11px;">{{ $sub->billing_cycle }}</span>
                            </td>
                            <td style="padding: 15px 20px; color: #10b981; font-weight: 600;">${{ number_format($sub->price, 2) }}</td>
                            <td style="padding: 15px 20px;">
                                @if($sub->status === 'active')
                                    <span style="background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); padding: 4px 10px; border-radius: 20px; font-size: 11px;">Active</span>
                                @else
                                    <span style="background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); padding: 4px 10px; border-radius: 20px; font-size: 11px;">Expired</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; padding: 30px; color: var(--text-muted);">No active subscribers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($userSubscriptions instanceof \Illuminate\Pagination\LengthAwarePaginator && $userSubscriptions->hasPages())
            <div style="padding: 15px 20px; border-top: 1px solid var(--border);">{!! $userSubscriptions->links() !!}</div>
        @endif
    </div>
</div>

{{-- View Details Modal --}}
<div id="viewPlanModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: var(--surface-2, #1c2333); width: 100%; max-width: 480px; border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">
        <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 16px; color: var(--text);"><i class="fa-solid fa-circle-info" style="color: var(--accent, #6366f1);"></i> Plan Configuration Details</h3>
            <button type="button" onclick="closeViewPlanModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 18px;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div style="padding: 25px;">
            <div style="text-align: center; margin-bottom: 25px; border-bottom: 1px solid var(--border); padding-bottom: 20px;">
                <h2 id="modalPlanName" style="margin: 0; font-size: 22px; color: var(--text);">-</h2>
                <div style="margin-top: 8px;">
                    <span id="modalPlanPrice" style="font-size: 28px; font-weight: 700; color: #10b981;">$0.00</span>
                    <span id="modalPlanBilling" style="font-size: 13px; color: var(--text-muted);">/ monthly</span>
                </div>
            </div>
            <div style="background: var(--surface); padding: 12px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 25px;">
                <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Duration / Validity</div>
                <div id="modalPlanDuration" style="font-size: 14px; color: var(--text); font-weight: 600; margin-top: 4px;">-</div>
            </div>
            <div>
                <h4 style="margin: 0 0 12px 0; font-size: 13px; color: var(--text-muted); text-transform: uppercase;">Included Features</h4>
                <ul id="modalPlanFeatures" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; max-height: 160px; overflow-y: auto;"></ul>
            </div>
        </div>
        <div style="padding: 15px 20px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
            <button type="button" onclick="closeViewPlanModal()" class="btn btn-secondary" style="padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px;">Close Window</button>
        </div>
    </div>
</div>

{{-- Create / Edit Modal --}}
<div id="planModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: var(--surface-2, #1c2333); width: 100%; max-width: 500px; border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">
        <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 id="planModalTitle" style="margin: 0; font-size: 16px; color: var(--text); font-weight: 600;">Create New Subscription Plan</h3>
            <button type="button" onclick="closePlanModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 18px;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="planForm" action="{{ route('admin.plans.store') }}" method="POST" style="padding: 20px;">
            @csrf
            <input type="hidden" id="inputMethod" name="_method" value="">
            <input type="hidden" id="inputPlanId" name="id" value="">

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; color: var(--text-muted); margin-bottom: 6px;">Plan Name *</label>
                <input type="text" id="inputName" name="name" required class="form-input" style="width: 100%; padding: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; color: var(--text-muted); margin-bottom: 6px;">Billing Cycle *</label>
                <select id="selectBilling" name="billing_cycle" style="width: 100%; padding: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
                    <option value="monthly">Monthly</option>
                    <option value="annual">Annual</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 13px; color: var(--text-muted); margin-bottom: 6px;">Price ($) *</label>
                    <input type="number" step="0.01" id="inputPrice" name="price" required class="form-input" style="width: 100%; padding: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; color: var(--text-muted); margin-bottom: 6px;">Duration (Days)</label>
                    <input type="number" id="inputDuration" name="duration" class="form-input" style="width: 100%; padding: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; color: var(--text-muted); margin-bottom: 6px;">Features (Comma Separated)</label>
                <input type="text" id="inputFeatures" placeholder="Feature 1, Feature 2, Feature 3" class="form-input" style="width: 100%; padding: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
                <div id="featuresHiddenContainer"></div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:5px;">Each feature separated by comma will be saved individually.</div>
            </div>

            <div style="padding-top: 15px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closePlanModal()" class="btn btn-secondary" style="padding: 10px 16px; border-radius: 6px; cursor: pointer;">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitBtn" style="padding: 10px 16px; border-radius: 6px; cursor: pointer; background: var(--accent); border: none; color: #fff;">Save Plan</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deletePlanModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: var(--surface-2, #1c2333); width: 100%; max-width: 400px; border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">
        <div style="padding: 20px; text-align: center;">
            <div style="background: rgba(239,68,68,0.1); color: #ef4444; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; margin: 0 auto 15px auto;">
                <i class="fa-solid fa-exclamation-triangle"></i>
            </div>
            <h3 style="margin: 0 0 8px 0; font-size: 16px; color: var(--text); font-weight: 600;">Delete Subscription Plan?</h3>
            <p style="margin: 0; font-size: 13px; color: var(--text-muted);">Are you sure you want to delete <strong id="deletePlanName" style="color: var(--text);"></strong>? This action cannot be undone.</p>
        </div>
        <form id="deleteForm" method="POST" style="margin: 0;">
            @csrf
            @method('DELETE')
            <div style="padding: 15px 20px; border-top: 1px solid var(--border); display: flex; justify-content: center; gap: 10px;">
                <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary" style="padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px;">Cancel</button>
                <button type="submit" class="btn" style="padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #fff; background: #ef4444; border: none;">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    const basePlanRoute = "{{ url('admin/plans') }}";
    const storeRoute    = "{{ route('admin.plans.store') }}";

    function openPlanModal(mode, plan = null) {
        const form = document.getElementById('planForm');
        const title = document.getElementById('planModalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const planIdInput = document.getElementById('inputPlanId');
        const methodInput = document.getElementById('inputMethod');

        if (mode === 'edit' && plan) {
            const id = plan.id;
            const name = plan.name ?? '';
            const billing_cycle = plan.billing_cycle ?? 'monthly';
            const price = plan.price ?? '';
            const duration = plan.duration ?? '';
            const features = plan.features ?? '';

            title.innerText = "Edit Subscription Plan";
            submitBtn.innerText = "Update Plan";

            planIdInput.value = id;
            methodInput.value = 'PUT';
            form.action = `${basePlanRoute}/${id}`;

            document.getElementById('inputName').value = name;
            document.getElementById('selectBilling').value = billing_cycle;
            document.getElementById('inputPrice').value = price;
            document.getElementById('inputDuration').value = duration;

            let featuresText = '';
            if (features) {
                if (Array.isArray(features)) {
                    featuresText = features.join(', ');
                } else if (typeof features === 'object') {
                    featuresText = Object.values(features).join(', ');
                } else if (typeof features === 'string') {
                    try {
                        const parsed = JSON.parse(features);
                        featuresText = Array.isArray(parsed) ? parsed.join(', ') : Object.values(parsed).join(', ');
                    } catch (e) {
                        featuresText = features;
                    }
                }
            }
            document.getElementById('inputFeatures').value = featuresText;

        } else {
            title.innerText = "Create New Subscription Plan";
            submitBtn.innerText = "Save Plan";
            planIdInput.value = "";
            methodInput.value = "";
            form.action = storeRoute;
            form.reset();
        }

        submitBtn.disabled = false;
        document.getElementById('planModal').style.display = 'flex';
    }

    function closePlanModal() {
        document.getElementById('planModal').style.display = 'none';
        const btn = document.getElementById('submitBtn');
        btn.disabled = false;
    }

    function prepareFeaturesAndSubmit(e) {
        e.preventDefault();

        const featuresInput = document.getElementById('inputFeatures').value;
        const container = document.getElementById('featuresHiddenContainer');
        container.innerHTML = '';

        if (featuresInput.trim()) {
            featuresInput.split(',').map(f => f.trim()).filter(f => f !== '').forEach(function(feature) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'features[]';
                input.value = feature;
                container.appendChild(input);
            });
        }

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="margin-right:6px"></i> Saving to Stripe...';

        e.target.submit();
    }

    document.getElementById('planForm').addEventListener('submit', prepareFeaturesAndSubmit);

    function viewPlanDetails(plan) {
        document.getElementById('modalPlanName').innerText = plan.name ?? '-';
        document.getElementById('modalPlanPrice').innerText = '$' + parseFloat(plan.price ?? 0).toFixed(2);
        document.getElementById('modalPlanBilling').innerText = '/ ' + (plan.billing_cycle ?? 'monthly');

        document.getElementById('modalPlanDuration').innerHTML = plan.duration
            ? `<i class="fa-solid fa-clock" style="margin-right: 6px; color: var(--accent);"></i> ${plan.duration} Days`
            : 'Unlimited';

        const featuresContainer = document.getElementById('modalPlanFeatures');
        featuresContainer.innerHTML = '';

        let featuresArray = [];
        const features = plan.features;
        if (features) {
            if (Array.isArray(features)) {
                featuresArray = features;
            } else if (typeof features === 'object') {
                featuresArray = Object.values(features);
            } else if (typeof features === 'string') {
                try {
                    const parsed = JSON.parse(features);
                    featuresArray = Array.isArray(parsed) ? parsed : Object.values(parsed);
                } catch (e) {
                    featuresArray = features.split(',').map(f => f.trim());
                }
            }
        }

        if (featuresArray.length > 0 && featuresArray[0] !== "") {
            featuresArray.forEach(feature => {
                const li = document.createElement('li');
                li.style.cssText = 'display:flex;align-items:center;gap:10px;font-size:13px;color:var(--text);';
                li.innerHTML = `<i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 13px;"></i> <span>${feature}</span>`;
                featuresContainer.appendChild(li);
            });
        } else {
            featuresContainer.innerHTML = `<li style="color: var(--text-muted); font-size: 13px; font-style: italic;">No specific features listed.</li>`;
        }

        document.getElementById('viewPlanModal').style.display = 'flex';
    }

    function closeViewPlanModal() {
        document.getElementById('viewPlanModal').style.display = 'none';
    }

    function openDeleteModal(id, name) {
        document.getElementById('deletePlanName').innerText = name;
        document.getElementById('deleteForm').action = `${basePlanRoute}/${id}`;
        document.getElementById('deletePlanModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deletePlanModal').style.display = 'none';
    }
</script>
@endsection