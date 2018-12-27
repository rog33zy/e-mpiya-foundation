<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'e-Mpiya OMPS') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div>
		<!-- One Ziko Menu Bar -->
		<?php $path = Request::path() ?>

		{{-- top bar  --}}
		<div class="top-bar">

		<div class="top-bar-left">
			<ul class="dropdown menu" data-dropdown-menu>
			<li class="menu-text"><a href="{{ route('home') }}">{{ config('app.name', 'e-Mpiya OMPS') }}</a></li>
			</ul>
		</div>

		<div class="top-bar-right">
			<ul class="menu">
				@if (Auth::guest())
					<li><a href="{{ route('login') }}">Login</a></li>
					<li><a href="{{ route('register') }}">Register</a></li>
				@else
					<ul class="dropdown menu" data-dropdown-menu>
						<li>
							<a href="{{ route('view-user-profile', $user->username_slug) }}">{{ Auth::user()->username }}</a>
							<ul class="menu">
								@if (isset($user_admin_access))
									<li><a href="{{ route('manage-users', Auth::user()->username_slug) }}">Manage Users</a></li>
								@endif
								<li><a href="{{ route('edit-user-profile', Auth::user()->username_slug) }}">Edit Profile</a></li>
								<li>
									<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
										Logout
									</a>

									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										{{ csrf_field() }}
									</form>
								</li>
							</ul>
						</li>
					</ul>
				@endif
			</ul>
		</div>

		</div>

		<div class="container">

			<div class="row">

				@yield('user_profile_card')
				@yield('content')

			</div>

		</div>

    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
	<script>
		$('.mobile-network').change(function(e){
			var selectedValue = $(this).val();
			document.getElementById('user-mobile').name = selectedValue;
			$('#mobile-number').html('<span class="prefix"><strong>'+selectedValue+' Number</strong></span>')
		});
	</script>
    <script>
        $(document).foundation();
    </script>
</body>
</html>
