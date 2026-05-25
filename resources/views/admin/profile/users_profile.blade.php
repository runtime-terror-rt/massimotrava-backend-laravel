@extends('layouts.admin')
@section('title', __('messages.settings_meta_title'))

@section('content')
<div class="auth-wrapper" style="padding: 40px 0;">
    <div class="auth-card" style="max-width: 900px; margin: 0 auto;"> 
        
        {{-- Settings Page Header --}}
        <div class="auth-header" style="margin-bottom: 30px;">
            <h1 style="font-size: 24px; font-weight: 700;">{{ __('messages.settings_header') }}</h1>
            <p class="auth-subtitle">{{ __('messages.settings_subtitle') }}</p>
        </div>

        {{-- Success Notification Toast --}}
        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); color: #4ade80; padding: 15px; border-radius: 12px; border: 1px solid rgba(34, 197, 94, 0.2); margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Error Notification Toast --}}
        @if(session('error'))
            <div style="background: rgba(239, 68, 68, 0.1); color: #f87171; padding: 15px; border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.2); margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr; gap: 40px;">
            
            {{-- Section: Profile Information --}}
            <section>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                    <i class="fa-solid fa-user-gear" style="color: #6366f1;"></i>
                    <h3 style="font-size: 18px; margin: 0;">{{ __('messages.sect_profile_info') }}</h3>
                </div>
                
                <form action="{{ route('admin.update.profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        
                        {{-- Avatar Management Stream --}}
                        <div class="form-group" style="grid-column: span 2; margin-bottom: 25px;">
                            <label class="form-label">{{ __('messages.lbl_profile_picture') }}</label>
                            <div style="display: flex; align-items: center; gap: 20px; background: var(--surface); padding: 15px; border-radius: 15px; border: 1px dashed var(--border);">
                                <div style="position: relative;">
                                    @if(Auth::user()->image)
                                        <img src="{{ Storage::url(Auth::user()->image) }}" alt="Profile" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #6366f1; padding: 3px;">
                                    @else
                                        <div style="width: 100px; height: 100px; border-radius: 50%; background: #334155; display: flex; align-items: center; justify-content: center; border: 3px solid #475569;">
                                            <i class="fa-solid fa-user" style="font-size: 40px; color: #94a3b8;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div style="flex: 1;">
                                    <input type="file" name="image" class="form-input" style="padding: 8px;">
                                    <p style="font-size: 11px; color: #94a3b8; margin-top: 5px;">{{ __('messages.lbl_avatar_hint') }}</p>
                                </div>
                            </div>
                            @error('image') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Name Input --}}
                        <div class="form-group">
                            <label class="form-label">{{ __('messages.lbl_full_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', Auth::user()->name) }}" required style="padding-left: 15px;">
                            @error('name') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email Input --}}
                        <div class="form-group">
                            <label class="form-label">{{ __('messages.lbl_email_address') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', Auth::user()->email) }}" required style="padding-left: 15px;">
                            @error('email') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Phone Input --}}
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">{{ __('messages.lbl_phone_number') }}</label>
                            <input type="text" name="phone" class="form-input" value="{{ old('phone', Auth::user()->phone) }}" placeholder="+1 234 567 890" style="padding-left: 15px;">
                            @error('phone') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-auth" style="margin-top: 20px; width: auto; padding: 12px 30px;">
                        <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('messages.btn_save_profile_changes') }}
                    </button>
                </form>
            </section>

            <hr style="border: 0; border-top: 1px solid var(--border);">

            {{-- Section: Update Password --}}
            <section>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                    <i class="fa-solid fa-shield-halved" style="color: #ef4444;"></i>
                    <h3 style="font-size: 18px; margin: 0;">{{ __('messages.sect_update_password') }}</h3>
                </div>

                <form action="{{ route('admin.update.password') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        
                        {{-- Current Password --}}
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">{{ __('messages.lbl_current_password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-input" required placeholder="••••••••" style="padding-left: 15px;">
                            @error('current_password') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        {{-- New Password --}}
                        <div class="form-group">
                            <label class="form-label">{{ __('messages.lbl_new_password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-input" required placeholder="••••••••" style="padding-left: 15px;">
                            @error('password') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="form-group">
                            <label class="form-label">{{ __('messages.lbl_confirm_new_password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-input" required placeholder="••••••••" style="padding-left: 15px;">
                        </div>
                    </div>

                    <button type="submit" class="btn-auth" style="margin-top: 20px; width: auto; padding: 12px 30px; background: #334155;">
                        <i class="fa-solid fa-key me-1"></i> {{ __('messages.btn_change_password') }}
                    </button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection