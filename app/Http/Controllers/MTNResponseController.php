<?php

namespace App\Http\Controllers;

use App\Models\UserMobile;
use App\MTNResponse;
use Illuminate\Http\Request;

class MTNResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
	/**
	*	Respond as SDP with deposit information
	**/
	public function confirmThirdPartyPayment()
	{
		// get incoming SOAP request contents
		try {
			$full_request = file_get_contents("php://input");
		} catch (\Exception $e) {
			$ERROR_RESPONSE= <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="http://b2b.mobilemoney.mtn.zm_v1.0/">
	<soapenv:Header/>
	<soapenv:Body>
		<b2b:processRequestResponse>
			<return>
				<name>Error</name>
				<value>No data found</value>
			</return>
		</b2b:processRequestResponse>
	</soapenv:Body>
</soapenv:Envelope>
XML;
			return $ERROR_RESPONSE;
		}
		// get SOAP request XML
		$request_xml = new \SimpleXMLElement(strstr($full_request, '<'), LIBXML_NOERROR);
		
		// get an array of SOAP request parameters
		foreach ($request_xml->xpath('//parameter') as $parameter) {
			switch($parameter->name) {
				case "ProcessingNumber":
					$ProcessingNumber = $parameter->value;
					break;
				case "senderID":
					$senderID = $parameter->value;
					break;
				case "AcctRef":
					$AcctRef = $parameter->value;
					break;
				case "RequestAmount":
					$RequestAmount = $parameter->value;
					break;
				case "paymentRef":
					$paymentRef = $parameter->value;
					break;
				case "ThirdPartyTransactionID":
					$ThirdPartyTransactionID = $parameter->value;
					break;
				case "MOMAcctNum":
					$MOMAcctNum = $parameter->value;
					break;
				case "CustName":
					$CustName = $parameter->value;
					break;
				case "TXNType":
					$TXNType = $parameter->value;
					break;
				case "StatusCode":
					$StatusCode = $parameter->value;
					break;
				case "OpCoID":
					$OpCoID = $parameter->value;
					break;
				case "MerchantName":
					$MerchantName = $parameter->value;
					break;
				default:
					echo "empty request";
			}
		}
		
		// logged in user
		$user = auth()->user();
		
		// client properties
		// Header Details
		// e-Mpiya client App generated
		// status code
		$StatusCode = 01;
		
		// status description code
		$StatusDesc = "Successfully Processed Transaction.";
		
		// token
		$Token = 121212;
		
		// generate response
		$RESPONSE_BODY= <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="http://b2b.mobilemoney.mtn.zm_v1.0/">
	<soapenv:Header/>
	<soapenv:Body>
		<b2b:processRequestResponse>
			<return>
				<name>ProcessingNumber</name>
				<value>$ProcessingNumber</value>
			</return>
			<return>
				<name>StatusCode</name>
				<value>$StatusCode</value>
			</return>
			<return>
				<name>StatusDesc</name>
				<value>$StatusDesc</value>
			</return>
			<return>
				<name>ThirdPartyAcctRef</name>
				<value>$AcctRef</value>
			</return>
			<return>
				<name>Token</name>
				<value>$Token</value>
			</return>
		</b2b:processRequestResponse>
	</soapenv:Body>
</soapenv:Envelope>
XML;
	
		return $RESPONSE_BODY;
	}
}
