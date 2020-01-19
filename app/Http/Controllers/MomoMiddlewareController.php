<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MomoMiddlewareController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api');
    }

    /**
     * Show the form for making MTN Collections.
     *
     * @return \Illuminate\Http\Response
     */
    public function collection(Request $request)
    {
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
        
        // API User
        $api_user = $request->input('apiUser');
        
        // API Key
        $api_key = $request->input('apiKey');
        
        //API User and Key
        $api_user_and_key  = $api_user . ':' . $api_key;
        
        // base64 encoded Basic Authentication Token
        $basic_auth = 'Basic ' . base64_encode($api_user_and_key);
        
        // JSON curl POST
        function doJSONCurl($host,$url,$basicAuth,$subscriptionKey,$postJSON = null){
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
            curl_close($CURL);
        
            return $jsonResponse;
        }
        // Create API User data
        $host_server = "sandbox.momodeveloper.mtn.com";
        $request_url = "https://sandbox.momodeveloper.mtn.com/collection/token/";
        $subscription_key = $request->input('subscriptionKey');
        
        // Submit for token creation
        try {
            $full_response = doJSONCurl($host_server, $request_url, $basic_auth, $subscription_key);
            $decoded_full_response = json_decode($full_response);
            if(!empty($decoded_full_response->message) && !empty($decoded_full_response->code)) {
                if($decoded_full_response->message && $decoded_full_response->code) {
                    $error_data = array("code" => $decoded_full_response->message, "message" => $decoded_full_response->code);

                    return response($error_data,200, ['Content-Type' => 'application/json']);
                }
            }
            if(!empty($decoded_full_response->message) && !empty($decoded_full_response->statusCode)) {
                if($decoded_full_response->message && $decoded_full_response->statusCode) {
                    $error_data = array("code" => $decoded_full_response->message, "message" => $decoded_full_response->statusCode);

                    return response($error_data,200, ['Content-Type' => 'application/json']);
                }
            }
        } catch(\Exception $e) {
            return 'Token failed to create. ' . $e;
        }
        
        // $contents = file_get_contents("http://localhost:8080/v1_0/apiuser/088c2c4c-1bb0-4c8f-98ae-892f2eb17bb3");
        // $results = json_decode($contents);
        try {
            $bearer_token = 'Bearer ' . $decoded_full_response->access_token;
        } catch(\Exception $e) {
            $error_data = array("code" => "Token Create Fail", "message" => "Token failed to create. Check your connection or credentials and try again.");

            return response($error_data,200, ['Content-Type' => 'application/json']);
        }
        
        // JSON curl Payment POST
        function doJSONCurlPay($host,$url,$bearerToken,$referenceId,$targetEnvironment,$subscriptionKey,$postJSON = null){
            $headers = array(
                "Host: " . $host,
                "Content-type: application/json",
                "Authorization: " . $bearerToken,
                "X-Reference-Id: " . $referenceId,
                "X-Target-Environment: " . $targetEnvironment,
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
            $httpcode = curl_getinfo($CURL, CURLINFO_HTTP_CODE);
            curl_close($CURL);
        
            if($httpcode == 202) {
                return json_encode(array("code" => "202", "message" => "Accepted"));
            } elseif($httpcode == 400) {
                return json_encode(array("code" => "400", "message" => "Bad Request"));
            } elseif($httpcode == 409) {
                return json_encode(array("code" => "409", "message" => "Conflict"));
            } elseif($httpcode == 500) {
                return json_encode(array("code" => "500", "message" => "Internal Server Error"));
            } else {
                return $jsonResponse;
            }
        }
        // Request To Pay
        $amount = 500;
        $currency = "EUR";
        $number = "{$request->input('number')}";
        $host_server = "sandbox.momodeveloper.mtn.com";
        $request_url = "https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay";
        $reference_id = gen_uuid();
        $target_environment = "sandbox";
        // $callback_url = $callback_url;
        $timestamp = date('Ymd_Gis');
        
        $REQUEST_BODY= <<<json
        {
            "amount": "{$amount}",
            "currency": "{$currency}",
            "externalId": "{$timestamp}",
            "payer": {
                "partyIdType": "MSISDN",
                "partyId": "{$number}"
            },
            "payerMessage": "Payment of K{$amount}",
            "payeeNote": "Payment of K{$amount} by {$number}"
        }
        json;
        
        // Submit payment json message
        try {
            $full_response = doJSONCurlPay($host_server, $request_url, $bearer_token, $reference_id, $target_environment, $subscription_key, $REQUEST_BODY);
            $decoded_full_response = json_decode($full_response);
            if($decoded_full_response->code == "202") {
                $app_data = array("amount"=>"{$amount}","currency"=>"{$currency}","externalId"=>"{$timestamp}","partyId"=>"{$number}","payerMessage"=>"Payment of K{$amount}","payeeNote"=>"Payment of K{$amount} by {$number}","X-Reference-Id"=>"{$reference_id}");

                return response($app_data,200, ['Content-Type' => 'application/json']);
            } else {
                return response($full_response,200, ['Content-Type' => 'application/json']);
            }
        } catch(\Exception $e) {
            return 'Payment failed! ' . $e;
        }
		
        return response($data,200, ['Content-Type' => 'application/json']);
    }

    /**
     * Show the form for making MTN Disbursements.
     *
     * @return \Illuminate\Http\Response
     */
    public function disbursement()
    {
        // header
        $data['header'] = 'Disbursements Demo';
		
        return response($data,200, ['Content-Type' => 'application/json']);
    }
}
