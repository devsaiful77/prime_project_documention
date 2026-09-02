<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BallanceInQueryController extends Controller
{

    public $username;
    public $password;
    public $token_url; 
    public $Pull_API_URL; 
    public $loan_api_request; 


    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:superadmin|admin|wformComplain'])->only('index', 'newWForm', 'submitWform', 'newComplaint', 'submitComplaint');

        $this->middleware(['role_or_permission:superadmin|admin|supportExecutive'])->only('handler');
        $this->middleware(['role_or_permission:superadmin|admin|wformComplain|supportExecutive'])->only('wFormDetails', 'complaintDetails');
        $this->middleware(['role_or_permission:superadmin|admin|revokeAssignedRequest'])->only('unassign');
        $this->middleware(['role_or_permission:superadmin|admin|logger|supportExecutive'])->only('uploadNewAttachment');
        $this->middleware(['role_or_permission:superadmin|admin|supportExecutive'])->only('workingOnHandler');
        $this->middleware(['role_or_permission:superadmin|admin|accessDashboard|accessReport'])->only('wFormReportDetails', 'complaintReportDetails', 'nonCustomerReportDetails');

        $this->middleware(['role_or_permission:superadmin|admin|dummyWformComplaint|accessIssueCategories'])->only('newDummyWForm', 'newDummyComplaint', 'submitDummyWform', 'submitDummyComplaint');

        $this->middleware(['role_or_permission:superadmin|admin|ceAnalysis'])->only('complaintClosing', 'complaintClosingSubmit', 'complaintClosingDetails');
        $api_credential = DB::table('api_credential')->first();
        if (!empty($api_credential)) {
            $this->loan_api_request = $api_credential->loan_api_request;
            $this->Pull_API_URL = $api_credential->Pull_API_URL;
            $this->token_url = $api_credential->token_url;
            $this->username = $api_credential->user_name;
            $this->password = base64_decode($api_credential->user_password);
        }
    }



    public function inquery(Request $request)
    {
        // Validate input
        $request->validate([
            'accountNumber' => 'required|string',
            'customerId' => 'nullable|string',
        ]);

        // Determine API URL and column based on account number
        $filterAccountId = $request->accountNumber;
        $accountInfo = $this->validateAccountNumber($request);
        $restUrlAccount = $accountInfo['url'];
        $column = $accountInfo['column'];
        $accno = $accountInfo['accno'];

        // Get access token

        $accessToken = $this->getAccessToken($this->token_url, $this->username, $this->password);

        // Check if token retrieval failed
        if (!isset($accessToken['responseCode'])) {
            return $this->handleError('Token retrieval failed. Please Contact API Team !');
        }

        if ($accessToken['responseCode'] != 200) {
            return $this->handleError('Token Issue. Please Contact API Team !');
        }

        // Prepare API request
        $post_data = json_encode([$column => $accno]);
        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $accessToken['data']['accessToken'],
        ];

        try {
            // Call the API

            $response = $this->getCustomerInfoByAPI($restUrlAccount, $post_data, $headers);
            $responseData = $this->handleApiResponse($response);

            // Handle API response errors
            if (isset($responseData['errorSMSAPI'])) {
                return $this->handleError($responseData['errorSMSAPI']);
            }

            // Process successful response
            if (isset($responseData['responseCode']) && $responseData['responseCode'] == 200) {
                // Filter accounts if accountNumber does not start with 'LD'
                if (substr($request->accountNumber, 0, 2) != 'LD') {
                    $matchingAccount = $this->filterAccountById($responseData, $filterAccountId, false);

                }else{
                    $matchingAccount = $this->filterAccountById($responseData, $filterAccountId, true);
                }

                if ($matchingAccount) {
                    return response()->json([
                        'success' => true,
                        'data' => $matchingAccount,
                    ]);
                } else {
                    return $this->handleError("No account found with accountId: $filterAccountId");
                }
            }
        } catch (\Exception $e) {
            Log::error('API Request Failed: ' . $e->getMessage());
            return $this->handleError('API Request Failed. Please try again later.');
        }
    }

    /**
     * Validate account number and determine API details.
     */
    private function validateAccountNumber(Request $request)
    {
        if (substr($request->accountNumber, 0, 2) == 'LD') {
            return [
                'url' => $this->loan_api_request,
                'column' => 'customerId',
                'accno' => $request->customerId,
            ];
        } else {
            return [
                'url' => $this->Pull_API_URL,
                'column' => 'accountNumber',
                'accno' => $request->accountNumber,
            ];
        }
    }

    /**
     * Filter accounts by accountId.
     */
    private function filterAccountById($responseData, $filterAccountId, $checkLn)
    {
        if($checkLn == true){
            if (isset($responseData['data']['loanList'])) {
                foreach ($responseData['data']['loanList'] as $loan) {
                    if ($loan['accountId'] === $filterAccountId) {
                        return $loan['outstandingAmount'];
                    }
                }
            }
        }else{

            if (isset($responseData['data']['accounts'])) {
                foreach ($responseData['data']['accounts'] as $account) {
                    if ($account['accountId'] === $filterAccountId) {
                        return $account['balance'];
                    }
                }
            }
        }

    }   

    /**
     * Handle API response errors.
     */
    private function handleApiResponse($response)
    {
        $responseData = json_decode($response, true);

        if (isset($responseData['curlMsg'])) {
            return ['errorSMSAPI' => $responseData['curlMsg']];
        }

        if ((isset($responseData['status']) && $responseData['status'] == 400) || 
            (isset($responseData['responseCode']) && $responseData['responseCode'] != 200)) {
            return ['errorSMSAPI' => $responseData['messages'][0] ?? 'Data Not Found Please Contact API Team !'];
        }

        return $responseData;
    }

    /**
     * Handle errors and return a consistent error response.
     */
    private function handleError($message)
    {
        Log::error($message);
        return response()->json([
            'success' => false,
            'error' => $message,
        ], 400);
    }



    public function getAccessToken($url, $username, $password)
    {

        $data = json_encode([
                'username' => $username,
                'password' => $password,
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if needed

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $timeout = 10;

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            if (curl_errno($ch) == CURLE_OPERATION_TIMEDOUT) {
                $response = '{"curlMsg": "API operation timed out after ' . ($timeout * 1000) . ' milliseconds. Any Queries Please Contact API Team for more information"}';
            } else {
                $response = '{"curlMsg": "API operation Issue . Please Contact API Team for more information"}';

            }
        }
        curl_close($ch);



        return json_decode($response, true);


    }



    public function getCustomerInfoByAPI($restUrl, $post_data, $headers)
    {
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_URL, $restUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $timeout = 10;
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            if (curl_errno($ch) == CURLE_OPERATION_TIMEDOUT) {
                $response = '{"curlMsg": "API operation timed out after ' . ($timeout * 1000) . ' milliseconds. Any Queries Please Contact API Team for more information"}';
            } else {
                $response = '{"curlMsg": "API operation Issue . Please Contact API Team for more information"}';

            }
        }
        curl_close($ch);

        return $response;


    }
}
