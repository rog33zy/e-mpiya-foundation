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
	public function mTNDepositResponse(Request $request)
	{
		$format = $request->input('format');
		if ($format == "soap")
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
				case "serviceId":
					$serviceId = $parameter->value;
					break;
				case "SenderID":
					$SenderID = $parameter->value;
					break;
				case "PrefLang":
					$PrefLang = $parameter->value;
					break;
				case "OpCoID":
					$OpCoID = $parameter->value;
					break;
				case "MSISDNNum":
					$MSISDNNum = $parameter->value;
					break;
				case "Amount":
					$Amount = $parameter->value;
					break;
				case "Narration":
					$Narration = $parameter->value;
					break;
				case "IMSINum":
					$IMSINum = $parameter->value;
					break;
				case "OrderDateTime":
					$OrderDateTime = $parameter->value;
					break;
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
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<soapenv:Body>
		<ns1:processRequestResponse xmlns:ns1="http://b2b.mobilemoney.mtn.zm_v1.0/">
			<return>
				<name>ProcessingNumber</name>
				<value>$ProcessingNumber</value>
			</return>
			<return>
				<name>SenderID</name>
				<value>$SenderID</value>
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
				<name>OpCoID</name>
				<value>$OpCoID</value>
			</return>
			<return>
				<name>IMSINum</name>
				<value>$IMSINum</value>
			</return>
			<return>
				<name>MSISDNNum</name>
				<value>$MSISDNNum</value>
			</return>
			<return>
				<name>OrderDateTime</name>
				<value>$OrderDateTime</value>
			</return>
			<return>
				<name>ThirdPartyAcctRef</name>
				<value>121212</value>
			</return>
			<return>
				<name>MOMTransactionID</name>
				<value>456</value>
			</return>
		</ns1:processRequestResponse>
	</soapenv:Body>
</soapenv:Envelope>
XML;
	
		return $RESPONSE_BODY;
		} elseif ($format == "json")
		{
		$ERROR_RESPONSE= <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="http://b2b.mobilemoney.mtn.zm_v1.0/">
	<soapenv:Header/>
	<soapenv:Body>
		<b2b:processRequestResponse>
			<return>
				<name>Error</name>
				<value>No JSON data found</value>
			</return>
		</b2b:processRequestResponse>
	</soapenv:Body>
</soapenv:Envelope>
XML;
			return $ERROR_RESPONSE;
		} else
		{
		$ERROR_RESPONSE= <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="http://b2b.mobilemoney.mtn.zm_v1.0/">
	<soapenv:Header/>
	<soapenv:Body>
		<b2b:processRequestResponse>
			<return>
				<name>Error</name>
				<value>Please use correct format parameter in URL.</value>
			</return>
		</b2b:processRequestResponse>
	</soapenv:Body>
</soapenv:Envelope>
XML;
			return $ERROR_RESPONSE;
		}
	}
	/**
	*	Respond as SDP with deposit information
	**/
	public function mTNPaymentResponse()
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
				case "DueAmount":
					$DueAmount = $parameter->value;
					break;
				case "MSISDNNum":
					$MSISDNNum = $parameter->value;
					break;
				case "ProcessingNumber":
					$ProcessingNumber = $parameter->value;
					break;
				case "serviceId":
					$serviceId = $parameter->value;
					break;
				case "AcctRef":
					$AcctRef = $parameter->value;
					break;
				case "AcctBalance":
					$AcctBalance = $parameter->value;
					break;
				case "MinDueAmount":
					$MinDueAmount = $parameter->value;
					break;
				case "Narration":
					$Narration = $parameter->value;
					break;
				case "PrefLang":
					$PrefLang = $parameter->value;
					break;
				case "OpCoID":
					$OpCoID = $parameter->value;
					break;
			}
		}
		
		// logged in user
		$user = auth()->user();
		
		// client properties
		// Header Details
		// e-Mpiya client App generated
		// status code
		$StatusCode = 222;
		
		// status description code
		$StatusDesc = "PENDING";
		
		// MOMTransactionID
		$MOMTransactionID = 111;
		
		// generate response
		$RESPONSE_BODY= <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<soapenv:Body>
		<ns1:processRequestResponse xmlns:ns1="http://b2b.mobilemoney.mtn.zm_v1.0">
			<return>
				<name>ProcessingNumber</name>
				<value>$ProcessingNumber</value>
			</return>
			<return>
				<name>ThirdPartyAcctRef</name>
				<value>$AcctRef</value>
			</return>
			<return>
				<name>senderID</name>
				<value>MOM</value>
			</return>
			<return>
				<name>StatusCode</name>
				<value>222</value>
			</return>
			<return>
				<name>StatusDesc</name>
				<value>$StatusDesc</value>
			</return>
			<return>
				<name>MOMTransactionID</name>
				<value>$MOMTransactionID</value>
			</return>
		</ns1:processRequestResponse>
	</soapenv:Body>
</soapenv:Envelope>
XML;
	
		return $RESPONSE_BODY;
	}
}
