<?php

namespace App\Http\Controllers;

use App\ApiProvider;
use App\MtnProductSubscription;
use Illuminate\Http\Request;

class MTNMomoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for MTN Collections Widget.
     *
     * @return \Illuminate\Http\Response
     */
    public function collectionWidget()
    {
		// User
		$data['user'] = auth()->user();
        // header
        $data['header'] = 'Collections Widget Demo';
		
        return view('mtn.collection_widget', $data);
    }

    /**
     * Show the form for making MTN Collections.
     *
     * @return \Illuminate\Http\Response
     */
    public function collections()
    {
		// User
		$data['user'] = auth()->user();
        // header
        $data['header'] = 'Collections Demo';
		
        return view('mtn.collections', $data);
    }

    /**
     * Show the form for making MTN Disbursements.
     *
     * @return \Illuminate\Http\Response
     */
    public function disbursements()
    {
		// User
		$data['user'] = auth()->user();
        // header
        $data['header'] = 'Disbursements Demo';
		
        return view('mtn.disbursements', $data);
    }

    /**
     * Show the form for making MTN Remittances.
     *
     * @return \Illuminate\Http\Response
     */
    public function remittances()
    {
		// User
        $data['user'] = auth()->user();
        // header
        $data['header'] = 'Remittances Demo';
		
        return view('mtn.remittances', $data);
    }

    /**
     * Show the form for MTN Settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiSettings()
    {
		// User
        $data['user'] = auth()->user();
        // API Registered Apps
        $data['api_data'] = ApiProvider::all();
        // API Product Subscriptions
        $data['api_subscriptions'] = MtnProductSubscription::all();
        // header
        $data['header'] = 'MTN API Settings';
		
        return view('mtn.settings', $data);
    }

    /**
     * Show the form for new MTN API App.
     *
     * @return \Illuminate\Http\Response
     */
    public function newMtnApp()
    {
		// User
        $data['user'] = auth()->user();
        // header
        $data['header'] = 'New MTN API App';
		
        return view('mtn.new_api_app', $data);
    }
	
    /**
     * New MTN API App POST.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postNewMtnApp(Request $request)
    {
		$this->validate($request, [
			'provider' => 'required|string',
			'callback_url' => 'required|string',
		]);
		// User
        $user = auth()->user();
        
        $mtn_app = new ApiProvider;

        $mtn_app->provider = $request->provider;
        $mtn_app->callback_url = $request->callback_url;
        
        // generate UUID
        function gen_uuid() {
            return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

                // 16 bits for "time_mid"
                mt_rand( 0, 0xffff ),

                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand( 0, 0x0fff ) | 0x4000,

                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand( 0, 0x3fff ) | 0x8000,

                // 48 bits for "node"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
            );
        }

        // v4 UUID
        $api_user = gen_uuid();
        // Assign api_user to ApiProvider model
        $mtn_app->api_user = $api_user;

        $collection_url = "https://sandbox.momodeveloper.mtn.com/collection/token/";

		// JSON curl POST
		function doJSONCurl($host,$url,$apiUser,$subscriptionKey,$postJSON = null){
			$headers = array(
                "Host: " . $host,
                "Content-type: application/json",
                "X-Reference-Id: " . $apiUser,
                "Ocp-Apim-Subscription-Key: " . $subscriptionKey,
			); 
			$CURL = curl_init();

			curl_setopt($CURL, CURLOPT_URL, $url); 
			curl_setopt($CURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
			curl_setopt($CURL, CURLOPT_POST, 1); 
			curl_setopt($CURL, CURLOPT_POSTFIELDS, $postJSON); 
			curl_setopt($CURL, CURLOPT_HEADER, false); 
			curl_setopt($CURL, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($CURL, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($CURL, CURLOPT_RETURNTRANSFER, true);
			$jsonResponse = curl_exec($CURL); 

			return $jsonResponse;
        }
        // Create API User data
        $host_server = "sandbox.momodeveloper.mtn.com";
        $request_url = "https://sandbox.momodeveloper.mtn.com/v1_0/apiuser";
        $subscription_key = "1e8c4cbf2c6549f388b9f8d686e87144";
        $callback_url = $request->callback_url;

        $REQUEST_BODY= <<<JSON
{
  "providerCallbackHost": "{$callback_url}"
}
JSON;
        // Submit API User for creation
        try {
            $full_response = doJSONCurl($host_server, $request_url, $api_user, $subscription_key, $REQUEST_BODY);
            $decoded_full_response = json_decode($full_response);
            if(!empty($decoded_full_response->message)) {
                if($decoded_full_response->message && $decoded_full_response->code) {
                    return redirect()->back()->withInput()->with('create_error', 'Error Message: ' . $decoded_full_response->message . ' | Error Code: ' . $decoded_full_response->code);
                }
            }
        } catch(\Exception $e) {
			return redirect()->back()->withInput()->with('create_error', 'API User failed to create. ' . $e);
        }
        // Create API Key data
        $request_url = "https://sandbox.momodeveloper.mtn.com/v1_0/apiuser/{$api_user}/apikey";
        $subscription_key = "1e8c4cbf2c6549f388b9f8d686e87144";
        $callback_url = $request->callback_url;
        // Create API Key
        try {
            $full_response = doJSONCurl($host_server, $request_url, $api_user, $subscription_key);
            $decoded_full_response = json_decode($full_response);
            if(!empty($decoded_full_response->message)) {
                if($decoded_full_response->message && $decoded_full_response->code) {
                    return redirect()->back()->withInput()->with('create_error', 'Error Message: ' . $decoded_full_response->message . ' | Error Code: ' . $decoded_full_response->code);
                }
            }
            $api_key = $decoded_full_response->apiKey;
        } catch(\Exception $e) {
			return redirect()->back()->withInput()->with('create_error', 'API User failed to create. ' . $e);
        }

        $mtn_app->api_key = $api_key;

        //API User and Key
        $api_user_and_key  = $api_user . ':' . $api_key;

        // base64 encoded Basic Authentication Token
        $basic_auth = base64_encode($api_user_and_key);
        $mtn_app->basic_auth = $basic_auth;
        $mtn_app->save();
		
        return redirect()->route('mtn_api_settings');
    }

    /**
     * Show the form for new MTN API App.
     *
     * @return \Illuminate\Http\Response
     */
    public function newMtnApiProductSubscription()
    {
		// User
        $data['user'] = auth()->user();
        // header
        $data['header'] = 'New MTN API Product Subscription';
        // Registered API Apps
        $data['api_providers'] = ApiProvider::all();
		
        return view('mtn.new_product_subscription', $data);
    }
	
    /**
     * MTN Collection API POST.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postNewMtnApiProductSubscription(Request $request)
    {
		$this->validate($request, [
			'api_provider' => 'required|integer',
			'product' => 'required|string',
			'primary_key' => 'required|string',
			'secondary_key' => 'required|string',
		]);
		// User
        $user = auth()->user();

		// JSON curl POST
		function doJSONCurl2($host,$url,$basicAuth,$subscriptionKey,$postJSON = null){
			$headers = array(
                "Host: " . $host,
                "Content-type: application/json",
                "Authorization: " . $basicAuth,
                "Ocp-Apim-Subscription-Key: " . $subscriptionKey,
			); 
			$CURL = curl_init();

			curl_setopt($CURL, CURLOPT_URL, $url); 
			curl_setopt($CURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
			curl_setopt($CURL, CURLOPT_POST, 1); 
			curl_setopt($CURL, CURLOPT_POSTFIELDS, $postJSON); 
			curl_setopt($CURL, CURLOPT_HEADER, false); 
			curl_setopt($CURL, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($CURL, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($CURL, CURLOPT_RETURNTRANSFER, true);
			$jsonResponse = curl_exec($CURL); 

			return $jsonResponse;
        }
        // New MTN Product Subscription
        $mtn_product_subscription = new MtnProductSubscription;
        // Select API App
        $mtn_api_app = ApiProvider::find($request->api_provider);

        // Create token data
        $host_server = "sandbox.momodeveloper.mtn.com";
        $request_url = "https://sandbox.momodeveloper.mtn.com/collection/token/";
        $basic_auth = "Basic " . $mtn_api_app->basic_auth;
        $subscription_key = $request->primary_key;
        $target_environment = "sandbox";

        try {
            $full_response = doJSONCurl2($host_server, $request_url, $basic_auth, $subscription_key);
            $decoded_full_response = json_decode($full_response);
            if(!empty($decoded_full_response->statusCode)) {
                if($decoded_full_response->message && $decoded_full_response->code) {
                    return redirect()->back()->withInput()->with('create_error', 'Error Message: ' . $decoded_full_response->message . ' | Error Code: ' . $decoded_full_response->statusCode);
                }
            }
            // Bearer Token
            $bearer_token = $decoded_full_response->access_token;
        } catch(\Exception $e) {
			return redirect()->back()->withInput()->with('create_error', 'API User failed to create. ' . $e);
        }
        $mtn_product_subscription->api_provider_id = $request->api_provider;
        $mtn_product_subscription->product = $request->product;
        $mtn_product_subscription->primary_key = $request->primary_key;
        $mtn_product_subscription->secondary_key = $request->secondary_key;
        $mtn_product_subscription->bearer_token = $bearer_token;
        $mtn_product_subscription->target_environment = $target_environment;
        $mtn_product_subscription->save();
		
        return redirect()->route('mtn_api_settings');
    }
	
    /**
     * MTN Collection API POST.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCollectionToken()
    {
		$this->validate($request, [
			'provider' => 'required|string',
			'callback_url' => 'required|string',
		]);
		// User
        $user = auth()->user();
        
        // generate UUID
        function gen_uuid() {
            return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

                // 16 bits for "time_mid"
                mt_rand( 0, 0xffff ),

                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand( 0, 0x0fff ) | 0x4000,

                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand( 0, 0x3fff ) | 0x8000,

                // 48 bits for "node"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
            );
        }

        // v4 UUID
        $api_user = gen_uuid();
        // Assign api_user to ApiProvider model
        $mtn_app->api_user = $api_user;

        $collection_url = "https://sandbox.momodeveloper.mtn.com/collection/token/";

		// JSON curl POST
		function doJSONCurl($host,$url,$apiUser,$subscriptionKey,$postJSON){
			$headers = array(
                "Host: " . $host,
                "Content-type: application/json",
                "X-Reference-Id: " . $apiUser,
                "Ocp-Apim-Subscription-Key: " . $subscriptionKey,
			); 
			$CURL = curl_init();

			curl_setopt($CURL, CURLOPT_URL, $url); 
			curl_setopt($CURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
			curl_setopt($CURL, CURLOPT_POST, 1); 
			curl_setopt($CURL, CURLOPT_POSTFIELDS, $postJSON); 
			curl_setopt($CURL, CURLOPT_HEADER, false); 
			curl_setopt($CURL, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($CURL, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($CURL, CURLOPT_RETURNTRANSFER, true);
			$jsonResponse = curl_exec($CURL); 

			return $jsonResponse;
        }
        
        $callback_url = $request->callback_url;

        $REQUEST_BODY= <<<JSON
{
  "providerCallbackHost": "{$callback_url}"
}
JSON;
        try {

        } catch(\Exception $e) {

        }
        // API Key
        $api_key = 'ddee4292edca4955abbff7191039cbe6';

        //API User and Key
        $api_user_and_key  = $api_user . ':' . $api_key;

        // base64 encoded Basic Authentication Token
        $basic_auth = base64_encode($api_user_and_key);
		
        return redirect()->route('mtn_api_settings');
    }
}
