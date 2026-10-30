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
                <h4 class="mb-1" style="color: #2C1A0E; font-weight: 700;">Welcome back! 👋</h4>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Sign-in to access your operator dashboard</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('admin.login.post') }}" class="mb-3" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email or Username</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required autofocus />
                    </div>
                    @error('email')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <label class="form-label mb-0" for="password">Password</label>
                        <a href="{{ route('admin.forget.password.get') }}" class="text-primary text-decoration-none" style="font-size: 0.85rem;"><small>Forgot Password?</small></a>
                    </div>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                        <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                        <span class="input-group-text cursor-pointer toggle-password" style="cursor: pointer;"><i class="bx bx-hide"></i></span>
                    </div>
                    @error('password')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100 py-3" type="submit">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Password visibility toggle
    const toggleBtn = document.querySelector(".toggle-password");
    const passwordInput = document.querySelector("#password");
    
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener("click", function() {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            
            // Toggle icon classes
            const icon = toggleBtn.querySelector("i");
            if (icon) {
                if (type === "text") {
                    icon.classList.remove("bx-hide");
                    icon.classList.add("bx-show");
                } else {
                    icon.classList.remove("bx-show");
                    icon.classList.add("bx-hide");
                }
            }
        });
    }
});
</script>
@endsection
