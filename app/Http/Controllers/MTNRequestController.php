<?php

namespace App\Http\Controllers;

use App\Models\UserMobile;
use App\MTNRequest;
use App\User;
use Illuminate\Http\Request;
use Session;

class MTNRequestController extends Controller
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
     * Show the form for making MTN deposit.
     *
     * @return \Illuminate\Http\Response
     */
    public function mTNDeposit()
    {
		// User
		$data['user'] = $user = auth()->user();
		// Use mobile
		$data['user_mobile'] = UserMobile::whereUserId($user->id)->first();
		
        return view('mtn.deposit', $data);
    }

    /**
     * Show the form for making MTN payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function mTNPayment()
    {
		// User
		$data['user'] = $user = auth()->user();
		// Use mobile
		$data['user_mobile'] = UserMobile::whereUserId($user->id)->first();
		
        return view('mtn.payment', $data);
    }
	
    /**
     * MTN Money Deposit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function postMTNDeposit(Request $request)
	{
		$this->validate($request, [
			'mtn_money_number' => 'required|regex:(^096\d{7}$)',
			'deposit_amount' => 'required|regex:(^[0-9]+(\.[0-9]{1,2})?$)',
		]);
		// XML curl POST Request Payment
		function doXMLCurl($url,$postXML){
			$headers = array(
				"Content-type: text/xml;charset=\"utf-8\"",
				"Accept: text/xml",
				"Cache-Control: no-cache",
				"Pragma: no-cache",
				"SOAPAction: \"run\"",
				"Content-length: ".strlen($postXML),
				"Host:" . $_SERVER['SERVER_NAME'],
				"Cookie: sessionid=" . Session::getId(),
			); 
			$CURL = curl_init();

			curl_setopt($CURL, CURLOPT_URL, $url); 
			curl_setopt($CURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
			curl_setopt($CURL, CURLOPT_POST, 1); 
			curl_setopt($CURL, CURLOPT_POSTFIELDS, $postXML); 
			curl_setopt($CURL, CURLOPT_HEADER, false); 
			curl_setopt($CURL, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($CURL, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($CURL, CURLOPT_RETURNTRANSFER, true);
			$xmlResponse = curl_exec($CURL); 

			return $xmlResponse;
		}
		// logged in user
		$user = auth()->user();
		$data['user'] = $user;
		
		// client properties
		// Request Payment Deposit URL
		$REQDEPOSITURL = route('post_mtn_deposit_response').'?format=soap';

		// Mobile Money ECW Version(1.5/1.7)
		$ECW_VERSION = 1.7;

		// Header Details
		// Service Partner ID
		$spId = 35000004;
		
		// SDP Password
		$Password = "bmeB500";
		
		// Service ID
		$serviceId = 102;

		// Sender ID
		$SenderID = "MOM";

		// Bundle ID
		$bundleID = 123;

		// Request Body Information(Both Payment & Deposit)
		// MSISDNNum
		$MSISDNNum = $request->mtn_money_number;
		
		// Minimum due amount for request payment API
		$MinDueAmount = 0;

		// Opco ID
		$OpCoID = 26001;

		// Prefered Language
		$PrefLang = "En";

		// Narration details
		$Narration = "Deposit into MTN Money Account";
		
		// timeStamp
		$timeStamp = date("YmdHis");

		// Order time for deposit money
		$OrderDateTime = $timeStamp;

		// User IMSI number for Deposit Money
		$IMSINum = 86;

		// Currency Code
		$currencyCode = 894;
		
		// spPassword
		$spPassword = md5($spId . $Password . $timeStamp);
		
		// e-Mpiya client App generated
		// transaction number
		$ProcessingNumber = "PAY". $timeStamp;
		
		// user account number
		$AcctRef = $user->id;
		
		// user account balance
		// App\Models\EmpiyaTransactionHistory::whereUserId(1)->orderBy('created_at', 'DESC')->get()->first()->final_balance;
		$AcctBalance = 1000;
		
		// DueAmount
		$Amount = $request->deposit_amount;
		
		$REQUEST_BODY= <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="http://b2b.mobilemoney.mtn.zm_v1.0/">
	<soapenv:Header>
		<RequestSOAPHeader xmlns="http://www.huawei.com.cn/schema/common/v2_1">
			<spId>$spId</spId>
			<spPassword>$spPassword</spPassword>
			<bundleID>$bundleID</bundleID>
			<serviceId>$serviceId</serviceId>
			<timeStamp>$timeStamp</timeStamp>
		</RequestSOAPHeader>
	</soapenv:Header>
	<soapenv:Body>
		<b2b:processRequest>
			<serviceId>201</serviceId>
			<parameter>
				<name>ProcessingNumber</name>
				<value>$ProcessingNumber</value>
			</parameter>
			<parameter>
				<name>serviceId</name>
				<value>102</value>
			</parameter>
			<parameter>
				<name>SenderID</name>
				<value>$SenderID</value>
			</parameter>
			<parameter>
				<name>PrefLang</name>
				<value>$PrefLang</value>
			</parameter>
			<parameter>
				<name>OpCoID</name>
				<value>$OpCoID</value>
			</parameter>
			<parameter>
				<name>MSISDNNum</name>
				<value>$MSISDNNum</value>
			</parameter>
			<parameter>
				<name>Amount</name>
				<value>$Amount</value>
			</parameter>
			<parameter>
				<name>Narration</name>
				<value>$Narration</value>
			</parameter>
			<parameter>
				<name>IMSINum</name>
				<value>$IMSINum</value>
			</parameter>
			<parameter>
				<name>OrderDateTime</name>
				<value>$OrderDateTime</value>
			</parameter>
		</b2b:processRequest>
	</soapenv:Body>
</soapenv:Envelope>
XML;
			$full_response = doXMLCurl($REQDEPOSITURL, $REQUEST_BODY);
			$response_xml = new \SimpleXMLElement(strstr($full_response, '<'), LIBXML_NOERROR);
		try {
		} catch (\Exception $e) {
			return redirect()->back()->withInput()->with('server_error', 'Server Unreachable. Please try again.');
		}
		// initiate string response
		$string_response = "";
		// initiate response array
		$json_response = [];
		// index
		$index = 0;
		foreach ($response_xml->xpath('//return') as $return) {
			// Concatenate string values to string response
			$string_response .= "//" . $return->name . " = " . $return->value . "<br>";
		
			// assign values to response array
			$json_response[] = ["$return->name" => "$return->value"];
		}
		$data['string_response'] = $string_response;
		$data['json_response'] = response()->json($json_response);
		
		// Redirect to home page with retrieved data
		return view('home', $data);
	}
	
    /**
     * MTN Money Request Payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function postMTNPayment(Request $request)
	{
		$this->validate($request, [
			'mtn_money_number' => 'required|regex:(^096\d{7}$)',
			'pay_amount' => 'required|regex:(^[0-9]+(\.[0-9]{1,2})?$)',
		]);
		// XML curl POST Request Payment
		function doXMLCurl($url,$postXML){
			$headers = array(
				"Content-type: text/xml;charset=\"utf-8\"",
				"Accept: text/xml",
				"Cache-Control: no-cache",
				"Pragma: no-cache",
				"SOAPAction: \"run\"",
				"Content-length: ".strlen($postXML),
				"Host:" . $_SERVER['SERVER_NAME'],
				"Cookie: sessionid=" . Session::getId(),
			); 
			$CURL = curl_init();

			curl_setopt($CURL, CURLOPT_URL, $url); 
			curl_setopt($CURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
			curl_setopt($CURL, CURLOPT_POST, 1); 
			curl_setopt($CURL, CURLOPT_POSTFIELDS, $postXML); 
			curl_setopt($CURL, CURLOPT_HEADER, false); 
			curl_setopt($CURL, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($CURL, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($CURL, CURLOPT_RETURNTRANSFER, true);
			$xmlResponse = curl_exec($CURL); 

			return $xmlResponse;
		}
		// logged in user
		$user = auth()->user();
		$data['user'] = $user;
		
		// client properties
		// RequestPayment URL
		$REQPAYEMENTURL = route('post_mtn_payment_response');

		// Mobile Money ECW Version(1.5/1.7)
		$ECW_VERSION = 1.7;

		// Header Details
		// Service Partner ID
		$spId = 000201;
		
		// SDP Password
		$Password = "bmeB500";
		
		// Service ID
		$serviceId = 35000001000001;

		// Sender ID
		$senderId = 452;

		// Bundle ID
		$bundleID = 256000039;

		// Request Body Information(Both Payment & Deposit)
		// MSISDNNum
		$MSISDNNum = $request->mtn_money_number;
		
		// Minimum due amount for request payment API
		$MinDueAmount = 0;

		// Opco ID
		$OpCoID = 26001;

		// Prefered Language
		$PrefLang = "En";

		// Narration details
		$Narration = "Deposit to e-Mpiya Account from MTN Money";

		// Order time for deposit money
		$orderDateTime = 124585123;

		// User IMSI number for Deposit Money
		$imsiNum = 552;

		// Currency Code
		$currencyCode = 894;
		
		// timeStamp
		$timeStamp = date("YmdHis");
		
		// spPassword
		$spPassword = md5($spId . $Password . $timeStamp);
		
		// e-Mpiya client App generated
		// transaction number
		$ProcessingNumber = "PAY". $timeStamp;
		
		// user account number;
		$AcctRef = $user->id;
		
		// user account balance
		$AcctBalance = 1000;
		
		// DueAmount
		$DueAmount = $request->payment_amount;
		
		$REQUEST_BODY= <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="http://b2b.mobilemoney.mtn.zm_v1.0">
	<soapenv:Header>
		<RequestSOAPHeader xmlns="http://www.huawei.com.cn/schema/common/v2_1">
			<spId>$spId</spId>
			<spPassword>$spPassword</spPassword>
			<bundleID>$bundleID</bundleID>
			<serviceId>$serviceId</serviceId>
			<timeStamp>$timeStamp</timeStamp>
		</RequestSOAPHeader>
	</soapenv:Header>
	<soapenv:Body>
		<b2b:processRequest>
			<serviceId>200</serviceId>
			<parameter>
				<name>DueAmount</name>
				<value>$DueAmount</value>
			</parameter>
			<parameter>
				<name>MSISDNNum</name>
				<value>$MSISDNNum</value>
			</parameter>
			<parameter>
				<name>ProcessingNumber</name>
				<value>$ProcessingNumber</value>
			</parameter>
			<parameter>
				<name>serviceId</name>
				<value>200</value>
			</parameter>
			<parameter>
				<name>AcctRef</name>
				<value>$AcctRef</value>
			</parameter>
			<parameter>
				<name>AcctBalance</name>
				<value>$AcctBalance</value>
			</parameter>
			<parameter>
				<name>MinDueAmount</name>
				<value>$MinDueAmount</value>
			</parameter>
			<parameter>
				<name>Narration</name>
				<value>$Narration</value>
			</parameter>
			<parameter>
				<name>PrefLang</name>
				<value>$PrefLang</value>
			</parameter>
			<parameter>
				<name>OpCoID</name>
				<value>$OpCoID</value>
			</parameter>
		</b2b:processRequest>
	</soapenv:Body>
</soapenv:Envelope>
XML;
		try {
			$full_response = doXMLCurl($REQPAYEMENTURL, $REQUEST_BODY);
			$response_xml = new \SimpleXMLElement(strstr($full_response, '<'), LIBXML_NOERROR);
		} catch (\Exception $e) {
			return redirect()->back()->withInput()->with('server_error', 'Server Unreachable. Please try again.');
		}
		// initiate string response
		$string_response = "";
		// initiate response array
		$json_response = [];
		// index
		$index = 0;
		foreach ($response_xml->xpath('//return') as $return) {
			// Concatenate string values to string response
			$string_response .= "//" . $return->name . " = " . $return->value . "<br>";
		
			// assign values to response array
			$json_response[] = ["$return->name" => "$return->value"];
		}
		$data['string_response'] = $string_response;
		$data['json_response'] = response()->json($json_response);
		
		// Redirect to home page with retrieved data
		return view('home', $data);
	}
}
