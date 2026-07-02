@extends('layouts.admin')
@section('page_title_key', 'settings_header')
@section('content')
@push('styles')
    
<style>
    .settings-wrapper {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
        align-items: start;
        width: 100%;
    }
    .profile-nav-card {
        background: var(--surface, #1e293b);
        border: 1px solid var(--border, #334155);
        border-radius: 16px;
        padding: 20px 15px;
        position: sticky;
        top: 30px;
    }
    .tab-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 12px 16px;
        background: transparent;
        border: none;
        color: #94a3b8;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s ease;
        margin-bottom: 6px;
    }
    .tab-btn:hover {
        background: rgba(255, 255, 255, 0.03);
        color: #fff;
    }
    .tab-btn.active {
        background: #6366f1;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
    }
    .tab-panel {
        display: none;
    }
    .tab-panel.active {
        display: block;
    }
    .password-field-group {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }
    .password-toggle-icon {
        position: absolute;
        right: 16px;
        color: #64748b;
        cursor: pointer;
        transition: color 0.2s;
        z-index: 10;
    }
    .password-toggle-icon:hover {
        color: #6366f1;
    }
    .noti-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid var(--border, #334155);
        border-radius: 12px;
        margin-bottom: 15px;
    }
    .switch-btn {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 24px;
    }
    .switch-btn input { opacity: 0; width: 0; height: 0; }
    .slider-ui {
        position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
        background-color: #334155; transition: .3s; border-radius: 24px;
    }
    .slider-ui:before {
        position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px;
        background-color: white; transition: .3s; border-radius: 50%;
    }
    input:checked + .slider-ui { background-color: #6366f1; }
    input:checked + .slider-ui:before { transform: translateX(22px); }

    @media (max-width: 991px) {
        .settings-wrapper {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .profile-nav-card {
            position: static;
        }
    }
</style>

<div class="profile-container" style="width: 100%; max-width: 1350px; margin: 20px auto; padding: 0 20px; font-family: 'Inter', sans-serif; color: #f8fafc;">
    
    @if(session('success'))
        <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #4ade80; padding: 14px 20px; border-radius: 12px; margin-bottom: 25px; font-size: 14px; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="settings-wrapper">
        
        <div class="profile-nav-card">
            <div style="padding: 0 10px 15px 10px; border-bottom: 1px solid var(--border, #334155); margin-bottom: 15px;">
                <h4 style="margin: 0; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #64748b;">Settings Menu</h4>
            </div>
            <button class="tab-btn active" onclick="switchSettingsTab(event, 'account-settings')">
                <i class="fa-solid fa-user-gear"></i> Account Settings
            </button>
            <button class="tab-btn" onclick="switchSettingsTab(event, 'health-profile')">
                <i class="fa-solid fa-heart-pulse"></i> Health Profile Summary
            </button>
            <button class="tab-btn" onclick="switchSettingsTab(event, 'kit-questionnaire')">
                <i class="fa-solid fa-clipboard-list"></i> Kit Questionnaire
            </button>
            <button class="tab-btn" onclick="switchSettingsTab(event, 'notification-settings')">
                <i class="fa-solid fa-bell"></i> Notification Settings
            </button>
            <button class="tab-btn" onclick="switchSettingsTab(event, 'update-password')">
                <i class="fa-solid fa-shield-halved"></i> Update Password
            </button>
        </div>

        <div class="profile-content-area">

            <div id="account-settings" class="tab-panel active">
                <div class="profile-card" style="background: var(--surface, #1e293b); border: 1px solid var(--border, #334155); border-radius: 20px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border, #334155); padding-bottom: 25px; margin-bottom: 30px;">
                        <div>
                            <h2 style="font-size: 20px; color: #ffffff; margin: 0; font-weight: 600;">{{ __('messages.account_settings') ?? 'Account Settings' }}</h2>
                            <p style="font-size: 13px; color: #94a3b8; margin: 6px 0 0 0;">Update your personal profile information and avatar.</p>
                        </div>
                        <span style="font-size: 12px; background: rgba(99, 102, 241, 0.15); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.3); padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                            USER ID: #{{ $user->id }}
                        </span>
                    </div>

                    <form action="{{ route('user.update.profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom: 35px;">
                            <label style="font-weight: 600; margin-bottom: 10px; display: block; color: #cbd5e1; font-size: 14px;">{{ __('messages.lbl_profile_picture') }}</label>
                            <div style="display: flex; align-items: center; gap: 25px; background: rgba(15, 23, 42, 0.4); padding: 20px; border-radius: 16px; border: 1px dashed var(--border, #475569);">
                                <div style="position: relative; width: 100px; height: 100px; flex-shrink: 0; border-radius: 50%; overflow: hidden; border: 3px solid #6366f1; background: #0f172a;">
                                    <img id="profile-display" src="{{ $user->image ? Storage::url($user->image) : '' }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; {{ $user->image ? '' : 'display: none;' }}">
                                    <div id="profile-placeholder" style="width: 100%; height: 100%; display: {{ $user->image ? 'none' : 'flex' }}; align-items: center; justify-content: center; background: #334155;">
                                        <i class="fa-solid fa-user" style="font-size: 40px; color: #94a3b8;"></i>
                                    </div>
                                </div>
                                <div style="flex: 1;">
                                    <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-start;">
                                        <label for="image-upload" style="display: inline-flex; align-items: center; gap: 8px; background: #6366f1; color: #ffffff; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 500; cursor: pointer;">
                                            <i class="fa-solid fa-cloud-arrow-up"></i> {{ __('Choose New Photo') }}
                                        </label>
                                        <input type="file" id="image-upload" name="image" accept="image/*" style="display: none;" onchange="previewProfileImage(this)">
                                        <p style="font-size: 12px; color: #64748b; margin: 0;">{{ __('messages.lbl_avatar_hint') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 35px;">
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <label style="font-size: 13px; font-weight: 500; color: #94a3b8;">Full Name <span style="color:#f87171">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 12px 16px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border, #334155); border-radius: 10px; font-size: 14px; color: #ffffff; outline: none;">
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <label style="font-size: 13px; font-weight: 500; color: #94a3b8;">Email Address <span style="color:#f87171">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required readonly style="width: 100%; padding: 12px 16px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border, #334155); border-radius: 10px; font-size: 14px; color: #ffffff; outline: none;">
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <label style="font-size: 13px; font-weight: 500; color: #94a3b8;">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" style="width: 100%; padding: 12px 16px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border, #334155); border-radius: 10px; font-size: 14px; color: #ffffff; outline: none;">
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 15px; border-top: 1px solid var(--border, #334155); padding-top: 25px;">
                            <a href="{{ route('user.actionitem.index') }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: #334155; color: #cbd5e1; padding: 12px 24px; border-radius: 10px; font-size: 14px;">
                                <i class="fa-solid fa-arrow-left"></i> {{ __('messages.back') ?? 'Cancel' }}
                            </a>
                            <button type="submit" style="border: none; display: inline-flex; align-items: center; gap: 8px; background: #6366f1; color: #ffffff; padding: 12px 28px; border-radius: 10px; font-size: 14px; font-weight: 500; cursor: pointer;">
                                <i class="fa-solid fa-check"></i> {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ২. Health Profile Summary Tab Panel -->
            <div id="health-profile" class="tab-panel">
                <div class="profile-card" style="background: var(--surface, #1e293b); border: 1px solid var(--border, #334155); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    <div style="border-bottom: 1px solid var(--border, #334155); padding-bottom: 15px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="font-size: 18px; color: #ffffff; margin: 0; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-heart-pulse" style="color:#ec4899;"></i> {{ __('Health Profile Summary') }}
                        </h3>
                    </div>

                    @if($user->health_profile)
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px;">
                            <div>
                                <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Age</span>
                                <strong style="font-size: 16px; color:#fff;">{{ $user->health_profile['age'] ?? 'N/A' }} Years</strong>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Biological Sex</span>
                                <strong style="font-size: 16px; color:#fff;">{{ $user->health_profile['biological_sex'] ?? 'N/A' }}</strong>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Activity Level</span>
                                <strong style="font-size: 16px; color:#38bdf8; text-transform: capitalize;">{{ str_replace('_', ' ', $user->health_profile['activity_level'] ?? 'N/A') }}</strong>
                            </div>
                        </div>

                        <div style="margin-top: 25px;">
                            <span style="font-size: 13px; color: #94a3b8; display: block; margin-bottom: 10px; font-weight: 500;">Health Goals</span>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @if(!empty($user->health_profile['health_goals']))
                                    @foreach($user->health_profile['health_goals'] as $goal)
                                        <span style="background: rgba(167, 139, 250, 0.15); color: #c084fc; border: 1px solid rgba(167, 139, 250, 0.3); padding: 6px 14px; border-radius: 8px; font-size: 12px; text-transform: capitalize;">
                                            <i class="fa-solid fa-bullseye" style="font-size: 10px; margin-right: 6px;"></i>{{ $goal }}
                                        </span>
                                    @endforeach
                                @else
                                    <span style="color: #64748b; font-size: 13px;">No goals selected</span>
                                @endif
                            </div>
                        </div>

                        <div style="margin-top: 25px;">
                            <span style="font-size: 13px; color: #94a3b8; display: block; margin-bottom: 10px; font-weight: 500;">Existing Medical Conditions</span>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @if(!empty($user->health_profile['medical_conditions']))
                                    @foreach($user->health_profile['medical_conditions'] as $condition)
                                        <span style="background: rgba(248, 113, 113, 0.12); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.2); padding: 6px 14px; border-radius: 8px; font-size: 12px; text-transform: capitalize;">
                                            <i class="fa-solid fa-notes-medical" style="font-size: 10px; margin-right: 6px;"></i>{{ str_replace('_', ' ', $condition) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span style="color: #64748b; font-size: 13px;">None reported</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div style="text-align: center; padding: 20px; color: #64748b; font-size: 14px; border: 1px dashed #334155; border-radius: 12px;">
                            <i class="fa-solid fa-folder-open" style="display:block; font-size: 24px; margin-bottom: 8px;"></i> No health profile data recorded yet.
                        </div>
                    @endif
                </div>
            </div>

            <!-- ৩. Kit Questionnaire Tab Panel -->
            <div id="kit-questionnaire" class="tab-panel">
                <div class="profile-card" style="background: var(--surface, #1e293b); border: 1px solid var(--border, #334155); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    <div style="border-bottom: 1px solid var(--border, #334155); padding-bottom: 15px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="font-size: 18px; color: #ffffff; margin: 0; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-clipboard-list" style="color:#3b82f6;"></i> {{ __('Latest Kit Questionnaire') }}
                        </h3>
                    </div>

                    @if($user->kit_questionnaire)
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; margin-bottom: 25px;">
                            <div>
                                <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Fasting Status</span>
                                <span style="font-size: 14px; background: #334155; padding: 4px 10px; border-radius: 6px; color:#fff; text-transform: capitalize;">{{ $user->kit_questionnaire['fasting_status'] ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Collection Time</span>
                                <span style="font-size: 14px; background: #334155; padding: 4px 10px; border-radius: 6px; color:#fff;">{{ str_replace('_', ' ', $user->kit_questionnaire['collection_time'] ?? 'N/A') }}</span>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Recent Illness</span>
                                <span style="font-size: 14px; background: #334155; padding: 4px 10px; border-radius: 6px; color:#fff;">{{ $user->kit_questionnaire['recent_illness'] ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div style="margin-bottom: 25px;">
                            <span style="font-size: 13px; color: #94a3b8; display: block; margin-bottom: 10px; font-weight: 500;">Current Medications</span>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @if(!empty($user->kit_questionnaire['medications']))
                                    @foreach($user->kit_questionnaire['medications'] as $med)
                                        <span style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); padding: 6px 14px; border-radius: 8px; font-size: 12px;">
                                            <i class="fa-solid fa-capsules" style="font-size: 11px; margin-right: 6px;"></i>{{ str_replace('_', ' ', $med) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span style="color: #64748b; font-size: 13px;">No medications reported</span>
                                @endif
                            </div>
                        </div>

                        @if(!empty($user->kit_questionnaire['additional_notes']))
                            <div style="background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border, #334155); padding: 15px; border-radius: 12px;">
                                <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 6px; font-weight: 600;">Additional Medical Notes</span>
                                <p style="font-size: 13px; color: #cbd5e1; margin: 0; line-height: 1.6;">{{ $user->kit_questionnaire['additional_notes'] }}</p>
                            </div>
                        @endif
                    @else
                        <div style="text-align: center; padding: 20px; color: #64748b; font-size: 14px; border: 1px dashed #334155; border-radius: 12px;">
                            <i class="fa-solid fa-folder-open" style="display:block; font-size: 24px; margin-bottom: 8px;"></i> No kit questionnaire submitted yet.
                        </div>
                    @endif
                </div>
            </div>

            <!-- ৪. Notification Settings Tab Panel -->
            <div id="notification-settings" class="tab-panel">
                <div class="profile-card" style="background: var(--surface, #1e293b); border: 1px solid var(--border, #334155); border-radius: 20px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    
                    <div style="border-bottom: 1px solid var(--border, #334155); padding-bottom: 15px; margin-bottom: 25px;">
                        <h3 style="font-size: 18px; color: #ffffff; margin: 0; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-bell" style="color:#fbbf24;"></i> Notification Preferences
                        </h3>
                        <p style="font-size: 13px; color: #94a3b8; margin: 6px 0 0 0;">Manage how and when you receive updates from VyraLabs.</p>
                    </div>

                    <div class="noti-row">
                        <div>
                            <h5 style="margin: 0 0 4px 0; font-size: 14px; color: #fff; font-weight: 500;">Email Alerts</h5>
                            <p style="margin: 0; font-size: 12px; color: #64748b;">Receive core account and report notifications via email.</p>
                        </div>
                        <label class="switch-btn">
                            <input type="checkbox" class="notif-toggle" data-setting="email_alerts" id="emailAlerts">
                            <span class="slider-ui"></span>
                        </label>
                    </div>

                    <div class="noti-row">
                        <div>
                            <h5 style="margin: 0 0 4px 0; font-size: 14px; color: #fff; font-weight: 500;">Report Generate Alerts</h5>
                            <p style="margin: 0; font-size: 12px; color: #64748b;">Receive core Test report notifications Alerts</p>
                        </div>
                        <label class="switch-btn">
                            <input type="checkbox" class="notif-toggle" data-setting="generate_report" id="generateReport">
                            <span class="slider-ui"></span>
                        </label>
                    </div>

                    <div class="noti-row">
                        <div>
                            <h5 style="margin: 0 0 4px 0; font-size: 14px; color: #fff; font-weight: 500;">Lab Kit Progress Updates</h5>
                            <p style="margin: 0; font-size: 12px; color: #64748b;">Get real-time tracking updates when your test kit status updates.</p>
                        </div>
                        <label class="switch-btn">
                            <input type="checkbox" class="notif-toggle" data-setting="lab_kit_updates" id="labKitUpdates">
                            <span class="slider-ui"></span>
                        </label>
                    </div>
                    <div class="noti-row">
                        <div>
                            <h5 style="margin: 0 0 4px 0; font-size: 14px; color: #fff; font-weight: 500;">SMS Notifications</h5>
                            <p style="margin: 0; font-size: 12px; color: #64748b;">Receive instant updates and alert messages directly to your phone.</p>
                        </div>
                        <label class="switch-btn">
                            <input type="checkbox" class="notif-toggle" data-setting="sms_notification" id="smsNotification">
                            <span class="slider-ui"></span>
                        </label>
                    </div>
                    <div class="noti-row">
                        <div>
                            <h5 style="margin: 0 0 4px 0; font-size: 14px; color: #fff; font-weight: 500;">Weekly Health Analytics</h5>
                            <p style="margin: 0; font-size: 12px; color: #64748b;">Receive tailored bio-insights and summaries weekly.</p>
                        </div>
                        <label class="switch-btn">
                            <input type="checkbox" class="notif-toggle" data-setting="weekly_analytics" id="weeklyAnalytics">
                            <span class="slider-ui"></span>
                        </label>
                    </div>

                </div>
            </div>

            <div id="update-password" class="tab-panel">
                <div class="profile-card" style="background: var(--surface, #1e293b); border: 1px solid var(--border, #334155); border-radius: 20px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    <div style="border-bottom: 1px solid var(--border, #334155); padding-bottom: 15px; margin-bottom: 25px;">
                        <h3 style="font-size: 18px; color: #ffffff; margin: 0; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-shield-halved" style="color:#818cf8;"></i> Update Password
                        </h3>
                        <p style="font-size: 13px; color: #94a3b8; margin: 6px 0 0 0;">Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                    
                    <form action="{{ route('user.update.password') }}" method="POST">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr; gap: 20px; max-width: 550px; margin-bottom: 30px;">
                            
                            {{-- Current Password --}}
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <label style="font-size: 13px; font-weight: 500; color: #94a3b8;">Current Password</label>
                                <div class="password-field-group">
                                    <input type="password" id="current_password" name="current_password" required style="width: 100%; padding: 12px 45px 12px 16px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border, #334155); border-radius: 10px; font-size: 14px; color:#fff; outline:none;">
                                    <i class="fa-solid fa-eye password-toggle-icon" onclick="togglePasswordVisibility('current_password', this)"></i>
                                </div>
                            </div>

                            {{-- New Password --}}
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <label style="font-size: 13px; font-weight: 500; color: #94a3b8;">New Password</label>
                                <div class="password-field-group">
                                    <input type="password" id="password" name="password" required style="width: 100%; padding: 12px 45px 12px 16px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border, #334155); border-radius: 10px; font-size: 14px; color:#fff; outline:none;">
                                    <i class="fa-solid fa-eye password-toggle-icon" onclick="togglePasswordVisibility('password', this)"></i>
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <label style="font-size: 13px; font-weight: 500; color: #94a3b8;">Confirm Password</label>
                                <div class="password-field-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation" required style="width: 100%; padding: 12px 45px 12px 16px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border, #334155); border-radius: 10px; font-size: 14px; color:#fff; outline:none;">
                                    <i class="fa-solid fa-eye password-toggle-icon" onclick="togglePasswordVisibility('password_confirmation', this)"></i>
                                </div>
                            </div>

                        </div>
                        <div style="display: flex; justify-content: flex-end; border-top: 1px solid var(--border, #334155); padding-top: 20px;">
                            <button type="submit" style="border: none; background: #6366f1; color: #fff; padding: 12px 28px; border-radius: 10px; font-size: 14px; font-weight: 500; cursor: pointer;">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function previewProfileImage(input) {
        const placeholder = document.getElementById('profile-placeholder');
        const displayImg = document.getElementById('profile-display');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                displayImg.src = e.target.result;
                displayImg.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function switchSettingsTab(event, tabId) {
        const panels = document.querySelectorAll('.tab-panel');
        const buttons = document.querySelectorAll('.tab-btn');

        panels.forEach(panel => panel.classList.remove('active'));
        buttons.forEach(btn => btn.classList.remove('active'));

        document.getElementById(tabId).classList.add('active');
        event.currentTarget.classList.add('active');
    }

    function togglePasswordVisibility(fieldId, iconElement) {
        const passwordField = document.getElementById(fieldId);
        
        if (passwordField.type === "password") {
            passwordField.type = "text";
            iconElement.classList.remove('fa-eye');
            iconElement.classList.add('fa-eye-slash');
        } else {
            passwordField.type = "password";
            iconElement.classList.remove('fa-eye-slash');
            iconElement.classList.add('fa-eye');
        }
    }
    
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = '{{ csrf_token() }}';

        function fetchAndRenderSettings() {
            fetch('/user/notifications/edit')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const settings = data.settings;
                        document.getElementById('emailAlerts').checked = settings.email_alerts;
                        document.getElementById('labKitUpdates').checked = settings.lab_kit_updates;
                        document.getElementById('generateReport').checked = settings.generate_report;
                        document.getElementById('smsNotification').checked = settings.sms_notification;
                        document.getElementById('weeklyAnalytics').checked = settings.weekly_analytics;
                    }
                })
                .catch(err => console.error('Settings load error:', err));
        }

        document.querySelectorAll('.notif-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const settingName = this.dataset.setting; 
                const isChecked = this.checked;

                this.disabled = true;

                fetch('/user/notifications/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        setting_name: settingName,
                        value: isChecked
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Server returned an error');
                    return res.json();
                })
                .then(data => {
                    this.disabled = false;
                    console.log(data.message);
                })
                .catch(err => {
                    this.checked = !isChecked;
                    this.disabled = false;
                    alert('Could not update setting. Please try again.');
                    console.error(err);
                });
            });
        });

        fetchAndRenderSettings();
    });
</script>
@endsection