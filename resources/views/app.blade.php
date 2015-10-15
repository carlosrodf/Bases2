<!DOCTYPE HTML>
<!--
	Striped by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>OUTGUAT - @yield('title')</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="/assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body>

		<!-- Content -->
			<div id="content">
				<div class="inner">

					<!-- Post -->
						<article class="box post post-excerpt">
							<header>
								<!--
									Note: Titles and subtitles will wrap automatically when necessary, so don't worry
									if they get too long. You can also remove the <p> entirely if you don't
									need a subtitle.
									<h2><a href="#">Welcome to Striped</a></h2>
									<p>A free, fully responsive HTML5 site template by HTML5 UP</p>
								-->
								@yield('header')
							</header>
							<div class="info">
								<!--
									Note: The date should be formatted exactly as it's shown below. In particular, the
									"least significant" characters of the month should be encapsulated in a <span>
									element to denote what gets dropped in 1200px mode (eg. the "uary" in "January").
									Oh, and if you don't need a date for a particular page or post you can simply delete
									the entire "date" element.

								
								<span class="date"><span class="month">Jul<span>y</span></span> <span class="day">14</span><span class="year">, 2014</span></span>
								
									Note: You can change the number of list items in "stats" to whatever you want.
								
								<ul class="stats">
									<li><a href="#" class="icon fa-comment">16</a></li>
									<li><a href="#" class="icon fa-heart">32</a></li>
									<li><a href="#" class="icon fa-twitter">64</a></li>
									<li><a href="#" class="icon fa-facebook">128</a></li>
								</ul>
								-->
								@yield('info')
							</div>
							<!--
							<a href="#" class="image featured"><img src="images/pic01.jpg" alt="" /></a>
							<p>
								<strong>Hello!</strong> You're looking at <strong>Striped</strong>, a fully responsive HTML5 site template designed by <a href="http://n33.co">AJ</a>
								for <a href="http://html5up.net">HTML5 UP</a> It features a clean, minimalistic design, styling for all basic page elements (including blockquotes, tables and lists), a
								repositionable sidebar (left or right), and HTML5/CSS3 code designed for quick and easy customization (see code comments for details).
							</p>
							<p>
								Striped is released for free under the <a href="http://html5up.net/license">Creative Commons Attribution license</a> so feel free to use it for personal projects
								or even commercial ones &ndash; just be sure to credit <a href="http://html5up.net">HTML5 UP</a> for the design. If you like what you see here, be sure to check out
								<a href="http://html5up.net">HTML5 UP</a> for more cool designs or follow me on <a href="http://twitter.com/n33co">Twitter</a> for new releases and updates.
							</p>
							-->
							@yield('body')
						</article>

					<!-- Post
						<article class="box post post-excerpt">
							<header>
								<h2><a href="#">Lorem ipsum dolor sit amet</a></h2>
								<p>Feugiat interdum sed commodo ipsum consequat dolor nullam metus</p>
							</header>
							<div class="info">
								<span class="date"><span class="month">Jul<span>y</span></span> <span class="day">8</span><span class="year">, 2014</span></span>
								<ul class="stats">
									<li><a href="#" class="icon fa-comment">16</a></li>
									<li><a href="#" class="icon fa-heart">32</a></li>
									<li><a href="#" class="icon fa-twitter">64</a></li>
									<li><a href="#" class="icon fa-facebook">128</a></li>
								</ul>
							</div>
							<a href="#" class="image featured"><img src="images/pic02.jpg" alt="" /></a>
							<p>
								Quisque vel sapien sit amet tellus elementum ultricies. Nunc vel orci turpis. Donec id malesuada metus.
								Nunc nulla velit, fermentum quis interdum quis, tate etiam commodo lorem ipsum dolor sit amet dolore.
								Quisque vel sapien sit amet tellus elementum ultricies. Nunc vel orci turpis. Donec id malesuada metus.
								Nunc nulla velit, fermentum quis interdum quis, convallis eu sapien. Integer sed ipsum ante.
							</p>
						</article>
					-->

					<!-- Pagination 
						<div class="pagination">
							<a href="#" class="button previous">Previous Page</a>
							<div class="pages">
								<a href="#" class="active">1</a>
								<a href="#">2</a>
								<a href="#">3</a>
								<a href="#">4</a>
								<span>&hellip;</span>
								<a href="#">20</a>
							</div>
							<a href="#" class="button next">Next Page</a>
						</div>
					-->
				</div>
			</div>

		<!-- Sidebar -->
			<div id="sidebar">

				<!-- Logo -->
					<h1 id="logo"><a href="/">OUTGUAT</a></h1>

					<nav id="nav">
						<ul>
							@yield('nav_items')
						</ul>
					</nav>

				<!-- Search -->
					<section class="box search">
						{!! Form::open(['url'=> '/busqueda']) !!}
							{!! Form::text('search',null,['class' => 'text', 'placeholder' => 'Buscar']) !!}
						{!! Form::close() !!}
					</section>

					@yield('sections')

				<!-- Copyright -->
					<ul id="copyright">
						<li>&copy; Untitled.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
					</ul>

			</div>

		<!-- Scripts -->
			<script src="/assets/js/jquery.min.js"></script>
			<script src="/assets/js/skel.min.js"></script>
			<script src="/assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="/assets/js/main.js"></script>

	</body>
</html>