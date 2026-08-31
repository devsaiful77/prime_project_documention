<?php


namespace App\Services\BPID;

use App\OSBAPIResponse;
use App\WDEApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TokenApiService
{
    public static function ApiCalling($ccid = '')
    {
        // base url for UAT
        $api_credential = DB::table('api_credential')->first();
        $restUrl = 'https://pblcon-uat-lb.primebank.com.bd:8443/api/v1/user/login';

        $post_data = [
            'password' => base64_encode($api_credential->user_password),
            'username' => $api_credential->user_name,
        ];

        $headers = [
            'Content-Type: application/json',
        ];

        self::osbApiRequestResponse([
            'account_number' => $ccid,
            'cif_number' => '',
            'type' => 1,
            'url' => $restUrl,
            'service' => 'Token Service',
            'json_node' => json_encode($post_data)
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_URL, $restUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $errorMgs = curl_error($ch);
            Log::error('Token Service Curl Error:', [$errorMgs]);

        }
        curl_close($ch);


        if (!empty($response)){

            $responseData = json_decode($response, true);

            self::osbApiRequestResponse([
                'account_number' => $ccid,
                'cif_number' => '', 'type' => 2,
                'url' => $restUrl,
                'service' => 'Token Service',
                'json_node' => $response
                ]);

            if (curl_errno($ch)) {
                if (curl_errno($ch) == CURLE_OPERATION_TIMEDOUT) {
                    $response = '{"curlMsg": "API operation timed out after ' . ($timeout * 1000) . ' milliseconds. Any Queries Please Contact API Team for more information"}';
                } else {
                    $response = '{"curlMsg": "API operation Issue . Please Contact API Team for more information"}';

                }
            }
            curl_close($ch);
	    return json_decode($response, true);
            
        }else{
            return false;
        }
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
