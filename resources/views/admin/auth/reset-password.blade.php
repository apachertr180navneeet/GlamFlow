@extends('admin.layouts.login_layout')
@section('content')

<div class="d-flex align-items-center justify-content-center min-vh-100" style="position: relative; overflow: hidden; padding: 20px 0;">
    
    <!-- Background Art Blobs -->
    <div style="position: absolute; width: 350px; height: 350px; background: radial-gradient(circle, rgba(218,164,100,0.15) 0%, transparent 70%); top: 10%; left: 10%; pointer-events: none; z-index: -1;"></div>
    <div style="position: absolute; width: 400px; height: 400px; background: radial-gradient(circle, rgba(232,221,180,0.2) 0%, transparent 70%); bottom: 10%; right: 10%; pointer-events: none; z-index: -1;"></div>

    <div class="card p-4" style="max-width: 440px; width: 100%; border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.3) !important;">
        <div class="card-body p-2">
            <!-- Brand Logo Header -->
            <div class="text-center mb-4">
                <a href="{{ route('admin.login') }}" class="d-inline-flex align-items-center mb-3 text-decoration-none">
                    <span style="font-family: 'Outfit', sans-serif; font-size: 2.1rem; font-weight: 800; color: #4e5152; letter-spacing: -0.5px;">The Infinity <span style="color: #787169;">Salon</span></span>
                </a>
                <h4 class="mb-1" style="color: #2C1A0E; font-weight: 700;">Reset Password 🔒</h4>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Please enter and confirm your new password below</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('admin.reset.password.post') }}" class="mb-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Min. 6 characters" required autofocus>
                    </div>
                    @if ($errors->has('password'))
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm your new password" required>
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>

                <div class="mb-0 text-center">
                    <button type="submit" class="btn btn-primary d-grid w-100 py-3">Reset Password</button>
                </div>
            </form>

            <div class="text-center">
                <a href="{{ route('admin.login') }}" class="d-flex align-items-center justify-content-center text-primary text-decoration-none gap-1">
                    <i class="bx bx-chevron-left bx-sm"></i>
                    Back to login
                </a>
            </div>
        </div>
    </div>
</div>

@endsection