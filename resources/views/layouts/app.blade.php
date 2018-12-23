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
    <div id="app">
		<!-- One Ziko Menu Bar -->
		<?php $path = Request::path() ?>
		<div data-sticky-container>
			<div class="title-bar" data-options="marginTop:0;" style="width:100%" data-responsive-toggle="responsive-menu" data-hide-for="medium">
				<button class="menu-icon" type="button" data-toggle="responsive-menu"></button>
				<div class="title-bar-title">Zambian Music Timeline</div>
			</div>
			<div class="responsive-menu-wrapper">
				<div class="row">
					<div class="top-bar" id="responsive-menu">
						<div class="top-bar-left">
							<ul class="menu">
								<li class="menu-text"><a href="{{ route('home') }}">{{ config('app.name', 'e-Mpiya OMPS') }}</a></li>
							</ul>
						</div>
						<div class="top-bar-right">
							<ul class="dropdown menu" data-dropdown-menu>
								<li class="divider"></li>
								@if (Auth::guest())
									<li @if ($path === 'login') class="has-form active" @endif><a href="{{ url('/login') }}" class="medium login button">Login</a></li>
									<li class="divider"></li>
									<li @if ($path === 'register') class="active" @endif><a href="{{ url('/register') }}" class="medium register button">Register</a></li>
									<li class="divider"></li>
								@else
									<?php $user_admin_access = App\Models\UserAccess::whereUserRoleId(1)->whereUserId(Auth::user()->id)->orderBy('user_role_id', 'ASC')->first() ?>
									<li>
										<a href="{{ route('view-user-profile', Auth::user()->username_slug) }}"><img class="user-profile-picture" src="<?php
											if (!(Auth::user()->user_profile_picture == "")) {
												echo str_replace('users', 'small', Auth::user()->user_profile_picture);
											} else {
												echo asset('/images/small/default-user-profile-picture.png');
											} ?>" title="{{ Auth::user()->username }}" alt="{{ Auth::user()->username }}"></a>
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
								@endif
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		@yield('user_profile_card')
        @yield('content')

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
