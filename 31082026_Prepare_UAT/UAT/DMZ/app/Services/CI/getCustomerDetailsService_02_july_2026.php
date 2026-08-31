<?php


namespace App\Services\CI;

use App\IssueConfig;
use App\issueFieldsetGroup;
use App\OSBAPIResponse;
use App\WDEApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use App\CustomerInterfaceAPI;

class getCustomerDetailsService
{
    public static function getCustomerDetailsById($checkSession = null)
    {

        $ciApimodel = new CustomerInterfaceAPI();
        $ciApiObj = $ciApimodel->where('product_type', 'account')->first();
        $restUrl = $ciApiObj->endpoint;

        $cus_email = '';
        $cus_phone = '';

        $accessToken = ApiAccessTokenService::getAccessToken();

        if (!empty($accessToken) && !empty($accessToken['responseCode']) && $accessToken['responseCode'] == 200){
            $headers = [
                'Content-Type: application/json',
                'Authorization: ' . $accessToken['data']['accessToken'],
            ];

            $postData = json_encode([
                'customerId' => $checkSession['resCif'],
            ]);

            $msc = microtime(true);

            CIRequestResponseService::ciRequestResponse([
                'cif_number' => $checkSession['resCif'],
                'product_type' => 2,
                'type' => 1,
                'endpoint' => $restUrl,
                'json_node' => $postData,
            ]);

            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_URL, $restUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                $error_msg = curl_errno($ch);
            }
	    curl_close($ch);
	    
            // $response = file_get_contents(base_path('/app/APIDemoResponse/getCustomerDetails.json'));

            if (!empty($response)){
                $responseData = json_decode($response, true);
                $customerCode = $responseData['responseCode'] ?? null;

                $msc = microtime(true) - $msc;
                $execution_time = number_format($msc,2);

                CIRequestResponseService::ciRequestResponse([
                    'cif_number' => $checkSession['resCif'],
                    'product_type' => 2,
                    'type' => 2,
                    'endpoint' => $restUrl,
                    'json_node' => $response,
                    'execution_time' => $execution_time,
                ]);

                if ($customerCode == 200){
                    if (!empty($responseData['data'])){
                        $cus_email = !empty($responseData['data']['customerEmail']) ? $responseData['data']['customerEmail'] : '';
                        $cus_phone = !empty($responseData['data']['customerPhone']) ? $responseData['data']['customerPhone'] : '';
                    }
                }
            }
        }

        return [
            'email' => $cus_email,
            'phone' => $cus_phone,
        ];

    }
}
