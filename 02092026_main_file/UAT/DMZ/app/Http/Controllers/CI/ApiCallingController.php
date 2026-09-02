<?php

namespace App\Http\Controllers\CI;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ApiCallingController extends Controller
{
    public function api()
    {
        // Define your headers and payload
        $headers = [
            'Authorization' => 'test',
            'Accept' => 'application/json',
 	    'Content-Type' => 'application/json',
            'username' => 'PrIMeCIFbl',
            'password' => 'eyJpdiI6IkwxV3VadjEyTnlJQXl6YWxqMGNoVnc9PSIsInZhbHVlIjoiVkZzcmNRdnk5WFI4TEVyZXVvckduYVFjazR6SWNvWkYwNUpuY2J3eTYvbz0iLCJtYWMiOiJlNGRkNzJhNmY4ZWI2Y2ZlZDY0YzM3MzMxN2RiOWU0ODJiNWNhNDEyYTAwM2E3Y2ViZjQ2NGU3OWI1MDU1MThhIiwidGFnIjoiIn0=',
        ];

        $payload = [
            "sessionId" => "106310",
            "myPrimeId" => "PB1071240",
        ];

        $url = 'https://primeservecust-uat.primebank.com.bd:8443/api/access-CI';
	
	$ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);

	if(curl_errno($ch)){
	    $error_msg = curl_error($ch);
	    curl_close($ch);
	    return $error_msg;
	}

	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if($httpCode >= 200 && $httpCode < 300){
	    $responseData = json_decode($response, true);
	    if(isset($responseData['ci_web_url'])){
		return redirect($responseData['ci_web_url']);
	    }
            return $response;
	}else{
	    return $response;
	}
    }
}
