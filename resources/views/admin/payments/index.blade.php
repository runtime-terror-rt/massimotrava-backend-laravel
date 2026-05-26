@extends('layouts.admin')
@section('title', __('messages.payments_title', ['default' => 'Subscription Plans']))
@section('page_title_key', 'sb_payments')

@section('content')
<div class="content">
    {{-- Page Header --}}
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h1 class="page-title">{{ __('messages.sb_payments') }}</h1>
            <p class="page-subtitle">Manage subscription configurations, durations, and system pricing tiers.</p>
        </div>
        
        {{-- Actions: Filter & Create Plan Button --}}
        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="billing-filter" style="background: rgba(255,255,255,0.03); padding: 4px; border-radius: 8px; border: 1px solid var(--border);">
                <a href="{{ request()->fullUrlWithQuery(['billing' => 'monthly']) }}" 
                   class="btn {{ request()->query('billing', 'monthly') === 'monthly' ? 'btn-primary' : 'btn-ghost' }}" style="padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none;">
                     Monthly
                </a>
                <a href="{{ request()->fullUrlWithQuery(['billing' => 'annual']) }}" 
                   class="btn {{ request()->query('billing', 'monthly') === 'annual' ? 'btn-primary' : 'btn-ghost' }}" style="padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none;">
                     Annual
                </a>
            </div>

            {{-- Create Plan Trigger Button --}}
            <button class="btn btn-primary" onclick="openPlanModal('create')" style="padding: 10px 16px; font-size: 13px; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                <i class="fa-solid fa-plus"></i> Create New Plan
            </button>
        </div>
    </div>

    {{-- Subscription Plans Grid --}}
    <div class="stats-grid" style="margin-bottom: 35px; display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        @forelse($plans as $plan)
            {{-- অ্যারে এবং অবজেক্ট হ্যান্ডেল করার জন্য সেফটি কোড --}}
            @php 
                $planId = is_array($plan) ? $plan['id'] : $plan->id;
                $planName = is_array($plan) ? $plan['name'] : $plan->name;
                $planPrice = is_array($plan) ? $plan['price'] : $plan->price;
                $planCycle = is_array($plan) ? $plan['billing_cycle'] : $plan->billing_cycle;
                $planDuration = is_array($plan) ? $plan['duration'] : $plan->duration;
            @endphp

            <div class="stat-card" style="border-top: 3px solid var(--accent, #6366f1); padding: 20px; background: var(--surface, #161b27); border-radius: 8px; position: relative; display: flex; flex-direction: column; justify-content: space-between; height: auto;">
                <div>
                    <div class="stat-top" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="stat-icon" style="background: rgba(99, 102, 241, 0.1); color: var(--accent, #6366f1); width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 14px;">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <span class="badge" style="background: rgba(255,255,255,0.05); color: #cbd5e1; font-size: 11px; padding: 4px 8px; border-radius: 4px; text-transform: capitalize;">
                            {{ $planCycle }}
                        </span>
                    </div>

                    <div class="stat-label" style="font-size: 16px; font-weight: 600; color: #cbd5e1; margin-top: 12px;">
                        {{ $planName }}
                    </div>

                    <div class="stat-value" style="font-size: 26px; font-weight: 700; margin-top: 5px; color: #ffffff;">
                        ${{ number_format($planPrice, 2) }}
                    </div>

                    <div style="margin-top: 8px; font-size: 11px; color: #64748b; display: flex; gap: 12px; margin-bottom: 15px;">
                        @if($planDuration)
                            <span><i class="fa-solid fa-clock" style="margin-right: 4px;"></i> {{ $planDuration }} Days</span>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; gap: 8px; border-top: 1px solid var(--border); padding-top: 12px; margin-top: 5px;">
                    <button type="button" class="btn btn-ghost" 
                            onclick="viewPlanDetails({{ json_encode($plan) }})"
                            style="flex: 1; padding: 6px; font-size: 11px; text-align: center; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; color: #94a3b8; background: rgba(255,255,255,0.02); border: none; cursor: pointer;">
                        <i class="fa-solid fa-eye" style="font-size: 10px;"></i> Show
                    </button>
                    
                    <button type="button" class="btn btn-ghost" 
                            onclick="openPlanModal('edit', {{ json_encode($plan) }})"
                            style="flex: 1; padding: 6px; font-size: 11px; text-align: center; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; color: var(--accent, #6366f1); background: rgba(99, 102, 241, 0.05); border: none; cursor: pointer;">
                        <i class="fa-solid fa-pen-to-square" style="font-size: 10px;"></i> Edit
                    </button>
                    
                    <button type="button" class="btn btn-ghost" 
                            onclick="openDeleteModal('{{ $planId }}', '{{ $planName }}')"
                            style="flex: 1; padding: 6px; font-size: 11px; text-align: center; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; color: #ef4444; background: rgba(239, 68, 68, 0.05); border: none; cursor: pointer;">
                        <i class="fa-solid fa-trash" style="font-size: 10px;"></i> Delete
                    </button>
                </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #64748b; background: var(--surface); border-radius: 8px;">
                <i class="fa-solid fa-box-open" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                No subscription plans found.
            </div>
        @endforelse
    </div>

    {{-- ===== ACTIVE USER SUBSCRIPTIONS TABLE ===== --}}
    <div class="card" style="background: var(--surface, #161b27); border-radius: 8px; overflow: hidden;">
        <div class="card-header" style="padding: 20px; border-bottom: 1px solid var(--border);">
            <h3 class="card-title" style="font-size: 16px; font-weight: 600; color: #ffffff; margin: 0;">Active User Subscriptions</h3>
        </div>

        <div class="table-wrap">
            <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.1);">
                        <th style="padding: 12px 20px; color: #94a3b8; font-size: 13px;">Subscriber Name</th>
                        <th style="padding: 12px 20px; color: #94a3b8; font-size: 13px;">Active Plan</th>
                        <th style="padding: 12px 20px; color: #94a3b8; font-size: 13px;">Billing Cycle</th>
                        <th style="padding: 12px 20px; color: #94a3b8; font-size: 13px;">Price</th>
                        <th style="padding: 12px 20px; color: #94a3b8; font-size: 13px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($userSubscriptions as $sub)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 15px 20px;">
                                <div style="font-weight: 600; color: #ffffff;">{{ $sub->user->name ?? 'Unknown' }}</div>
                                <div style="font-size: 12px; color: #64748b;">{{ $sub->user->email ?? 'N/A' }}</div>
                            </td>
                            <td style="padding: 15px 20px; font-weight: 600; color: var(--accent, #6366f1);">
                                {{ $sub->plan->name ?? 'Custom Plan' }}
                            </td>
                            <td style="padding: 15px 20px;">
                                <span class="badge" style="background: rgba(255,255,255,0.05); color: #cbd5e1; text-transform: capitalize;">
                                    {{ $sub->billing_cycle }}
                                </span>
                            </td>
                            <td style="padding: 15px 20px; color: #10b981; font-weight: 600;">
                                ${{ number_format($sub->price, 2) }}
                            </td>
                            <td style="padding: 15px 20px;">
                                @if($sub->status === 'active')
                                    <span class="badge badge-active">
                                        <span class="badge-dot"></span> Active
                                    </span>
                                @else
                                    <span class="badge" style="background: rgba(239,68,68,0.1); color: #ef4444;">
                                        <span class="badge-dot" style="background: #ef4444;"></span> Expired
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #64748b;">
                                No active subscribers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($userSubscriptions instanceof \Illuminate\Pagination\LengthAwarePaginator && $userSubscriptions->hasPages())
            <div style="padding: 15px 20px; border-top: 1px solid var(--border);">
                {!! $userSubscriptions->links() !!}
            </div>
        @endif
    </div>
</div>

{{-- ===== PLAN DETAILS MODAL ===== --}}
<div id="viewPlanModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
    <div style="background: var(--surface-2, #1c2333); width: 100%; max-width: 480px; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5);">
        <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.1);">
            <h3 style="margin: 0; font-size: 16px; color: #fff; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-info" style="color: var(--accent, #6366f1);"></i> Plan Configuration Details
            </h3>
            <button onclick="closeViewPlanModal()" style="background: none; border: none; color: #64748b; cursor: pointer; font-size: 18px;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <div style="padding: 25px;">
            <div style="text-align: center; margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 20px;">
                <h2 id="modalPlanName" style="margin: 0; font-size: 22px; color: #ffffff; font-weight: 600;">-</h2>
                <div style="margin-top: 8px;">
                    <span id="modalPlanPrice" style="font-size: 28px; font-weight: 700; color: #10b981;">$0.00</span>
                    <span id="modalPlanBilling" style="font-size: 13px; color: #64748b; text-transform: capitalize;">/ monthly</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 15px; margin-bottom: 25px;">
                <div style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px solid var(--border);">
                    <div style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Duration / Validity</div>
                    <div id="modalPlanDuration" style="font-size: 14px; color: #cbd5e1; font-weight: 600; margin-top: 4px;"><i class="fa-solid fa-clock" style="margin-right: 6px; color: var(--accent);"></i>-</div>
                </div>
            </div>

            <div>
                <h4 style="margin: 0 0 12px 0; font-size: 13px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Included Features</h4>
                <ul id="modalPlanFeatures" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; max-height: 160px; overflow-y: auto;">
                </ul>
            </div>
        </div>

        <div style="padding: 15px 20px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; background: rgba(0,0,0,0.1);">
            <button type="button" onclick="closeViewPlanModal()" class="btn btn-secondary" style="padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px;">Close Window</button>
        </div>
    </div>
</div>

{{-- ===== CREATE / EDIT MODAL (Unified Action) ===== --}}
<div id="planModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
    <div style="background: var(--surface-2, #1c2333); width: 100%; max-width: 500px; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5);">
        <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 id="planModalTitle" style="margin: 0; font-size: 16px; color: #fff;">Create New Subscription Plan</h3>
            <button onclick="closePlanModal()" style="background: none; border: none; color: #64748b; cursor: pointer; font-size: 18px;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <form id="planForm" action="{{ route('admin.plans.store') }}" method="POST" style="padding: 20px;">
            @csrf
            <input type="hidden" id="inputPlanId" name="plan_id" value="">

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; color: #94a3b8; margin-bottom: 6px;">Plan Name *</label>
                <input type="text" id="inputName" name="name" required class="form-input" style="width: 100%; padding: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; color: #fff;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; color: #94a3b8; margin-bottom: 6px;">Billing Cycle *</label>
                <select id="selectBilling" name="billing_cycle" style="width: 100%; padding: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; color: #fff;">
                    <option value="monthly">Monthly</option>
                    <option value="annual">Annual</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 13px; color: #94a3b8; margin-bottom: 6px;">Price ($) *</label>
                    <input type="number" step="0.01" id="inputPrice" name="price" required class="form-input" style="width: 100%; padding: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; color: #fff;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; color: #94a3b8; margin-bottom: 6px;">Duration (Days)</label>
                    <input type="number" id="inputDuration" name="duration" class="form-input" style="width: 100%; padding: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; color: #fff;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; color: #94a3b8; margin-bottom: 6px;">Features (Comma Separated)</label>
                <input type="text" id="inputFeatures" name="features" placeholder="Feature 1, Feature 2, Feature 3" class="form-input" style="width: 100%; padding: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; color: #fff;">
            </div>

            <div style="padding-top: 15px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closePlanModal()" class="btn btn-secondary" style="padding: 10px 16px; border-radius: 6px; cursor: pointer;">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitBtn" style="padding: 10px 16px; border-radius: 6px; cursor: pointer;">Save Plan</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== CUSTOM DELETE CONFIRMATION MODAL ===== --}}
<div id="deletePlanModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
    <div style="background: var(--surface-2, #1c2333); width: 100%; max-width: 400px; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5);">
        <div style="padding: 20px; text-align: center;">
            <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; margin: 0 auto 15px auto;">
                <i class="fa-solid fa-exclamation-triangle"></i>
            </div>
            <h3 style="margin: 0 0 8px 0; font-size: 16px; color: #fff;">Delete Subscription Plan?</h3>
            <p style="margin: 0; font-size: 13px; color: #94a3b8; line-height: 1.5;">Are you sure you want to delete <strong id="deletePlanName" style="color: #fff;"></strong>? This action cannot be undone.</p>
        </div>
        
        <form id="deleteForm" method="POST" style="margin: 0;">
            @csrf
            @method('DELETE')
            <div style="padding: 15px 20px; border-top: 1px solid var(--border); display: flex; justify-content: center; gap: 10px; background: rgba(0,0,0,0.1);">
                <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary" style="padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px;">Cancel</button>
                <button type="submit" class="btn" style="padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #fff; background: #ef4444; border: none;">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    const basePlanRoute = "{{ url('admin/plans') }}"; 

    // Create / Edit Modal logic
    function openPlanModal(mode, plan = null) {
        const form = document.getElementById('planForm');
        const title = document.getElementById('planModalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const planIdInput = document.getElementById('inputPlanId');

        if (mode === 'edit' && plan) {
            const id = plan.id ?? plan['id'];
            const name = plan.name ?? plan['name'] ?? '';
            const billing_cycle = plan.billing_cycle ?? plan['billing_cycle'] ?? 'monthly';
            const price = plan.price ?? plan['price'] ?? '';
            const duration = plan.duration ?? plan['duration'] ?? '';
            const features = plan.features ?? plan['features'] ?? '';

            title.innerText = "Edit Subscription Plan";
            submitBtn.innerText = "Update Plan";
            
            planIdInput.value = id;

            document.getElementById('inputName').value = name;
            document.getElementById('selectBilling').value = billing_cycle;
            document.getElementById('inputPrice').value = price;
            document.getElementById('inputDuration').value = duration;
            
            let featuresText = '';
            if (features) {
                if (Array.isArray(features)) {
                    featuresText = features.join(', ');
                } else if (typeof features === 'string') {
                    try {
                        let parsed = JSON.parse(features);
                        featuresText = Array.isArray(parsed) ? parsed.join(', ') : features;
                    } catch(e) {
                        featuresText = features;
                    }
                }
            }
            document.getElementById('inputFeatures').value = featuresText;

        } else {
            // Create Mode
            title.innerText = "Create New Subscription Plan";
            submitBtn.innerText = "Save Plan";
            planIdInput.value = "";

            form.reset();
            document.getElementById('inputName').value = '';
            document.getElementById('inputPrice').value = '';
            document.getElementById('inputDuration').value = '';
            document.getElementById('inputFeatures').value = '';
        }

        document.getElementById('planModal').style.display = 'flex';
    }

    function closePlanModal() {
        document.getElementById('planModal').style.display = 'none';
    }

    // View Details Modal
    function viewPlanDetails(plan) {
        const name = plan.name ?? plan['name'] ?? '-';
        const price = plan.price ?? plan['price'] ?? 0;
        const billing_cycle = plan.billing_cycle ?? plan['billing_cycle'] ?? 'monthly';
        const duration = plan.duration ?? plan['duration'] ?? null;
        const features = plan.features ?? plan['features'] ?? null;

        document.getElementById('modalPlanName').innerText = name;
        document.getElementById('modalPlanPrice').innerText = '$' + parseFloat(price).toFixed(2);
        document.getElementById('modalPlanBilling').innerText = '/ ' + billing_cycle;
        
        document.getElementById('modalPlanDuration').innerHTML = duration 
            ? `<i class="fa-solid fa-clock" style="margin-right: 6px; color: var(--accent);"></i> ${duration} Days` 
            : 'Unlimited';

        const featuresContainer = document.getElementById('modalPlanFeatures');
        featuresContainer.innerHTML = '';

        let featuresArray = [];
        if (features) {
            if (Array.isArray(features)) {
                featuresArray = features;
            } else if (typeof features === 'string') {
                try {
                    featuresArray = JSON.parse(features);
                } catch(e) {
                    featuresArray = features.split(',').map(f => f.trim());
                }
            }
        }

        if (featuresArray.length > 0 && featuresArray[0] !== "") {
            featuresArray.forEach(feature => {
                const li = document.createElement('li');
                li.style.display = 'flex';
                li.style.alignItems = 'center';
                li.style.gap = '10px';
                li.style.fontSize = '13px';
                li.style.color = '#cbd5e1';
                li.innerHTML = `<i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 13px;"></i> <span>${feature}</span>`;
                featuresContainer.appendChild(li);
            });
        } else {
            featuresContainer.innerHTML = `<li style="color: #64748b; font-size: 13px; font-style: italic;">No specific features listed.</li>`;
        }

        document.getElementById('viewPlanModal').style.display = 'flex';
    }

    function closeViewPlanModal() {
        document.getElementById('viewPlanModal').style.display = 'none';
    }

    // Custom Delete Modal Logic
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