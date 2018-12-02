<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		$other = array('required_if:mobile_network,Other','regex:/^(((?!095)\d{10})|((?!096)\d{10})|((?!097)\d{10}))$/');
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'MobileNetwork' => 'required_with:Number',
			'Airtel' => 'required_if:mobile_network,Airtel|regex:(^097\d{7}$)',
			'MTN' => 'required_if:mobile_network,MTN|regex:(^096\d{7}$)',
			'Zamtel' => 'required_if:mobile_network,Zamtel|regex:(^095\d{7}$)',
			'Other' => $other,
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
		// Set user mobile network
		if ($data['mobile_network'] == "Airtel") {
			$mobile_service_provider_id = 1;
		} elseif ($data['mobile_network'] == "MTN") {
			$mobile_service_provider_id = 2;
		} elseif ($data['mobile_network'] == "Zamtel") {
			$mobile_service_provider_id = 3;
		} elseif ($data['mobile_network'] == "Other") {
			$mobile_service_provider_id = 4;
		}
		// Set user mobile number
		if (!is_null($data['Airtel'])) {
			$user_mobile_number = $data['Airtel'];
		} elseif (!is_null($data['MTN'])) {
			$user_mobile_number = $data['MTN'];
		} elseif (!is_null($data['Zamtel'])) {
			$user_mobile_number = $data['Zamtel'];
		} elseif (!is_null($data['Other'])) {
			$user_mobile_number = $data['Other'];
		}
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile_service_provider_id' => $mobile_service_provider_id,
            'mobile_number' => $user_mobile_number,
            'password' => Hash::make($data['password']),
        ]);
    }
}
