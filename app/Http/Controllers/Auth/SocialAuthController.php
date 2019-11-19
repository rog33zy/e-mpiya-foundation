<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Socialite;

class SocialAuthController extends Controller
{
	/**
	 * Redirect the user to the Provider authentication page.
	 *
	 * @return Response
	 */
	public function redirect($provider)
	{
		return Socialite::driver($provider)->redirect();    
	}
	/**
	 * Obtain the user information from Provider.
	 *
	 * @return Response
	 */
	public function callback($provider)
	{
		try {
			$socialUser = Socialite::driver($provider)->user();
		} catch (\Exception $e) {
			return Redirect::to(route('social-login'));
		}
		
		if ((!empty($socialUser->email)) || (!empty($socialUser->id))) {
			$authUser = $this->findOrCreateUser($socialUser);

			Auth::login($authUser, true);

			return Redirect::to('user/assigndefaults');
		}
		
		return Redirect::to('register');
	}

	/**
	 * Return user if exists; create and return if doesn't
	 *
	 * @param $social_user
	 * @return User
	 */
	private function findOrCreateUser($social_user)
	{
		if ($authUser = User::where('email', $social_user->email)->where('social_user_id', $social_user->id)->first()) {
			return $authUser;
		}
		if ($authUser = User::where('email', $social_user->email)->first()) {
			return $authUser;
		}

		return User::create([
			'user_type_id' => 3,
			'social_user_id' => $social_user->id,
			'username' => $social_user->name,
			'username_slug' => str_slug($social_user->name, ''),
			'user_profile_picture' => $social_user->avatar,
			'email' => $social_user->email
		]);
	}
}
