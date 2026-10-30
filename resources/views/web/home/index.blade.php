@extends('web.layouts.app')

@section('title', 'The Infinity Unisex Salon and Spa')
@section('meta_description', 'Welcome to our Beauty Salon - Professional Hair, Makeup, Nails, Tattoo & more services')

@section('content')

<!-- Hero Slider -->
<section class="page_slider">
	<div class="flexslider nav-true">
		<ul class="slides">
			<li class="ls cover-image">
				<img src="{{ asset('website/images/slide01.jpg') }}" alt="">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="intro_layers_wrapper">
								<div class="intro_layers">
									<div class="intro_layer" data-animation="fadeInLeft">
										<h2 class="intro_featured_word">beauty</h2>
									</div>
									<div class="intro_layer" data-animation="fadeInLeft">
										<img src="{{ asset('website/images/person1.png') }}" alt="" class="intro_image">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</li>

			<li class="ls cover-image">
				<img src="{{ asset('website/images/slide02.jpg') }}" alt="">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="intro_layers_wrapper">
								<div class="intro_layers">
									<div class="intro_layer" data-animation="fadeInLeft">
										<h2 class="intro_featured_word">barber</h2>
									</div>
									<div class="intro_layer text-right" data-animation="fadeInLeft">
										<img src="{{ asset('website/images/person2.png') }}" alt="" class="intro_image2">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</li>

			<li class="ls cover-image">
				<img src="{{ asset('website/images/slide03.jpg') }}" alt="">
			</li>
		</ul>
	</div>
	<div class="prev-slide"></div>
	<div class="next-slide"></div>
</section>

<!-- Gallery Grid -->
<section class="main-gallery container-px-0 mt-10">
	<div class="container-fluid">
		<div class="row isotope-wrapper masonry-layout c-gutter-10 c-mb-10">

			<div class="col-xl-2 col-sm-6">
				<div class="vertical-item item-gallery text-center ds">
					<div class="item-media">
						<img src="{{ asset('website/images/gallery/01.jpg') }}" class="rounded-0" alt="img">
						<div class="media-links">
							<a class="abs-link" title="" href="#"></a>
						</div>
						<div class="galery-vertical">
							<p>Microblading</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-2 col-sm-6">
				<div class="vertical-item item-gallery text-center ds">
					<div class="item-media h-100 w-100 d-block">
						<img src="{{ asset('website/images/gallery/02.jpg') }}" class="rounded-0" alt="img">
						<div class="media-links">
							<a class="abs-link" title="" href="#"></a>
						</div>
						<div class="galery-vertical">
							<p>Piercing</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-4 col-sm-6">
				<div class="vertical-item item-gallery text-center ds">
					<div class="item-media h-100 w-100 d-block">
						<img src="{{ asset('website/images/gallery/03.jpg') }}" class="rounded-0" alt="img">
						<div class="media-links">
							<a class="abs-link" title="" href="#"></a>
						</div>
						<div class="galery-gorizontal">
							<h3>Manicure
								& Pedicure Services</h3>
							<p>Paraffin Nails, Glitter or Nail Arts, Manicure</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-4 col-sm-6">
				<div class="vertical-item item-gallery text-center ds">
					<div class="item-media h-100 w-100 d-block">
						<img src="{{ asset('website/images/gallery/04.jpg') }}" class="rounded-0" alt="img">
						<div class="media-links">
							<a class="abs-link" title="" href="#"></a>
						</div>
						<div class="galery-gorizontal gallery-center">
							<h3>Hair Stylist</h3>
							<p>Hair Cut, Hair Wash, Beard, Mustache</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-2 col-sm-6">
				<div class="vertical-item item-gallery text-center ds">
					<div class="item-media h-100 w-100 d-block">
						<img src="{{ asset('website/images/gallery/06.jpg') }}" class="rounded-0" alt="img">
						<div class="media-links">
							<a class="abs-link" title="" href="#"></a>
						</div>
						<div class="galery-vertical title-right">
							<p>Tanning</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-2 col-sm-6">
				<div class="vertical-item item-gallery text-center ds">
					<div class="item-media h-100 w-100 d-block">
						<img src="{{ asset('website/images/gallery/07.jpg') }}" class="rounded-0" alt="img">
						<div class="media-links">
							<a class="abs-link" title="" href="#"></a>
						</div>
						<div class="galery-vertical title-right">
							<p>Makeup</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-4 col-sm-6">
				<div class="vertical-item item-gallery text-center ds">
					<div class="item-media h-100 w-100 d-block">
						<img src="{{ asset('website/images/gallery/05.jpg') }}" class="rounded-0" alt="img">
						<div class="media-links">
							<a class="abs-link" title="" href="#"></a>
						</div>
						<div class="galery-gorizontal gallery-center">
							<h3>Tattoo Salon</h3>
							<p>Micriblading, Piercing, Modifications</p>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<!-- Welcome Section -->
<section class="ls hello s-pt-50 s-pt-lg-100 s-pt-xl-150 c-mb-30 c-gutter-60">
	<div class="container">
		<div class="row">
			<div class="col-5 d-none d-md-block">
				<img src="{{ asset('website/images/parallax/hello-bg.png') }}" alt="">
			</div>
			<div class="col-md-7 col-12">
				<div class="divider-60 d-none d-xl-block"></div>
				<div class="title-section text-center text-md-left">
					<span class="sub-title absolute-subtitle">welcome</span>
					<h3 class="special-heading"> <span><span>B</span>eautiqua <br></span>Beauty Salon</h3>
				</div>
				<div class="divider-60 d-none d-xl-block"></div>
				<div class="row">
					<div class="col-lg-6">
						<p class="text-center text-md-left">Consetetur sadipscing elitr, sed nonumy eirmod tempor ut labore dolore magna aliquyam erat, sed diam voluptua at vero eos et accusam.</p>
					</div>
					<div class="col-lg-6">
						<p class="text-center text-md-left">Lorem ipsum dolamet consetetur elitrd diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat sed diam voluptua.</p>
					</div>
				</div>
				<div class="divider-40 d-none d-xl-block"></div>
				<div class="row c-gutter-30 justify-content-start">
					<div class="owl-carousel" data-loop="true" data-margin="30" data-nav="false" data-dots="true" data-center="false" data-items="1" data-autoplay="false" data-responsive-xs="1" data-responsive-sm="1" data-responsive-md="2" data-responsive-lg="3">
						<div class="step-item">
							<div class="hello-img text-center">
								<img src="{{ asset('website/images/square/05.jpg') }}" width="200" height="200" alt="">
								<span>Haircut</span>
							</div>
							<div class="hello-content text-center">
								<h3>01</h3>
								<p>Lorem ipsum dolor sit amet, consetetur sadipscing.</p>
							</div>
						</div>
						<div class="step-item">
							<div class="hello-item">
								<div class="hello-img text-center">
									<img src="{{ asset('website/images/square/12.jpg') }}" width="200" height="200" alt="">
									<span>Makeup</span>
								</div>
								<div class="hello-content text-center">
									<h3>02</h3>
									<p>Elsed diam nonumy eirmod tempor invidut labore.</p>
								</div>
							</div>
						</div>
						<div class="step-item">
							<div class="hello-item">
								<div class="hello-img text-center">
									<img src="{{ asset('website/images/square/13.jpg') }}" width="200" height="200" alt="">
									<span>Tanning</span>
								</div>
								<div class="hello-content text-center">
									<h3>03</h3>
									<p>Magna aliquyam erat, sed diam voluptua vero eos et.</p>
								</div>
							</div>
						</div>
						<div class="step-item">
							<div class="hello-item">
								<div class="hello-img text-center">
									<img src="{{ asset('website/images/square/01.jpg') }}" width="200" height="200" alt="">
									<span>Tattoo</span>
								</div>
								<div class="hello-content text-center">
									<h3>04</h3>
									<p>Magna aliquyam erat, sed diam voluptua vero eos et.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Video Section -->
<section class="ls ms s-overlay s-parallax video-section s-pt-295 s-pt-sm-410 s-pt-md-360 s-pb-md-100 s-pt-lg-395 s-pb-lg-150 s-pt-xl-430 s-pb-xl-230 s-pb-60">
	<div class="container">
		<div class="row">
			<div class="col-xl-2">
				<h3 class="special-heading video-title text-center text-xl-left">Our Video Presentation</h3>
			</div>
			<div class="col-xl-10">
				<div class="owl-carousel" data-loop="true" data-margin="30" data-nav="true" data-dots="true" data-center="false" data-items="1" data-autoplay="false" data-responsive-xs="1" data-responsive-sm="1" data-responsive-md="1" data-responsive-lg="1">
					<div class="video-simple">
						<a href="{{ asset('website/images/parallax/video_background_1.jpg') }}" class="photoswipe-link" data-width="800" data-height="800" data-iframe="https://www.youtube.com/embed/mcixldqDIEQ">
							<img src="{{ asset('website/images/parallax/video_background_1.jpg') }}" alt="img">
						</a>
					</div>
					<div class="video-simple">
						<a href="{{ asset('website/images/parallax/video_background_2.jpg') }}" class="photoswipe-link" data-width="800" data-height="800" data-iframe="https://www.youtube.com/embed/mcixldqDIEQ">
							<img src="{{ asset('website/images/parallax/video_background_2.jpg') }}" alt="img">
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Appointment Section -->
<section class="ls appointment s-pt-60 s-pt-md-0 container-px-xl-165 container-px-lg-40 container-px-md-10">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-6 col-xl-6 d-none d-md-block">
				<img src="{{ asset('website/images/parallax/appoitment-bg.jpg') }}" alt="">
			</div>
			<div class="col-12 col-md-6 col-xl-6">
				<form class="appointment-form c-mb-20 c-gutter-20" method="post" action="#">
					@csrf
					<div class="divider-150 d-none d-xl-block"></div>
					<span class="sub-title text-center text-md-left">Client's Quick</span>
					<h3 class="special-heading text-center text-md-left">Appointment</h3>
					<div class="divider-70 d-none d-xl-block"></div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<i class="flaticon-profile"></i>
								<input type="text" name="name" class="form-control" placeholder="full name">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<i class="flaticon-clock"></i>
								<input type="tel" name="phone" class="form-control" placeholder="time">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<i class="flaticon-cut"></i>
								<input type="text" name="city" class="form-control" placeholder="service">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<i class="flaticon-clip"></i>
								<textarea rows="5" cols="45" name="message" class="form-control" placeholder="extra notes"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 text-center text-md-left">
							<div class="divider-60 d-none d-xl-block"></div>
							<div class="divider-20 d-xl-none"></div>
							<div class="form-group justify-content-center justify-content-md-start">
								<button type="submit" class="btn btn-outline-maincolor">make Appointment</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<!-- Services Section -->
<section class="ls s-pt-60 s-pt-lg-110 s-pt-xl-145 s-pb-60 s-pb-lg-70 s-pb-xl-110 service">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-6 text-center text-lg-left">
				<span class="sub-title">Featured</span>
				<h3 class="special-heading">Most Popular Services</h3>
				<p>Consetetur sadipscing elitr, sed nonumy eirmod tempor invidunt ut labore dolore.</p>
				<div class="divider-110 d-none d-xl-block"></div>
				<div class="divider-40 d-xl-none"></div>
				<div class="sevices-single">
					<div class="sevices-single__image">
						<a href="#"><img src="{{ asset('website/images/services/01.jpg') }}" width="500" height="600" alt=""></a>
					</div>
					<a href="#">
						<h4>Professional Makeup</h4>
					</a>
					<a href="#"><span>Makeup</span></a>
				</div>
				<div class="sevices-single">
					<div class="sevices-single__image">
						<a href="#"><img src="{{ asset('website/images/services/02.jpg') }}" width="400" height="480" alt=""></a>
					</div>
					<a href="#">
						<h4>Tattoo & Piercing</h4>
					</a>
					<a href="#"><span>Modifications</span></a>
				</div>
			</div>
			<div class="col-12 col-lg-6 text-center text-lg-right">
				<div class="sevices-single text-left">
					<div class="sevices-single__image">
						<a href="#"><img src="{{ asset('website/images/services/03.jpg') }}" width="400" height="480" alt=""></a>
					</div>
					<a href="#">
						<h4>Beard Cutting Experts</h4>
					</a>
					<a href="#"><span>Hair</span></a>
				</div>
				<div class="sevices-single text-left">
					<div class="sevices-single__image">
						<a href="#"><img src="{{ asset('website/images/services/04.jpg') }}" width="500" height="600" alt=""></a>
					</div>
					<a href="#">
						<h4>Manicure & Pedicure Services</h4>
					</a>
					<a href="#"><span>Nails</span></a>
				</div>
				<div class="services-button text-center text-lg-left">
					<a href="#" class="btn btn-outline-maincolor">all services</a>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Portfolio Section -->
<section class="ls ms portfolio-section s-pt-60 s-pb-60 s-pt-lg-110 s-pb-lg-90 s-pt-xl-150 s-pb-xl-140 s-overlay s-parallax container-px-xl-165 container-px-lg-100 container-px-md-10 c-gutter-70">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-5 text-center text-lg-left">
				<div class="title-portfolio">
					<span class="sub-title">Check Out</span>
					<h3 class="special-heading">Our
						Portfolio</h3>
					<p>Stet clita kasd gubergren, no takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor.</p>
				</div>
			</div>
			<div class="col-xl-7">
				<div class="owl-carousel" data-loop="true" data-margin="30" data-nav="true" data-dots="true" data-center="false" data-items="1" data-autoplay="false" data-responsive-xs="1" data-responsive-sm="1" data-responsive-md="1" data-responsive-lg="1" data-filters=".portfolio-filters">
					<div class="posrtfolio-single hair other">
						<img src="{{ asset('website/images/portfolio/01.jpg') }}" alt="">
					</div>
					<div class="posrtfolio-single hair makeup">
						<img src="{{ asset('website/images/portfolio/02.jpg') }}" alt="">
					</div>
					<div class="posrtfolio-single makeup nails">
						<img src="{{ asset('website/images/portfolio/03.jpg') }}" alt="">
					</div>
					<div class="posrtfolio-single nails">
						<img src="{{ asset('website/images/portfolio/04.jpg') }}" alt="">
					</div>
					<div class="posrtfolio-single other">
						<img src="{{ asset('website/images/portfolio/05.jpg') }}" alt="">
					</div>
				</div>
				<div class="filters portfolio-filters text-lg-right">
					<a href="#" data-filter="*" class="active selected">All</a>
					<a href="#" data-filter=".hair">Hair</a>
					<a href="#" data-filter=".makeup">Makeup</a>
					<a href="#" data-filter=".nails">Nails</a>
					<a href="#" data-filter=".other">Other</a>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Testimonials Section -->
<section class="ls quote-section s-py-60 s-pt-lg-90 s-pb-lg-100 s-pt-xl-160 s-pb-xl-150">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="owl-carousel" data-loop="true" data-margin="30" data-nav="true" data-dots="true" data-center="false" data-items="1" data-autoplay="false" data-responsive-xs="1" data-responsive-sm="1" data-responsive-md="1" data-responsive-lg="1">
					<div class="quote-item">
						<div class="quote__image">
							<img src="{{ asset('website/images/square/08.jpg') }}" alt="">
						</div>
						<blockquote>
							<footer>
								<cite>Jesse D. Caballero, <span>Makeup Client</span></cite>
							</footer>
							<p>Lorem ipsum dolor amet, consetetur sadipscing elitr sdiam nonumy eirmod tempor invidunt labore dolore magna aliquyam erat sed diam.</p>
							<img src="{{ asset('website/images/signature.png') }}" alt="">
						</blockquote>
					</div>
					<div class="quote-item">
						<div class="quote__image">
							<img src="{{ asset('website/images/square/07.jpg') }}" alt="">
						</div>
						<blockquote>
							<footer>
								<cite>Jesse D. Caballero, <span>Makeup Client</span></cite>
							</footer>
							<p>Lorem ipsum dolor amet, consetetur sadipscing elitr sdiam nonumy eirmod tempor invidunt labore dolore magna aliquyam erat sed diam.</p>
							<img src="{{ asset('website/images/signature.png') }}" alt="">
						</blockquote>
					</div>
					<div class="quote-item">
						<div class="quote__image">
							<img src="{{ asset('website/images/square/06.jpg') }}" alt="">
						</div>
						<blockquote>
							<footer>
								<cite>Jesse D. Caballero, <span>Makeup Client</span></cite>
							</footer>
							<p>Lorem ipsum dolor amet, consetetur sadipscing elitr sdiam nonumy eirmod tempor invidunt labore dolore magna aliquyam erat sed diam.</p>
							<img src="{{ asset('website/images/signature.png') }}" alt="">
						</blockquote>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Blog Section -->
<section class="ls post-section s-pt-xl-0 s-pb-50 s-pb-lg-100 s-pb-xl-140 container-px-xl-0 container-px-xl-20">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="owl-carousel" data-loop="true" data-margin="30" data-nav="true" data-dots="true" data-center="true" data-items="1" data-autoplay="false" data-responsive-xs="1" data-responsive-sm="1" data-responsive-md="3" data-responsive-lg="3">
					<article class="text-center text-md-left vertical-item post type-post status-publish format-standard">
						<div class="item-media post-thumbnail">
							<a href="#">
								<img src="{{ asset('website/images/blog/10.jpg') }}" alt="img">
							</a>
						</div>
						<div class="item-content">
							<header class="entry-header">
								<a href="#">Barber</a>
							</header>
							<div class="entry-content">
								<h3 class="entry-title">
									<a href="#" rel="bookmark">Lorem ipsum dolor sit amet, consetetur</a>
								</h3>
							</div>
						</div>
					</article>
					<article class="text-center text-md-left vertical-item post type-post status-publish format-standard">
						<div class="item-media post-thumbnail">
							<a href="#">
								<img src="{{ asset('website/images/blog/11.jpg') }}" alt="img">
							</a>
						</div>
						<div class="item-content">
							<header class="entry-header">
								<a href="#">Tattoo</a>
							</header>
							<div class="entry-content">
								<h3 class="entry-title">
									<a href="#" rel="bookmark">Elitr sed diam nonumy eirmod tempor</a>
								</h3>
							</div>
						</div>
					</article>
					<article class="text-center text-md-left vertical-item post type-post status-publish format-standard">
						<div class="item-media post-thumbnail">
							<a href="#">
								<img src="{{ asset('website/images/blog/02.jpg') }}" alt="img">
							</a>
						</div>
						<div class="item-content">
							<header class="entry-header">
								<a href="#">Microblading</a>
							</header>
							<div class="entry-content">
								<h3 class="entry-title">
									<a href="#" rel="bookmark">Elitr sed diam nonumy eirmod tempor</a>
								</h3>
							</div>
						</div>
					</article>
					<article class="text-center text-md-left vertical-item post type-post status-publish format-standard">
						<div class="item-media post-thumbnail">
							<a href="#">
								<img src="{{ asset('website/images/blog/02.jpg') }}" alt="img">
							</a>
						</div>
						<div class="item-content">
							<header class="entry-header">
								<a href="#">Makeup</a>
							</header>
							<div class="entry-content">
								<h3 class="entry-title">
									<a href="#" rel="bookmark">Dolore magna aliquyam erat sed diam voluptua</a>
								</h3>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Team Section -->
<section class="section-team team ls s-pb-60 s-pb-md-90 s-pb-lg-130 s-pb-xl-220">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<span class="sub-title text-center">meet our</span>
				<h3 class="special-heading text-center">creative team</h3>
				<div class="divider-70 d-none d-xl-block"></div>
				<div class="divider-30 d-block d-xl-none"></div>
				<div class="owl-carousel" data-loop="true" data-margin="30" data-nav="true" data-dots="true" data-center="false" data-items="1" data-autoplay="false" data-responsive-xs="1" data-responsive-sm="1" data-responsive-md="2" data-responsive-lg="3">
					<div class="vertical-item content-padding text-center">
						<div class="item-media">
							<img src="{{ asset('website/images/team/01.jpg') }}" alt="img">
							<div class="media-links">
								<a class="abs-link" title="" href="#"></a>
							</div>
						</div>
						<div class="item-content">
							<h4 class="tile">
								<a href="#">Bridgette Jameson</a>
							</h4>
							<a href="#"><span class="color-main">Tattoo</span></a>
							<p>Consetetur sadipscing elitr, sed diam nonumy.</p>
							<div class="social-icons">
								<a href="#" class="fa fa-facebook" title="facebook"></a>
								<a href="#" class="fa fa-twitter" title="twitter"></a>
								<a href="#" class="fa fa-google-plus" title="google"></a>
							</div>
						</div>
					</div>
					<div class="vertical-item content-padding text-center">
						<div class="item-media">
							<img src="{{ asset('website/images/team/02.jpg') }}" alt="img">
							<div class="media-links">
								<a class="abs-link" title="" href="#"></a>
							</div>
						</div>
						<div class="item-content">
							<h4 class="tile">
								<a href="#">Selina Scott</a>
							</h4>
							<a href="#"><span class="color-main">Makeup</span></a>
							<p>At vero eos et accusam et justo duo dolores etea.</p>
							<div class="social-icons">
								<a href="#" class="fa fa-facebook" title="facebook"></a>
								<a href="#" class="fa fa-twitter" title="twitter"></a>
								<a href="#" class="fa fa-google-plus" title="google"></a>
							</div>
						</div>
					</div>
					<div class="vertical-item content-padding text-center">
						<div class="item-media">
							<img src="{{ asset('website/images/team/03.jpg') }}" alt="img">
							<div class="media-links">
								<a class="abs-link" title="" href="#"></a>
							</div>
						</div>
						<div class="item-content">
							<h4 class="tile">
								<a href="#">Carlos Linton</a>
							</h4>
							<a href="#"><span class="color-main">Barber</span></a>
							<p>Eirmod tempor invidunt ut labore et dolore magna.</p>
							<div class="social-icons">
								<a href="#" class="fa fa-facebook" title="facebook"></a>
								<a href="#" class="fa fa-twitter" title="twitter"></a>
								<a href="#" class="fa fa-google-plus" title="google"></a>
							</div>
						</div>
					</div>
					<div class="vertical-item content-padding text-center">
						<div class="item-media">
							<img src="{{ asset('website/images/team/04.jpg') }}" alt="img">
							<div class="media-links">
								<a class="abs-link" title="" href="#"></a>
							</div>
						</div>
						<div class="item-content">
							<h4 class="tile">
								<a href="#">Robin Augustine</a>
							</h4>
							<a href="#"><span class="color-main">Nail Artist</span></a>
							<p>Consetetur sadipscing elitr, sed diam nonumy.</p>
							<div class="social-icons">
								<a href="#" class="fa fa-facebook" title="facebook"></a>
								<a href="#" class="fa fa-twitter" title="twitter"></a>
								<a href="#" class="fa fa-google-plus" title="google"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection
