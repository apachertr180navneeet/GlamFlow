<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
	<div class="app-brand demo d-flex align-items-center justify-content-between px-3">
		<a href="{{route('admin.dashboard')}}" class="app-brand-link text-decoration-none flex-grow-1 me-2 overflow-hidden">
			<span class="app-brand-text demo menu-text fw-bold text-nowrap" style="font-family: 'Outfit', sans-serif; font-size: 1.15rem; letter-spacing: -0.3px; text-transform: none; color: #4e5152;">The Infinity <span style="background: linear-gradient(135deg, #c5a059 0%, #9e7d3b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800;">Salon</span></span>
		</a>
		<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large flex-shrink-0 d-flex align-items-center justify-content-center p-2 rounded-circle d-xl-none" aria-label="Close Sidebar" style="width: 32px; height: 32px; background: rgba(197, 160, 89, 0.15);">
			<i class="bx bx-chevron-left bx-sm align-middle" style="color: #4e5152;"></i>
		</a>
	</div>
	<div class="menu-inner-shadow"></div>
	<ul class="menu-inner py-1">
		<li class="menu-header small text-uppercase"><span class="menu-header-text">CORE</span></li>
		<li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : ''}}">
			<a href="{{route('admin.dashboard')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-grid-alt"></i>
				<div>Dashboard</div>
			</a>
		</li>

		@if(auth()->user()->can('view stores') || auth()->user()->can('view branches') || auth()->user()->can('view customers') || auth()->user()->can('view birthday customers') || auth()->user()->can('view birthday msg logs') || auth()->user()->can('view staffs') || auth()->user()->can('view service categories') || auth()->user()->can('view service durations') || auth()->user()->can('view items') || auth()->user()->can('view services') || auth()->user()->can('view packages') || auth()->user()->can('view appointments') || auth()->user()->can('view attendances') || auth()->user()->can('view leave requests') || auth()->user()->can('view holidays') || auth()->user()->can('view payrolls') || auth()->user()->can('view advances') || auth()->user()->can('view laundry orders') || auth()->user()->can('view expenses') || auth()->user()->can('view feedbacks'))
		<li class="menu-header small text-uppercase"><span class="menu-header-text">MANAGEMENT</span></li>
		@endif

		@can('view stores')
		<li class="menu-item {{ request()->routeIs('admin.stores.*') ? 'active' : ''}}">
			<a href="{{route('admin.stores.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-store-alt"></i>
				<div>Stores</div>
			</a>
		</li>
		@endcan

		@can('view branches')
		<li class="menu-item {{ request()->routeIs('admin.branches.*') ? 'active' : ''}}">
			<a href="{{route('admin.branches.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-buildings"></i>
				<div>Branches</div>
			</a>
		</li>
		@endcan

		@can('view customers')
		<li class="menu-item {{ request()->routeIs('admin.customers.index') || request()->routeIs('admin.customers.create') || request()->routeIs('admin.customers.edit') || request()->routeIs('admin.customers.show') ? 'active' : ''}}">
			<a href="{{route('admin.customers.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-group"></i>
				<div>Customers</div>
			</a>
		</li>
		@endcan

		@can('view birthday customers')
		<li class="menu-item {{ request()->routeIs('admin.customers.birthdays') ? 'active' : ''}}">
			<a href="{{route('admin.customers.birthdays')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-cake"></i>
				<div>Birthday Customers</div>
			</a>
		</li>
		@endcan

		@can('view birthday msg logs')
		<li class="menu-item {{ request()->routeIs('admin.customers.birthday-msg-logs') ? 'active' : ''}}">
			<a href="{{route('admin.customers.birthday-msg-logs')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-message-dots"></i>
				<div>Birthday Msg Logs</div>
			</a>
		</li>
		@endcan

		@can('view staffs')
		<li class="menu-item {{ request()->routeIs('admin.staffs.*') ? 'active' : ''}}">
			<a href="{{route('admin.staffs.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-id-card"></i>
				<div>Staff</div>
			</a>
		</li>
		@endcan

		@can('view service categories')
		<li class="menu-item {{ request()->routeIs('admin.service-categories.*') ? 'active' : ''}}">
			<a href="{{route('admin.service-categories.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-category"></i>
				<div>Category Master</div>
			</a>
		</li>
		@endcan

		@can('view service durations')
		<li class="menu-item {{ request()->routeIs('admin.durations.*') ? 'active' : ''}}">
			<a href="{{route('admin.durations.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-time"></i>
				<div>Duration Master</div>
			</a>
		</li>
		@endcan

		@can('view items')
		<li class="menu-item {{ request()->routeIs('admin.items.*') ? 'active' : ''}}">
			<a href="{{route('admin.items.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-cube"></i>
				<div>Item Master</div>
			</a>
		</li>
		@endcan

		@can('view services')
		<li class="menu-item {{ request()->routeIs('admin.services.*') ? 'active' : ''}}">
			<a href="{{route('admin.services.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-cut"></i>
				<div>Services</div>
			</a>
		</li>
		@endcan

		@can('view packages')
		<li class="menu-item {{ request()->routeIs('admin.packages.*') ? 'active' : ''}}">
			<a href="{{route('admin.packages.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-package"></i>
				<div>Packages</div>
			</a>
		</li>
		@endcan
		
		@can('view appointments')
		<li class="menu-item {{ request()->routeIs('admin.appointments.*') ? 'active' : ''}}">
			<a href="{{route('admin.appointments.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-calendar-event"></i>
				<div>Appointments</div>
			</a>
		</li>
		@endcan

		@can('view attendances')
		<li class="menu-item {{ request()->routeIs('admin.attendances.*') ? 'active' : ''}}">
			<a href="{{route('admin.attendances.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-check-circle"></i>
				<div>Attendance</div>
			</a>
		</li>
		@endcan

		@can('view leave requests')
		<li class="menu-item {{ request()->routeIs('admin.leave-requests.*') ? 'active' : ''}}">
			<a href="{{route('admin.leave-requests.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-calendar-minus"></i>
				<div>Leave Requests</div>
			</a>
		</li>
		@endcan

		@can('view holidays')
		<li class="menu-item {{ request()->routeIs('admin.holidays.*') ? 'active' : ''}}">
			<a href="{{route('admin.holidays.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-calendar-star"></i>
				<div>Holiday Master</div>
			</a>
		</li>
		@endcan

		@can('view payrolls')
		<li class="menu-item {{ request()->routeIs('admin.payrolls.*') ? 'active' : ''}}">
			<a href="{{route('admin.payrolls.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-wallet"></i>
				<div>Payroll</div>
			</a>
		</li>
		@endcan

		@can('view advances')
		<li class="menu-item {{ request()->routeIs('admin.advances.*') ? 'active' : ''}}">
			<a href="{{route('admin.advances.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-money"></i>
				<div>Employee Advances</div>
			</a>
		</li>
		@endcan

		@can('view laundry orders')
		<li class="menu-item {{ request()->routeIs('admin.laundry-orders.*') ? 'active' : ''}}">
			<a href="{{route('admin.laundry-orders.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-droplet"></i>
				<div>Laundry</div>
			</a>
		</li>
		@endcan

		@can('view expenses')
		<li class="menu-item {{ request()->routeIs('admin.expenses.*') || request()->routeIs('admin.expense-categories.*') ? 'active' : ''}}">
			<a href="{{route('admin.expenses.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-credit-card"></i>
				<div>Expenses</div>
			</a>
		</li>
		@endcan

		@can('view feedbacks')
		<li class="menu-item {{ request()->routeIs('admin.feedbacks.*') ? 'active' : ''}}">
			<a href="{{route('admin.feedbacks.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-star"></i>
				<div>Feedback</div>
			</a>
		</li>
		@endcan

		@if(auth()->user()->can('view crm') || auth()->user()->can('view sms logs') || auth()->user()->can('view whatsapp logs') || auth()->user()->can('view email logs'))
		<li class="menu-header small text-uppercase"><span class="menu-header-text">CRM</span></li>

		<li class="menu-item {{ request()->routeIs('admin.crm.sms.*') ? 'active' : ''}}">
			<a href="{{route('admin.crm.sms.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-message-square-dots"></i>
				<div>SMS Logs</div>
			</a>
		</li>

		<li class="menu-item {{ request()->routeIs('admin.crm.whatsapp.*') ? 'active' : ''}}">
			<a href="{{route('admin.crm.whatsapp.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bxl-whatsapp"></i>
				<div>WhatsApp Logs</div>
			</a>
		</li>

		<li class="menu-item {{ request()->routeIs('admin.crm.email.*') ? 'active' : ''}}">
			<a href="{{route('admin.crm.email.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-envelope"></i>
				<div>Email Logs</div>
			</a>
		</li>
		@endif

		@if(auth()->user()->can('view reports') || auth()->user()->can('view sales reports') || auth()->user()->can('view customer reports') || auth()->user()->can('view staff reports') || auth()->user()->can('view expense reports') || auth()->user()->can('view laundry report') || auth()->user()->can('view store ledger') || auth()->user()->can('view package usage'))
		<li class="menu-header small text-uppercase"><span class="menu-header-text">REPORTS</span></li>

		@if(auth()->user()->can('view reports') || auth()->user()->can('view sales reports'))
		<li class="menu-item {{ request()->routeIs('admin.reports.sales') ? 'active' : ''}}">
			<a href="{{route('admin.reports.sales')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-line-chart"></i>
				<div>Sales Reports</div>
			</a>
		</li>
		@endif

		@if(auth()->user()->can('view reports') || auth()->user()->can('view customer reports'))
		<li class="menu-item {{ request()->routeIs('admin.reports.customers') ? 'active' : ''}}">
			<a href="{{route('admin.reports.customers')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-user-pin"></i>
				<div>Customer Reports</div>
			</a>
		</li>
		@endif

		@if(auth()->user()->can('view reports') || auth()->user()->can('view staff reports'))
		<li class="menu-item {{ request()->routeIs('admin.reports.staff') ? 'active' : ''}}">
			<a href="{{route('admin.reports.staff')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-group"></i>
				<div>Staff Reports</div>
			</a>
		</li>
		@endif

		@if(auth()->user()->can('view reports') || auth()->user()->can('view expense reports'))
		<li class="menu-item {{ request()->routeIs('admin.reports.expenses') ? 'active' : ''}}">
			<a href="{{route('admin.reports.expenses')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-credit-card-front"></i>
				<div>Expense Reports</div>
			</a>
		</li>
		@endif

		@if(auth()->user()->can('view reports') || auth()->user()->can('view laundry report'))
		<li class="menu-item {{ request()->routeIs('admin.reports.laundry') ? 'active' : ''}}">
			<a href="{{route('admin.reports.laundry')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-droplet"></i>
				<div>Laundry Report</div>
			</a>
		</li>
		@endif

		@if(auth()->user()->can('view reports') || auth()->user()->can('view store ledger'))
		<li class="menu-item {{ request()->routeIs('admin.reports.store-ledger') ? 'active' : ''}}">
			<a href="{{route('admin.reports.store-ledger')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-book-content"></i>
				<div>Store Ledger</div>
			</a>
		</li>
		@endif

		@if(auth()->user()->can('view reports') || auth()->user()->can('view package usage'))
		<li class="menu-item {{ request()->routeIs('admin.reports.package-usage') ? 'active' : ''}}">
			<a href="{{route('admin.reports.package-usage')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-pie-chart-alt-2"></i>
				<div>Package Usage</div>
			</a>
		</li>
		@endif

		@if(auth()->user()->can('view reports') || auth()->user()->can('view attendances'))
		<li class="menu-item {{ request()->routeIs('admin.attendances.report') ? 'active' : ''}}">
			<a href="{{route('admin.attendances.report')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-calendar-check"></i>
				<div>Attendance Report</div>
			</a>
		</li>
		@endif

		@if(auth()->user()->can('view reports') || auth()->user()->can('view sales reports') || auth()->user()->can('view customer reports'))
		<li class="menu-item {{ request()->routeIs('admin.reports.outstanding') ? 'active' : ''}}">
			<a href="{{route('admin.reports.outstanding')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-error-circle"></i>
				<div>Outstanding Report</div>
			</a>
		</li>
		@endif
		@endif

		@if(auth()->user()->can('view users') || auth()->user()->can('view roles') || auth()->user()->can('view permissions') || auth()->user()->can('view settings') || auth()->user()->can('view activity logs'))
		<li class="menu-header small text-uppercase"><span class="menu-header-text">ADMIN</span></li>

		@can('view users')
		<li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : ''}}">
			<a href="{{route('admin.users.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-user-circle"></i>
				<div>Users</div>
			</a>
		</li>
		@endcan

		@can('view roles')
		<li class="menu-item {{ request()->routeIs('admin.roles.*') ? 'active' : ''}}">
			<a href="{{route('admin.roles.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-lock"></i>
				<div>Roles</div>
			</a>
		</li>
		@endcan

		@can('view permissions')
		<li class="menu-item {{ request()->routeIs('admin.permissions.*') ? 'active' : ''}}">
			<a href="{{route('admin.permissions.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-key"></i>
				<div>Permissions</div>
			</a>
		</li>
		@endcan

		@can('view activity logs')
		<li class="menu-item {{ request()->routeIs('admin.activity-logs.*') ? 'active' : ''}}">
			<a href="{{route('admin.activity-logs.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-history"></i>
				<div>Activity Logs</div>
			</a>
		</li>
		@endcan

		@can('view settings')
		<li class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : ''}}">
			<a href="{{route('admin.settings.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-cog"></i>
				<div>Company Settings</div>
			</a>
		</li>
		@endcan
		@endif
	</ul>
</aside>
