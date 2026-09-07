<?php

namespace App\Http\Controllers;

use App\BpId;
use App\OSBAPIResponse;
use App\Services\BPID\CustomerDetailsService;
use Illuminate\Support\Facades\Session;
use App\UnitItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BpIdController extends Controller
{

    public $checker = false;
    public $username;
    public $password;
    private $localEnvironment = true;

    public function __construct()
    {
        $api_credential = DB::table('api_credential')->first();
        if (!empty($api_credential)) {
            $this->username = $api_credential->user_name;
            $this->password = base64_decode($api_credential->user_password);
        }

        if (config('app.env') != 'local') {
            $this->localEnvironment = false;
        }
    }

    public function firstAccountApi(Request $request)
    {
	$fullUrl = Session::get('searchDataForView', '');
        $queryString = parse_url($fullUrl, PHP_URL_QUERY);
        parse_str($queryString, $params);
        $categoryTitle = $params['categoryTitle'] ?? null;
	

        $accountDataArray = [];
        $account_number = $request->account_number;

	$w_form_type = 1192;

        $api_credential = DB::table('api_credential')->first();
        $accessToken = $this->getAccessToken($api_credential->token_url, $this->username, $this->password);

        if (empty($accessToken)) {
            return response()->json(['data' => []]);
        }

        
        // ── 1. Customer Details (single holder info) ──────────────────────────
        $responseCD = CustomerDetailsService::getCustomerDetails([
            'account_number' => $account_number,
            'accessToken'    => $accessToken
        ]);

        // ── 2. Join Account (joint holders + nominees) ────────────────────────
        $responseAC = CustomerDetailsService::getJoinAccount([
            'account_number' => $account_number,
            'accessToken'    => $accessToken
        ]);


        if (empty($responseAC) || empty($responseCD)) {
            return response()->json(['data' => []]);
        }

        $responseAC = json_decode($responseAC, true);
        $responseCD = json_decode($responseCD, true);

        $dataAC = $responseAC['data'] ?? [];   // joint account data
        $dataCD = $responseCD['data'] ?? [];   // customer details data


        /* ===================== Applicant Type Logic ====================== */
        $accountsCount = count($dataAC['accounts'] ?? []);
        if ($accountsCount > 1) {
            $accountDataArray['applicantType']  = "Second";
            $accountDataArray['applicantCount'] = $accountsCount;
        } else {
            $accountDataArray['applicantType']  = "First";
            $accountDataArray['applicantCount'] = 1;
        }

        // nominee count
        $nomineeCount = count($dataAC['nomineeAccounts'] ?? []);
        if ($nomineeCount > 1) {
            $accountDataArray['nomineeCount'] = $nomineeCount;
        } else {
            $accountDataArray['nomineeCount'] = 1;
        }


        /* ===================== Root level mapping (from getCustomerDetails) ====================== */
        $accountDataArray['bpType']         = 'Individual';
        $accountDataArray['accountNumber']  = $dataAC['accountNumber']  ?? $dataCD['accounts'][0]['accountId'] ?? null;
        $accountDataArray['accountTitle']   = $dataAC['accountTitle']   ?? $dataCD['customerTitle'] ?? null;
        $accountDataArray['customerId']     = $dataCD['customerId']      ?? null;
        $accountDataArray['customerEmail']  = $dataCD['customerEmail']   ?? null;

        $phone = $dataCD['customerPhone'] ?? null;

        if ($phone) {
            $phone = preg_replace('/^\+?88/', '', $phone);
        }

        $accountDataArray['customerPhone'] = $phone;
        $accountDataArray['customerMobile'] = $phone;

        //$accountDataArray['customerType']   = $dataCD['customerType']    ?? null;
        $accountDataArray['customerType']   = $categoryTitle;

        // Branch from getCustomerDetails > accounts[0]
        $accountDataArray['branchName']     = $dataCD['accounts'][0]['branchName'] ?? null;

	$routing_number = DB::table('branch_routing')->where('branch_name',$accountDataArray['branchName'])->first();

        $accountDataArray['branchCode']     = $routing_number ? $routing_number->routing_number : null;


        /* Address (from getCustomerDetails contactAddress fields) */
        $addressParts = array_filter([
            $dataCD['contactAddress1'] ?? null,
            $dataCD['contactAddress2'] ?? null,
            $dataCD['contactAddress3'] ?? null,
            $dataCD['contactAddress4'] ?? null,
        ]);
        $fullAddress = implode(' ', $addressParts);

        $fullAddress = str_replace(['/', '\\'], ['-', ' '], $fullAddress);

        $accountDataArray['presentAddress']   = $fullAddress;
        $accountDataArray['permanentAddress'] = $fullAddress;


        /* ===================== Primary / Joint Account Holders (from getJoinAccount) ====================== */
        if (!empty($dataAC['accounts'])) {
            foreach ($dataAC['accounts'] as $index => $account) {
                $i = $index + 1;
                $accountDataArray["name_$i"]       = $account['name'] ?? null;
                $accountDataArray["dob_$i"]        = !empty($account['dob']) ? date('Y-m-d', strtotime($account['dob'])) : null;
                $accountDataArray["gender_$i"]     = $account['gender']        ?? null;
                $accountDataArray["nid_$i"]        = $account['cusLegalDoc']   ?? null;
                $accountDataArray["father_$i"]     = $account['fathersName']   ?? null;
                $accountDataArray["mother_$i"]     = $account['mothersName']   ?? null;

                $mobile = $account['contactNo'] ?? null;
                $accountDataArray["mobile_$i"]     = $mobile ? preg_replace('/^\+?88/', '', $mobile) : null;

                $accountDataArray["email_$i"]      = $account['email']         ?? null;
                $accountDataArray["occupation_$i"] = $account['occupation']    ?? null;
                $accountDataArray["tin_$i"]        = $account['tin']           ?? null;
            }
        }

        /* ===================== Nominee Mapping (from getJoinAccount) ====================== */
        if (!empty($dataAC['nomineeAccounts'])) {
            foreach ($dataAC['nomineeAccounts'] as $index => $nominee) {
                $i = $index + 1;

                $nomineeAddr = $nominee['nomineeAddress'] ?? null;
                if ($nomineeAddr) {
                    $nomineeAddr = str_replace(['/', '\\'], ['-', ' '], $nomineeAddr);
                }

                $accountDataArray["nomineeName$i"]     = $nominee['nomineeName']     ?? null;
                $accountDataArray["nomineeRelation$i"] = $nominee['nomineeRelation'] ?? null;
                $accountDataArray["nomineeShare$i"]    = $nominee['nomineeShare']    ?? null;
                $accountDataArray["nomineeNID$i"]      = $nominee['nomineeLegalDoc'] ?? null;
                $accountDataArray["nomineeAddress$i"]  = $nomineeAddr;
                $accountDataArray["nomineeDOB$i"]      = !empty($nominee['nomineeDOB'])
                    ? date('Y-m-d', strtotime($nominee['nomineeDOB']))
                    : null;
            }
        }

        /* ===================== Raw arrays (for frontend convenience) ====================== */
        $accountDataArray['accounts']        = $dataAC['accounts']        ?? [];
        $accountDataArray['nomineeAccounts'] = $dataAC['nomineeAccounts'] ?? [];

        /* Defaults */
        $accountDataArray['bankName']  = 'Prime Bank PLC.';
        $accountDataArray['residence'] = ($dataCD['residence'] ?? '') === 'BD' ? 'Resident' : 'Non-Resident';


        return response()->json([
            'data' => $accountDataArray
        ]);
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

    //$url = 'https://pblcon-uat-lb.primebank.com.bd:8443/api/v1/user/login';
    public function getAccessToken($url, $username, $password)
    {
        if ($this->localEnvironment == true) {
            $response = '{
                "data": {
                    "accessToken": "e7253c93e2f66709*****77912a954fb6d0acfbbc"
                },
                "responseCode": 200,
                "messages": [
                    "Operation Successful"
                ]
               }';
        } else {
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

            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $timeout = 30;


            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                if (curl_errno($ch) == CURLE_OPERATION_TIMEDOUT) {
                    $response = '{"curlMsg": "API operation timed out after ' . ($timeout * 1000) . ' milliseconds. Any Queries Please Contact API Team for more information"}';
                } else {
                    $response = '{"curlMsg": "API operation Issue . Please Contact API Team for more information"}';
                }
            }
            curl_close($ch);
        }
        return json_decode($response, true);
    }


    private function sameIssueRequestCheck($accountNumbers, $issue_id)
    {
        $sameIssueRequestFound = true;
        $unitItems = UnitItem::select('master_id', 'issues_from')->where('master_id', $issue_id)->first();

        if ($unitItems->issues_from == 'wform') {
            $findSameUserIssue = DB::table('w_form')
                ->leftJoin('reference', 'w_form.reference_number', 'reference.reference_number')
                ->select('w_form.reference_number')
                ->where('w_form.account_number', '=', $accountNumbers)
                ->where('w_form.w_form_type', '=', $issue_id)
                ->whereNotIn('reference.form_status', [11, -7])
                ->first();

            if (empty($findSameUserIssue)) {
                $sameIssueRequestFound  = false;
            }
        } elseif ($unitItems->issues_from == 'complaint') {
            $findSameUserIssue = DB::table('complaint')
                ->leftJoin('reference', 'complaint.reference_number', 'reference.reference_number')
                ->select('complaint.reference_number')
                ->where('complaint.account_number', '=', $accountNumbers)
                ->where('complaint.complaint_type', '=', $issue_id)
                ->whereNotIn('reference.form_status', [11, -7])
                ->first();

            if (empty($findSameUserIssue)) {
                $sameIssueRequestFound  = false;
            }
        }

        return $sameIssueRequestFound;
    }


    public function getBpIdWithTreasury(Request $request)
    {
        $bpId = BpId::where('account_number', $request->account_number)->where('bp_id', '!=', null)->orderBy('id', 'DESC')->first();
        return response()->json([
            'status' => true,
            'bpId' => $bpId
        ]);
    }
    
}
