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
use App\Traits\LogCurlError;

class PBSessionIdApiService
{
    use LogCurlError;

    public static function checkPrimeSession($primeId = null, $sessionId = null)
    {

        // base url for UAT
        $ciApimodel = new CustomerInterfaceAPI();
        $ciApiObj = $ciApimodel->where('product_type', 'validate_myprime_id')->first();
        $restUrl = $ciApiObj->endpoint;

        $resCif = '';
        $resCallbackUrl = '';

        if (!empty($primeId) && !empty($sessionId)) {
            $post_data = [
                'myPrimeId' => $primeId,
                'sessionId' => $sessionId,
            ];

            $headers = [
                'Content-Type: application/json',
                'Authorization: Basic cHJpbWVzZXJ2ZTpwJGVydmUxMjMjQCE='
            ];

            Log::error('validate_myprime_id Header', [$headers]);

            CIRequestResponseService::ciRequestResponse([
                'myPrimeId' => $primeId,
                'type' => 1,
                'endpoint' => $restUrl,
                'json_node' => json_encode($post_data),
            ]);


            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_URL, $restUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_ENCODING, '');
                curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if (curl_errno($ch)) {
                    self::logCurlError($ch, $restUrl);
                } else {
                    self::logHttpResponse($httpCode, $response, $restUrl);
                }

                curl_close($ch);

                // $response = '{"success":true,"data":{"loginId":"1fd4027460ea131cda5785309dc07220","cfId":"1818606"}}';
            } catch (\Exception $e) {
                Log::error('validate_myprime_id Log', [$e->getMessage()]);
            }

            if (!empty($response)) {
                $responseData = json_decode($response, true);
                //Log::info('validate_myprime_id Success', [$response]);
                CIRequestResponseService::ciRequestResponse([
                    'myPrimeId' => $primeId,
                    'type' => 2,
                    'endpoint' => $restUrl,
                    'json_node' => json_encode($response),
                ]);
                if ($responseData['success'] == true) {
                    $resCif = !empty($responseData['data']['cfId']) ? $responseData['data']['cfId'] : '';
                    $resCallbackUrl = !empty($responseData['data']['callbackUrl']) ? $responseData['data']['callbackUrl'] : '';
                }
            }
        }
        //Log::error('My Prime Session Validation Failed!', ['primeId' => $primeId, 'sessionId' => $sessionId]);
        return [
            'resCif' => $resCif,
            'resCallbackUrl' => $resCallbackUrl,
        ];
    }
}
