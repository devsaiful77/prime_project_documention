<?php


namespace App\Services\CI;

use App\CustomerInterfaceToken;
use App\IssueConfig;
use App\issueFieldsetGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Traits\LogCurlError;

class ApiAccessTokenService
{
    use LogCurlError;

    public static function getAccessToken()
    {
        $api_credential = DB::table('api_credential')->first();
        $url = $api_credential->token_url;
	
        $data = json_encode([
            'username' => $api_credential->user_name,
            'password' => base64_decode($api_credential->user_password),
        ]);

         $ch = curl_init($url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, [
             'Content-Type: application/json',
         ]);
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if needed
         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
         curl_setopt($ch, CURLOPT_TIMEOUT, 30);

         $response = curl_exec($ch);
	 $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

         if (curl_errno($ch)) {
             self::logCurlError($ch, $url);
         }else{
             self::logHttpResponse($httpCode, $response, $url);
         }

	 curl_close($ch);

        // $response = '{
        //     "data": {
        //         "accessToken": "e7253c93e2f66709*****77912a954fb6d0acfbbc"
        //     },
        //     "responseCode": 200,
        //     "messages": [
        //         "Operation Successful"
        //     ]
        //    }';

        return json_decode($response, true);
    }
}
