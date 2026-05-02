@extends('layouts.admin') 

@section('content')
<div class="auth-wrapper" style="padding: 50px 0;">
    <div class="auth-card" style="max-width: 800px;"> 
        <div class="auth-header">
            <h1>Update Profile</h1>
            <p class="auth-subtitle">Manage your personal information and profile picture</p>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.2); color: #4ade80; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.update.profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', Auth::user()->name) }}" required>
                    @error('name') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', Auth::user()->email) }}" required>
                    @error('email') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', Auth::user()->phone) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-input" value="{{ old('age', Auth::user()->age) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-input" style="background: #1e293b;">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ Auth::user()->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ Auth::user()->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ Auth::user()->gender == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Height</label>
                    <input type="text" name="height" class="form-input" value="{{ old('height', Auth::user()->height) }}" placeholder="e.g. 5'8''">
                </div>

                <div class="form-group">
                    <label class="form-label">Weight</label>
                    <input type="text" name="weight" class="form-input" value="{{ old('weight', Auth::user()->weight) }}" placeholder="e.g. 70kg">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Profile Picture</label>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        @if(Auth::user()->image)
                            <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="Profile" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #6366f1;">
                        @else
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: #334155; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-user" style="font-size: 30px; color: #94a3b8;"></i>
                            </div>
                        @endif
                        <input type="file" name="image" class="form-input">
                    </div>
                    @error('image') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

            </div>

            <button type="submit" class="btn-auth" style="margin-top: 30px;">Save Changes</button>
        </form>
    </div>
</div>
@endsection