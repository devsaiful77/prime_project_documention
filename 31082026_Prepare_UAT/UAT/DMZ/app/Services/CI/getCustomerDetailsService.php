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
use Illuminate\Support\Facades\DB;

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


    public static function getCustomerDetails($prams = [])
    {
        $api_credential = DB::table('api_credential')->first();
        $endpoint = $api_credential->Pull_API_URL;
        $accessToken = $prams['accessToken']['data']['accessToken'];

        $post_data = json_encode([
            'accountNumber' => $prams['account_number'],
        ]);

        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $accessToken,
        ];

        self::osbApiRequestResponse([
            'account_number' => $prams['account_number'],
            'cif_number' => '',
            'type' => 1,
            'url' =>  $accessToken,
            'service' => 'getCustomerDetails',
            'json_node' => json_encode($post_data)
        ]);


        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $errorMgs = curl_error($ch);
            Log::error('getCustomerDetails Curl Error:', [$errorMgs]);
        }
        curl_close($ch);

        // if (config('app.env') == 'local') {
        //     $response = file_get_contents(base_path('/app/APIDemoResponse/getCustomerDetails.json'));
        // }

        return $response;
    }


    // Join Account
    public static function getJoinAccount($prams = [])
    {
        $api_credential = DB::table('api_credential')->first();
        $endpoint = $api_credential->BPID_API_URL;
        $accessToken = $prams['accessToken']['data']['accessToken'];

        $post_data = json_encode([
            'accountNumber' => $prams['account_number'],
        ]);

        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $accessToken,
        ];

        self::osbApiRequestResponse([
            'account_number' => $prams['account_number'],
            'cif_number' => '',
            'type' => 1,
            'url' =>  $accessToken,
            'service' => 'getJoinCustomerDetails',
            'json_node' => json_encode($post_data)
        ]);


        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $errorMgs = curl_error($ch);
            Log::error('getJoinCustomerDetails Curl Error:', [$errorMgs]);
        }
        curl_close($ch);

        // if (config('app.env') == 'local') {
        //     $response = file_get_contents(base_path('/app/APIDemoResponse/getCustomerDetailsJoinAcc.json'));
        // }

        return $response;
    }


    public static function osbApiRequestResponse($params = array())
    {
        /*type Credit Card = 1, Account = 2, Debit Card = 3, Loan = 4, Trade = 5 */
        $modelName = new OSBAPIResponse;
        $modelName->reference_number = (!empty($params['reference_number'])) ? $params['reference_number'] : '';
        $modelName->account_number = (!empty($params['account_number'])) ? $params['account_number'] : '';
        $modelName->cif_number = (!empty($params['cif_number'])) ? $params['cif_number'] : '';
        $modelName->type = (!empty($params['type'])) ? $params['type'] : 0;
        $modelName->service = (!empty($params['service'])) ? $params['service'] : 0;
        $modelName->url = (!empty($params['url'])) ? $params['url'] : '';
        $modelName->json_node = (!empty($params['json_node'])) ? $params['json_node'] : '';
        $modelName->log_user = (!empty(Auth::user()->user_id)) ? Auth::user()->user_id : '';
        $modelName->status_code = (!empty($params['status_code'])) ? $params['status_code'] : '';
        $modelName->status_msg = (!empty($params['status_msg'])) ? $params['status_msg'] : '';
        $modelName->save();
    }

}
