@extends('layouts.admin') 

@section('content')
<div class="auth-wrapper" style="padding: 40px 0;">
    <div class="auth-card" style="max-width: 900px; margin: 0 auto;"> 
        <div class="auth-header" style="margin-bottom: 30px;">
            <h1 style="font-size: 24px; font-weight: 700;">Account Settings</h1>
            <p class="auth-subtitle">Update your profile information and security settings</p>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); color: #4ade80; padding: 15px; border-radius: 12px; border: 1px solid rgba(34, 197, 94, 0.2); margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: rgba(239, 68, 68, 0.1); color: #f87171; padding: 15px; border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.2); margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr; gap: 40px;">
            
            <section>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                    <i class="fa-solid fa-user-gear" style="color: #6366f1;"></i>
                    <h3 style="font-size: 18px; margin: 0;">Profile Information</h3>
                </div>
                
                <form action="{{ route('admin.update.profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group" style="grid-column: span 2; margin-bottom: 25px;">
                            <label class="form-label">Profile Picture</label>
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
                                    <p style="font-size: 11px; color: #94a3b8; margin-top: 5px;">Allowed JPG, PNG or GIF. Max size of 5MB</p>
                                </div>
                            </div>
                            @error('image') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', Auth::user()->name) }}" required style="padding-left: 15px;">
                            @error('name') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', Auth::user()->email) }}" required style="padding-left: 15px;">
                            @error('email') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-input" value="{{ old('phone', Auth::user()->phone) }}" placeholder="+1 234 567 890" style="padding-left: 15px;">
                            @error('phone') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-auth" style="margin-top: 20px; width: auto; padding: 12px 30px;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Profile Changes
                    </button>
                </form>
            </section>

            <hr style="border: 0; border-top: 1px solid var(--border);">

            <section>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                    <i class="fa-solid fa-shield-halved" style="color: #ef4444;"></i>
                    <h3 style="font-size: 18px; margin: 0;">Update Password</h3>
                </div>

                <form action="{{ route('admin.update.password') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-input" required placeholder="••••••••" style="padding-left: 15px;">
                            @error('current_password') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-input" required placeholder="••••••••" style="padding-left: 15px;">
                            @error('password') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-input" required placeholder="••••••••" style="padding-left: 15px;">
                        </div>
                    </div>

                    <button type="submit" class="btn-auth" style="margin-top: 20px; width: auto; padding: 12px 30px; background: #334155;">
                        <i class="fa-solid fa-key"></i> Change Password
                    </button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection