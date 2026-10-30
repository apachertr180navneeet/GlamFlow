<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
	id="layout-navbar">
	<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
		<a class="nav-item nav-link px-2 py-1 rounded-circle me-xl-4 d-flex align-items-center justify-content-center" href="javascript:void(0)" aria-label="Open Sidebar Menu" style="background: rgba(197, 160, 89, 0.15); width: 38px; height: 38px;">
			<i class="bx bx-menu bx-sm" style="color: #4e5152; font-size: 1.5rem;"></i>
		</a>
	</div>

	<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
		<!-- Search / Date Pill -->
		<div class="navbar-nav align-items-center">
			<div class="nav-item d-flex align-items-center px-2 px-sm-3 py-1 rounded-pill" style="background: rgba(197, 160, 89, 0.12); border: 1px solid rgba(197, 160, 89, 0.28); color: #4e5152; font-weight: 600; font-size: 0.85rem;">
				<i class="bx bx-calendar fs-5 me-1 me-sm-2" style="color: #9e7d3b;"></i>
				<span class="d-none d-sm-inline">{{ date('D') }}, {{ date('d M Y') }}</span>
				<span class="d-inline d-sm-none" style="font-size: 0.75rem;">{{ date('d M') }}</span>
			</div>
		</div>
		<!-- /Search / Date Pill -->

		<ul class="navbar-nav flex-row align-items-center ms-auto">
			<!-- User -->
			<li class="nav-item navbar-dropdown dropdown-user dropdown">
				<a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
					data-bs-toggle="dropdown">
					<div class="avatar avatar-online">
						@if(Auth::user() && !empty(Auth::user()->avatar) && file_exists(public_path('/').Auth::user()->avatar))
							<img src="{{asset(Auth::user()->avatar)}}" alt="admin" class="w-px-40 h-auto rounded-circle" style="object-fit: cover;" />
						@else
							<img src="{{asset('assets/admin/img/avatars/1.png')}}" alt="admin" class="w-px-40 h-auto rounded-circle" />
						@endif
					</div>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					<li>
						<a class="dropdown-item" href="{{route('admin.profile')}}">
							<div class="d-flex">
								<div class="flex-shrink-0 me-3">
									<div class="avatar avatar-online">
										@if(Auth::user() && !empty(Auth::user()->avatar) && file_exists(public_path('/').Auth::user()->avatar))
		                                    <img src="{{asset(Auth::user()->avatar)}}" alt="User Image" class="w-px-40 h-auto rounded-circle" style="object-fit: cover;">
		                                @else
		                                    <img src="{{asset('assets/admin/img/avatars/1.png')}}"  alt="User Image" class="w-px-40 h-auto rounded-circle">
		                                @endif
									</div>
								</div>
								<div class="flex-grow-1">
									<span class="fw-medium d-block">{{Auth::user()->full_name}}</span>
									<small class="text-muted">{{ucfirst(Auth::user()->role)}}</small>
								</div>
							</div>
						</a>
					</li>
					<li>
						<div class="dropdown-divider"></div>
					</li>
					<li>
						<a class="dropdown-item" href="{{route('admin.profile')}}">
							<i class="bx bx-user me-2"></i>
							<span class="align-middle">My Profile</span>
						</a>
					</li>
					<li>
						<a class="dropdown-item" href="{{route('admin.change.password')}}">
							<i class="bx bx-key me-2"></i>
							<span class="align-middle">Change Password</span>
						</a>
					</li>
					<li>
						<div class="dropdown-divider"></div>
					</li>
					<li>
						<a class="dropdown-item" href="{{route('admin.logout')}}">
							<i class="bx bx-power-off me-2"></i>
							<span class="align-middle">Log Out</span>
						</a>
					</li>
				</ul>
			</li>
			<!--/ User -->
		</ul>
	</div>
</nav>