<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
	<title>@yield('title', 'The Infinity Unisex Salon and Spa')</title>
	<meta charset="utf-8">
	<meta name="description" content="@yield('meta_description', 'Professional Beauty Salon - Hair, Makeup, Nails & More')">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" type="image/x-icon" href="{{ asset('assets/admin/img/favicon/favicon.ico') }}" />
	<link rel="shortcut icon" type="image/png" href="{{ asset('assets/admin/img/favicon/favicon-32x32.png') }}" />

	<!-- Stylesheets -->
	<link rel="stylesheet" href="{{ asset('website/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('website/css/animations.css') }}">
	<link rel="stylesheet" href="{{ asset('website/css/font-awesome.css') }}">
	<link rel="stylesheet" href="{{ asset('website/css/flaticon.css') }}">
	<link rel="stylesheet" href="{{ asset('website/css/main.css') }}" class="color-switcher-link">
	<link rel="stylesheet" href="{{ asset('website/css/shop.css') }}" class="color-switcher-link">
	<link rel="stylesheet" href="{{ asset('website/css/color-override.css') }}">
	<script src="{{ asset('website/js/vendor/modernizr-custom.js') }}"></script>


	<style>
		.page_header .logo img {
			max-height: 60px;
			width: auto;
		}
	</style>
	@stack('styles')
</head>

<body>

	<div class="preloader">
		<div class="preloader_image"></div>
	</div>

	<!-- search modal -->
	<div class="modal" tabindex="-1" role="dialog" aria-labelledby="search_modal" id="search_modal">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="widget widget_search">
			<form method="get" class="searchform search-form" action="#">
				<div class="form-group">
					<input type="text" value="" name="search" class="form-control" placeholder="Search keyword" id="modal-search-input">
				</div>
				<button type="submit" class="btn">Search</button>
			</form>
		</div>
	</div>

	<!-- wrappers for visual page editor and boxed version of template -->
	<div id="canvas">
		<div id="box_wrapper">

			<!-- Top Bar -->
			<section class="page_topline ls ms s-py-5 top-1">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 text-center">
							<div class="top-includ">
								<!--modal search-->
								<span>
									<a href="#" class="search_modal_button">
										<i class="flaticon-search"></i>
									</a>
								</span>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Header -->
			<header class="page_header ls">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-8 col-xl-3 text-left">
							<a href="{{ url('/') }}" class="logo text-decoration-none">
								<span style="font-family: 'Outfit', sans-serif; font-size: 1.45rem; font-weight: 800; color: #4e5152; text-transform: uppercase; letter-spacing: 0.5px;">The Infinity <span style="color: #787169;">Salon</span></span>
							</a>
						</div>
						<div class="col-2 col-xl-7 text-center">
							<!-- main nav start -->
							<nav class="top-nav">
								<ul class="nav sf-menu">
									<li class="{{ request()->is('/') || request()->is('home') ? 'active' : '' }}">
										<a href="{{ url('/') }}">Home</a>
									</li>
									<li class="{{ request()->is('about') ? 'active' : '' }}">
										<a href="{{ url('/about') }}">About</a>
									</li>
									<li class="{{ request()->is('services*') ? 'active' : '' }}">
										<a href="{{ url('/services') }}">Services</a>
									</li>
									<li class="{{ request()->is('gallery*') ? 'active' : '' }}">
										<a href="{{ url('/gallery') }}">Gallery</a>
									</li>
									<li class="{{ request()->is('contact') ? 'active' : '' }}">
										<a href="{{ url('/contact') }}">Contact</a>
									</li>
								</ul>
							</nav>
							<!-- eof main nav -->
						</div>
						<div class="col-2 col-xl-2 text-right">
							<div class="header-includ">
								<!--modal search-->
								<span>
									<a href="#" class="search_modal_button">
										<i class="flaticon-search"></i>
									</a>
								</span>
							</div>
						</div>
					</div>
				</div>
				<!-- header toggler -->
				<span class="toggle_menu"><span></span></span>
			</header>

			<!-- Page Content -->
			@yield('content')

			<!-- Footer Gallery -->
			<section class="ls footer-gallery c-gutter-0 container-px-0">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-12 text-center">
							<div class="owl-carousel small-gallery-carousel" data-margin="0" data-responsive-lg="8" data-responsive-md="4" data-responsive-sm="3" data-responsive-xs="2" data-nav="true" data-loop="true">
								@for($i = 1; $i <= 11; $i++)
								<a href="{{ asset('website/images/square/' . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg') }}" class="photoswipe-link" data-width="800" data-height="800">
									<img src="{{ asset('website/images/square/' . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg') }}" alt="">
								</a>
								@endfor
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer -->
			<footer class="page_footer overflow-hidden ls s-pt-10 mt-10 s-overlay s-parallax">
				<div class="container">
					<div class="row">
						<div class="fw-divider-space hidden-below-xl mt-140"></div>
						<div class="fw-divider-space hidden-below-lg d-xl-none mt-110"></div>
						<div class="fw-divider-space hidden-above-lg mt-50"></div>
						<div class="col-12 text-center animate" data-animation="fadeInUp">
							<div class="widget widget_mailchimp">
								<span class="sub-title">Subscribe</span>
								<h2 class="special-heading">Newsletter</h2>
								<p>Subscribe newsletter to be updated, we promise not to spam</p>
								<form class="signup" action="#">
									<label for="mailchimp_email1">
										<span class="screen-reader-text">Subscribe:</span>
									</label>
									<i class="flaticon-envelope"></i>
									<input id="mailchimp_email1" name="email" type="email" class="form-control mailchimp_email" placeholder="Your Email">
									<button type="submit" class="search-submit">
										<i class="fa fa-pencil"></i>
									</button>
									<div class="response"></div>
								</form>
							</div>
							<div class="widget widget_social_buttons">
								<a href="#" class="fa fa-facebook border-icon rounded-icon" title="facebook"></a>
								<a href="#" class="fa fa-twitter border-icon rounded-icon" title="twitter"></a>
								<a href="#" class="fa fa-google-plus border-icon rounded-icon" title="google"></a>
								<a href="#" class="fa fa-instagram border-icon rounded-icon" title="instagram"></a>
								<a href="#" class="fa fa-youtube-play border-icon rounded-icon" title="youtube"></a>
							</div>
						</div>
						<div class="fw-divider-space hidden-below-xl mt-140"></div>
						<div class="fw-divider-space hidden-below-lg d-xl-none mt-110"></div>
						<div class="fw-divider-space hidden-above-lg mt-50"></div>
					</div>
				</div>
			</footer>

			<!-- Copyright -->
			<section class="page_copyright ls">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-md-12 text-center">
							<div class="fw-divider-space hidden-below-xl mt-65"></div>
							<div class="fw-divider-space hidden-above-xl mt-20"></div>
							<p>&copy; Copyright <span class="copyright_year">{{ date('Y') }}</span> Salon ERP. All Rights Reserved.</p>
							<div class="fw-divider-space hidden-below-xl mt-65"></div>
							<div class="fw-divider-space hidden-above-xl mt-20"></div>
						</div>
					</div>
				</div>
			</section>

		</div><!-- eof #box_wrapper -->
	</div><!-- eof #canvas -->

	<!-- Scripts -->
	<script src="{{ asset('website/js/compressed.js') }}"></script>
	<script src="{{ asset('website/js/main.js') }}"></script>

	@stack('scripts')
</body>
</html>
