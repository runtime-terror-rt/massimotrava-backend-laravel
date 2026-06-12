@extends('layouts.admin') {{-- বা আপনার কাস্টমার লেআউট ফাইল --}}

@section('title', __('messages.dashboard') . ' - Massimotrava')
@section('content')

{{-- গুগল ফন্ট ইমপোর্ট (যদি লেআউটে না থাকে) --}}
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">

<div class="container-fluid px-4 py-4" style="background-color: var(--bg-main); min-height: 100vh; color: var(--text); font-family: var(--font-body); transition: all var(--transition);">
    
    <!-- TOP HEADER: Premium Welcome -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
        <div>
            <span class="text-xs text-uppercase tracking-wider font-weight-bold" style="color: var(--accent); letter-spacing: 0.1em;">Premium Longevity Portal</span>
            <h1 class="h2 font-weight-bold mt-1 mb-0" style="font-family: var(--font-display); color: var(--text-main);">
                Welcome Back, {{ Auth::user()->name ?? 'Explorer' }}
            </h1>
        </div>
        
        <!-- Fast Actions Quick Row -->
        <div class="d-flex gap-2">
            <button class="btn d-flex align-items-center gap-2 px-3 py-2 text-sm font-weight-medium" 
                    style="background: var(--surface); border: 1px solid var(--border); color: var(--text); border-radius: var(--radius-sm);"
                    data-bs-toggle="modal" data-bs-target="#activateKitModal">
                <i class="fa-solid fa-qrcode" style="color: var(--accent);"></i> Activate Kit
            </button>
            <button class="btn text-white d-flex align-items-center gap-2 px-4 py-2 text-sm font-weight-medium" 
                    style="background: var(--accent); border: none; border-radius: var(--radius-sm); box-shadow: 0 4px 14px var(--accent-glow);">
                <i class="fa-regular fa-calendar-check"></i> Schedule Pickup
            </button>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="row g-4">
        
        <!-- LEFT COLUMN: Main Dashboard (Longevity & Biomarkers) - HIGH PRIORITY -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 p-4 mb-4" style="background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border) !important;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h5 font-weight-bold mb-0" style="font-family: var(--font-display); color: var(--text-main);">Longevity Overview</h2>
                    <span class="badge px-2 py-1 text-xs" style="background: rgba(56, 189, 248, 0.1); color: var(--accent); border-radius: 6px;">Updated 2 days ago</span>
                </div>

                <div class="row align-items-center g-4">
                    <!-- Longevity Score Radial Visual -->
                    <div class="col-md-5 text-center d-flex flex-column align-items-center justify-content-center border-end-md" style="border-color: var(--border) !important;">
                        <div class="position-relative d-flex align-items-center justify-content-center" style="width: 160px; height: 160px;">
                            <!-- Outer Tech Glow Ring -->
                            <div class="position-absolute w-100 h-100 rounded-circle" style="border: 4px solid var(--border); border-top-color: var(--accent); transform: rotate(45deg);"></div>
                            <div class="position-absolute rounded-circle" style="width: 85%; height: 85%; border: 2px dashed var(--border-dashed);"></div>
                            <div class="text-center">
                                <span class="d-block display-5 font-weight-bold" style="font-family: var(--font-display); color: var(--text-main); line-height: 1;">84</span>
                                <span class="text-xs text-uppercase font-weight-bold tracking-wider" style="color: var(--accent-3);">Optimal</span>
                            </div>
                        </div>
                        <p class="text-xs text-muted mt-3 mb-0 px-3">Your biological age indicators suggest a resilient baseline profile.</p>
                    </div>

                    <!-- Biomarker Breakdowns -->
                    <div class="col-md-7">
                        <h3 class="text-xs text-uppercase tracking-wider font-weight-bold mb-3" style="color: var(--text-muted);">Key Biomarkers</h3>
                        
                        <div class="vstack gap-3">
                            <!-- Biomarker 1 -->
                            <div>
                                <div class="d-flex justify-content-between text-sm mb-1">
                                    <span class="font-weight-medium" style="color: var(--text);">Metabolic Health (HbA1c)</span>
                                    <span class="font-weight-bold" style="color: var(--accent-3);">Optimal <small class="text-muted font-weight-normal">(5.1%)</small></span>
                                </div>
                                <div class="progress" style="height: 6px; background: var(--surface-2); border-radius: 10px;">
                                    <div class="progress-bar" style="width: 85%; background: var(--accent-3); border-radius: 10px;"></div>
                                </div>
                            </div>
                            <!-- Biomarker 2 -->
                            <div>
                                <div class="d-flex justify-content-between text-sm mb-1">
                                    <span class="font-weight-medium" style="color: var(--text);">Cellular Inflammation (hs-CRP)</span>
                                    <span class="font-weight-bold" style="color: var(--accent);">Good <small class="text-muted font-weight-normal">(0.9 mg/L)</small></span>
                                </div>
                                <div class="progress" style="height: 6px; background: var(--surface-2); border-radius: 10px;">
                                    <div class="progress-bar" style="width: 70%; background: var(--accent); border-radius: 10px;"></div>
                                </div>
                            </div>
                            <!-- Biomarker 3 -->
                            <div>
                                <div class="d-flex justify-content-between text-sm mb-1">
                                    <span class="font-weight-medium" style="color: var(--text);">Cardiovascular & Lipids (ApoB)</span>
                                    <span class="font-weight-bold" style="color: var(--accent-4);">Attention <small class="text-muted font-weight-normal">(94 mg/dL)</small></span>
                                </div>
                                <div class="progress" style="height: 6px; background: var(--surface-2); border-radius: 10px;">
                                    <div class="progress-bar" style="width: 45%; background: var(--accent-4); border-radius: 10px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RESULTS & HISTORY: Modern Placeholder Layout -->
            <div class="card border-0 p-4" style="background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border) !important;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h5 font-weight-bold mb-0" style="font-family: var(--font-display); color: var(--text-main);">Lab Reports Archive</h2>
                    <a href="#" class="text-xs font-weight-bold text-decoration-none" style="color: var(--accent);">View Detailed History →</a>
                </div>

                <div class="vstack gap-2">
                    <!-- Report Item 1 -->
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-lg border-hover" style="background: var(--bg-main); border: 1px solid var(--border); border-radius: var(--radius-sm); transition: var(--transition);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded" style="width: 42px; height: 42px; background: rgba(56, 189, 248, 0.08); border: 1px solid rgba(56, 189, 248, 0.15);">
                                <i class="fa-regular fa-file-lines" style="color: var(--accent); font-size: 18px;"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-weight-bold mb-0" style="color: var(--text-main);">Comprehensive Longevity Panel v2</h4>
                                <small class="text-xs text-muted">Completed: June 04, 2026</small>
                            </div>
                        </div>
                        <button class="btn btn-sm text-sm px-3" style="background: var(--surface-2); color: var(--text); border: 1px solid var(--border); border-radius: 6px;">
                            <i class="fa-solid fa-download me-1"></i> PDF
                        </button>
                    </div>

                    <!-- Report Item 2 (Placeholder Concept) -->
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-lg opacity-60" style="background: var(--bg-main); border: 1px dashed var(--border-dashed); border-radius: var(--radius-sm);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded" style="width: 42px; height: 42px; background: var(--surface-2);">
                                <i class="fa-solid fa-flask-vial text-muted" style="font-size: 16px;"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-weight-medium mb-0 text-muted">Baseline Epigenetic Age Diagnostic</h4>
                                <small class="text-xs text-muted-dark">Sample processing at laboratory</small>
                            </div>
                        </div>
                        <span class="badge text-xs px-2.5 py-1.5" style="background: var(--surface-2); color: var(--text-muted); border-radius: 6px;">Processing</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Kits Tracking, Insights Engine & Account Summary -->
        <div class="col-xl-4 col-lg-5">
            
            <!-- MY KITS: Live Order Tracking -->
            <div class="card border-0 p-4 mb-4" style="background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border) !important;">
                <h2 class="h5 font-weight-bold mb-3" style="font-family: var(--font-display); color: var(--text-main);">Track My Kit</h2>
                
                <div class="p-3 rounded mb-3" style="background: var(--bg-main); border: 1px solid var(--border); border-radius: var(--radius-sm);">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold" style="color: var(--text-muted);">ID: #MT-84920-X</span>
                        <span class="text-xs font-weight-bold" style="color: var(--accent);">In Transit</span>
                    </div>
                    
                    <!-- Micro Tracking Timeline -->
                    <div class="d-flex justify-content-between position-relative my-3 px-1">
                        <div class="position-absolute w-100 style-line" style="height: 2px; background: var(--border); top: 50%; transform: translateY(-50%); z-index: 1;"></div>
                        <div class="position-absolute style-line-filled" style="height: 2px; background: var(--accent); top: 50%; transform: translateY(-50%); width: 66%; z-index: 1;"></div>
                        
                        <span class="rounded-circle d-inline-block position-relative" style="width: 10px; height: 10px; background: var(--accent); z-index: 2; box-shadow: 0 0 8px var(--accent);"></span>
                        <span class="rounded-circle d-inline-block position-relative" style="width: 10px; height: 10px; background: var(--accent); z-index: 2; box-shadow: 0 0 8px var(--accent);"></span>
                        <span class="rounded-circle d-inline-block position-relative" style="width: 10px; height: 10px; background: var(--border); z-index: 2;"></span>
                    </div>
                    <p class="text-xs text-muted-dark mb-0"><i class="fa-solid fa-truck-fast me-1"></i> Handed over to DHL Express Courier. Expected delivery tomorrow.</p>
                </div>

                <!-- Digital Preparation Guide Link -->
                <a href="#" class="d-flex align-items-center justify-content-between p-2 text-decoration-none rounded text-sm hover-clean-bg" style="color: var(--text); border: 1px solid var(--border);">
                    <span class="d-flex align-items-center gap-2"><i class="fa-regular fa-compass" style="color: var(--accent-3);"></i> Digital Fasting & Prep Guide</span>
                    <i class="fa-solid fa-chevron-right text-muted-dark fs-xs"></i>
                </a>
            </div>

            <!-- INSIGHTS ENGINE: AI Phase-Ready Architecture Layout -->
            <div class="card border-0 p-4 overflow-hidden position-relative mb-4" style="background: linear-gradient(135deg, var(--surface) 0%, var(--bg-main) 100%); border-radius: var(--radius); border: 1px solid var(--border) !important;">
                <!-- Decorative Subtle Ambient AI Radial Glow -->
                <div class="position-absolute" style="width: 120px; height: 120px; background: var(--accent-glow); filter: blur(40px); bottom: -20px; right: -20px; pointer-events: none;"></div>
                
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 class="h5 font-weight-bold mb-0" style="font-family: var(--font-display); color: var(--text-main);">
                        <i class="fa-solid fa-wand-magic-sparkles me-1 text-sm" style="color: var(--accent);"></i> Insights Engine
                    </h2>
                    <span class="text-uppercase tracking-wider font-weight-bold" style="font-size: 9px; background: rgba(52, 211, 153, 0.1); color: var(--accent-3); padding: 2px 6px; border-radius: 4px;">AI Core Architecture</span>
                </div>
                
                <p class="text-xs text-muted mb-3">Predictive analytics engine mapping biological biomarkers against customized life-extension methodologies.</p>
                
                <!-- Placeholder Engine Output Container -->
                <div class="p-3 rounded-lg" style="background: rgba(255,255,255,0.02); border: 1px dashed var(--border-dashed); border-radius: var(--radius-sm);">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="fa-regular fa-lightbulb text-muted mt-0.5"></i>
                        <span class="text-xs font-weight-medium text-muted" style="line-height: 1.5;">
                            As your physical kit finishes parsing inside the lab, personalized dynamic cellular age optimization vectors will unlock instantly within this engine framework.
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ================= MODAL: ACTIVATE KIT (QR & BARCODE) ================= -->
<div class="modal fade" id="activateKitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="background: var(--surface-modal); border-radius: var(--radius); color: var(--text);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold" style="font-family: var(--font-display); color: var(--text-main);">Link Physical Bio-Kit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <p class="text-xs text-muted mb-4">Input the alpha-numeric code located directly underneath the outer QR Matrix sticker on your collection box.</p>
                    
                    <div class="mb-3">
                        <label class="text-xs text-uppercase tracking-wider font-weight-bold mb-2 d-block" style="color: var(--text-muted);">Kit Barcode Identifier</label>
                        <div class="position-relative">
                            <input type="text" name="kit_code" placeholder="e.g., MST-9482-BRCD" required
                                   class="form-control px-3 py-2.5 text-sm" 
                                   style="background: var(--surface-input); border: 1px solid var(--border); color: var(--text); border-radius: var(--radius-sm); padding-right: 40px;">
                            <i class="fa-solid fa-barcode position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex gap-2">
                    <button type="button" class="btn text-xs font-weight-medium" style="color: var(--text-muted);" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn text-white px-4 text-xs font-weight-bold" style="background: var(--accent); border-radius: var(--radius-sm);">Link Kit Identity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Premium Aesthetic Adjustments */
    .border-hover:hover {
        border-color: rgba(56, 189, 248, 0.3) !important;
        background: var(--surface-2) !important;
    }
    .hover-clean-bg:hover {
        background: var(--surface-2) !important;
    }
    .opacity-60 { opacity: 0.6; }
    .fs-xs { font-size: 10px; }
    .tracking-wider { letter-spacing: 0.05em; }
    @media (min-width: 768px) {
        .border-end-md { border-right: 1px solid var(--border) !important; }
    }
</style>

@endsection