<?php

namespace App\Http\Controllers\CI;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ApiCallingController extends Controller
{
    public function api()
    {
	    // Define your headers and payload
	    $password = "eyJpdiI6ImJLUXRHZG9ZeW9VUjNVdVBySTR0dFE9PSIsInZhbHVlIjoiV3NLQXhCRFdTamFYcEMrSi9k
Qm9raVdZdGpRemdDRjZWY1h3QTVkZnhoSldkVmg3bXFpczh3dTBMWGZEdnljUyIsIm1hYyI6ImEzMWYz
NDYxNjQ2OWExMTA0MTczOTc2MzYxYjVjNjVmMTYyNzkyYTdiNjljMjk4Y2IxYWRjNTVhNGUwNGUxODgi
LCJ0YWciOiIifQ==";
	    $password = trim(str_replace(["\r", "\n"], '', $password));
	    $username = "PBCIServe2026";
	    $basicAuth = base64_encode($username . ':' . $password);
	    $headers = [
	    'Authorization: Basic ' . $basicAuth,
	    'Accept' => 'application/json',
	    'Content-Type' => 'application/json',
            'username' => 'PBCIServe2026',
            'password' => 'eyJpdiI6ImJLUXRHZG9ZeW9VUjNVdVBySTR0dFE9PSIsInZhbHVlIjoiV3NLQXhCRFdTamFYcEMrSi9k
Qm9raVdZdGpRemdDRjZWY1h3QTVkZnhoSldkVmg3bXFpczh3dTBMWGZEdnljUyIsIm1hYyI6ImEzMWYz
NDYxNjQ2OWExMTA0MTczOTc2MzYxYjVjNjVmMTYyNzkyYTdiNjljMjk4Y2IxYWRjNTVhNGUwNGUxODgi
LCJ0YWciOiIifQ==',
        ];

        $payload = [
            "sessionId" => "43255289",
            "myPrimeId" => "PB1212141",
        ];

        $url = url('/') . '/api/access-CI';

        // Send the HTTP GET request with headers and payload
/* $response = Http::withHeaders($headers)
            ->post($url, $payload);

        // Check if the request was successful
        if ($response->successful()) {
            $responseData = $response->json();
            return redirect($responseData['ci_web_url']);

        } else {
            $errorMessage = $response->body();
            return $errorMessage;
        }
    }*/



            $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload, // IMPORTANT
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_VERBOSE => true, // debug purpose (remove in production)
        ]);

        $response = curl_exec($ch);

            if (curl_errno($ch)) {
		 $error_msg = curl_errno($ch);
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
