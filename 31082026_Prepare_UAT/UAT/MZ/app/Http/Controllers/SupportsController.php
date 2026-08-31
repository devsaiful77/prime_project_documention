<?php

/******************************************
 **** This items change For Production ****
 ******************************************

1   ALl CI Group ID chnage To 185


****************************************/

namespace App\Http\Controllers;

use App\Unit;
use App\BpId;
use App\IssueGroupMember;
use DateTime;
use App\WForm;
use Exception;
use App\Source;
use App\Comment;
use App\Sequence;
use App\SMSEmail;
use App\UnitItem;
use App\UserUnit;
use App\Complaint;
use App\GroupInfo;
use App\Reference;
use App\UnitChild;
use App\WFormType;
use Carbon\Carbon;
use App\Attachment;
use App\Department;
use App\FormStatus;
use App\WorkingDay;
use App\IssueConfig;
use App\NonCustomer;
use App\OutgoingSMS;

use App\ProductType;

use App\SegmentCode;
use App\WformMaster;
use App\SubgroupInfo;
use App\ComplaintType;
use App\Enum\FlowEnum;
use App\Http\Requests;
use App\IssueWorkflow;
use App\OutgoingEMAIL;
use App\WformCategory;
use GuzzleHttp\Client;
use App\OSBAPIResponse;
use App\ComplaintClosing;
use App\WFormTypeHistory;
use App\AttachmentHistory;
use App\BranchCode;
use App\ComplaintFormType;
use App\Enum\IssueTypeEnum;
use App\IssueGroupWorkflow;
use Illuminate\Http\Request;
use App\IssueCheckListConfig;
use App\Services\UtilService;
use App\IssueConditionalField;
use App\ComplaintFormTypeHistory;
use App\Services\TokenApiService;
use App\Services\WorkFlowService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\WFormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ComplaintRequest;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\WFormDummyRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\WFormUpdateRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ComplaintDummyRequest;
use App\Http\Requests\WorkingOnHanderRequest;
use App\Http\Requests\AttachmentUploadRequest;
use App\Http\Requests\ComplaintClosingRequest;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as ImageResizer;

class SupportsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $username;
    public $password;
    private $localEnvironment = true;


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

        $this->middleware(['role_or_permission:superadmin|admin|ceAnalysis|complaint_closing'])->only('complaintClosing', 'complaintClosingSubmit', 'complaintClosingDetails');
        $api_credential = DB::table('api_credential')->first();
        if (!empty($api_credential)) {
            $this->username = $api_credential->user_name;
            // $this->password = $api_credential->user_password;
            $this->password = base64_decode($api_credential->user_password);
        }

        if (config('app.env') != 'local') {
            $this->localEnvironment = false;
        }
    }


    //$url = 'https://pblcon-uat-lb.primebank.com.bd:8443/api/v1/user/login';
    public function getAccessToken($url, $username, $password)
    {
        if($this->localEnvironment == true){
            $response = '{
                "data": {
                    "accessToken": "e7253c93e2f66709*****77912a954fb6d0acfbbc"
                },
                "responseCode": 200,
                "messages": [
                    "Operation Successful"
                ]
               }';
        }else{
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

    private function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    private function encrypt_decrypt_rtgs($action, $string)
    {
        $encryptionKey = '59367512309356D8AC80BF0807DA5D49';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = $encryptionKey;
        $secret_iv = 'D12C4GJ5917638D9';
        // hash
        $key = $secret_key;//hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = $secret_iv;//substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'ENC') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 1, $iv);
            $output = base64_encode($output);
        } else if ($action == 'DEC') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 1, $iv);
        }

        return $output;
    }

    public function getCustomerInfoByAPI($restUrl, $post_data, $headers)
    {
        if($this->localEnvironment == true){
            if($restUrl == 'https://pblcon-uat-lb.primebank.com.bd:8443/cbs-service/api/v1/getCustomerDetails'){
                $responseAC = '{
                    "data": {
                        "customerId": "717587",
                        "customerTitle": "ABU NASER MD. BIN HOSSAIN",
                        "customerType": "STAFF",
                        "customerEmail": "naser.prime@gmail.com",
                        "customerPhone": "+8801611344344",
                        "customerDoB": "19801201",
                        "customerNID": "8231001754",
                        "jointCustomerId": "",
                        "contactAddress1": "HOUSE-36 ROAD 9/5 BLOCK G",
                        "contactAddress2": "KHILGAON",
                        "contactAddress3": "KHILGAON,DHAKA",
                        "contactAddress4": "BANGLADESH",
                        "customerTarget": "999",
                        "fatherName": "AMIR HOSSAIN",
                        "motherName": "NILUFA AKTER",
                        "spouseName": "TANFINA BINTA NAZMA",
                        "nationality": "BD",
                        "docType": "NATIONAL.ID",
                        "tinNumber": "790986168730",
                        "gender": "MALE",
                        "residence": "BD",
                        "occupation": "PROFESSION",
                        "passportNumber": "",
                        "passportAuthority": "",
                        "birthRegistration": "",
                        "businessIdNumber": "",
                        "drivingLicense": "",
                        "customerTitlePrefix": "MR",
                        "accounts": [
                            {
                                "accountId": "2145216009705",
                                "customerId": null,
                                "alternateAccountId": "14521040011765",
                                "accountCategory": "6001",
                                "categoryTitle": "Saving Accounts Monthly Basis",
                                "currency": "BDT",
                                "dormant": null,
                                "joinAccount": null,
                                "accountOpenDate": "20090517",
                                "accountStatus": null,
                                "branchCode": "BD0010145",
                                "branchName": "LALDIGHI EAST BRANCH",
                                "balance": 24304.52,
                                "onlineActualBal": 24304.52,
                                "accountTitle": "ABU NASER MD. BIN HOSSAIN"
                            },
                            {
                                "accountId": "2145217004648",
                                "customerId": null,
                                "alternateAccountId": "14521080013140",
                                "accountCategory": "6001",
                                "categoryTitle": "Saving Accounts Monthly Basis",
                                "currency": "BDT",
                                "dormant": "Y",
                                "joinAccount": "754683",
                                "accountOpenDate": "20090910",
                                "accountStatus": null,
                                "branchCode": "BD0010145",
                                "branchName": "LALDIGHI EAST BRANCH",
                                "balance": 5.95,
                                "onlineActualBal": 5.95,
                                "accountTitle": "ABU NASER & TANFINA"
                            },
                            {
                                "accountId": "3133216006695",
                                "customerId": null,
                                "alternateAccountId": "13321010000002",
                                "accountCategory": "6018",
                                "categoryTitle": "Mudaraba Savings Account (Daily)",
                                "currency": "BDT",
                                "dormant": null,
                                "joinAccount": null,
                                "accountOpenDate": "20120228",
                                "accountStatus": null,
                                "branchCode": "BD0010133",
                                "branchName": "IBB MIRPUR BRANCH",
                                "balance": 574568.02,
                                "onlineActualBal": 574568.02,
                                "accountTitle": "ABU NASER MD. BIN HOSSAIN"
                            }
                        ]
                    },
                    "responseCode": 200,
                    "messages": [
                        "Operation Successful"
                    ]
                }';

                return $responseAC;
            }else{
                $responseLoanData = '{
                    "data": {
                        "loanList": [
                            {
                                "branchCode": "BD0010104",
                                "accountId": "LD2231706205",
                                "loanType": "Staff Consumer",
                                "currency": "BDT",
                                "customerId": "1338246",
                                "customerTitle": "SHUSOVON ROY",
                                "outstandingAmount": 243462.64,
                                "valueDate": "20221113",
                                "maturityDate": "20291026",
                                "interestRate": 6,
                                "totalInterest": 40.58,
                                "customerContactNo": null,
                                "customerAddress": null,
                                "lcNo": null,
                                "disbursementAmount": 300000,
                                "installmentType": "1M",
                                "installmentAmount": 0,
                                "installmentRepaymentDate": null,
                                "overdueAmount": 0,
                                "overdueAging": null,
                                "companyName": "MOTIJHEEL BRANCH"
                            },
                            {
                                "branchCode": "BD0010104",
                                "accountId": "LD-test2231706205",
                                "loanType": "Test",
                                "currency": "BDT",
                                "customerId": "1338246",
                                "customerTitle": "Mr. Test",
                                "outstandingAmount": 243462.64,
                                "valueDate": "20221113",
                                "maturityDate": "20291026",
                                "interestRate": 9,
                                "totalInterest": 54.58,
                                "customerContactNo": null,
                                "customerAddress": null,
                                "lcNo": null,
                                "disbursementAmount": 300000,
                                "installmentType": "1M",
                                "installmentAmount": 0,
                                "installmentRepaymentDate": "20211113",
                                "overdueAmount": 0,
                                "overdueAging": null,
                                "companyName": "MOTIJHEEL BRANCH"
                            }
                        ],
                        "limitList": [],
                        "bankGuaranteeList": [],
                        "exportLcList": [],
                        "importLcList": [],
                        "billAcceptanceDrList": [],
                        "billForCollectionDrList": [],
                        "importLcBtbList": [],
                        "drawingsListABP": [],
                        "drawingsListBTB": []
                    },
                    "responseCode": 200,
                    "messages": [
                        "Operation Successful"
                    ]
                }';
                return $responseLoanData;
            }
        }else{
            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_URL, $restUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

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

            return $response;
        }
    }

    public function getCustomerInfoByCif($restUrl, $post_data, $headers)
    {
        if($this->localEnvironment == true){
            if($restUrl == 'https://pblcon-uat-lb.primebank.com.bd:8443/card-api/v1/card/details/by/cif'){
                $responseCIF = '{
                    "data": {
                        "requestId": "1740899059UmWOs",
                        "responseId": "QehOcrtzSacmUfuMkjAt",
                        "responseDateTime": "2025-05-18T11:45:09+06:00",
                        "sourceChannel": "MIB",
                        "cardNumber": null,
                        "cardCurrency": null,
                        "cardDetails": [
                            {
                                "embossName": "ASHRAF SIDDIQUE",
                                "cardHolderName": "ASHRAF SIDDIQUE .",
                                "cardNumber": "R89iguX1jT5cDNdH1MHdz2tpiUlAenzSO79CZvjHIS4=",
                                "cardType": "Visa",
                                "cardCurrency": "50",
                                "cardStatus": "NORMAL",
                                "plasticStatus": "001",
                                "availableCash": 314870.92,
                                "cardOutstandingBalance": -294433.52,
                                "availableBalance": 314870.92,
                                "totalLimit": 0,
                                "minPaymentDueAmt": 0.00,
                                "lastDueAmt": 0.00,
                                "lastDueDate": null,
                                "cardProduct": "VRDA01",
                                "cardProductName": "VISA STAFF PREPAID (F)",
                                "cardProductType": "VISA STAFF PREPAID (F)",
                                "accountNumber": "PA00000000003845",
                                "branchCode": "000001",
                                "dob": "23-Oct-1981",
                                "mobileNumber": "+8801766677001",
                                "aliasPan": null,
                                "email": "ASHRAF.SIDDIQUE@GMAIL.COM",
                                "clientCode": "2375381",
                                "typeOfCard": "PP",
                                "cardDeliveryFlag": "Y",
                                "renewedCardFlag": "Y",
                                "cardActivationFlag": "Y"
                            },
                            {
                                "embossName": "ASHRAF SIDDIQUE",
                                "cardHolderName": "ASHRAF SIDDIQUE .",
                                "cardNumber": "6cX/n9WvF8GPLzRTDHGQ3rdCaZnE1bfmsKjHojZRd4w=",
                                "cardType": "Master",
                                "cardCurrency": "50",
                                "cardStatus": "NORMAL",
                                "plasticStatus": "001",
                                "availableCash": null,
                                "cardOutstandingBalance": null,
                                "availableBalance": 65890.00,
                                "totalLimit": null,
                                "minPaymentDueAmt": null,
                                "lastDueAmt": null,
                                "lastDueDate": null,
                                "cardProduct": "557602",
                                "cardProductName": "MC DEBIT REGULAR OLD BIN SILKWAYS (F)",
                                "cardProductType": "MC DEBIT REGULAR OLD BIN SILKWAYS (F)",
                                "accountNumber": "2104212037352",
                                "branchCode": "000104",
                                "dob": "23-Oct-1981",
                                "mobileNumber": "+8801766677001",
                                "aliasPan": null,
                                "email": "ASHRAF.SIDDIQUE@GMAIL.COM",
                                "clientCode": "2375381",
                                "typeOfCard": "DC",
                                "cardDeliveryFlag": "Y",
                                "renewedCardFlag": "Y",
                                "cardActivationFlag": "Y"
                            },
                            {
                                "embossName": "RAFIQUE SIDDIQUE",
                                "cardHolderName": "RAFIQUE SIDDIQUE .",
                                "cardNumber": "6cX/n9WvF8GPLzRTDHGQ3rdCaZnE1bfmsKjHojZRd4w=",
                                "cardType": "Master",
                                "cardCurrency": "50",
                                "cardStatus": "Cancelled",
                                "plasticStatus": "001",
                                "availableCash": null,
                                "cardOutstandingBalance": null,
                                "availableBalance": 320984,
                                "totalLimit": null,
                                "minPaymentDueAmt": null,
                                "lastDueAmt": null,
                                "lastDueDate": null,
                                "cardProduct": "557602",
                                "cardProductName": "MC DEBIT REGULAR OLD BIN SILKWAYS (F)",
                                "cardProductType": "MC DEBIT REGULAR OLD BIN SILKWAYS (F)",
                                "accountNumber": "3124212037420",
                                "branchCode": "000216",
                                "dob": "23-Oct-1990",
                                "mobileNumber": "+8801766677002",
                                "aliasPan": null,
                                "email": "RAFIQ.SIDDIQUE@GMAIL.COM",
                                "clientCode": "2375382",
                                "typeOfCard": "CC",
                                "cardDeliveryFlag": "Y",
                                "renewedCardFlag": "Y",
                                "cardActivationFlag": "Y"
                            }
                        ]
                    },
                    "responseCode": 200,
                    "messages": [
                        "Operation Successful"
                    ]
                }';
                return $responseCIF;
            }
        }else{
            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_URL, $restUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

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

            return $response;
        }

    }

    public function getCustomerInfoByMobile($restUrl, $post_data, $headers)
    {
        if($this->localEnvironment == true){
            if($restUrl == 'https://pblcon-uat-lb.primebank.com.bd:8443/card-api/v1/card/details/by/mobile/number'){
                $responseMOBILE = '{
                    "data": {
                        "requestId": "20210128MPP0000056",
                        "responseId": "rWtCtpfopKkxNbYWYNMe",
                        "responseDateTime": "2025-05-18T11:56:20+06:00",
                        "sourceChannel": "MIB",
                        "cardNumber": null,
                        "cardCurrency": null,
                        "cardDetails": [
                            {
                                "embossName": "A.H.S AHMED SHARIF",
                                "cardHolderName": "A.H.S AHMED SHARIF .",
                                "cardNumber": "yaBnyNovXQBP0m04itiFwRj9VMVnCKEddy5GkNNVYo8=",
                                "cardType": "JCB",
                                "cardCurrency": "050",
                                "cardStatus": "Cancelled",
                                "plasticStatus": "001",
                                "availableCash": null,
                                "cardOutstandingBalance": null,
                                "availableBalance": null,
                                "totalLimit": null,
                                "minPaymentDueAmt": null,
                                "lastDueAmt": null,
                                "lastDueDate": null,
                                "cardProduct": "357701",
                                "cardProductName": "JCB DEBIT REGULAR: V1 (F)",
                                "cardProductType": "JCB DEBIT REGULAR: V1 (F)",
                                "accountNumber": "2104218000396",
                                "branchCode": "000104",
                                "dob": "22-Nov-1987",
                                "mobileNumber": "+8801717637323",
                                "aliasPan": null,
                                "email": "SHARIF.EWU@GMAIL.COM",
                                "clientCode": "1118814",
                                "typeOfCard": null,
                                "cardDeliveryFlag": "Y",
                                "renewedCardFlag": "Y",
                                "cardActivationFlag": "N"
                            },
                            {
                                "embossName": "A.H.S AHMED SHARIF",
                                "cardHolderName": "A.H.S AHMED SHARIF .",
                                "cardNumber": "SM+wQ+au0QiA6pbaVtJuLiaf6LYEKLZYpFZneT6lW6Q=",
                                "cardType": "JCB",
                                "cardCurrency": "050",
                                "cardStatus": "NORMAL",
                                "plasticStatus": "001",
                                "availableCash": null,
                                "cardOutstandingBalance": null,
                                "availableBalance": null,
                                "totalLimit": null,
                                "minPaymentDueAmt": null,
                                "lastDueAmt": null,
                                "lastDueDate": null,
                                "cardProduct": "357701",
                                "cardProductName": "JCB DEBIT REGULAR: V1 (F)",
                                "cardProductType": "JCB DEBIT REGULAR: V1 (F)",
                                "accountNumber": "2104218000396",
                                "branchCode": "000104",
                                "dob": "22-Nov-1987",
                                "mobileNumber": "+8801717637323",
                                "aliasPan": null,
                                "email": "SHARIF.EWU@GMAIL.COM",
                                "clientCode": "1118814",
                                "typeOfCard": null,
                                "cardDeliveryFlag": "Y",
                                "renewedCardFlag": "Y",
                                "cardActivationFlag": "N"
                            },
                            {
                                "embossName": "A H S AHMED SHARIF",
                                "cardHolderName": "A.H.S AHMED SHARIF .",
                                "cardNumber": "gyL26ACrgBsqqbWGUlmJPdmL4KOehTxsaaJq7EfkzfM=",
                                "cardType": "Master",
                                "cardCurrency": "050",
                                "cardStatus": "Cancelled",
                                "plasticStatus": "001",
                                "availableCash": null,
                                "cardOutstandingBalance": null,
                                "availableBalance": null,
                                "totalLimit": null,
                                "minPaymentDueAmt": null,
                                "lastDueAmt": null,
                                "lastDueDate": null,
                                "cardProduct": "557601",
                                "cardProductName": "MC DEBIT REGULAR: V1 (F)",
                                "cardProductType": "MC DEBIT REGULAR: V1 (F)",
                                "accountNumber": "2104218000396",
                                "branchCode": "000104",
                                "dob": "22-Nov-1987",
                                "mobileNumber": "+8801717637323",
                                "aliasPan": null,
                                "email": "SHARIF.EWU@GMAIL.COM",
                                "clientCode": "1118814",
                                "typeOfCard": null,
                                "cardDeliveryFlag": "Y",
                                "renewedCardFlag": "Y",
                                "cardActivationFlag": "N"
                            },
                            {
                                "embossName": "A H S AHMED SHARIF",
                                "cardHolderName": "A.H.S AHMED SHARIF .",
                                "cardNumber": "h0ZVTRp0MoCGfpvACvytFwGeJ7lAQQT1GRqHFX2pLS0=",
                                "cardType": "Master",
                                "cardCurrency": "050",
                                "cardStatus": "NORMAL",
                                "plasticStatus": "001",
                                "availableCash": null,
                                "cardOutstandingBalance": null,
                                "availableBalance": null,
                                "totalLimit": null,
                                "minPaymentDueAmt": null,
                                "lastDueAmt": null,
                                "lastDueDate": null,
                                "cardProduct": "557602",
                                "cardProductName": "MC DEBIT REGULAR OLD BIN SILKWAYS (F)",
                                "cardProductType": "MC DEBIT REGULAR OLD BIN SILKWAYS (F)",
                                "accountNumber": "2104218000396",
                                "branchCode": "000104",
                                "dob": "22-Nov-1987",
                                "mobileNumber": "+8801717637323",
                                "aliasPan": null,
                                "email": "SHARIF.EWU@GMAIL.COM",
                                "clientCode": "1118814",
                                "typeOfCard": null,
                                "cardDeliveryFlag": "Y",
                                "renewedCardFlag": "Y",
                                "cardActivationFlag": "Y"
                            },
                            {
                                "embossName": "AHS AHMED SHARIF ",
                                "cardHolderName": "A.H.S AHMED SHARIF .",
                                "cardNumber": "j+9fe3PoWBiPZWUmR7s0xWpO9xT4VJ/ZpoQpLrcC9Os=",
                                "cardType": "Master",
                                "cardCurrency": "050",
                                "cardStatus": "REPLACED",
                                "plasticStatus": "001",
                                "availableCash": 500000.00,
                                "cardOutstandingBalance": 0.00,
                                "availableBalance": 1000000.00,
                                "totalLimit": 1000000,
                                "minPaymentDueAmt": 0.00,
                                "lastDueAmt": 0.00,
                                "lastDueDate": null,
                                "cardProduct": "905210",
                                "cardProductName": "MASTERCARD WORLD CONSUMER TQ EMP NFC",
                                "cardProductType": "MASTERCARD WORLD CONSUMER TQ EMP NFC",
                                "accountNumber": "190856059",
                                "branchCode": "000001",
                                "dob": "22-Nov-1987",
                                "mobileNumber": "01717637323",
                                "aliasPan": "5597242186992901",
                                "email": "SHARIF.EWU@GMAIL.COM",
                                "clientCode": "1118814",
                                "typeOfCard": null,
                                "cardDeliveryFlag": "N",
                                "renewedCardFlag": "N",
                                "cardActivationFlag": "N"
                            },
                            {
                                "embossName": "AHS AHMED SHARIF ",
                                "cardHolderName": "A.H.S AHMED SHARIF .",
                                "cardNumber": "RGPs6cxvGzkoE+thCquZrRjecOe+2WP2HBcyPrJ/jfM=",
                                "cardType": "Master",
                                "cardCurrency": "050",
                                "cardStatus": "NORMAL",
                                "plasticStatus": "001",
                                "availableCash": 500000.00,
                                "cardOutstandingBalance": 0.00,
                                "availableBalance": 1000000.00,
                                "totalLimit": 1000000,
                                "minPaymentDueAmt": 0.00,
                                "lastDueAmt": 0.00,
                                "lastDueDate": null,
                                "cardProduct": "905210",
                                "cardProductName": "MASTERCARD WORLD CONSUMER TQ EMP NFC",
                                "cardProductType": "MASTERCARD WORLD CONSUMER TQ EMP NFC",
                                "accountNumber": "190856059",
                                "branchCode": "000001",
                                "dob": "22-Nov-1987",
                                "mobileNumber": "01717637323",
                                "aliasPan": null,
                                "email": "SHARIF.EWU@GMAIL.COM",
                                "clientCode": "1118814",
                                "typeOfCard": null,
                                "cardDeliveryFlag": "N",
                                "renewedCardFlag": "N",
                                "cardActivationFlag": "N"
                            },
                            {
                                "embossName": "A H S AHMED SHARIF",
                                "cardHolderName": "ABUL HASNAT SHEKH AHMED SHARIF .",
                                "cardNumber": "AvS93Xg+hV0/D3t8/AwZgQiEjnlSjaj5jIIr2O596kE=",
                                "cardType": "Visa",
                                "cardCurrency": "050",
                                "cardStatus": "NORMAL",
                                "plasticStatus": "001",
                                "availableCash": 4073.74,
                                "cardOutstandingBalance": 490926.26,
                                "availableBalance": 4073.74,
                                "totalLimit": 495000,
                                "minPaymentDueAmt": 0.00,
                                "lastDueAmt": 0.00,
                                "lastDueDate": null,
                                "cardProduct": "903300",
                                "cardProductName": "VISA HASANAH DUAL GOLD TQ EMP",
                                "cardProductType": "VISA HASANAH DUAL GOLD TQ EMP",
                                "accountNumber": "00000000000000160354",
                                "branchCode": "000001",
                                "dob": "22-Nov-1987",
                                "mobileNumber": "01717637323",
                                "aliasPan": null,
                                "email": "SHARIF.EWU@GMAIL.COM",
                                "clientCode": "2154790",
                                "typeOfCard": null,
                                "cardDeliveryFlag": "Y",
                                "renewedCardFlag": "Y",
                                "cardActivationFlag": "Y"
                            },
                            {
                                "embossName": "A H S AHMED SHARIF",
                                "cardHolderName": "ABUL HASNAT SHEKH AHMED SHARIF .",
                                "cardNumber": "/rsXwRC/ZeIrpcxkQ3CaAkUCU4VY8K+z6Xy68ts6fBU=",
                                "cardType": "Master",
                                "cardCurrency": "050",
                                "cardStatus": "NORMAL",
                                "plasticStatus": "001",
                                "availableCash": 2514.80,
                                "cardOutstandingBalance": -14.80,
                                "availableBalance": 5014.80,
                                "totalLimit": 5000,
                                "minPaymentDueAmt": 0.00,
                                "lastDueAmt": 0.00,
                                "lastDueDate": null,
                                "cardProduct": "905000",
                                "cardProductName": "MASTERCARD DUAL PLATINUM TQ EMP",
                                "cardProductType": "MASTERCARD DUAL PLATINUM TQ EMP",
                                "accountNumber": "00000000000000128637",
                                "branchCode": "000001",
                                "dob": "22-Nov-1987",
                                "mobileNumber": "01717637323",
                                "aliasPan": null,
                                "email": "SHARIF.EWU@GMAIL.COM",
                                "clientCode": "2154790",
                                "typeOfCard": null,
                                "cardDeliveryFlag": "Y",
                                "renewedCardFlag": "Y",
                                "cardActivationFlag": "Y"
                            }
                        ]
                    },
                    "responseCode": 200,
                    "messages": [
                        "Operation Successful"
                    ]
                }';

                return $responseMOBILE;
            }
        }else{
            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_URL, $restUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

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

            return $response;
        }



    }

    /* ======= post not debit ========== */
    public function getPostNoDebit($restUrl, $post_data, $headers)
    {
        if ($this->localEnvironment == true) {
            if ($restUrl) {
                // Mock response for local environment
                $response = [
                    "data" => [
                        "transactionId" => "2123418021528",
                        "successIndicator" => "Success",
                        "application" => "ACCOUNT"
                    ],
                    "responseCode" => 200,
                    "messages" => [
                        "Operation Successful"
                    ]
                ];


                return json_encode($response);
            }
        } else {

            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL            => $restUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $post_data, // JSON payload
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_HTTPAUTH       => CURLAUTH_ANY,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_TIMEOUT        => 30,
            ]);

            $response = curl_exec($ch);
            $timeout  = 30;

            if (curl_errno($ch)) {
                if (curl_errno($ch) === CURLE_OPERATION_TIMEDOUT) {
                    $response = json_encode([
                        "responseCode" => 999,
                        "messages" => [
                            "API operation timed out after " . ($timeout * 1000) . " milliseconds. Please contact API Team."
                        ]
                    ]);
                } else {
                    $response = json_encode([
                        "responseCode" => 999,
                        "messages" => [
                            "API operation issue. Please contact API Team."
                        ]
                    ]);
                }
            }

            curl_close($ch);
            return $response;
        }
    }


    /********* For Logger Panel *****/
    public function index(Request $request)
    {
        Session::forget('searchDataForView');
        Session::forget('real_account_balance');

         //dd($request->all());
        $dataForView = array();
        $searchDataForView = $request->all();
        $searchDataForView['account_type'] = $request->account_type;
        $searchDataForView['account_number'] = $request->account_number;
        // $searchDataForView['customer_search_id'] = $request->customer_search_id;
        $searchDataForView['active_tab'] = $request->active_tab;
        // $searchDataForView['customer_id'] = "";
        // $searchDataForView['acctNumber'] = "";
        $searchDataForView['cif_mobile'] = $request->cif_mobile;

        $searchDataForView['errorSMSAPI'] = "";
        $searchDataForView['DCSearchMgs'] = "";

        $acc_number = "";

        $accountNumber = "";
        $cardNoF6Digit = "";
        $cardNoL4Digit = "";
        $mobileNo = "";
        $dateoBirth = "";
        $multiCardData = "";
        $isCardMultiple = "";

        $isLoanMultiple = "";
        $multiLoanData = "";
        $isACMultiple = "";
        $multiACData = "";

        $isCCMultiple = "";
        $multiCCData = "";

        $isDCMultiple = "";
        $multiDCData = "";
	    $restUrl = "";

        if (empty($request->acc_number) && (!empty($request->mobile) || !empty($request->date)) && empty($request->customer_search_id) && $request->account_type == 1) {

            $flash_message = "Please enter Credit Card Number with Mobile Number or Credit Card Number with Date of Birth or Only Customer ID.";
            flash($flash_message, 'warning');
            return redirect('Supports/home');

        }

        // if ((empty($request->acc_number) | empty($request->account_number)) && $request->account_type == 3) {
        //     $flash_message = "Please enter Debit Card Number with Account Number";
        //     flash($flash_message, 'warning');
        //     return redirect('Supports/home');
        // }

        /*if (!empty($request->acc_number) && $request->account_type == 1) {

            $searchDataForView['acctNumber'] = $request->acc_number;
            $accountNumber = explode("******", $request->acc_number);
            $cardNoF6Digit = $accountNumber[0];
            $cardNoL4Digit = $accountNumber[1];
            $mobileNo = $request->mobile;
            if (!empty($request->date)) {
                $dateoBirth = str_replace("/", "", $request->date);
            }

            if (empty($mobileNo) && empty($dateoBirth)) {

                $flash_message = "Please enter Credit Card Number with Mobile Number or Credit Card Number with Date of Birth or Only Customer ID.";
                flash($flash_message, 'warning');
                return redirect('Supports/home');
            }

        }*/

        // pr($cardNoF6Digit);
        // pr($cardNoL4Digit);
        // pr($dateoBirth);
        // prd($searchDataForView);

        $tblData = array();

        $tblData['customer_id'] = "";
        $tblData['reference_number'] = "";
        $tblData['account_number'] = $request->account_number;
        $tblData['mask_card_no'] = "";
        $tblData['customer_name'] = "";
        $tblData['acc_name'] = "";
        $tblData['mobile_number'] = "";
        $tblData['def_email_addr'] = "";
        $tblData['product_type'] = "";
        $tblData['item_type'] = "";
        $tblData['priority'] = "";
        $tblData['time_and_ext'] = "";
        $tblData['source'] = "";
        $tblData['tin_verified'] = "";
        $tblData['caller_id'] = "";
        $tblData['date_of_birth'] = "";
        $tblData['mother_name'] = "";
        $tblData['father_name'] = "";
        $tblData['mobile_number2'] = "";

        $tblData['address'] = "";
        $tblData['mail_addr'] = "";
        $tblData['permanent_addr'] = "";

        $tblData['other'] = "";
        $tblData['dynamic_question'] = "";
        $tblData['other2'] = "";
        $tblData['notes'] = "";
        $tblData['w_form_type'] = "";

        $tblData['customer_office_addr'] = "";
        $tblData['customer_mail_address'] = "";
        $tblData['customer_perma_address'] = "";
        $tblData['customer_perma_email'] = "";
        $tblData['customer_present_addr'] = "";
        $tblData['customer_phone_off'] = "";
        $tblData['customer_off_email'] = "";
        $tblData['customer_gender'] = "";
        $tblData['customer_nationality'] = "";

        $tblData['communication'] = "";
        $tblData['card_status'] = "";
        $tblData['card_type'] = "";

        $tblData['CIF_number'] = "";
        $tblData['current_out'] = "";
        $tblData['pay_due_date'] = "";
        $tblData['rewards_point'] = "";
        $tblData['stat_billing_cycle'] = "";

        $tblData['last_stat_balance'] = "";
        $tblData['NID'] = "";
        $tblData['cb_fin_acctno'] = "";

        //FIAccountInquiry API //FICustomerInquiry
        $tblData['account_status'] = "";
        $tblData['acc_opening_branch'] = "";
        $tblData['acc_effective_balance'] = "";
        $tblData['product_name'] = "";
        $tblData['TIN'] = "";
        $tblData['SegmentCode'] = "";

        //FITDAccInquiry API
        $tblData['mode_of_operation'] = "";
        $tblData['TDNo'] = "";
        $tblData['PrincipalAmount'] = "";
        $tblData['Tenure'] = "";
        $tblData['MaturityDate'] = "";
        $tblData['InterestRate'] = "";
        $tblData['RenewalOption'] = "";
        $tblData['RepaymentAccount'] = "";

        //FIAccountInquiry API

        //FILoanAccountInquiry API
        $tblData['ProductName'] = "";
        $tblData['EMIAmount'] = "";
        $tblData['OpenDisbursementDate'] = "";
        $tblData['OverdueAmount'] = "";
        $tblData['OutstandingAmount'] = "";
        $tblData['IBStatus'] = "";
        $tblData['NextEMIDate'] = "";
        $tblData['Disbursementamount'] = "";

        // Muajjam 25/0724
        $tblData['customer_type'] = "";
        $tblData['spouse_name'] = "";
        $tblData['passport_number'] = "";
        $tblData['account_open_date'] = "";

        $tmpName = '';
        $name = '';

        $api_credential = DB::table('api_credential')->first();


        if ((!empty($searchDataForView['cif_mobile']) && !empty($searchDataForView['cardSearchingType'])) && $searchDataForView['account_type'] == "1")
        {
            /*dd($request->all());*/
            //Credit Card Customer Info
            // dd($request->all());
            $tblData['acc_number'] =  $request->acc_number;
            $searchDataForView['customer_search_id'] = $cardNoF6Digit;
            $tblData['customer_id'] = $cardNoF6Digit;
            $tblData['mask_card_no'] = $request->acc_number;
            $tblData['customer_name'] =  '';
            $tblData['CIF_number'] = '';
            $tblData['date_of_birth'] =$request->date;
            $tblData['mobile_number'] = $request->mobile;
            $tblData['NID'] = '';
            $tblData['TIN'] ='';
            $tblData['def_email_addr'] = '';
            $tblData['father_name'] = '';
            $tblData['mother_name'] = '';
            $tblData['communication'] = '';
            $tblData['customer_type'] ='';
            $tblData['spouse_name'] = '';
            $tblData['customer_gender'] ='';
            $tblData['passport_number'] = '';


            $tblData['product_name'] =  '';
            $tblData['acc_name'] =  '';
            $tblData['account_number'] =  '';
            $tblData['account_status'] = '';
            $tblData['PrincipalAmount'] = '';
            $tblData['acc_opening_branch'] = '';
            $tblData['account_open_date'] = '';
            $tblData['acc_effective_balance'] = '';

            $CIFNo = "";
            $MOBILENo = "";

            $cif_mobile = $searchDataForView['cif_mobile'];
            $card_searching_type = $searchDataForView['cardSearchingType'];

            $responseResult = false;
            $accessToken = $this->getAccessToken($api_credential->token_url,$this->username, $this->password);
            $requestIdLength = random_int(16, 20);
            $requestId = $this->generateRandomString($requestIdLength);

            if ($searchDataForView['cardSearchingType'] == 'Cif')
            {
                $CIFNo = $searchDataForView['cif_mobile'];
                if(isset($accessToken['responseCode']) && $accessToken['responseCode'] == 200){
                    $restUrl = $api_credential->PULL_CARD_API_BY_CIF;
                    $post_data = json_encode([
                        'cifNumber' => $cif_mobile,
                        'requestDateTime' => date('m/d/Y'),
                        'requestId' => $requestId,
                        'sourceChannel' => 'MIB'
                    ]);
                    $headers = [
                        'Content-Type: application/json',
                        'Authorization: ' . $accessToken['data']['accessToken'],
                    ];
                    $responseCIF = $this->getCustomerInfoByCif($restUrl, $post_data, $headers);
                }else{
                    $searchDataForView['errorSMSAPI'] = 'Token Issue. Please Contact API Team !';
                }

                if (!empty($responseCIF)) {
                    $responseData = json_decode($responseCIF, true);
                    if (isset($responseData->curlMsg)){
                        $searchDataForView['errorSMSAPI'] = $responseData['curlMsg'];
                    }

                    if (isset($responseData['responseCode']) && $responseData['responseCode'] == '200'){
                        if (!empty($responseData['data']['cardDetails'])){
                            if(isset($responseData['data']['cardDetails'][0])){
                                // $cardNumber = $responseData['data']['cardDetails'][0]['cardNumber'] ?? null;
                                // $dyc = $this->encrypt_decrypt_rtgs('DEC',$cardNumber);

                                $isCCMultiple = 'yes';
                                $onlyCCData = collect($responseData['data']['cardDetails'])
                                    ->reject(function ($card) {
                                        return $card['typeOfCard'] === 'DC';
                                    })
                                    ->values()
                                    ->all();

                                $multiCCData = [];

                                foreach ($onlyCCData as $card) {
                                    // $cardNumberEncrypted = $card['cardNumber'] ?? null;

                                    // if ($cardNumberEncrypted) {
                                    //     $decryptedCardNumber = $this->encrypt_decrypt_rtgs('DEC', $cardNumberEncrypted);
                                    //     $maskedCardNumber = ccMasking($decryptedCardNumber);
                                    //     $card['maskedCardNumber'] = $maskedCardNumber;
                                    // } else {
                                    //     $card['maskedCardNumber'] = '';
                                    // }
                                    // $multiCCData[] = $card;

                                    $card['maskedCardNumber'] = $card['cardNumber'] ?? '';
                                    $multiCCData[] = $card;

                                }

                                foreach ($onlyCCData as $cifNumber){
                                    if ($cifNumber['clientCode'] == $CIFNo){
                                        $tblData['product_name'] = !empty($cifNumber['cardProductName']) ? $cifNumber['cardProductName'] : '';
                                        $tblData['acc_name'] = !empty($cifNumber['cardProductName']) ? $cifNumber['cardProductName'] : '';
                                        $tblData['account_number'] = !empty($cifNumber['accountNumber']) ? $cifNumber['accountNumber'] : '';
                                        $tblData['account_status'] = !empty($cifNumber['aliasPan']) ? $cifNumber['aliasPan'] : '';
                                        $tblData['PrincipalAmount'] = !empty($cifNumber['availableBalance']) ? $cifNumber['availableBalance'] : '';
                                        $tblData['acc_opening_branch'] = !empty($cifNumber['branchCode']) ? $cifNumber['branchCode'] : '';
                                        $tblData['account_open_date'] = !empty($cifNumber['accountOpenDate']) ? $cifNumber['accountOpenDate'] : '';
                                        $tblData['acc_effective_balance'] = !empty($cifNumber['onlineActualBal']) ? $cifNumber['onlineActualBal'] : '';
                                        Session::put('real_account_balance', $cifNumber['availableBalance'] ?? '');
                                    }
                                }
                            }
                        } else{
                            $searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                        }
                        $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details', 'json_node' => $responseCIF, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                    } else{
                        //$searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                        $searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                        $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details', 'json_node' => $responseCIF, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                    }
                } else{
                    $searchDataForView['errorSMSAPI'] = 'Response Issue. Please Contact API Team !';
                    $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 1, 'url' => $restUrl, 'service' => 'Card Details', 'json_node' => '', 'status_msg' => 'No Api response Found!']);
                }
            }
            elseif ($searchDataForView['cardSearchingType'] == 'Mobile')
            {
                $MOBILENo = $searchDataForView['cif_mobile'];

                if(isset($accessToken['responseCode']) && $accessToken['responseCode'] == 200){
                    $restUrl = $api_credential->PULL_CARD_API_BY_MOBILE;
                    $post_data = json_encode([
                        'requestDateTime' => date('m/d/Y'),
                        'requestId' => $requestId,
                        'sourceChannel' => 'MIB',
                        'mobileNumber' => $cif_mobile,
                    ]);

                    $headers = [
                        'Content-Type: application/json',
                        'Authorization: ' . $accessToken['data']['accessToken'],
                    ];

                    $responseMOBILE = $this->getCustomerInfoByMobile($restUrl, $post_data, $headers);

                }else{
                    $searchDataForView['errorSMSAPI'] = 'Token Issue. Please Contact API Team !';
                }

                if (!empty($responseMOBILE)) {
                    $responseData = json_decode($responseMOBILE, true);

                    if (isset($responseData->curlMsg)){
                        $searchDataForView['errorSMSAPI'] = $responseData['curlMsg'];
                    }

                    if (isset($responseData['responseCode']) && $responseData['responseCode'] == '200'){
                        if (!empty($responseData['data']['cardDetails'])){
                            if(isset($responseData['data']['cardDetails'][0])){
                                $clientCode = $responseData['data']['cardDetails'][0]['clientCode'] ?? null;
                                // Calling By CIF Number Here:
                                if ($clientCode) {
                                    $restUrl = $api_credential->PULL_CARD_API_BY_CIF;
                                    $post_data = json_encode([
                                        'cifNumber' => $clientCode,
                                        'requestDateTime' => date('m/d/Y'),
                                        'requestId' => $requestId,
                                        'sourceChannel' => 'MIB'
                                    ]);

                                    $headers = [
                                        'Content-Type: application/json',
                                        'Authorization: ' . $accessToken['data']['accessToken'],
                                    ];

                                    $responseCIF = $this->getCustomerInfoByCif($restUrl, $post_data, $headers);

                                    if (!empty($responseCIF)) {
                                        $responseData = json_decode($responseCIF, true);
                                        if (isset($responseData->curlMsg)){
                                            $searchDataForView['errorSMSAPI'] = $responseData['curlMsg'];
                                        }
                                        if (isset($responseData['responseCode']) && $responseData['responseCode'] == '200'){
                                            if (!empty($responseData['data']['cardDetails'])){
                                                if(isset($responseData['data']['cardDetails'][0])){
                                                    $cardNumber = $responseData['data']['cardDetails'][0]['cardNumber'] ?? null;
                                                    $dyc = $this->encrypt_decrypt_rtgs('DEC',$cardNumber);

                                                    $isCCMultiple = 'yes';
                                                    $onlyCCData = collect($responseData['data']['cardDetails'])
                                                        ->reject(function ($card) {
                                                            return $card['typeOfCard'] === 'DC';
                                                        })
                                                        ->values()
                                                        ->all();

                                                    $multiCCData = [];

                                                    foreach ($onlyCCData as $card) {
                                                        // $cardNumberEncrypted = $card['cardNumber'] ?? null;

                                                        // if ($cardNumberEncrypted) {
                                                        //     $decryptedCardNumber = $this->encrypt_decrypt_rtgs('DEC', $cardNumberEncrypted);
                                                        //     $maskedCardNumber = ccMasking($decryptedCardNumber);
                                                        //     $card['maskedCardNumber'] = $maskedCardNumber;
                                                        // } else {
                                                        //     $card['maskedCardNumber'] = '';
                                                        // }
                                                        // $multiCCData[] = $card;

                                                        $card['maskedCardNumber'] = $card['cardNumber'] ?? '';
                                                        $multiCCData[] = $card;
                                                    }

                                                    foreach ($onlyCCData as $cifNumber){
                                                        if ($cifNumber['clientCode'] == $CIFNo){
                                                            $tblData['product_name'] = !empty($cifNumber['cardProductName']) ? $cifNumber['cardProductName'] : '';
                                                            $tblData['acc_name'] = !empty($cifNumber['cardProductName']) ? $cifNumber['cardProductName'] : '';
                                                            $tblData['account_number'] = !empty($cifNumber['accountNumber']) ? $cifNumber['accountNumber'] : '';
                                                            $tblData['account_status'] = !empty($cifNumber['aliasPan']) ? $cifNumber['aliasPan'] : '';
                                                            $tblData['PrincipalAmount'] = !empty($cifNumber['availableBalance']) ? $cifNumber['availableBalance'] : '';
                                                            $tblData['acc_opening_branch'] = !empty($cifNumber['branchCode']) ? $cifNumber['branchCode'] : '';
                                                            $tblData['account_open_date'] = !empty($cifNumber['accountOpenDate']) ? $cifNumber['accountOpenDate'] : '';
                                                            $tblData['acc_effective_balance'] = !empty($cifNumber['onlineActualBal']) ? $cifNumber['onlineActualBal'] : '';
                                                        
                                                            Session::put('real_account_balance', $cifNumber['availableBalance'] ?? '');
                                                        }
                                                    }
                                                }
                                            } else{
                                                $searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                                            }
                                            $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details By CIF', 'json_node' => $responseCIF, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                                        } else{
                                            //$searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                                            $searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                                            $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details By CIF', 'json_node' => $responseCIF, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                                        }
                                    } else{
                                        $searchDataForView['errorSMSAPI'] = 'Response Issue. Please Contact API Team !';
                                        $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 1, 'url' => $restUrl, 'service' => 'Card Details By CIF', 'json_node' => '', 'status_msg' => 'No Api response Found!']);
                                    }
                                }else{
                                    $searchDataForView['errorSMSAPI'] = 'Client Code Not Found!';
                                }
                            }
                        }
                        $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details By Mobile', 'json_node' => $responseCIF, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                    }else{
                        $searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                        $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details By Mobile', 'json_node' => $responseMOBILE, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                    }

                } else{
                    $searchDataForView['errorSMSAPI'] = 'Response Issue. Please Contact API Team !';
                    $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 1, 'url' => $restUrl, 'service' => 'Card Details By Mobile', 'json_node' => '', 'status_msg' => 'No Api response Found!']);
                }
            }

        }
        elseif (!empty($searchDataForView['account_number']) && ($searchDataForView['account_type'] == "2" || $searchDataForView['account_type'] == "5"))
        {
            //Accounts Customer Info
            $CIFNo = "";
            $accno = $searchDataForView['account_number'] ?? '';
            $responseResult = false;
            $responseAC = null;

            try {
                // Get access token
                $accessToken = $this->getAccessToken($api_credential->token_url, $this->username, $this->password);

                $tokenCode = $accessToken['responseCode'] ?? null;

                if ($tokenCode !== 200) {
                    switch ($tokenCode) {
                        case 401:
                            $searchDataForView['errorSMSAPI'] = 'Unauthorized access. Check credentials.';
                            break;
                        case 403:
                            $searchDataForView['errorSMSAPI'] = 'Access forbidden. Please contact API Team!';
                            break;
                        case 404:
                            $searchDataForView['errorSMSAPI'] = 'Token service not found.';
                            break;
                        case 999:
                        default:
                            $searchDataForView['errorSMSAPI'] = 'Token Issue. Please contact API Team!';
                            break;
                    }
                    return;
                }

                // Token is fine, proceed to get customer info
                $restUrlAccount = $api_credential->Pull_API_URL;
                $post_data = json_encode([
                    'accountNumber' => $accno,
                ]);

                $headers = [
                    'Content-Type: application/json',
                    'Authorization: ' . $accessToken['data']['accessToken'],
                ];

                $responseAC = $this->getCustomerInfoByAPI($restUrlAccount, $post_data, $headers);

                if (!$responseAC) {
                    $searchDataForView['errorSMSAPI'] = 'No response from customer info API.';
                    return;
                }

                $responseData = json_decode($responseAC, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $searchDataForView['errorSMSAPI'] = 'Invalid JSON in API response.';
                    return;
                }

                // Handle customer info response code
                $customerCode = $responseData['responseCode'] ?? null;

                switch ($customerCode) {
                    case 200:
                        $data = $responseData['data'] ?? [];

                        // Basic Info
                        $tblData['customer_name'] = $data['customerTitle'] ?? '';
                        $tblData['CIF_number'] = $data['customerId'] ?? '';
                        $tblData['date_of_birth'] = $data['customerDoB'] ?? '';
                        $tblData['mobile_number'] = $data['customerPhone'] ?? '';
                        $tblData['NID'] = $data['customerNID'] ?? '';
                        $tblData['TIN'] = $data['tinNumber'] ?? '';
                        $tblData['def_email_addr'] = $data['customerEmail'] ?? '';
                        $tblData['father_name'] = $data['fatherName'] ?? '';
                        $tblData['mother_name'] = $data['motherName'] ?? '';

                        $Addr1 = $data['contactAddress1'] ?? '';
                        $Addr2 = $data['contactAddress2'] ?? '';
                        $Addr3 = $data['contactAddress3'] ?? '';
                        $Addr4 = $data['contactAddress4'] ?? '';
                        $tblData['communication'] = implode('--', array_filter([$Addr1, $Addr2, $Addr3, $Addr4]));

                        $tblData['customer_type'] = $data['customerType'] ?? '';
                        $tblData['spouse_name'] = $data['spouseName'] ?? '';
                        $tblData['customer_gender'] = $data['gender'] ?? '';
                        $tblData['passport_number'] = $data['passportNumber'] ?? '';

                        // Account Info
                        if (!empty($data['accounts']) && is_array($data['accounts'])) {
                            $isACMultiple = 'yes';
                            $multiACData = $data['accounts'];

                            // Mask balance for STAFF
                            if (trim($data['customerType']) === 'STAFF') {
                                foreach ($multiACData as &$account) {
                                    Session::put('real_account_balance', $account['balance'] ?? '');
                                    $account['balance'] = '***********';
                                }
                                unset($account);
                            }

                            foreach ($multiACData as $account) {
                                if (($account['accountId'] ?? '') === $accno) {
                                    $tblData['product_name'] = $account['categoryTitle'] ?? '';
                                    $tblData['acc_name'] = $account['categoryTitle'] ?? '';
                                    $tblData['account_number'] = $account['accountId'] ?? '';
                                    $tblData['account_status'] = $account['dormant'] ?? '';
                                    $tblData['PrincipalAmount'] = $account['balance'] ?? '';
                                    $tblData['acc_opening_branch'] = $account['branchName'] ?? '';
                                    $tblData['account_open_date'] = $account['accountOpenDate'] ?? '';
                                    $tblData['acc_effective_balance'] = $account['onlineActualBal'] ?? '';
                                }
                            }
                        }

                        // Optional: Save log
                        $this->osbApiRequestResponse([
                            'account_number' => $accno,
                            'cif_number' => $tblData['CIF_number'] ?? '',
                            'type' => 2,
                            'url' => $restUrlAccount,
                            'service' => 'Account Details',
                            'json_node' => json_encode([
                                                        'json_node' => $responseAC,
                                                    ])
                        ]);
                        break;

                        $this->osbApiRequestResponse([
                            'account_number' => $accno,
                            'cif_number' => $tblData['CIF_number'] ?? '',
                            'type' => 2,
                            'url' => $restUrlAccount,
                            'service' => 'Account Details',
                            'json_node' => json_encode([
                                                        'json_node' => $responseAC,
                                                    ])
                        ]);

                    case 404:
                        $searchDataForView['errorSMSAPI'] = 'Customer not found.';
                        break;
                    case 997:
                        $searchDataForView['errorSMSAPI'] = 'Empty response from CBS.';
                        break;
                    case 998:
                        $searchDataForView['errorSMSAPI'] = 'Internal error in CBS.';
                        break;
                    case 999:
                        $searchDataForView['errorSMSAPI'] = 'Customer info fetch failed. Please try again.';
                        break;
                    default:
                        $searchDataForView['errorSMSAPI'] = 'Account not found.  ' . $customerCode;
                        break;
                }

            } catch (\Exception $e) {
                $searchDataForView['errorSMSAPI'] = 'Exception occurred: ' . $e->getMessage();
            }


        }
        elseif ((!empty($searchDataForView['cif_mobile']) && !empty($searchDataForView['cardSearchingType'])) && $searchDataForView['account_type'] == "3")
        {
            $tblData['acc_number'] =  $request->acc_number;
            $searchDataForView['customer_search_id'] = $cardNoF6Digit;
            $tblData['customer_id'] = $cardNoF6Digit;
            $tblData['mask_card_no'] = $request->acc_number;
            $tblData['customer_name'] =  '';
            $tblData['CIF_number'] = '';
            $tblData['date_of_birth'] =$request->date;
            $tblData['mobile_number'] = $request->mobile;
            $tblData['NID'] = '';
            $tblData['TIN'] ='';
            $tblData['def_email_addr'] = '';
            $tblData['father_name'] = '';
            $tblData['mother_name'] = '';
            $tblData['communication'] = '';
            $tblData['customer_type'] ='';
            $tblData['spouse_name'] = '';
            $tblData['customer_gender'] ='';
            $tblData['passport_number'] = '';


            $tblData['product_name'] =  '';
            $tblData['acc_name'] =  '';
            $tblData['account_number'] =  '';
            $tblData['account_status'] = '';
            $tblData['PrincipalAmount'] = '';
            $tblData['acc_opening_branch'] = '';
            $tblData['account_open_date'] = '';
            $tblData['acc_effective_balance'] = '';

            $CIFNo = "";
            $MOBILENo = "";

            $cif_mobile = $searchDataForView['cif_mobile'];
            $card_searching_type = $searchDataForView['cardSearchingType'];

            $responseResult = false;
            $accessToken = $this->getAccessToken($api_credential->token_url,$this->username, $this->password);
            $requestIdLength = random_int(16, 20);
            $requestId = $this->generateRandomString($requestIdLength);

            if ($searchDataForView['cardSearchingType'] == 'Cif')
            {
                $CIFNo = $searchDataForView['cif_mobile'];
                if(isset($accessToken['responseCode']) && $accessToken['responseCode'] == 200){
                    $restUrl = $api_credential->PULL_CARD_API_BY_CIF;
                    $post_data = json_encode([
                        'cifNumber' => $cif_mobile,
                        'requestDateTime' => date('m/d/Y'),
                        'requestId' => $requestId,
                        'sourceChannel' => 'MIB'
                    ]);
                    $headers = [
                        'Content-Type: application/json',
                        'Authorization: ' . $accessToken['data']['accessToken'],
                    ];
                    $responseCIF = $this->getCustomerInfoByCif($restUrl, $post_data, $headers);
                }else{
                    $searchDataForView['errorSMSAPI'] = 'Token Issue. Please Contact API Team !';
                }

                if (!empty($responseCIF)) {
                    $responseData = json_decode($responseCIF, true);
                    if (isset($responseData->curlMsg)){
                        $searchDataForView['errorSMSAPI'] = $responseData['curlMsg'];
                    }

                    if (isset($responseData['responseCode']) && $responseData['responseCode'] == '200'){
                        if (!empty($responseData['data']['cardDetails'])){
                            if(isset($responseData['data']['cardDetails'][0])){
                                $cardNumber = $responseData['data']['cardDetails'][0]['cardNumber'] ?? null;
                                $dyc = $this->encrypt_decrypt_rtgs('DEC',$cardNumber);

                                $isDCMultiple = 'yes';
                                $onlyDCData = collect($responseData['data']['cardDetails'])
                                    ->filter(function ($card) {
                                        return $card['typeOfCard'] === 'DC';
                                    })
                                    ->values()
                                    ->all();

                                $multiDCData = [];

                                foreach ($onlyDCData as $card) {
                                    // $cardNumberEncrypted = $card['cardNumber'] ?? null;

                                    // if ($cardNumberEncrypted) {
                                    //     $decryptedCardNumber = $this->encrypt_decrypt_rtgs('DEC', $cardNumberEncrypted);
                                    //     $maskedCardNumber = ccMasking($decryptedCardNumber);
                                    //     $card['maskedCardNumber'] = $maskedCardNumber;
                                    // } else {
                                    //     $card['maskedCardNumber'] = '';
                                    // }
                                    // $multiDCData[] = $card;

                                    $card['maskedCardNumber'] = $card['cardNumber'] ?? '';
                                    $multiDCData[] = $card;
                                }

                                foreach ($onlyDCData as $cifNumber){
                                    if ($cifNumber['clientCode'] == $CIFNo){
                                        $tblData['product_name'] = !empty($cifNumber['cardProductName']) ? $cifNumber['cardProductName'] : '';
                                        $tblData['acc_name'] = !empty($cifNumber['cardProductName']) ? $cifNumber['cardProductName'] : '';
                                        $tblData['account_number'] = !empty($cifNumber['accountNumber']) ? $cifNumber['accountNumber'] : '';
                                        $tblData['account_status'] = !empty($cifNumber['aliasPan']) ? $cifNumber['aliasPan'] : '';
                                        $tblData['PrincipalAmount'] = !empty($cifNumber['availableBalance']) ? $cifNumber['availableBalance'] : '';
                                        $tblData['acc_opening_branch'] = !empty($cifNumber['branchCode']) ? $cifNumber['branchCode'] : '';
                                        $tblData['account_open_date'] = !empty($cifNumber['accountOpenDate']) ? $cifNumber['accountOpenDate'] : '';
                                        $tblData['acc_effective_balance'] = !empty($cifNumber['onlineActualBal']) ? $cifNumber['onlineActualBal'] : '';
                                        Session::put('real_account_balance', $cifNumber['availableBalance'] ?? '');
                                    }
                                }
                            }
                        } else{
                            $searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                        }
                        $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details', 'json_node' => $responseCIF, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                    } else{
                        //$searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                        $searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                        $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details', 'json_node' => $responseCIF, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                    }
                } else{
                    $searchDataForView['errorSMSAPI'] = 'Response Issue. Please Contact API Team !';
                    $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 1, 'url' => $restUrl, 'service' => 'Card Details', 'json_node' => '', 'status_msg' => 'No Api response Found!']);
                }
            }
            elseif ($searchDataForView['cardSearchingType'] == 'Mobile')
            {
                $MOBILENo = $searchDataForView['cif_mobile'];

                if(isset($accessToken['responseCode']) && $accessToken['responseCode'] == 200){
                    $restUrl = $api_credential->PULL_CARD_API_BY_MOBILE;
                    $post_data = json_encode([
                        'requestDateTime' => date('m/d/Y'),
                        'requestId' => $requestId,
                        'sourceChannel' => 'MIB',
                        'mobileNumber' => $cif_mobile,
                    ]);

                    $headers = [
                        'Content-Type: application/json',
                        'Authorization: ' . $accessToken['data']['accessToken'],
                    ];

                    $responseMOBILE = $this->getCustomerInfoByMobile($restUrl, $post_data, $headers);

                }else{
                    $searchDataForView['errorSMSAPI'] = 'Token Issue. Please Contact API Team !';
                }

                if (!empty($responseMOBILE)) {
                    $responseData = json_decode($responseMOBILE, true);

                    if (isset($responseData->curlMsg)){
                        $searchDataForView['errorSMSAPI'] = $responseData['curlMsg'];
                    }

                    if (isset($responseData['responseCode']) && $responseData['responseCode'] == '200'){
                        if (!empty($responseData['data']['cardDetails'])){
                            if(isset($responseData['data']['cardDetails'][0])){
                                $clientCode = $responseData['data']['cardDetails'][0]['clientCode'] ?? null;
                                // Calling By CIF Number Here:
                                if ($clientCode) {
                                    $restUrl = $api_credential->PULL_CARD_API_BY_CIF;
                                    $post_data = json_encode([
                                        'cifNumber' => $clientCode,
                                        'requestDateTime' => date('m/d/Y'),
                                        'requestId' => $requestId,
                                        'sourceChannel' => 'MIB'
                                    ]);

                                    $headers = [
                                        'Content-Type: application/json',
                                        'Authorization: ' . $accessToken['data']['accessToken'],
                                    ];

                                    $responseCIF = $this->getCustomerInfoByCif($restUrl, $post_data, $headers);

                                    if (!empty($responseCIF)) {
                                        $responseData = json_decode($responseCIF, true);
                                        if (isset($responseData->curlMsg)){
                                            $searchDataForView['errorSMSAPI'] = $responseData['curlMsg'];
                                        }
                                        if (isset($responseData['responseCode']) && $responseData['responseCode'] == '200'){
                                            if (!empty($responseData['data']['cardDetails'])){
                                                if(isset($responseData['data']['cardDetails'][0])){
                                                    $cardNumber = $responseData['data']['cardDetails'][0]['cardNumber'] ?? null;
                                                    $dyc = $this->encrypt_decrypt_rtgs('DEC',$cardNumber);

                                                    $isDCMultiple = 'yes';
                                                    $onlyDCData = collect($responseData['data']['cardDetails'])
                                                        ->filter(function ($card) {
                                                            return $card['typeOfCard'] === 'DC';
                                                        })
                                                        ->values()
                                                        ->all();

                                                    $multiDCData = [];

                                                    foreach ($onlyDCData as $card) {
                                                        // $cardNumberEncrypted = $card['cardNumber'] ?? null;

                                                        // if ($cardNumberEncrypted) {
                                                        //     $decryptedCardNumber = $this->encrypt_decrypt_rtgs('DEC', $cardNumberEncrypted);
                                                        //     $maskedCardNumber = ccMasking($decryptedCardNumber);
                                                        //     $card['maskedCardNumber'] = $maskedCardNumber;
                                                        // } else {
                                                        //     $card['maskedCardNumber'] = '';
                                                        // }
                                                        // $multiDCData[] = $card;

                                                        $card['maskedCardNumber'] = $card['cardNumber'] ?? '';
                                                        $multiDCData[] = $card;
                                                    }

                                                    foreach ($onlyDCData as $cifNumber){
                                                        if ($cifNumber['clientCode'] == $CIFNo){
                                                            $tblData['product_name'] = !empty($cifNumber['cardProductName']) ? $cifNumber['cardProductName'] : '';
                                                            $tblData['acc_name'] = !empty($cifNumber['cardProductName']) ? $cifNumber['cardProductName'] : '';
                                                            $tblData['account_number'] = !empty($cifNumber['accountNumber']) ? $cifNumber['accountNumber'] : '';
                                                            $tblData['account_status'] = !empty($cifNumber['aliasPan']) ? $cifNumber['aliasPan'] : '';
                                                            $tblData['PrincipalAmount'] = !empty($cifNumber['availableBalance']) ? $cifNumber['availableBalance'] : '';
                                                            $tblData['acc_opening_branch'] = !empty($cifNumber['branchCode']) ? $cifNumber['branchCode'] : '';
                                                            $tblData['account_open_date'] = !empty($cifNumber['accountOpenDate']) ? $cifNumber['accountOpenDate'] : '';
                                                            $tblData['acc_effective_balance'] = !empty($cifNumber['onlineActualBal']) ? $cifNumber['onlineActualBal'] : '';
                                                            Session::put('real_account_balance', $cifNumber['availableBalance'] ?? '');
                                                        }
                                                    }
                                                }
                                            } else{
                                                $searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                                            }
                                            $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details By CIF', 'json_node' => $responseCIF, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                                        } else{
                                            //$searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                                            $searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                                            $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details By CIF', 'json_node' => $responseCIF, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                                        }
                                    } else{
                                        $searchDataForView['errorSMSAPI'] = 'Response Issue. Please Contact API Team !';
                                        $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 1, 'url' => $restUrl, 'service' => 'Card Details By CIF', 'json_node' => '', 'status_msg' => 'No Api response Found!']);
                                    }
                                }else{
                                    $searchDataForView['errorSMSAPI'] = 'Client Code Not Found!';
                                }
                            }
                        }
                        $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details By Mobile', 'json_node' => $responseCIF, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                    }else{
                        $searchDataForView['errorSMSAPI'] = 'Data Not Found Please Try Again!';
                        $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 2, 'url' => $restUrl, 'service' => 'Card Details By Mobile', 'json_node' => $responseMOBILE, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                    }

                } else{
                    $searchDataForView['errorSMSAPI'] = 'Response Issue. Please Contact API Team !';
                    $this->osbApiRequestResponse(['account_number' => $tblData['account_number'], 'cif_number' => $CIFNo, 'type' => 1, 'url' => $restUrl, 'service' => 'Card Details By Mobile', 'json_node' => '', 'status_msg' => 'No Api response Found!']);
                }
            }

        }
        elseif (!empty($searchDataForView['account_number']) && $searchDataForView['account_type'] == "4")
        {
            //Loan Customer Info
            $CIFNo = "";
            $accno = $searchDataForView['account_number'] ?? '';
            $responseResult = false;
            $responseAC = null;
            $responseLoanData = null;

            try {
                // Step 1: Get Access Token
                $accessToken = $this->getAccessToken($api_credential->token_url, $this->username, $this->password);
                $tokenCode = $accessToken['responseCode'] ?? null;

                if ($tokenCode !== 200) {
                    switch ($tokenCode) {
                        case 401:
                            $searchDataForView['errorSMSAPI'] = 'Unauthorized access. Check credentials.';
                            break;
                        case 403:
                            $searchDataForView['errorSMSAPI'] = 'Access forbidden. Please contact API Team!';
                            break;
                        case 404:
                            $searchDataForView['errorSMSAPI'] = 'Token service not found.';
                            break;
                        case 999:
                        default:
                            $searchDataForView['errorSMSAPI'] = 'Token Issue. Please contact API Team!';
                            break;
                    }
                    return;
                }

                // Step 2: Prepare account request
                $restUrlAccount = $api_credential->Pull_API_URL;
                $restUrlLoan = $api_credential->loan_api_request;

                $post_data = json_encode([
                    'accountNumber' => $accno,
                ]);

                $headers = [
                    'Content-Type: application/json',
                    'Authorization: ' . $accessToken['data']['accessToken'],
                ];

                // Step 3: Call Customer Account API
                $responseAC = $this->getCustomerInfoByAPI($restUrlAccount, $post_data, $headers);
                if (!$responseAC) {
                    $searchDataForView['errorSMSAPI'] = 'No response from customer info API.';
                    return redirect()->back();
                }

                $responseData = json_decode($responseAC, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $searchDataForView['errorSMSAPI'] = 'Invalid JSON in account API response.';
                    return redirect()->back();
                }

                // Step 4: Handle Customer Info Response
                $customerCode = $responseData['responseCode'] ?? null;

                switch ($customerCode) {
                    case 200:
                        $data = $responseData['data'] ?? [];

                        // Basic Info
                        $tblData['customer_name'] = $data['customerTitle'] ?? '';
                        $tblData['CIF_number'] = $data['customerId'] ?? '';
                        $tblData['date_of_birth'] = $data['customerDoB'] ?? '';
                        $tblData['mobile_number'] = $data['customerPhone'] ?? '';
                        $tblData['NID'] = $data['customerNID'] ?? '';
                        $tblData['TIN'] = $data['tinNumber'] ?? '';
                        $tblData['def_email_addr'] = $data['customerEmail'] ?? '';
                        $tblData['father_name'] = $data['fatherName'] ?? '';
                        $tblData['mother_name'] = $data['motherName'] ?? '';

                        $Addr1 = $data['contactAddress1'] ?? '';
                        $Addr2 = $data['contactAddress2'] ?? '';
                        $Addr3 = $data['contactAddress3'] ?? '';
                        $Addr4 = $data['contactAddress4'] ?? '';
                        $tblData['communication'] = implode('--', array_filter([$Addr1, $Addr2, $Addr3, $Addr4]));

                        $tblData['customer_type'] = $data['customerType'] ?? '';
                        $tblData['spouse_name'] = $data['spouseName'] ?? '';
                        $tblData['customer_gender'] = $data['gender'] ?? '';
                        $tblData['passport_number'] = $data['passportNumber'] ?? '';

                        // Account Info
                        if (!empty($data['accounts']) && is_array($data['accounts'])) {
                            $isACMultiple = 'yes';
                            $multiACData = $data['accounts'];

                            // Mask balance for STAFF
                            if (trim($data['customerType']) === 'STAFF') {
                                foreach ($multiACData as &$account) {
                                    Session::put('real_account_balance', $account['balance'] ?? '');
                                    $account['balance'] = '***********';
                                }
                                unset($account);
                            }

                            foreach ($multiACData as $account) {
                                if (($account['accountId'] ?? '') === $accno) {
                                    $tblData['product_name'] = $account['categoryTitle'] ?? '';
                                    $tblData['acc_name'] = $account['categoryTitle'] ?? '';
                                    $tblData['account_number'] = $account['accountId'] ?? '';
                                    $tblData['account_status'] = $account['dormant'] ?? '';
                                    $tblData['PrincipalAmount'] = $account['balance'] ?? '';
                                    $tblData['acc_opening_branch'] = $account['branchName'] ?? '';
                                    $tblData['account_open_date'] = $account['accountOpenDate'] ?? '';
                                    $tblData['acc_effective_balance'] = $account['onlineActualBal'] ?? '';
                                }
                            }
                        }

                        // Save Account API Log
                        $this->osbApiRequestResponse([
                            'account_number' => $accno,
                            'cif_number' => $tblData['CIF_number'] ?? '',
                            'type' => 4,
                            'url' => $restUrlAccount,
                            'service' => 'Account Details for loan call',
                            'json_node' => json_encode([
                                                        'json_node' => $responseAC,
                                                    ])
                        ]);

                        break;
                        $this->osbApiRequestResponse([
                            'account_number' => $accno,
                            'cif_number' => $tblData['CIF_number'] ?? '',
                            'type' => 4,
                            'url' => $restUrlAccount,
                            'service' => 'Account Details for loan call',
                            'json_node' => json_encode([ 'json_node' => $responseAC,])
                        ]);

                    case 404:
                        $searchDataForView['errorSMSAPI'] = 'Customer not found.';
                        return;
                    case 997:
                        $searchDataForView['errorSMSAPI'] = 'Empty response from CBS.';
                        return;
                    case 998:
                        $searchDataForView['errorSMSAPI'] = 'Internal error in CBS.';
                        return;
                    case 999:
                        $searchDataForView['errorSMSAPI'] = 'Customer info fetch failed. Please try again.';
                        return;
                    default:
                        $searchDataForView['errorSMSAPI'] = 'Account not found. ' . $customerCode;
                        break;
                }

                // Step 5: Call Loan Info API
                if (!empty($tblData['CIF_number'])) {
                    $postLoanData = json_encode([
                        'customerId' => $tblData['CIF_number'],
                    ]);

                    $responseLoanData = $this->getCustomerInfoByAPI($restUrlLoan, $postLoanData, $headers);
                    if (!$responseLoanData) {
                        $searchDataForView['errorSMSAPI'] = 'No response from loan info API.';
                    } else {
                        $responseLoan = json_decode($responseLoanData, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            $searchDataForView['errorSMSAPI'] = 'Invalid JSON in loan API response.';
                        } else {
                            $loanCode = $responseLoan['responseCode'] ?? null;

                            switch ($loanCode) {
                                case 200:
                                    $loanList = $responseLoan['data']['loanList'] ?? [];

                                    if (!empty($loanList)) {
                                        $isLoanMultiple = 'yes';
                                        $multiLoanData = $loanList;

                                        foreach ($loanList as $loan) {
                                            $tblData['ProductName'] = $loan['loanType'] ?? '';
                                            $tblData['Disbursementamount'] = $loan['disbursementAmount'] ?? '';
                                            $tblData['InterestRate'] = $loan['interestRate'] ?? '';
                                            $tblData['OutstandingAmount'] = $loan['outstandingAmount'] ?? '';
                                            $tblData['NextEMIDate'] = $loan['installmentRepaymentDate'] ?? '';
                                            $tblData['OverdueAmount'] = $loan['overdueAmount'] ?? '';
                                            $tblData['IBStatus'] = '';
                                            $tblData['SegmentCode'] = '';
                                            $tblData['EMIAmount'] = '';
                                            $tblData['OpenDisbursementDate'] = '';
                                            $tblData['Tenure'] = '';
                                            Session::put('real_account_balance', $loan['outstandingAmount'] ?? '');
                                        }
                                    }

                                    // Save Loan API Log
                                    $this->osbApiRequestResponse([
                                        'account_number' => $accno,
                                        'cif_number' => $tblData['CIF_number'] ?? '',
                                        'type' => 4,
                                        'url' => $restUrlLoan,
                                        'service' => 'Loan Customer Details',
                                        'json_node' => json_encode(['json_node' => $responseLoanData,])
                                    ]);

                                    break;
                                    $this->osbApiRequestResponse([
                                        'account_number' => $accno,
                                        'cif_number' => $tblData['CIF_number'] ?? '',
                                        'type' => 4,
                                        'url' => $restUrlLoan,
                                        'service' => 'Loan Customer Details',
                                        'json_node' => json_encode(['json_node' => $responseLoanData,])
                                    ]);

                                case 404:
                                    $searchDataForView['errorSMSAPI'] = 'Loan details not found.';
                                    break;
                                case 999:
                                    $searchDataForView['errorSMSAPI'] = 'Loan info fetch failed. Please try again.';
                                    break;
                                default:
                                    $searchDataForView['errorSMSAPI'] = 'No loan account found for this account. ' . $loanCode;
                                    break;
                            }
                        }
                    }
                } else {
                    $searchDataForView['errorSMSAPI'] = 'No loan account found for this account.';
                }

            } catch (\Exception $e) {
                $searchDataForView['errorSMSAPI'] = 'Exception occurred: ' . $e->getMessage();
            }


        } else {
            //Do Nothing
        }


        $wFormData = array();
        $complaintData = array();
        //prd($searchDataForView);
        if (!empty($tblData)) {
            $searchDataForView['account_number'] = $tblData['account_number'];
            $searchDataForView['customer_name'] = $tblData['customer_name'];
            $searchDataForView['acc_name'] = $tblData['acc_name'];
            $searchDataForView['customer_mobile'] = $tblData['mobile_number'];
            $searchDataForView['def_email_addr'] = $tblData['def_email_addr'];
            $searchDataForView['customer_id'] = $tblData['customer_id'];

            $searchDataForView['CIF_number'] = $tblData['CIF_number'];
            $searchDataForView['date_of_birth'] = $tblData['date_of_birth'];

            // Muajjam 25/0724
            $searchDataForView['customer_type'] = $tblData['customer_type'];
            // $searchDataForView['spouse_name'] = $tblData['spouse_name'];
            // $searchDataForView['gender'] = $tblData['customer_gender'];
            // $searchDataForView['customer_gender'] = $tblData['customer_gender'];
            // $searchDataForView['passport_number'] = $tblData['passport_number'];
            $searchDataForView['account_open_date'] = $tblData['account_open_date'];
            $searchDataForView['accountId'] = '';

            // $searchDataForView['SegmentCode'] = $tblData['SegmentCode'];
            $searchDataForView['cb_fin_acctno'] = $tblData['cb_fin_acctno'];
            $searchDataForView['card_status'] = $tblData['card_status'];
            $searchDataForView['account_status'] = $tblData['account_status'];
            $searchDataForView['product_desc'] = $tblData['product_name'];
            $searchDataForView['mask_card_no'] = $tblData['mask_card_no'];
            $searchDataForView['acc_opening_branch'] = $tblData['acc_opening_branch'];

            /*if ($searchDataForView['account_type'] == 1) {

                if (!empty($searchDataForView['account_number'])) {
                    $acc_number = $tblData['customer_id'];
                } else {
                    $acc_number = (!empty($tblData['customer_id'])) ? $tblData['customer_id'] : $searchDataForView['customer_search_id'];
                }

            } else {
                $acc_number = (!empty($tblData['account_number'])) ? $tblData['account_number'] : $searchDataForView['account_number'];
            }*/

            if (!empty($acc_number)) {
                $wformModelName = new WForm;
                $wFormDataObj = $wformModelName
                    ->select(
                        // "w_form.*"
                        "w_form.reference_number"
                        ,
                        "w_form.w_form_type"
                        ,
                        "unit_items.name as category_name"
                        ,
                        "reference.created_by"
                        ,
                        "reference.date"
                        ,
                        "reference.status"
                        ,
                        "reference.form_status"
                    )
                    ->leftJoin('reference', 'reference.reference_number', '=', 'w_form.reference_number')
                    ->leftJoin('unit_items', function ($join) {
                        $join->on('unit_items.master_id', '=', 'w_form.w_form_type');
                        $join->on('unit_items.issues_from', '=', DB::raw("'wform'"));
                    });
                // ->where("w_form.account_number",$searchDataForView['account_number'])
                if (!empty($acc_number)) {
                    $wFormDataObj = $wFormDataObj->where("w_form.account_number", $acc_number);
                } else {
                    $wFormDataObj = $wFormDataObj->where("reference.date", ">=", strtotime(date('d-m-Y')));

                }

                $wFormDataObj = $wFormDataObj->where("reference.form_status", "<>", -7);

                $wFormDataObj = $wFormDataObj
                    ->orderBy("w_form.reference_number", "DESC")
                    ->get();
                if (!empty($wFormDataObj)) {
                    $wFormData = $wFormDataObj->toArray();
                }

                //die;

                $complaintModelName = new Complaint;
                $complaintDataObj = $complaintModelName
                    ->select(
                        // "complaint.*"
                        "complaint.reference_number"
                        ,
                        "complaint.complaint_type"
                        ,
                        "unit_items.name as category_name"
                        ,
                        "reference.created_by"
                        ,
                        "reference.date"
                        ,
                        "reference.status"
                        ,
                        "reference.form_status"
                    )
                    ->leftJoin('reference', 'reference.reference_number', '=', 'complaint.reference_number')
                    ->leftJoin('unit_items', function ($join) {
                        $join->on('unit_items.master_id', '=', 'complaint.complaint_type');
                        $join->on('unit_items.issues_from', '=', DB::raw("'complaint'"));
                    });
                // ->leftJoin('complaint_type', 'complaint_type.id', '=', 'complaint.complaint_type')
                // ->where("complaint.account_number",$searchDataForView['account_number'])
                if (!empty($acc_number)) {
                    $complaintDataObj = $complaintDataObj->where("complaint.account_number", $acc_number);
                } else {
                    $complaintDataObj = $complaintDataObj->where("reference.date", ">=", strtotime(date('d-m-Y')));

                }

                $complaintDataObj = $complaintDataObj->where("reference.form_status", "<>", -7);

                $complaintDataObj = $complaintDataObj
                    ->orderBy("complaint.reference_number", "DESC")
                    ->get();
                if (!empty($complaintDataObj)) {
                    $complaintData = $complaintDataObj->toArray();
                }

            }

        }
        // prd(strtotime(date('d-m-Y')));
        //prd($complaintData);
        $title = "Supports";
        $title_for_layout = "Supports";

        return view('Supports.index', compact('title', 'title_for_layout', 'isCardMultiple', 'isLoanMultiple', 'multiLoanData', 'isACMultiple', 'multiACData','isCCMultiple','multiCCData','isDCMultiple','multiDCData','multiCardData', 'tblData', 'searchDataForView', 'wFormData', 'complaintData'));
    }
    public function newWForm(Request $request)
    {
        $url = Session::get('searchDataForView', []);

        if(empty($url)){
            return redirect('Supports/home');
        }

        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $params);

        $dataForView = array();
        $title = "New Service Request";
        $title_for_layout = "New Service Request";

        $dataForView = [];
        $dataForView['account_type'] = isset($params['account_type']) ? $params['account_type'] : null;

        if (isset($params['account_type'])) {
            /*if ($params['account_type'] == 1) {*/
            if ($params['account_type'] == 1 || $params['account_type'] == 3) {
                /*$dataForView['account_number'] = isset($params['customer_id']) ? $params['customer_id'] : null;*/
                $dataForView['account_number'] = isset($params['cardNo']) ? $params['cardNo'] : null;
            } elseif ($params['account_type'] == 4) {
                $dataForView['account_number'] = isset($params['accountId']) ? $params['accountId'] : null;
            } else {
                $dataForView['account_number'] = isset($params['accountNumber']) ? $params['accountNumber'] : null;
            }
        }

        $dataForView['reference_number'] = isset($params['reference_number']) ? $params['reference_number'] : null;
        $dataForView['customer_name'] = isset($params['customer_name']) ? $params['customer_name'] : null;
        if ($params['account_type'] == 1 || $params['account_type'] == 3) {
            $dataForView['mobile_number'] = isset($params['mobileNumber']) ? $params['mobileNumber'] : null;
        } else {
            $dataForView['mobile_number'] = isset($params['customer_mobile']) ? $params['customer_mobile'] : null;
        }
        $dataForView['def_email_addr'] = isset($params['def_email_addr']) ? $params['def_email_addr'] : null;
        $dataForView['CIF_number'] = isset($params['CIF_number']) ? $params['CIF_number'] : null;
        $dataForView['SegmentCode'] = isset($params['SegmentCode']) ? $params['SegmentCode'] : null;
        $dataForView['cb_fin_acctno'] = isset($params['cb_fin_acctno']) ? $params['cb_fin_acctno'] : null;
        $dataForView['card_status'] = isset($params['card_status']) ? $params['card_status'] : null;
        $dataForView['acc_name'] = isset($params['categoryTitle']) ? $params['categoryTitle'] : null;
        $dataForView['acc_number'] = isset($params['acc_number']) ? $params['acc_number'] : null;
        $dataForView['mask_card_no'] = isset($params['mask_card_no']) ? $params['mask_card_no'] : null;
        $dataForView['account_status'] = isset($params['account_status']) ? $params['account_status'] : null;
        $dataForView['product_name'] = isset($params['product_name']) ? $params['product_name'] : null;
        $dataForView['branchName'] = isset($params['branchName']) ? $params['branchName'] : null;
        $dataForView['branch_code'] = isset($params['branchCode']) ? $params['branchCode'] : null;
        $dataForView['communication'] = isset($params['communication']) ? $params['communication'] : null;
        $dataForView['customer_nid'] = isset($params['customerNid']) ? $params['customerNid'] : null;
        $dataForView['passpor_number'] = isset($params['passporNumber']) ? $params['passporNumber'] : null;

        $dataForView['accountTitle'] = isset($params['accountTitle']) ? $params['accountTitle'] : null;
        $dataForView['cardType'] = isset($params['cardType']) ? $params['cardType'] : null;
        $dataForView['cardStatus'] = isset($params['cardStatus']) ? $params['cardStatus'] : null;
        $dataForView['dob'] = isset($params['dob']) ? $params['dob'] : null;
        $dataForView['email'] = isset($params['email']) ? $params['email'] : null;
        $dataForView['cardProductName'] = isset($params['cardProductName']) ? $params['cardProductName'] : null;
        $dataForView['clientCode'] = isset($params['clientCode']) ? $params['clientCode'] : null;

        if (isset($params['date_of_birth']) && !empty($params['date_of_birth'])) {
            $dataForView['date_of_birth'] = substr($params['date_of_birth'], 0, 10);
        } else {
            $dataForView['date_of_birth'] = "";
        }


        $productTypeModelName = new ProductType;
        $allProductTypeData = $productTypeModelName
            ->select('id', 'name')
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->pluck('name', 'id')
            ->toArray();

        /*$unitModelName = new Unit;
        $allUnitData = $unitModelName
                        ->select("id","name")
                        ->where("status","1")
                        ->whereNotIn('id', [1,2,21])
                        ->pluck("name","id")
                        ->toArray();*/

        $unitItemModelName = new UnitItem;
        $allUnitItemData = $unitItemModelName
            ->select("master_id", "name")
            ->where("status", "1")
            ->where("issues_from", "wform")
            ->orderBy("name")
            ->pluck("name", "master_id")
            ->toArray();

        $sourceModelName = new Source;
        $allSourceData = $sourceModelName
            ->select("id", "source_name")
            ->pluck("source_name", "source_name")
            ->toArray();

        $wformMasterModelName = new WformMaster;
        $tmpWformMasterData = $wformMasterModelName
            ->select("master_id", "name", "type")
            ->get()
            ->toArray();

        /* Default Array Initialization */
        $allWformMasterData = array();
        $allWformMasterData['auto_debit_type'] = array();
        $allWformMasterData['auto_bills_pay_partner'] = array();
        $allWformMasterData['auto_bills_pay_type'] = array();
        $allWformMasterData['branch_service_type'] = array();
        $allWformMasterData['branch_service_type_cc'] = array();
        $allWformMasterData['branch_service_type_ast'] = array();
        $allWformMasterData['category'] = array();
        $allWformMasterData['charge_type'] = array();
        $allWformMasterData['cle_request'] = array();
        $allWformMasterData['closure_type'] = array();
        $allWformMasterData['credit_card_type'] = array();
        $allWformMasterData['de_enrollment'] = array();
        $allWformMasterData['delivery_option'] = array();
        $allWformMasterData['enrollment_type'] = array();
        $allWformMasterData['ezy_pay_txn_type'] = array();
        $allWformMasterData['issuance_bank'] = array();
        $allWformMasterData['instant_pay_process_fee'] = array();
        $allWformMasterData['loan_type'] = array();
        $allWformMasterData['mode_of_payment'] = array();
        $allWformMasterData['profession'] = array();
        $allWformMasterData['renewal_request'] = array();
        $allWformMasterData['replacement_reason'] = array();
        $allWformMasterData['resendto'] = array();
        $allWformMasterData['reversal_request'] = array();
        $allWformMasterData['security_item_activation'] = array();
        $allWformMasterData['security_item_type'] = array();
        $allWformMasterData['tdr_type'] = array();
        $allWformMasterData['tenor'] = array();
        $allWformMasterData['transfer_type'] = array();

        if (!empty($tmpWformMasterData)) {
            foreach ($tmpWformMasterData as $key => $value) {
                $type = $value['type'];
                $master_id = $value['master_id'];
                $name = $value['name'];
                if (empty($allWformMasterData[$type])) {
                    $allWformMasterData[$type] = array();
                }
                $allWformMasterData[$type][$name] = $name;
            }
        }
        $attachment_item = 0;
        $issue_fields = [];
        $check_lists = [];
        $type = "";
        return view('Supports.new_wform', compact('title', 'title_for_layout', 'dataForView', 'allProductTypeData', 'allUnitItemData', 'allSourceData', 'allWformMasterData', 'attachment_item', 'issue_fields', 'check_lists', 'type'));
    }

    public function submitWform(WFormRequest $request)
    {
       // dd($request->toArray());
        $extra_field = '';
        $issue_check_field = '';

        if ($request->isMethod('post')) {
	    /* ================= post no debit checking start ================= */
            $postNODebitStatus = false;
            if ($request->w_form_type == 1190) {
                $api_credential = DB::table('api_credential')->first();
                $accessToken = $this->getAccessToken($api_credential->token_url, $this->username, $this->password);

                if (isset($accessToken['responseCode']) && $accessToken['responseCode'] == 200) {
                    $restUrl = $api_credential->Post_No_Debit_API_URL;
                    $post_data = json_encode([
                        'accountNumber' => $request->account_number,
                        'restrictedType' => "1",
                        'channel' => "MIB",
                        'restrictedReason' => $request->remarks,
                    ]);
                    $headers = [
                        'Content-Type: application/json',
                        'Authorization: ' . $accessToken['data']['accessToken'],
                    ];
                    $response = $this->getPostNoDebit($restUrl, $post_data, $headers);
			
                    /* ================= success post no debit ================= */
                    if (!empty($response)) {
                        $responseData = json_decode($response, true);
			
			            if (isset($responseData['responseCode']) && $responseData['responseCode'] == '994') {
			                $this->osbApiRequestResponse(['account_number' => $request->account_number, 'cif_number' => '', 'type' => 2, 'url' => $restUrl, 'service' => '1- Post No Debit', 'json_node' => $response, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                            Log::info('POST NO DEBIT ALREADY IMPOSED FOR THIS ACCOUNT: ');
                            $additionalParams = (!empty($request->additionalParams)) ? $request->additionalParams : "";
                            flash('POST NO DEBIT ALREADY IMPOSED FOR THIS ACCOUNT!', 'danger');
                            return redirect('Supports/NewWForm' . $additionalParams);
                        }


                        if (isset($responseData['responseCode']) && $responseData['responseCode'] == '200') {
			                $this->osbApiRequestResponse(['account_number' => $request->account_number, 'cif_number' => '', 'type' => 2, 'url' => $restUrl, 'service' => '1- Post No Debit', 'json_node' => $response, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                            $postNODebitStatus = true;
			                Log::info('Post No Debit Executed');
                        }else{
                            $this->osbApiRequestResponse(['account_number' => $request->account_number, 'cif_number' => '', 'type' => 2, 'url' => $restUrl, 'service' => '1- Post No Debit', 'json_node' => $response, 'status_msg' => $responseData['messages'][0] ?? '', 'status_code' => $responseData['responseCode'] ?? '']);
                            Log::info('Post No Debit Not Execute: ' . $responseData['curlMsg']);
                            $additionalParams = (!empty($request->additionalParams)) ? $request->additionalParams : "";
                            flash('Post No Debit Not Executed!', 'danger');
                            return redirect('Supports/NewWForm' . $additionalParams);
                        }


                    }
                } else {
                    $searchDataForView['errorSMSAPI'] = 'Token Issue. Please Contact API Team !';
                }
            }
            /* ================= post no debit checking end ================= */

            /* This part is dynamic input*/
            $issue_config = IssueConfig::where('issue_id', $request->w_form_type)->get();
            if (count($issue_config) != 0) {
                /* For TQ Local issue ID 1103, UAT */
                if ($request->w_form_type == 1103 || $request->w_form_type == 1105) {
                    $dataName = [];
                    $passport = [];
                    $currentYear = [];
                    $nextYear = [];
                    $medicalQuota = [];
                    $customerInfo = [];
                    $customerInfo = $request->customer_info;
                    if (!empty($customerInfo)){
                        $decryptCustomerInfo = decrypt($customerInfo);
                        $cardStatus = !empty($decryptCustomerInfo['customer']) ? $decryptCustomerInfo['customer']['cardStatus'] : '';
                        $productType = !empty($decryptCustomerInfo['product']) ? $decryptCustomerInfo['product']['productType'] : '';
                        if ($request->productType == 0 && $request->productType != $productType){
                            return \redirect()->back()->with('error','This Card is not Supported For Medical Quota!');
                        }elseif($request->productType == 1 && $request->productType != $productType){
                            return \redirect()->back()->with('error','This Card is not Supported For Travel Quota!');
                        }

                        if ($cardStatus != 00){
                            return \redirect()->back()->with('error','This Card is not Active!');
                        }
                    }

                    foreach ($issue_config as $issue_con) {
                        if (!empty($request['passport'])) {
                            if (array_key_exists($issue_con->field_name, $request['passport'])) {
                                $keys = explode(':', $issue_con->api_key);
                                if($keys[0] == 'passportAddress'){
                                    $spacilaChaP = str_replace("&", "&amp;", $request['passport'][$issue_con->field_name]);
                                    $passport[] = [
                                        $issue_con->label_name => $spacilaChaP,
                                        'api_key' => $issue_con->api_key,
                                    ];
                                }else{
                                    $passport[] = [
                                        $issue_con->label_name => $request['passport'][$issue_con->field_name],
                                        'api_key' => $issue_con->api_key,
                                    ];
                                }
                            }
                            $passport['request_type'] = !empty($request['passport']['request_type']) ? $request['passport']['request_type'] : '';
                            $passport['customer_id'] = !empty($request['passport']['customer_id']) ? $request['passport']['customer_id'] : '';
                            $passport['response'] = !empty($request['passport']['p_response']) ? $request['passport']['p_response'] : '';
                        }

                        if (!empty($request['currentYear'])) {
                            if (array_key_exists($issue_con->field_name, $request['currentYear'])) {
                                $currentYear[] = [
                                    $issue_con->label_name => $request['currentYear'][$issue_con->field_name],
                                    'api_key' => $issue_con->api_key,
                                ];
                            }
                            $currentYear['request_type'] = !empty($request['currentYear']['request_type']) ? $request['currentYear']['request_type'] : '';
                            $currentYear['quota_id'] = (!empty($request['currentYear']['quota_id'])) ? $request['currentYear']['quota_id'] : '';
                            $currentYear['customer_info'] = (!empty($request['currentYear']['customer_info'])) ? $request['currentYear']['customer_info'] : '';
                            $currentYear['response'] = (!empty($request['currentYear']['response'])) ? $request['currentYear']['response'] : '';
                        }

                        if (!empty($request['nextYear'])) {
                            if (array_key_exists($issue_con->field_name, $request['nextYear'])) {
                                $nextYear[] = [
                                    $issue_con->label_name => $request['nextYear'][$issue_con->field_name],
                                    'api_key' => $issue_con->api_key,
                                ];
                            }
                            $nextYear['request_type'] = !empty($request['nextYear']['request_type']) ? $request['nextYear']['request_type'] : '';
                            $nextYear['quota_id'] = (!empty($request['nextYear']['quota_id'])) ? $request['nextYear']['quota_id'] : '';
                            $nextYear['customer_info'] = (!empty($request['nextYear']['customer_info'])) ? $request['nextYear']['customer_info'] : '';
                            $nextYear['response'] = (!empty($request['nextYear']['response'])) ? $request['nextYear']['response'] : '';
                        }

                        if (!empty($request['medicalQuota'])) {
                            if (array_key_exists($issue_con->field_name, $request['medicalQuota'])) {
                                $medicalQuota[] = [
                                    $issue_con->label_name => $request['medicalQuota'][$issue_con->field_name],
                                    'api_key' => $issue_con->api_key,
                                ];
                            }
                            $medicalQuota['request_type'] = !empty($request['medicalQuota']['request_type']) ? $request['medicalQuota']['request_type'] : '';
                            $medicalQuota['quota_id'] = (!empty($request['medicalQuota']['quota_id'])) ? $request['medicalQuota']['quota_id'] : '';
                            $medicalQuota['customer_info'] = (!empty($request['medicalQuota']['customer_info'])) ? $request['medicalQuota']['customer_info'] : '';
                            $medicalQuota['response'] = (!empty($request['medicalQuota']['response'])) ? $request['medicalQuota']['response'] : '';
                        }
                    }

                    $dataName = [
                        'P' => $passport,
                        'C' => $currentYear,
                        'N' => $nextYear,
                        'MQ' => $medicalQuota,
                        'CInfo' => $customerInfo,
                    ];
                }else {
                    foreach ($issue_config as $issue_con) {
                        if ($request[$issue_con->field_name] != null) {
                            $dataName[] = [
                                $issue_con->label_name => $request[$issue_con->field_name]
                            ];
                        } else {
                            $dataName[] = [
                                $issue_con->label_name => ''
                            ];
                        }
                    }
                }

                $extra_field = json_encode($dataName);
            }
            $issue_checkList = IssueCheckListConfig::where('issue_id', $request->w_form_type)->get();
            if (count($issue_checkList) != 0) {
                foreach ($issue_checkList as $issue_check) {
                    if ($request[$issue_check->field_name]) {
                        $dataCheckList[] = [
                            $issue_check->label_name => $request[$issue_check->field_name]
                        ];
                    } else {
                        $dataCheckList[] = [
                            $issue_check->label_name => ''
                        ];
                    }

                }
                $issue_check_field = json_encode($dataCheckList);
            }
            /* Get Unit ID from WForm Type */
            $unitItemModelName = new UnitItem;
            $unitItemData = $unitItemModelName->select("id", "unit_id", "is_sent_sms", "name")->where([["master_id", $request->w_form_type], ["issues_from", "wform"]])->first();

            $workflowlist = "";

            $unitUser = DB::table('user_units')
                ->join('subgroup_info', 'subgroup_info.id', '=', 'user_units.subgroup_info_id')
                ->where('user_units.user_id', Auth::id())->first();
            $workflow = IssueWorkflow::where('issue_id', $unitItemData->id)->first();
            $firstWorkFlow = IssueGroupWorkflow::where('group_info_id', $unitUser->group_info_id)
                ->where('issue_workflow_id', $workflow->issue_workflow_id)->first();

            if ($workflow->flow_type == FlowEnum::REGULAR) {
                if ($firstWorkFlow->touch_checker == 1) {
                    $subgroup_id = $firstWorkFlow->group_info_id;
                    $next_label = $firstWorkFlow->touch_checker;
                    $unit_label = 2;

                } else {
                    $workflowlist = IssueGroupWorkflow::where('issue_workflow_id', $workflow->issue_workflow_id)->where('is_touch_point', '<>', 1)->orderBy('issue_group_workflow_id', 'ASC')->first();
                    //prd($workflowlist->toArray());
                    if ($workflowlist->touch_maker == 1) {
                        $subgroup_id = $workflowlist->group_info_id;
                        $next_label = $workflowlist->touch_maker;
                        $unit_label = 1; //'1' mean Maker
                    } else {
                        $subgroup_id = $workflowlist->group_info_id;
                        $next_label = $workflowlist->touch_maker;
                        $unit_label = 2; //'2' mean Checker
                    }

                }
            }
            if ($workflow->flow_type == FlowEnum::FORWARD) {

                //$NotTouchWorkFlow = IssueGroupWorkflow::where('issue_workflow_id',$workflow->issue_workflow_id)->where('is_touch_point',0)->first();
                //$subgroup_id = $NotTouchWorkFlow->group_info_id;
                //$unit_label=1; //'1' mean maker

                $subgroup_id = $firstWorkFlow->group_info_id;
                $next_label = $firstWorkFlow->touch_checker;
                $unit_label = 2;
            }

            /* End of Get Unit ID from WForm Type */
		
	        $prodTypeAlpha = "";
            if ($request->product_type == 1) {
                $prodTypeAlpha = "CC";
            } elseif ($request->product_type == 2) {
                 $prodTypeAlpha = "AC";
            } elseif ($request->product_type == 3) {
                 $prodTypeAlpha = "DC";
            } elseif ($request->product_type == 4) {
                 $prodTypeAlpha = "LN";
            } elseif ($request->product_type == 5) {
                 $prodTypeAlpha = "TR";
            }

            $reference_number =
                "S" .
                date("ymd") . $prodTypeAlpha .
                //userIdPadRightWith0($request->product_type, 2, '0').
                //userIdPadLeftWith0($issueId, 4, '0').
                userIdPadLeftWith0($this->dayWiseSequence('sr'), 6, '0');

	  
            /* Document Upload Process */
            $docDestPath = 'public/attachments';

            // BPID Attachment upload
            if ($request->w_form_type == getId('BPID')) {

                foreach ($request->allFiles() as $field => $file) {
                    if (!$file->isValid()) continue;
                    $fileName = $field . '_attach_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    Attachment::create([
                        'file_name'        => $fileName,
                        'reference_number' => $reference_number,
                        'attachment_date'  => now()->toDateString(),
                        'uploaded_by'      => auth()->id(),
                        'name'             => ucwords(str_replace('_', ' ', $field)),
                    ]);

                    // $file->move($docDestPath, $fileName);
                    $fileContent = File::get($file->getRealPath());
                    Storage::disk('custom_storage')->put($fileName, $fileContent);
                }
            }

            else{
                if (!empty($request->file('file_name'))) {
                    foreach ($request->file('file_name') as $key => $files) {
                        $extension = $files->getClientOriginalExtension();
                        $origin_name = pathinfo($files->getClientOriginalName(), PATHINFO_FILENAME);
                        $origin_name = str_replace(' ', '_', $origin_name);
                        $origin_name = substr($origin_name, 0, 20);
                        $fileName = $origin_name . "_attach_nX_" . round(microtime(true) * 10) . "_" . ($key + 1) . '.' . $extension;

                        $attachment = new Attachment();
                        $attachment->file_name = $fileName;
                        $attachment->reference_number = $reference_number;
                        $attachment->attachment_date = date('Y-m-d');
                        $attachment->uploaded_by = Auth::user()->id;
                        $attachment->save();
                        //$files->move($docDestPath, $fileName);

                        $fileContent = File::get($files->getRealPath());
                        //Storage::disk('custom_storage')->put($fileName, $fileContent);
                        
                        try {
                            
                            $result = Storage::disk('custom_storage')->put($fileName, $fileContent);
                            Log::info('upload status : '. $result);
                        } catch (\Throwable $th) {
                            Log::info('File Upload Failed' . $th->getMessage());
                            //throw $th;
                        }

                        /*$image                   =       $files;
                        $img                     =       ImageResizer::make($image->path());

                        // --------- [ Resize Image ] ---------------
                        $imgInfo = $img->resize(150, 100, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($docDestPath.'/'.$fileName);*/
                    }
                }
            }

	        // check document
            try {

                $attachments = Attachment::where('reference_number', $reference_number)->get();
                foreach ($attachments as $attachment) {
                    $filePath = $docDestPath . '/' . $attachment->file_name;
                    if (!file_exists($filePath)) {
                        // delete attachment data
                        Attachment::where('reference_number', $reference_number)->delete();
                        throw new \Exception('Unable to upload file, Contact with system admin!');
                    }
                }
            } catch (\Exception $e) {
                flash($e->getMessage(), 'danger');
                return redirect('Supports/NewWForm');
            }

            /* End of Get Unit ID from WForm Type */
            $referenceModelName = new Reference;
            if ($referenceModelName->save()) {
                /* Generate Reference No*/
                // $referenceId = $referenceModelName->id;
                // $reference_number = "S".date("Ym").userIdPadLeftWith0(($referenceId), 6, '0');

                $issueId = (!empty($workflow->issue_id)) ? $workflow->issue_id : 0;

                $referenceModelName->reference_number = $reference_number;
                $referenceModelName->unit_id = (!empty($unit_label)) ? $unit_label : 0;
                $referenceModelName->subgroup_id = (!empty($subgroup_id)) ? $subgroup_id : 0;

                $unitList = Auth::user()->user_unit;
                if (!empty($workflowlist->group_info_id)) {
                    $subgroup_info_id = SubgroupInfo::where('group_info_id', $workflowlist->group_info_id)->first();
                    $referenceModelName->sub_group_info_id = $subgroup_info_id->id;
                } else {

                    if (!empty($unitList)) {
                        $getSubGroupId = $unitList->subgroup_info_id;
                        $referenceModelName->sub_group_info_id = $getSubGroupId;
                    }
                }

                // For User Segment code filter Priority
                if (!empty($request->segment)){
                    $segCode = SegmentCode::where('status', 1)->get(['code']);
                    if (!empty($segCode)){
                        foreach ($segCode as $code){
                            if ($code->code == $request->segment){
                                $referenceModelName->segment_priority = 1;
                            }
                        }
                    }
                }

                $referenceModelName->issue_id = (!empty($workflow->issue_id)) ? $workflow->issue_id : 0;
                $referenceModelName->date = strtotime(date('d-m-Y h:i:s A'));
                $referenceModelName->created_by = Auth::user()->user_id;
                $referenceModelName->account_type = $request->product_type;
                $referenceModelName->is_tara = $request->is_tara;
                $referenceModelName->issues_from = 'wform';
                $referenceModelName->status = 47;
                $referenceModelName->save();

                /* Store WForm */
                $wformModelName = new WForm;

                $wformModelName->acc_opening_branch = $request->branchName;
                $wformModelName->branch_code = $request->branch_code;
                $wformModelName->communication = $request->communication;
                $wformModelName->customer_nid = $request->customer_nid;
                $wformModelName->passpor_number = $request->passpor_number;


                $wformModelName->reference_number = $reference_number;
                $wformModelName->account_number = $request->account_number;
                $wformModelName->customer_name = $request->customer_name;
                $wformModelName->mobile_number = $request->mobile_number;
                $wformModelName->email_address = $request->def_email_addr;
                $wformModelName->product_type = $request->product_type;
                // $wformModelName->item_type = $request->item_type;
                $wformModelName->priority = $request->priority;
                $wformModelName->time_and_ext = $request->time_and_ext;
                $wformModelName->source = $request->source;
                $wformModelName->tin_verified = $request->tin_verified;
                $wformModelName->caller_id = $request->caller_id;
                $wformModelName->date_of_birth = $request->date_of_birth;
                $wformModelName->segment = $request->segment;
                $wformModelName->individual_acct_no = $request->cb_fin_acctno;
                if (empty($request->card_status)) {
                    $request->card_status = "SB";
                }
                $wformModelName->card_status = $request->card_status;
                //$wformModelName->address = $request->address;
                //$wformModelName->other = $request->other;
                //$wformModelName->dynamic_question = $request->dynamic_question;
                //$wformModelName->other2 = $request->other2;
                $wformModelName->SIF_Number = $request->SIF_Number;
                $wformModelName->static_verified = $request->static_verified;
                $wformModelName->dynamic_verified = $request->dynamic_verified;
                $wformModelName->notes = $request->notes;
                $wformModelName->w_form_type = $request->w_form_type;
                $wformModelName->acc_name = $request->acc_name;
                $wformModelName->product_desc = $request->product_desc;
                // $wformModelName->acc_opening_branch = $request->acc_opening_branch;
                $wformModelName->account_status = $request->account_status;
                if (!empty($request->inputted_masking_card)){
                    $wformModelName->mask_card_no = $request->inputted_masking_card;
                }else{
                    $wformModelName->mask_card_no = $request->mask_card_no;
                }

                $wformModelName->balance = Session::get('real_account_balance', '');
                $wformModelName->save();
                /* End of Store W-Form*/

                /* Store W-Form type */
                $w_form_type = $request->w_form_type;
                $wformTypeModelName = new WFormType;
                $wformTypeModelName->reference_number = $reference_number;
                $wformTypeModelName->extra_field = $extra_field;
                $wformTypeModelName->check_list = $issue_check_field;
                $wformTypeModelName->save();
                // $this->form_status($reference_number,0,20);
                
                if (!empty($workflow['log'] == 1)) {
                    $outgoingSMSMessage = $this->outgoingSMSEmail("wform", $w_form_type, $reference_number, "open", $unitItemData['name']);

                    if (!empty($outgoingSMSMessage['sms'])) {
                        $this->sendSMS($request->mobile_number, $outgoingSMSMessage['sms'], $reference_number, 0);
                    }
                    if (!empty($outgoingSMSMessage['mail'])) {
                        if (!empty($request->def_email_addr)) {
                            $this->sendEMAIL($request->def_email_addr, $outgoingSMSMessage['mail'], $reference_number, 0);
                        }
                    }
                }

	            /* End of Store W-Form type */

                /* ====================== Auto Assign START ====================== */
                $issueGroup = DB::table('issue_groups')->where('unit_item_id', $request->w_form_type)->first();
                if ($issueGroup) {
                    // base query (reuse)
                    $baseQuery = IssueGroupMember::where('issue_group_id', $issueGroup->id)
                        ->where('subgroup_info_id', $referenceModelName->sub_group_info_id)
                        ->where('unit_id', $referenceModelName->unit_id)
                        ->lockForUpdate();

                    // current pointer
                    $current = (clone $baseQuery)->where('sequence', 1)->first();
                    if ($current) {
                        // next member
                        $next = (clone $baseQuery)->where('id', '>', $current->id)->orderBy('id')->first();
                        // wrap around
                        if (!$next) {
                            $next = (clone $baseQuery)->orderBy('id')->first();
                        }
                        // rotate pointer
                        $current->update(['sequence' => 0]);
                        $next->update(['sequence' => 1]);
                    } else {
                        // first assignment
                        $next = (clone $baseQuery)->orderBy('id')->first();
                        if ($next) {
                            $next->update(['sequence' => 1]);
                        }
                    }

                    if (isset($next)) {
                        $user_id = User::where('id', $next->user_id)->value('user_id');

                        $referenceModelName->form_status = 2; // Assigned
                        $referenceModelName->access_by = $user_id;
                        $referenceModelName->access_date = strtotime(date('Y-m-d H:i:s'));
                        $referenceModelName->save();

                        // comment store
                        $this->audit([
                            'reference_number' => $referenceModelName->reference_number,
                            'unit_id' => $referenceModelName->unit_id,
                            'group_id' => $referenceModelName->subgroup_id,
                            'user_id' => $user_id,
                            'action' => 'Assigned',
                            'comments' => '',
                            'duration_in_minutes' => 0,
                            // 'form_load' => $request->st,
                            'form_load' => '2025-12-21 20:53:39',
                            'isapproved' => '0',
                            'subgroup_id' => $referenceModelName->sub_group_info_id,
                        ]);
                    }
                }
                /* ====================== Auto Assign END ====================== */

                /* ====================== Store bp id START ====================== */
                if ($request->w_form_type == getId('BPID')) {
                    $bpId = new BpId();
                    $bpId->bp_id = null;
                    $bpId->account_number = $request->account_number;
                    $bpId->reference_number = $reference_number;
                    $bpId->branch_name = $request->branchName;
                    $bpId->account_title = $request->account_name;

                    $bpId->contact_no_1 = $request->first_app_contact_no ?? null;
                    $bpId->email_1 = $request->first_app_email ?? null;

                    $bpId->contact_no_2 = $request->second_app_contact_no ?? null;
                    $bpId->email_2 = $request->second_app_email ?? null;

                    $bpId->contact_no_3 = $request->third_app_contact_no ?? null;
                    $bpId->email_3 = $request->third_app_email ?? null;

                    $bpId->contact_no_4 = $request->four_app_contact_no ?? null;
                    $bpId->email_4 = $request->four_app_email ?? null;
                    $bpId->save();
                }
                /* ====================== Store bp id END ====================== */
		
		        /* ================== Post no Debit ==================== */
                if ($request->w_form_type == 1190 && $postNODebitStatus) {

                    $referenceModelName->form_status = 11;
                    $referenceModelName->save();

                    if ($request->request_from != "non-customer") {

                        $outgoingSMSMessage = $this->outgoingSMSEmail("wform", $w_form_type, $reference_number, "close", $unitItemData['name']);
                        //print_r($outgoingSMSMessage);
                        if (!empty($outgoingSMSMessage['sms'])) {
                            $this->sendSMS($request->mobile_number, $outgoingSMSMessage['sms'], $reference_number, 11);
                        }
                        if (!empty($outgoingSMSMessage['mail']) && (!empty($dataObj->email_address))) {
                            if (!empty($request->def_email_addr)) {
                                $this->sendEMAIL($request->def_email_addr, $outgoingSMSMessage['mail'], $reference_number, 11);
                            }
                        }
                    }


                    $this->audit([
                        'reference_number' => $reference_number,
                        'unit_id' => 1,
                        'group_id' => $firstWorkFlow->group_info_id,
                        'user_id' => Auth::user()->user_id,
                        'action' => "Close",
                        'comments' => $request->remarks,
                        'isapproved' => '1',
                        'subgroup_id' => $unitList->subgroup_info_id
                    ]);

                    Session::forget('real_account_balance'); 
                    flash("Ticket No: $reference_number have been closed successfully. And Post No Debit Execute", 'success');
                    return redirect('Supports/home');
                }
                /* ================== Post no Debit ==================== */



                $this->audit(['reference_number' => $reference_number, 'unit_id' => 1, 'group_id' => $firstWorkFlow->group_info_id, 'user_id' => Auth::user()->user_id, 'action' => Session::get('subgroupStr') . ' Logged', 'comments' => '', 'isapproved' => '1', 'subgroup_id' => $unitList->subgroup_info_id]);

                //\Session::flash('W-Form have been saved successfully. Ref: '.$reference_number, 'success');
                //Session::flush();
                $additionalParams = (!empty($request->additionalParams)) ? $request->additionalParams : "";

                flash('Service Request have been saved successfully. Ticket No: ' . $reference_number, 'success');
                // echo "<script>window.close();</script>";
                // echo "<script>alert('W-Form have been saved successfully');window.close();</script>";
                // echo "<script>open(location, '_self').close();  //window.close();</script>";
                //return redirect('Supports/home'.$additionalParams)->with('success','Service Request have been saved successfully. Ticket No:'.$reference_number);
                //return redirect('Supports/home'.$additionalParams);
                Session::forget('real_account_balance'); 
                return redirect('Supports/home');
            } else {
                $additionalParams = (!empty($request->additionalParams)) ? $request->additionalParams : "";
                flash('Failed to save data', 'danger');
                return redirect('Supports/NewWForm' . $additionalParams);
            }
        }
    }

    public function wFormUpdate(WFormUpdateRequest $request, $reference_number)
    {
        $bpid_reference_number = '';
        if($request->issue_id == getId('AUCTION')){
            $bpid = BpId::where('account_number',$request->account_number)->latest()->first();
            if(!$bpid){
                flash('BPID Not Found!', 'danger');
                return redirect()->back();
            }

            if($request->bp_id != $bpid->bp_id){
                flash('BPID Number Invalid!', 'danger');
                return redirect()->back();
            }

            $bpid_reference_number = $bpid->reference_number;

        }

        $extra_field = '';
        $issue_check_field = '';
        $w_form_type = WFormType::where('reference_number', $reference_number)->first();

        WFormTypeHistory::create([
            'reference_number' => $reference_number,
            'extra_field' => $w_form_type->extra_field,
            'check_list' => $w_form_type->check_list,
            'user_id' => Auth::id(),
        ]);

        $issue_config = IssueConfig::where('issue_id', $request->issue_id)->get();
        if (count($issue_config) != 0) {
            if ($request->issue_id == 1103 || $request->issue_id == 1105) {
                $dataName = [];
                $passport = [];
                $currentYear = [];
                $nextYear = [];
                $medicalQuota = [];
                $customerInfo = [];
                $customerInfo = $request->customer_info;
                foreach ($issue_config as $issue_con) {
                    if (!empty($request['passport'])) {
                        if (array_key_exists($issue_con->field_name, $request['passport'])) {
                            $passport[] = [
                                $issue_con->label_name => $request['passport'][$issue_con->field_name],
                                'api_key' => $issue_con->api_key,
                            ];
                        }
                        $passport['request_type'] = $request['passport']['request_type'];
                        $passport['customer_id'] = $request['passport']['customer_id'];
                        $passport['response'] = $request['passport']['response'];
                    }

                    if (!empty($request['currentYear'])) {
                        if (array_key_exists($issue_con->field_name, $request['currentYear'])) {
                            $currentYear[] = [
                                $issue_con->label_name => $request['currentYear'][$issue_con->field_name],
                                'api_key' => $issue_con->api_key,
                            ];
                        }
                        $currentYear['request_type'] = $request['currentYear']['request_type'];
                        $currentYear['quota_id'] = (!empty($request['currentYear']['quota_id'])) ? $request['currentYear']['quota_id'] : '';
                        $currentYear['customer_info'] = (!empty($request['currentYear']['customer_info'])) ? $request['currentYear']['customer_info'] : '';
                        $currentYear['response'] = (!empty($request['currentYear']['response'])) ? $request['currentYear']['response'] : '';
                    }

                    if (!empty($request['nextYear'])) {
                        if (array_key_exists($issue_con->field_name, $request['nextYear'])) {
                            $nextYear[] = [
                                $issue_con->label_name => $request['nextYear'][$issue_con->field_name],
                                'api_key' => $issue_con->api_key,
                            ];
                        }
                        $nextYear['request_type'] = $request['nextYear']['request_type'];
                        $nextYear['quota_id'] = (!empty($request['nextYear']['quota_id'])) ? $request['nextYear']['quota_id'] : '';
                        $nextYear['customer_info'] = (!empty($request['nextYear']['customer_info'])) ? $request['nextYear']['customer_info'] : '';
                        $nextYear['response'] = (!empty($request['nextYear']['response'])) ? $request['nextYear']['response'] : '';
                    }

                    if (!empty($request['medicalQuota'])) {
                        if (array_key_exists($issue_con->field_name, $request['medicalQuota'])) {
                            $medicalQuota[] = [
                                $issue_con->label_name => $request['medicalQuota'][$issue_con->field_name],
                                'api_key' => $issue_con->api_key,
                            ];
                        }
                        $medicalQuota['request_type'] = $request['medicalQuota']['request_type'];
                        $medicalQuota['quota_id'] = (!empty($request['medicalQuota']['quota_id'])) ? $request['medicalQuota']['quota_id'] : '';
                        $medicalQuota['customer_info'] = (!empty($request['medicalQuota']['customer_info'])) ? $request['medicalQuota']['customer_info'] : '';
                        $medicalQuota['response'] = (!empty($request['medicalQuota']['response'])) ? $request['medicalQuota']['response'] : '';
                    }
                }

                $dataName = [
                    'P' => $passport,
                    'C' => $currentYear,
                    'N' => $nextYear,
                    'MQ' => $medicalQuota,
                    'CInfo' => $customerInfo,
                ];

            } else{
                foreach ($issue_config as $issue_con) {
		            // Skip file fields -- same as submitWform
                    if ($issue_con->field_type === 'file') {
                        continue;
                    }

                    if ($request[$issue_con->field_name] != null) {
                        $dataName[] = [
                            $issue_con->label_name => $request[$issue_con->field_name]
                        ];
                    } else {
                        $dataName[] = [
                            $issue_con->label_name => ''
                        ];
                    }

                }
            }
            //pr($dataName);
            $extra_field = json_encode($dataName);
        }
        $issue_checkList = IssueCheckListConfig::where('issue_id', $request->issue_id)->get();
        if (count($issue_checkList) != 0) {
            foreach ($issue_checkList as $issue_check) {
                if ($request[$issue_check->field_name]) {
                    $dataCheckList[] = [
                        $issue_check->label_name => $request[$issue_check->field_name]
                    ];
                } else {
                    $dataCheckList[] = [
                        $issue_check->label_name => ''
                    ];
                }

            }
            //pr($dataCheckList);
            $issue_check_field = json_encode($dataCheckList);
        }



        /* ================= Document Upload Process (same format as submitWform) ================= */
        $docDestPath = 'public/attachments';

        // BPID Attachment upload
        if ($request->issue_id == getId('BPID') || $request->issue_id == getId('AUCTION') ) {

            foreach ($request->allFiles() as $field => $file) {
                if (!$file->isValid()) continue;
                $fileName = $field . '_attach_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                Attachment::create([
                    'file_name'        => $fileName,
                    'reference_number' => $request->issue_id == getId('AUCTION') ? $bpid_reference_number : $reference_number,
                    'attachment_date'  => now()->toDateString(),
                    'uploaded_by'      => auth()->id(),
                    'name'             => ucwords(str_replace('_', ' ', $field)),
                ]);

                $file->move($docDestPath, $fileName);
            }
        }
        // Another Attachment upload
        else {
            if (!empty($request->file('file_name'))) {
                foreach ($request->file('file_name') as $key => $files) {
                    if (!$files->isValid()) continue;

                    $extension = $files->getClientOriginalExtension();
                    $origin_name = pathinfo($files->getClientOriginalName(), PATHINFO_FILENAME);
                    $origin_name = str_replace(' ', '_', $origin_name);
                    $origin_name = substr($origin_name, 0, 20);
                    $fileName = $origin_name . "_attach_nX_" . round(microtime(true) * 10) . "_" . ($key + 1) . '.' . $extension;

                    $attachment = new Attachment();
                    $attachment->file_name = $fileName;
                    $attachment->reference_number = $reference_number;
                    $attachment->attachment_date = date('Y-m-d');
                    $attachment->uploaded_by = Auth::user()->id;
                    $attachment->save();
                    $files->move($docDestPath, $fileName);
                }
            }
        }

        // check document (same integrity check as submitWform)
        try {
            $attachments = Attachment::where('reference_number', $reference_number)->get();
            foreach ($attachments as $attachment) {
                $filePath = $docDestPath . '/' . $attachment->file_name;
                if (!file_exists($filePath)) {
                    Attachment::where('reference_number', $reference_number)
                        ->where('file_name', $attachment->file_name)
                        ->delete();

                    throw new \Exception('Attachment file not found!');
                }
            }
        } catch (\Exception $e) {
            flash($e->getMessage(), 'danger');
            return \redirect()->back();
        }
        /* ================= End Document Upload Process ================= */

        $wformTypeModelName = WFormType::where('reference_number', $reference_number)->first();

        if (!empty($extra_field)) {
            $wformTypeModelName->extra_field = $extra_field;
        }
        if (!empty($issue_check_field)) {
            $wformTypeModelName->check_list = $issue_check_field;
        }

        $wformTypeModelName->save();
        flash('Successfully Update Information', 'success');
        return \redirect()->back();
    }

    // UAT Server
    public function wFormDetails(Request $request, $reference_number = "")
    {
        // DB::enableQueryLog();
        try {
            $reference_number = decrypt($reference_number);
        } catch (DecryptException $e) {
            abort(403, 'Un-Authorize Access!!!');
        }

        $isAdminOrLogger = false;
        $reportView = false;
        $getUnitId = "";
        $getUnitIdArr = array();
        $getSubGroupIdArr = array();
        $subGroupInfoArr = array();
        $getDepartmentId = "";
        $getDivisionId = "";
        $getGroupId = "";
        $get_subgroup_info_id = "";

        if (!empty($request->viewFrom)) {
            $reportView = true;
        }

        if (Auth::user()->hasRole(['superadmin', 'admin'])) {
            $isAdminOrLogger = true;
        } else {
            if (Auth::user()->hasRole(['logger'])) {
                $isAdminOrLogger = true;
            }
            $unitList = Auth::user()->user_unit;
            if (!empty($unitList)) {
                $getUnitId = $unitList->unit_id;
                $getDepartmentId = $unitList->department_id;
                $getDivisionId = $unitList->division_id;
                $get_subgroup_info_id = $unitList->subgroup_info_id;
                $getGroupId = $unitList->group_info_id;
            }
            if ($getUnitId != "1,2" && $getUnitId != "2,1" && $getUnitId != "1" && $getUnitId != "2") {
                $getUnitIdArr = array();
            }
            if (!empty($getUnitId)) {
                $getUnitIdArr = explode(',', $getUnitId);
                if ($getUnitId != "1,2" && $getUnitId != "2,1" && $getUnitId != "1" && $getUnitId != "2") {
                    $getUnitIdArr = array();
                }
                /*$getGroup = SubgroupInfo::find($get_subgroup_info_id);
                $getGroupId='';
                if(!empty($getGroup)){
                    $getGroupId=$getGroup->group_info_id;
                }*/
            }
            if (!empty($getGroupId)) {
                $subGroupInfoModel = new SubgroupInfo;
                $subGroupInfoArr = $subGroupInfoModel->where('group_info_id', $getGroupId)->pluck('id', 'id');
                if (!empty($subGroupInfoArr)) {
                    $getSubGroupIdArr = $subGroupInfoArr->toArray();
                    ;
                }
            }
            if (!empty($getDepartmentId)) {
                $subGroupInfoModel = new SubgroupInfo;
                $subGroupInfoArr = $subGroupInfoModel->where('department_id', $getDepartmentId)->pluck('id', 'id');
                if (!empty($subGroupInfoArr)) {
                    $tmpGetSubGroupIdArr = $subGroupInfoArr->toArray();
                    $getSubGroupIdArr = array_merge($getSubGroupIdArr, $tmpGetSubGroupIdArr);
                }
            }

            /*
            if (!empty($getDepartmentId)) {
                $unitChildModel = new UnitChild;
                $unitChildArr = $unitChildModel->where('department_id',$getDepartmentId)->pluck('unit_id','unit_id')->toArray();
                if (!empty($unitChildArr)) {
                    $getUnitIdArr = array_merge($getUnitIdArr,$unitChildArr);
                }
            }
            */
            /*if (!empty($getDivisionId)) {
                $unitChildModel = new UnitChild;
                $unitChildArr =
                    $unitChildModel
                        ->select('unit_childs.unit_id')
                        ->leftJoin('departments','departments.id','unit_childs.department_id')
                        ->where('departments.division_id',$getDivisionId)
                        ->pluck('unit_childs.unit_id','unit_childs.unit_id')
                        ->toArray();
                if (!empty($unitChildArr)) {
                    $getUnitIdArr = array_merge($getUnitIdArr,$unitChildArr);
                }
            }*/
        }

        $title = "W-Form Details";
        $title_for_layout = "W-Form Details";

        $wformModelName = new WForm;
        $dataForViewObj = $wformModelName
            ->select(
                // "w_form.*",
                "w_form.reference_number",
                "w_form.account_number",
                "w_form.acc_name",
                "w_form.mobile_number",
                "w_form.email_address",
                "w_form.customer_name",
                "w_form.time_and_ext",
                "w_form.SIF_Number",
                "w_form.customer_nid",
                "w_form.passpor_number",
                "w_form.branch_code",
                "w_form.communication",
                "w_form.segment",
                "w_form.card_status",
                "w_form.date_of_birth",
                "w_form.mask_card_no",
                "w_form.priority",
                "w_form.source",
                "w_form.tin_verified",
                "w_form.static_verified",
                "w_form.dynamic_verified",
                "w_form.notes",
                "w_form.caller_id",
                "w_form.product_type",
                "w_form.product_desc",
                "w_form.account_status",
                "w_form.acc_opening_branch",
                "w_form.w_form_type AS depricate_wform_type",
                "unit_items.name as category_name",
                "unit_items.master_id as master_id",
                "unit_items.id as main_id",
                "cb_unit_items.auto_unit_id as auto_unit_id",
                "reference.created_by",
                "reference.date",
                "reference.is_tara",
                "reference.status",
                "reference.form_status",
                "reference.access_by",
                "reference.subgroup_id",
                "reference.unit_id",
                "reference.api_status",
                "reference.iris_api_status",
                "reference.memo",
                "subgroup_info.name",
                "product_types.name as product_name",
                "unit_items.id as issue_id",
                "unit_items.name as issue_name",
                "unit_items.is_api"
            )
            ->leftJoin('reference', 'reference.reference_number', '=', 'w_form.reference_number')
            ->leftJoin('product_types', 'product_types.id', '=', 'w_form.product_type')
            ->leftJoin('subgroup_info', 'subgroup_info.id', '=', 'reference.sub_group_info_id')
            ->leftJoin('unit_items', function ($join) {
                $join->on('unit_items.master_id', '=', 'w_form.w_form_type');
                $join->on('unit_items.issues_from', '=', DB::raw("'wform'"));
            })
            ->leftJoin('unit_items AS cb_unit_items', function ($join) {
                $join->on('cb_unit_items.master_id', '=', 'w_form.w_form_type');
                $join->on('cb_unit_items.issues_from', '=', DB::raw("'wform'"));
                $join->on('cb_unit_items.unit_id', '=', 'reference.unit_id');
            })

            ->where("w_form.reference_number", $reference_number);

        $dataForViewFind = $dataForViewObj->first();

        //getting the acc opening branch code for the customer
        $company_code = $dataForViewFind->branch_code ?? '';
        $acc_br_code = '';
        if(!empty($company_code)) {
            $br_code = BranchCode::select('mnemonic')
            ->where('company_code', $company_code)
            ->first();

            if(!empty($br_code)) {
                $acc_br_code = $br_code->mnemonic;
            }
        }

        $countIssueLastMonth = WForm::where('SIF_Number', $dataForViewFind->SIF_Number)->where('w_form_type', $dataForViewFind->w_form_type)->where('time_and_ext', '>=', date("d-m-Y H:i:s", strtotime(Carbon::now() . ' - 30 days')))->count();

        if ($isAdminOrLogger == false) {
            if ($reportView == false) {
                if (!empty($getUnitIdArr)) {
                    $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id", $getUnitIdArr);
                }
                $dataForViewObj = $dataForViewObj->where(function ($q) use ($getUnitIdArr, $getGroupId, $get_subgroup_info_id, $getSubGroupIdArr) {
                    $q->where("reference.sub_group_info_id", $get_subgroup_info_id)
                        ->orWhereIn("reference.sub_group_info_id", $getSubGroupIdArr)
                    ;
                });
            }

            /*$dataForViewObj = $dataForViewObj->where(function($q) use ($getUnitIdArr,$getGroupId,$get_subgroup_info_id,$getSubGroupIdArr) {
                       $q->whereIn("reference.unit_id",$getUnitIdArr)
                         ->orWhere("reference.subgroup_id",$getGroupId)
                         ->orWhere("reference.sub_group_info_id",$get_subgroup_info_id)
                         ->orWhereIn("reference.sub_group_info_id",$getSubGroupIdArr)
                         ;
                   });*/
            /*if (!empty($subGroupInfoArr)) {
                $dataForViewObj = $dataForViewObj->whereIn("reference.sub_group_info_id",$subGroupInfoArr);
            } else {
                $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id",$getUnitIdArr);
            }*/
            /*if (!empty($getSubGroupIdArr)) {
                $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id",$getUnitIdArr);
            } else {
                $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id",$getUnitIdArr);
            }*/

        }

        $dataForViewObj = $dataForViewObj->first();

        $dataForView = array();

        if (!empty($dataForViewObj)) {
            $dataForView = $dataForViewObj->toArray();
        } else {
            abort(403, 'No Data Found');
        }

        $refNumber = $dataForView['reference_number'];

        $wformTypeData = array();
        $wformTypeModel = new WFormType;
        $wformTypeDataObj = $wformTypeModel
            ->select('id', 'reference_number', 'extra_field', 'check_list')
            ->where('w_form_type.reference_number', 'LIKE', $refNumber)
            ->first();
        if ($wformTypeDataObj) {
            $wformTypeData = $wformTypeDataObj->toArray();
        }
        $dataForView['w_form_type'] = $wformTypeData;

        $commentData = array();
        $commentsModel = new Comment;
        $commentDataObj = $commentsModel
            ->select(
                // 'comments.*'
                'comments.reference_number'
                ,
                'comments.group_id'
                ,
                'comments.subgroup_id'
                ,
                'comments.unit_id'
                ,
                'comments.user_id'
                ,
                'units.name as unit_name'
            )
            ->leftJoin('units', 'units.id', 'comments.unit_id')
            ->where('comments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($commentDataObj) {
            $commentData = $commentDataObj->toArray();
        }
        $dataForView['comment'] = $commentData;

        $commentInData = array();
        $commentsInModel = new Comment;
        $commentInDataObj = $commentsInModel
            ->select('id', 'reference_number', 'user_id', 'time', 'issendback', 'sendbacksms')
            ->where('comments.reference_number', 'LIKE', $refNumber)
            ->where('isapproved', '1')
            ->orderBy('time', 'DESC')
            ->first();
        if ($commentInDataObj) {
            $commentInData = $commentInDataObj->toArray();
        }
        $dataForView['in_date_time'] = $commentInData;

        $attachmentData = array();
        $attachmentModel = new Attachment;
        $attachmentDataObj = $attachmentModel
            ->select('id', 'file_name', 'reference_number', 'attachment_date', 'uploaded_by', 'created_at', 'updated_at')
            ->where('attachments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($attachmentDataObj) {
            $attachmentData = $attachmentDataObj->toArray();
        }
        $dataForView['w_form_attachment'] = $attachmentData;



        $unitModelName = new Unit;
        $allUnitData = $unitModelName
            ->select("id", "name")
            ->where("status", "1")
            ->pluck("name", "id")
            ->toArray();

        $dataForView['current_unit'] = "";

        $loggerCanAssign = false;

        if (!empty($dataForView['unit_id'])) {
            if (!empty($getUnitIdArr) && $isAdminOrLogger == true) {

                if (in_array($dataForView['unit_id'], $getUnitIdArr)) {
                    $loggerCanAssign = true;
                }
            }

            $dataForView['current_unit'] = (!empty($allUnitData[$dataForView['unit_id']])) ? $allUnitData[$dataForView['unit_id']] : 'N/A';
            unset($allUnitData[$dataForView['unit_id']]);
        }

        $service_request = Reference::where('reference_number', $reference_number)->first();

        $allmakers = DB::table('comments')
            ->select('comments.group_id', 'group_info.name as g_name', 'group_info.id', 'comments.unit_id', 'comments.reference_number', 'subgroup_info.name', 'comments.subgroup_id')
            ->join('group_info', 'comments.group_id', '=', 'group_info.id')
            ->join('issue_group_workflows', 'issue_group_workflows.group_info_id', '=', 'group_info.id')
            ->join('issue_workflows', 'issue_workflows.issue_workflow_id', '=', 'issue_group_workflows.issue_workflow_id')
            ->leftjoin('subgroup_info', 'comments.subgroup_id', '=', 'subgroup_info.id')
            ->where('comments.reference_number', $reference_number)
            ->where('issue_workflows.issue_id', $service_request->issue_id)
            ->where('comments.unit_id', 1)
            ->distinct('group_info.name')
            ->get();

        $row = \App\IssueWorkflow::where('issue_id', $service_request->issue_id)->first();
        $unit = \App\UserUnit::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        if ($isAdminOrLogger == false) {
            $workflow = \App\IssueGroupWorkflow::where('issue_workflow_id', $row->issue_workflow_id)->where('group_info_id', $unit->subgroup_info_id)->first();
            $lastworkflow = \App\IssueGroupWorkflow::where('issue_workflow_id', $row->issue_workflow_id)->where('is_touch_point', '<>', 1)->orderby('issue_group_workflow_id', 'desc')->first();
            //dd($lastworkflow);
            $maker_user = 0;
            $checker_user = 0;
            if ($unit->unit_id == 1) {
                $maker_user = 1;
            } elseif ($unit->unit_id == 2) {
                $checker_user = 1;
            } else {
                $maker_user = 0;
                $checker_user = 0;
            }
            $last_checker = 0;
            $last_maker = 0;
            if (!empty($lastworkflow->touch_cheker) != 0) {
                $last_checker = 1;

            } else {
                $last_maker = 1;
            }
        } else {
            $maker_user = 0;
            $checker_user = 0;
            $last_checker = 0;
            $last_maker = 0;
            $workflow = [];
        }
        $issue_fields = [];
        $check_lists = [];

        $issue_data = IssueConfig::where('issue_id', $service_request->issue_id)->get();
        $check_data = IssueCheckListConfig::where('issue_id', $service_request->issue_id)->get();
        $issue_checklist_status = true;
        if (count($issue_data) == 0 && count($check_data) == 0) {
            $issue_checklist_status = false;
        }
        $cif_workflow = DB::table('cif_workflow')
            ->where('cif_workflow.issue_id', $service_request->issue_id)
            ->where('cif_workflow.status', 1)
            ->first();
        $is_exist = !empty($cif_workflow) ? 1 : 0;
        // $query = DB::getQueryLog();
        // prd($query);
        // pr($wformTypeData);
        // prd($dataForView);

        $bpidPrefixes = ['IN137', 'GI137', 'LI137', 'CB137', 'IC137', 'MF137', 'FI137', 'PF137', 'OT137'];
        $bpidFull = BpId::where('reference_number', $reference_number)->value('bp_id') ?? '';

        $bpid_type = '';
        $bpid = '';

        if (!empty($bpidFull)) {
            foreach ($bpidPrefixes as $prefix) {
                if (str_starts_with($bpidFull, $prefix)) {
                    $bpid_type = $prefix;
                    $bpid = substr($bpidFull, strlen($prefix)); // baki ongsho = number
                    break;
                }
            }

            // fallback: jodi kono prefix match na kore (purono data hote pare)
            if (empty($bpid_type)) {
                $bpid = $bpidFull;
            }
        }

        return view('Supports.wform_details', compact(
            'title',
            'is_exist',
            'countIssueLastMonth',
            'title_for_layout',
            'dataForView',
            'allUnitData',
            'loggerCanAssign',
            'isAdminOrLogger',
            'allmakers',
            'maker_user',
            'checker_user',
            'last_checker',
            'last_maker',
            'workflow',
            'issue_fields',
            'check_lists',
            'issue_checklist_status',
            'acc_br_code',
            'bpid',
            'bpid_type'
        )
        );
    }


    public function newFormDataSession(Request $re)
    {
        Session::forget('searchDataForView');
        $searchDataForView = [];
        $searchDataForView = $re->queryString;
        Session::put('searchDataForView', $searchDataForView);

        return response()->json(['message' => 'Data stored in session successfully']);
    }

    public function newComplaint(Request $request)
    {
        $url = Session::get('searchDataForView', []);

        if(empty($url)){
            return redirect('Supports/home');
        }

        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $params);

        $request = $params;

        $dataForView = array();
        $title = "New Complaint";
        $title_for_layout = "New Complaint";

        $dataForView = [];
        $dataForView['account_type'] = isset($params['account_type']) ? $params['account_type'] : null;

        if (isset($params['account_type'])) {
            /*if ($params['account_type'] == 1) {*/
            if ($params['account_type'] == 1 || $params['account_type'] == 3) {
                /*$dataForView['account_number'] = isset($params['customer_id']) ? $params['customer_id'] : null;*/
                $dataForView['account_number'] = isset($params['cardNo']) ? $params['cardNo'] : null;
            } elseif ($params['account_type'] == 4) {
                $dataForView['account_number'] = isset($params['accountId']) ? $params['accountId'] : null;
            } else {
                $dataForView['account_number'] = isset($params['accountNumber']) ? $params['accountNumber'] : null;
            }
        }

        $dataForView['reference_number'] = isset($params['reference_number']) ? $params['reference_number'] : null;
        $dataForView['customer_name'] = isset($params['customer_name']) ? $params['customer_name'] : null;
        if ($params['account_type'] == 1 || $params['account_type'] == 3) {
            $dataForView['mobile_number'] = isset($params['mobileNumber']) ? $params['mobileNumber'] : null;
        } else {
            $dataForView['mobile_number'] = isset($params['customer_mobile']) ? $params['customer_mobile'] : null;
        }
        $dataForView['def_email_addr'] = isset($params['def_email_addr']) ? $params['def_email_addr'] : null;
        $dataForView['CIF_number'] = isset($params['CIF_number']) ? $params['CIF_number'] : null;
        $dataForView['SegmentCode'] = isset($params['SegmentCode']) ? $params['SegmentCode'] : null;
        $dataForView['cb_fin_acctno'] = isset($params['cb_fin_acctno']) ? $params['cb_fin_acctno'] : null;
        $dataForView['card_status'] = isset($params['card_status']) ? $params['card_status'] : null;
        $dataForView['acc_name'] = isset($params['categoryTitle']) ? $params['categoryTitle'] : null;
        $dataForView['acc_number'] = isset($params['acc_number']) ? $params['acc_number'] : null;
        $dataForView['mask_card_no'] = isset($params['mask_card_no']) ? $params['mask_card_no'] : null;
        $dataForView['account_status'] = isset($params['account_status']) ? $params['account_status'] : null;
        $dataForView['product_name'] = isset($params['product_name']) ? $params['product_name'] : null;
        $dataForView['branchName'] = isset($params['branchName']) ? $params['branchName'] : null;
        $dataForView['branch_code'] = isset($params['branchCode']) ? $params['branchCode'] : null;
        $dataForView['communication'] = isset($params['communication']) ? $params['communication'] : null;
        $dataForView['customer_nid'] = isset($params['customerNid']) ? $params['customerNid'] : null;
        $dataForView['passpor_number'] = isset($params['passporNumber']) ? $params['passporNumber'] : null;

        $dataForView['accountTitle'] = isset($params['accountTitle']) ? $params['accountTitle'] : null;
        $dataForView['cardType'] = isset($params['cardType']) ? $params['cardType'] : null;
        $dataForView['cardStatus'] = isset($params['cardStatus']) ? $params['cardStatus'] : null;
        $dataForView['dob'] = isset($params['dob']) ? $params['dob'] : null;
        $dataForView['email'] = isset($params['email']) ? $params['email'] : null;
        $dataForView['cardProductName'] = isset($params['cardProductName']) ? $params['cardProductName'] : null;
        $dataForView['clientCode'] = isset($params['clientCode']) ? $params['clientCode'] : null;

        if (isset($params['date_of_birth']) && !empty($params['date_of_birth'])) {
            $dataForView['date_of_birth'] = substr($params['date_of_birth'], 0, 10);
        } else {
            $dataForView['date_of_birth'] = "";
        }


        $productTypeModelName = new ProductType;
        $allProductTypeData = $productTypeModelName
            ->select('id', 'name')
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->pluck('name', 'id')
            ->toArray();

        $sourceModelName = new Source;
        $allSourceData = $sourceModelName
            ->select("id", "source_name")
            ->pluck("source_name", "source_name")
            ->toArray();
        $unitModelName = new Unit;
        $allUnitData = $unitModelName
            ->select("id", "name")
            ->where("status", "1")
            ->whereNotIn('id', [1, 2, 21])
            ->pluck("name", "id")
            ->toArray();

        $unitItemModelName = new UnitItem;
        $allUnitItemData = $unitItemModelName
            ->select("master_id", "name")
            ->where("status", "1")
            ->where("issues_from", "complaint")
            ->pluck("name", "master_id")
            ->toArray();

        $attachment_item = 0;
        $issue_fields = [];
        $check_lists = [];
        $type = "";
        return view('Supports.new_complaint', compact('title', 'title_for_layout', 'dataForView', 'allProductTypeData', 'allUnitData', 'allUnitItemData', 'allSourceData', 'attachment_item', 'issue_fields', 'check_lists', 'type'));
    }

    public function submitComplaint(ComplaintRequest $request)
    {
        //dd($request);
        $extra_field = '';
        $issue_check_field = '';
        if ($request->isMethod('post')) {

            /* This part is dynamic input*/
            $issue_config = IssueConfig::where('issue_id', $request->complaint_type)->get();
            if (count($issue_config) != 0) {
                foreach ($issue_config as $issue_con) {
                    if ($request[$issue_con->field_name] != null) {
                        $dataName[] = [
                            $issue_con->label_name => $request[$issue_con->field_name]
                        ];
                    } else {
                        $dataName[] = [
                            $issue_con->label_name => ''
                        ];
                    }

                }
                $extra_field = json_encode($dataName);
            }

            $issue_checkList = IssueCheckListConfig::where('issue_id', $request->complaint_type)->get();
            if (count($issue_checkList) != 0) {
                foreach ($issue_checkList as $issue_check) {
                    if ($request[$issue_check->field_name]) {
                        $dataCheckList[] = [
                            $issue_check->label_name => $request[$issue_check->field_name]
                        ];
                    } else {
                        $dataCheckList[] = [
                            $issue_check->label_name => ''
                        ];
                    }

                }
                $issue_check_field = json_encode($dataCheckList);
            }

            /* Get Unit ID from WForm Type */
            $unitItemModelName = new UnitItem;
            $unitItemData = $unitItemModelName->select("id", "unit_id", "is_sent_sms", "name")->where([["master_id", $request->complaint_type], ["issues_from", "complaint"]])->first();
            $workflow = IssueWorkflow::where('issue_id', $unitItemData->id)->first();

            $unitUser = DB::table('user_units')
                ->join('subgroup_info', 'subgroup_info.id', '=', 'user_units.subgroup_info_id')
                ->where('user_units.user_id', Auth::id())->first();

            $firstWorkFlow = IssueGroupWorkflow::where('group_info_id', $unitUser->group_info_id)->where('issue_workflow_id', $workflow->issue_workflow_id)->first();
            //dd($firstWorkFlow);
            if ($workflow->flow_type == FlowEnum::REGULAR) {
                if ($firstWorkFlow->touch_checker == 1) {
                    $workflowlist = IssueGroupWorkflow::where('issue_workflow_id', $workflow->issue_workflow_id)->where('is_touch_point', 1)->orderBy('issue_group_workflow_id', 'ASC')->first();
                    $subgroup_id = $firstWorkFlow->group_info_id;
                    $next_label = $firstWorkFlow->touch_checker;
                    $unit_label = 2;

                } else {
                    // $workflowlist = IssueGroupWorkflow::where('issue_workflow_id', $workflow->issue_workflow_id)->where('group_info_id', $unitUser->group_info_id)->where('is_touch_point', '<>', 1)->first();
                    // $workflowlist = IssueGroupWorkflow::where('issue_workflow_id', $workflow->issue_workflow_id)->where('is_touch_point', '<>', 1)->first();
                    $workflowlist = IssueGroupWorkflow::where('issue_workflow_id', $workflow->issue_workflow_id)->where('is_touch_point', '<>', 1)->orderBy('issue_group_workflow_id', 'ASC')->first();
                    // dd($firstWorkFlow, $workflowlist);

                    if ($workflowlist->touch_maker == 1) {
                        $subgroup_id = $workflowlist->group_info_id;
                        $next_label = $workflowlist->touch_maker;
                        $unit_label = 1;
                    } else {
                        $subgroup_id = $workflowlist->group_info_id;
                        $next_label = $workflowlist->touch_maker;
                        $unit_label = 2;
                    }

                }
            }
            if ($workflow->flow_type == FlowEnum::FORWARD) {

                //$NotTouchWorkFlow = IssueGroupWorkflow::where('issue_workflow_id',$workflow->issue_workflow_id)->where('is_touch_point',0)->first();
                //$subgroup_id = $NotTouchWorkFlow->group_info_id;
                //$unit_label=1; //'1' mean maker

                $subgroup_id = $firstWorkFlow->group_info_id;
                $next_label = $firstWorkFlow->touch_checker;
                $unit_label = 2;

            }


		$prodTypeAlpha = "";
                if ($request->product_type == 1) {
                    $prodTypeAlpha = "CC";
                } elseif ($request->product_type == 2) {
                    $prodTypeAlpha = "AC";
                } elseif ($request->product_type == 3) {
                    $prodTypeAlpha = "DC";
                } elseif ($request->product_type == 4) {
                    $prodTypeAlpha = "LN";
                } elseif ($request->product_type == 5) {
                    $prodTypeAlpha = "TR";
                }

                $reference_number =
                    "C" .
                    date("ymd") . $prodTypeAlpha .
                    //userIdPadRightWith0($request->product_type, 2, '0').
                    //userIdPadLeftWith0($issueId, 4, '0').
                    userIdPadLeftWith0($this->dayWiseSequence('cm'), 6, '0');


		/* Document Upload Process */
                $docDestPath = 'public/attachments';
                if (!empty($request->file('file_name'))) {
                    foreach ($request->file('file_name') as $key => $files) {
                        $extension = $files->getClientOriginalExtension();
                        $origin_name = pathinfo($files->getClientOriginalName(), PATHINFO_FILENAME);
                        $origin_name = str_replace(' ', '_', $origin_name);
                        $origin_name = substr($origin_name, 0, 20);
                        $fileName = $origin_name . "_attach_nX_" . round(microtime(true) * 10) . "_" . ($key + 1) . '.' . $extension;
                        $attachment = new Attachment();
                        $attachment->file_name = $fileName;
                        $attachment->reference_number = $reference_number;
                        $attachment->attachment_date = date('Y-m-d');
                        $attachment->uploaded_by = Auth::user()->id;
                        $attachment->save();
                        //$files->move($docDestPath, $fileName);

                        $fileContent = File::get($files->getRealPath());
                        Storage::disk('custom_storage')->put($fileName, $fileContent);

                        /*$image                   =       $files;
                        $img                     =       ImageResizer::make($image->path());

                        // --------- [ Resize Image ] ---------------
                        $imgInfo = $img->resize(150, 100, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($docDestPath.'/'.$fileName);*/
                    }
                }

	    // check document
            try {

                $attachments = Attachment::where('reference_number', $reference_number)->get();
                foreach ($attachments as $attachment) {
                    $filePath = $docDestPath . '/' . $attachment->file_name;
                    if (!file_exists($filePath)) {
                        // delete attachment data
                        Attachment::where('reference_number', $reference_number)->delete();
                        throw new \Exception('Unable to upload file, Contact with system admin!');
                    }
                }
            } catch (\Exception $e) {
                flash($e->getMessage(), 'danger');
                return redirect('Supports/NewWForm');
            }



            /* End of Get Unit ID from WForm Type */

            $referenceModelName = new Reference;
            if ($referenceModelName->save()) {
                /* Generate Reference No*/
                // $referenceId = $referenceModelName->id;
                // $reference_number = "C".date("Ym").userIdPadLeftWith0(($referenceId), 6, '0');

                $issueId = (!empty($workflow->issue_id)) ? $workflow->issue_id : 0;

                

                $referenceModelName->reference_number = $reference_number;
                $referenceModelName->unit_id = (!empty($unit_label)) ? $unit_label : 0;

                $referenceModelName->subgroup_id = (!empty($subgroup_id)) ? $subgroup_id : 0;

                $unitList = Auth::user()->user_unit;

                // Get Subgroup ID // TODO: Muajjam Hossain
                if (isset($workflowlist) && !empty($workflowlist)){
                    if($subgroup_id == $unitUser->group_info_id){
                        $getSubGroupId = $unitList->subgroup_info_id;
                        $referenceModelName->sub_group_info_id = $getSubGroupId;
                    }else{
                        $getSubGroupId = SubgroupInfo::where('group_info_id', $subgroup_id)->first()->id;
                        $referenceModelName->sub_group_info_id = $getSubGroupId;
                    }
                }else{
                    if (!empty($unitList)) {
                        $getSubGroupId = $unitList->subgroup_info_id;
                        $referenceModelName->sub_group_info_id = $getSubGroupId;
                    }
                }
                // For User Segment code filter Priority
                if (!empty($request->segment)){
                    $segCode = SegmentCode::where('status', 1)->get(['code']);
                    if (!empty($segCode)){
                        foreach ($segCode as $code){
                            if ($code->code == $request->segment){
                                $referenceModelName->segment_priority = 1;
                            }
                        }
                    }
                }

                $referenceModelName->issue_id = (!empty($workflow->issue_id)) ? $workflow->issue_id : 0;
                $referenceModelName->date = strtotime(date('d-m-Y h:i:s A'));
                $referenceModelName->created_by = Auth::user()->user_id;
                $referenceModelName->account_type = $request->product_type;
                // $referenceModelName->item_type = $request->item_type;
                $referenceModelName->status = 47;
                $referenceModelName->issues_from = 'complaint';
                $referenceModelName->save();

                /* Store Complaint */
                $complaintModelName = new Complaint;

                $complaintModelName->acc_opening_branch = $request->branchName;
                $complaintModelName->branch_code = $request->branch_code;
                $complaintModelName->communication = $request->communication;
                $complaintModelName->customer_nid = $request->customer_nid;
                $complaintModelName->passpor_number = $request->passpor_number;

                $complaintModelName->reference_number = $reference_number;
                $complaintModelName->account_number = $request->account_number;
                $complaintModelName->customer_name = $request->customer_name;
                $complaintModelName->mobile_number = $request->mobile_number;
                $complaintModelName->email_address = $request->def_email_addr;
                $complaintModelName->segment = $request->segment;
                $complaintModelName->product_type = $request->product_type;
                $complaintModelName->complaint_type = $request->complaint_type;
                $complaintModelName->priority = $request->priority;
                $complaintModelName->time_and_ext = $request->time_and_ext;
                $complaintModelName->complaint_details = $request->complaint_details;
                $complaintModelName->caller_id = $request->caller_id;
                $complaintModelName->source = $request->source;
                $complaintModelName->repeat_complaint = $request->repeat_complaint;
                $complaintModelName->tin_verified = $request->tin_verified;
                $complaintModelName->amount = $request->amount;
                $complaintModelName->SIF_Number = $request->SIF_Number;
                $complaintModelName->date_of_birth = $request->date_of_birth;
                $complaintModelName->individual_acct_no = $request->cb_fin_acctno;
                $complaintModelName->acc_name = $request->acc_name;
                $complaintModelName->product_desc = $request->product_desc;
                $complaintModelName->account_status = $request->account_status;
                // $complaintModelName->acc_opening_branch = $request->acc_opening_branch;
                if (!empty($request->inputted_masking_card)){
                    $complaintModelName->mask_card_no = $request->inputted_masking_card;
                }else{
                    $complaintModelName->mask_card_no = $request->mask_card_no;
                }

                if (empty($request->card_status)) {
                    $request->card_status = "SB";
                }
                $complaintModelName->card_status = $request->card_status;

                $complaintModelName->balance = Session::get('real_account_balance', '');
                $complaintModelName->save();
                /* End of Store Complaint */

                /* Store Complaint-Form type */
                $complaintFormTypeModelName = new ComplaintFormType;
                $complaintFormTypeModelName->reference_number = $reference_number;
                $complaintFormTypeModelName->extra_field = $extra_field;
                $complaintFormTypeModelName->check_list = $issue_check_field;
                $complaintFormTypeModelName->save();

                //Form Status entry
                //$this->form_status($reference_number,0,20);
                

                if (!empty($workflow->log)) {
                    $outgoingSMSMessage = $this->outgoingSMSEmail("complaint", $request->complaint_type, $reference_number, "open", $unitItemData['name']);
                    if (!empty($outgoingSMSMessage['sms'])) {
                        $this->sendSMS($request->mobile_number, $outgoingSMSMessage['sms'], $reference_number, 0);
                    }
                    if (!empty($outgoingSMSMessage['mail'])) {
                        if (!empty($request->def_email_addr)) {
                            $this->sendEMAIL($request->def_email_addr, $outgoingSMSMessage['mail'], $reference_number, 0);
                        }
                    }
                }

                $this->audit(['reference_number' => $reference_number, 'unit_id' => 1, 'group_id' => $firstWorkFlow->group_info_id, 'user_id' => Auth::user()->user_id, 'action' => Session::get('subgroupStr') . ' Logged', 'comments' => '', 'isapproved' => '1', 'subgroup_id' => $unitList->subgroup_info_id]);

                //flash('Complaint have been saved successfully. Ref: '.$reference_number, 'success');
                $additionalParams = (!empty($request->additionalParams)) ? $request->additionalParams : "";
                flash('Complaint have been saved successfully. Ticket No: ' . $reference_number, 'success');
                // echo "<script>window.close();</script>";
                // return redirect('Supports/home'.$additionalParams)->with('success','Complaint have been saved successfully. Ticket No: '.$reference_number);
                //return redirect('Supports/home'.$additionalParams);
                Session::forget('real_account_balance');
                return redirect('Supports/home');
            } else {
                $additionalParams = (!empty($request->additionalParams)) ? $request->additionalParams : "";
                flash('Failed to save data', 'danger');
                return redirect('Supports/NewWForm' . $additionalParams);
            }
        }
    }

    public function complainFormUpdate(Request $request, $reference_number)
    {
        //dd($request);
        $extra_field = '';
        $issue_check_field = '';
        $w_form_type = ComplaintFormType::where('reference_number', $reference_number)->first();
        ComplaintFormTypeHistory::create([
            'reference_number' => $reference_number,
            'extra_field' => $w_form_type->extra_field,
            'check_list' => $w_form_type->check_list,
            'user_id' => Auth::id(),
        ]);

        $issue_config = IssueConfig::where('issue_id', $request->issue_id)->get();
        if (count($issue_config) != 0) {
            foreach ($issue_config as $issue_con) {
                if ($request[$issue_con->field_name] != null) {
                    $dataName[] = [
                        $issue_con->label_name => $request[$issue_con->field_name]
                    ];
                } else {
                    $dataName[] = [
                        $issue_con->label_name => ''
                    ];
                }

            }
            //pr($dataName);
            $extra_field = json_encode($dataName);
        }
        $issue_checkList = IssueCheckListConfig::where('issue_id', $request->issue_id)->get();
        if (count($issue_checkList) != 0) {
            foreach ($issue_checkList as $issue_check) {
                if ($request[$issue_check->field_name]) {
                    $dataCheckList[] = [
                        $issue_check->label_name => $request[$issue_check->field_name]
                    ];
                } else {
                    $dataCheckList[] = [
                        $issue_check->label_name => ''
                    ];
                }

            }
            //prd($dataCheckList);
            $issue_check_field = json_encode($dataCheckList);
        }

        $wformTypeModelName = ComplaintFormType::where('reference_number', $reference_number)->first();

        if (!empty($extra_field)) {
            $wformTypeModelName->extra_field = $extra_field;
        }
        if (!empty($issue_check_field)) {
            $wformTypeModelName->check_list = $issue_check_field;
        }

        $wformTypeModelName->save();

        return \redirect()->back();
    }

    public function newNonCustomer(Request $request)
    {
        $dataForView = array();
        $title = "New Non Customer";
        $title_for_layout = "New Non Customer";

        $dataForView['account_type'] = $request->account_type;
        $dataForView['account_number'] = $request->account_number;
        $dataForView['reference_number'] = $request->reference_number;
        $dataForView['customer_name'] = $request->customer_name;
        $dataForView['mobile_number'] = $request->customer_mobile;
        $dataForView['def_email_addr'] = $request->def_email_addr;

        $productTypeModelName = new ProductType;
        $allProductTypeData = $productTypeModelName
            ->select('id', 'name')
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->pluck('name', 'id')
            ->toArray();

        $sourceModelName = new Source;
        $allSourceData = $sourceModelName
            ->select("id", "source_name")
            ->pluck("source_name", "source_name")
            ->toArray();
        $unitModelName = new Unit;
        $allUnitData = $unitModelName
            ->select("id", "name")
            ->where("status", "1")
            ->whereNotIn('id', [1, 2, 21])
            ->pluck("name", "id")
            ->toArray();

        $unitItemModelName = new UnitItem;
        $allUnitItemData = $unitItemModelName
            ->select("master_id", "name")
            ->where("status", "1")
            ->where("issues_from", "complaint")
            ->pluck("name", "master_id")
            ->toArray();



        return view('Supports.new_non_customer', compact('title', 'title_for_layout', 'dataForView', 'allProductTypeData', 'allUnitData', 'allUnitItemData', 'allSourceData'));
    }

    public function submitNonCustomer(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'customer_name' => 'required|max:100|string',
            'mobile_number' => 'required|max:100|string',
            //'type' =>'required',
            'details' => 'required|max:1000|string',
            'forward_to' => 'required',
            'time_and_ext' => 'required',
            'request_type' => 'required',
            'service_length' => 'max:3',
            //'caller_id'=> 'required|regex:/^[0-9]+$/|min:1|max:11'
            //'forward_to'=>'required',
        ]);
        $unit_label = 1;
        $referenceModelName = new Reference;
        if ($referenceModelName->save()) {
            /* Generate Reference No*/
            /*$referenceId = $referenceModelName->id;
            if($request['type']==1){
                $reference_number = "N" . date("Ym") . userIdPadLeftWith0(($referenceId), 6, '0');
            }else{
                $reference_number = "N" . date("Ym") . userIdPadLeftWith0(($referenceId), 6, '0');
            }*/

            $reference_number = "N" . date("ymd") . userIdPadLeftWith0($this->dayWiseSequence('nc'), 8, '0');

            $subgroup_info_id = "";
            $subGroupInfoModel = new SubgroupInfo;
            $subgroup_info_data = $subGroupInfoModel
                ->select('id')
                ->where('group_info_id', '=', $request['forward_to'])
                ->pluck('id')
                ->toArray();

            foreach ($subgroup_info_data as $value) {

                $subgroup_info_id = $value;
            }

            $referenceModelName->reference_number = $reference_number;
            $referenceModelName->unit_id = (!empty($unit_label)) ? $unit_label : 0;
            $referenceModelName->subgroup_id = $request['forward_to'];
            $referenceModelName->issue_id = 0;
            $referenceModelName->date = strtotime(date('d-m-Y h:i:s A'));
            $referenceModelName->created_by = Auth::user()->user_id;
            $referenceModelName->account_type = $request->product_type;
            $referenceModelName->sub_group_info_id = $subgroup_info_id;
            $referenceModelName->status = 47;
            $referenceModelName->issues_from = 'noncustomer';
            $referenceModelName->save();
        }
        $row = NonCustomer::create([
            'reference_number' => $reference_number,
            'customer_name' => $request['customer_name'],
            'customer_address' => $request['customer_address'],
            'customer_email' => $request['customer_email'],
            'customer_profession' => $request['customer_profession'],
            'customer_dob' => $request['customer_dob'],
            'mobile_number' => $request['mobile_number'],
            //'type'=>$request['type'],
            'details' => $request['details'],
            'forward_to' => $request['forward_to'],
            //'caller_id'=>$request['caller_id'],
            'time_and_ext' => $request['time_and_ext'],

            'employment_address' => $request['employment_address'],
            'salary_income' => $request['salary_income'],
            'service_length' => $request['service_length'],
            'request_type' => $request['request_type'],
            'sales_lead' => $request['sales_lead'],
            'other_bank_loan' => $request['other_bank_loan'],
            'other_bank_credit_card' => $request['other_bank_credit_card'],

            'created_by' => Auth::id(),
        ]);

        $settingsInfo = \App\Setting::first();
        if ($settingsInfo && $settingsInfo->noncustomersms == 1) {
            //$unitItemData['name'] = "";
            $outgoingSMSMessage = $this->outgoingSMSEmail("noncustomer", $request->complaint_type, $reference_number, "open", "");
            //print_r($outgoingSMSMessage);die;
            if (!empty($outgoingSMSMessage['sms'])) {
                $this->sendSMS($request->mobile_number, $outgoingSMSMessage['sms'], $reference_number, 0);
            }
            if (!empty($outgoingSMSMessage['mail'])) {
                if (!empty($request['customer_email'])) {
                    $this->sendEMAIL($request['customer_email'], $outgoingSMSMessage['mail'], $reference_number, 0);
                }
            }
        }

        $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelName->unit_id, 'group_id' => $request['forward_to'], 'user_id' => Auth::user()->user_id, 'action' => Session::get('subgroupStr') . ' Logged', 'comments' => '', 'isapproved' => '1', 'subgroup_id' => $subgroup_info_id]);
        flash('Non Customer Request have been saved successfully. Ticket No: ' . $reference_number, 'success');
        // echo "<script>window.close();</script>";
        $additionalParams = (!empty($request->additionalParams)) ? $request->additionalParams : "";
        //return redirect('Supports/home'.$additionalParams);
        return redirect('Supports/home');
    }

    public function nonCustomerDetails(Request $request, $reference_number = "")
    {

        try {
            $reference_number = decrypt($reference_number);

        } catch (DecryptException $e) {
            abort(403, 'Un-Authorize Access!!!');
        }

        $isAdminOrLogger = false;
        $getUnitId = "";
        $getUnitIdArr = array();
        $getSubGroupIdArr = array();
        $subGroupInfoArr = array();
        $getDepartmentId = "";
        $getDivisionId = "";
        $getGroupId = "";
        $get_subgroup_info_id = "";

        $reportView = false;

        if (!empty($request->viewFrom)) {
            $reportView = true;
        }

        if (Auth::user()->hasRole(['superadmin', 'admin'])) {
            $isAdminOrLogger = true;
        } else {
            if (Auth::user()->hasRole(['logger'])) {
                $isAdminOrLogger = true;
            }
            $unitList = Auth::user()->user_unit;
            if (!empty($unitList)) {
                $getUnitId = $unitList->unit_id;
                $getDepartmentId = $unitList->department_id;
                $getDivisionId = $unitList->division_id;
                $get_subgroup_info_id = $unitList->subgroup_info_id;
                $getGroupId = $unitList->group_info_id;
            }
            if ($getUnitId != "1,2" && $getUnitId != "2,1" && $getUnitId != "1" && $getUnitId != "2") {
                $getUnitIdArr = array();
            }
            if (!empty($getUnitId)) {
                $getUnitIdArr = explode(',', $getUnitId);
                if ($getUnitId != "1,2" && $getUnitId != "2,1" && $getUnitId != "1" && $getUnitId != "2") {
                    $getUnitIdArr = array();
                }
                /*$getGroup = SubgroupInfo::find($get_subgroup_info_id);
                $getGroupId='';
                if(!empty($getGroup)){
                    $getGroupId=$getGroup->group_info_id;
                }*/
            }
            if (!empty($getGroupId)) {
                $subGroupInfoModel = new SubgroupInfo;
                $subGroupInfoArr = $subGroupInfoModel->where('group_info_id', $getGroupId)->pluck('id', 'id');
                if (!empty($subGroupInfoArr)) {
                    $getSubGroupIdArr = $subGroupInfoArr->toArray();
                    ;
                }
            }
            if (!empty($getDepartmentId)) {
                $subGroupInfoModel = new SubgroupInfo;
                $subGroupInfoArr = $subGroupInfoModel->where('department_id', $getDepartmentId)->pluck('id', 'id');
                if (!empty($subGroupInfoArr)) {
                    $tmpGetSubGroupIdArr = $subGroupInfoArr->toArray();
                    $getSubGroupIdArr = array_merge($getSubGroupIdArr, $tmpGetSubGroupIdArr);
                }
            }

            /*
            if (!empty($getDepartmentId)) {
                $unitChildModel = new UnitChild;
                $unitChildArr = $unitChildModel->where('department_id',$getDepartmentId)->pluck('unit_id','unit_id')->toArray();
                if (!empty($unitChildArr)) {
                    $getUnitIdArr = array_merge($getUnitIdArr,$unitChildArr);
                }
            }
            */
            /*if (!empty($getDivisionId)) {
                $unitChildModel = new UnitChild;
                $unitChildArr =
                    $unitChildModel
                        ->select('unit_childs.unit_id')
                        ->leftJoin('departments','departments.id','unit_childs.department_id')
                        ->where('departments.division_id',$getDivisionId)
                        ->pluck('unit_childs.unit_id','unit_childs.unit_id')
                        ->toArray();
                if (!empty($unitChildArr)) {
                    $getUnitIdArr = array_merge($getUnitIdArr,$unitChildArr);
                }
            }*/
        }

        $title = "Non Customer Details";
        $title_for_layout = "Non Customer Details";

        $nonCustomerModelName = new NonCustomer();
        $dataForViewObj = $nonCustomerModelName
            ->select(
                // "non_customers.*",
                "non_customers.reference_number",
                "non_customers.customer_name",
                "non_customers.customer_address",
                "non_customers.mobile_number",
                "non_customers.customer_email",
                "non_customers.customer_dob",
                "non_customers.time_and_ext",
                "non_customers.customer_profession",
                "non_customers.employment_address",
                "non_customers.salary_income",
                "non_customers.service_length",
                "non_customers.other_bank_loan",
                "non_customers.other_bank_credit_card",
                "non_customers.details",
                "reference.created_by",
                "reference.date",
                "reference.status",
                "reference.form_status",
                "reference.access_by",
                "reference.unit_id",
                "profession.name as customer_profession",
                "request_type.name as request_name",
                "sales_lead.name as sales_lead_name"
            )
            ->leftJoin('reference', 'reference.reference_number', '=', 'non_customers.reference_number')
            ->leftJoin('profession', 'profession.id', '=', 'non_customers.customer_profession')
            ->leftJoin('request_type', 'request_type.id', '=', 'non_customers.request_type')
            ->leftJoin('sales_lead', 'sales_lead.id', '=', 'non_customers.sales_lead')
            ->where("non_customers.reference_number", $reference_number);

        if ($isAdminOrLogger == false) {
            if ($reportView == false) {
                if (!empty($getUnitIdArr)) {
                    $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id", $getUnitIdArr);
                }
                $dataForViewObj = $dataForViewObj->where(function ($q) use ($getUnitIdArr, $getGroupId, $get_subgroup_info_id, $getSubGroupIdArr) {
                    $q->where("reference.sub_group_info_id", $get_subgroup_info_id)
                        ->orWhereIn("reference.sub_group_info_id", $getSubGroupIdArr)
                    ;
                });
            }
            /*$dataForViewObj = $dataForViewObj->where(function($q) use ($getUnitIdArr,$getGroupId,$get_subgroup_info_id,$getSubGroupIdArr) {
                       $q->whereIn("reference.unit_id",$getUnitIdArr)
                         ->orWhere("reference.subgroup_id",$getGroupId)
                         ->orWhere("reference.sub_group_info_id",$get_subgroup_info_id)
                         ->orWhereIn("reference.sub_group_info_id",$getSubGroupIdArr)
                         ;
                   });*/
            /*if (!empty($subGroupInfoArr)) {
                $dataForViewObj = $dataForViewObj->whereIn("reference.sub_group_info_id",$subGroupInfoArr);
            } else {
                $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id",$getUnitIdArr);
            }*/
            /*if (!empty($getSubGroupIdArr)) {
                $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id",$getUnitIdArr);
            } else {
                $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id",$getUnitIdArr);
            }*/
        }

        $dataForViewObj = $dataForViewObj->first();

        $dataForView = array();
        if (!empty($dataForViewObj)) {
            $dataForView = $dataForViewObj->toArray();
        } else {
            abort(403, 'No Data Found');
        }

        $refNumber = $dataForView['reference_number'];

        $commentData = array();
        $commentsModel = new Comment;
        $commentDataObj = $commentsModel
            ->select(
                // 'comments.*'
                'comments.reference_number'
                ,
                'comments.group_id'
                ,
                'comments.subgroup_id'
                ,
                'comments.unit_id'
                ,
                'comments.user_id'
                ,
                'units.name as unit_name'
            )
            ->leftJoin('units', 'units.id', 'comments.unit_id')
            ->where('comments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($commentDataObj) {
            $commentData = $commentDataObj->toArray();
        }
        $dataForView['comment'] = $commentData;

        $attachmentData = array();
        $attachmentModel = new Attachment;
        $attachmentDataObj = $attachmentModel
            ->select('id', 'file_name', 'reference_number', 'attachment_date', 'uploaded_by', 'created_at', 'updated_at')
            ->where('attachments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($attachmentDataObj) {
            $attachmentData = $attachmentDataObj->toArray();
        }
        $dataForView['non_customers_attachment'] = $attachmentData;


        $unitModelName = new Unit;
        $allUnitData = $unitModelName
            ->select("id", "name")
            ->where("status", "1")
            ->pluck("name", "id")
            ->toArray();
        // prd($dataForView);

        $allmakers = DB::table('comments')
            ->select('comments.group_id', 'group_info.name as g_name', 'group_info.id', 'comments.unit_id', 'comments.reference_number')
            ->join('group_info', 'comments.group_id', '=', 'group_info.id')
            ->where('comments.reference_number', $reference_number)
            ->distinct('group_info.name')
            ->get();

        // $allformStatuses = DB::table('form_status')
        //     ->join('comments','comments.reference_number','=','form_status.reference_number')
        //     ->where('form_status.reference_number',$reference_number)
        //     ->get();

        $loggerCanAssign = false;
        $dataForView['current_unit'] = "";
        /*$dataForView['current_unit'] = "";
        if (!empty($dataForView['unit_id'])) {
            if (!empty($getUnitIdArr) && $isAdminOrLogger == true) {
                if (in_array($dataForView['unit_id'], $getUnitIdArr)) {
                    $loggerCanAssign = true;
                }
            }
            $dataForView['current_unit'] = (!empty($allUnitData[$dataForView['unit_id']])) ? $allUnitData[$dataForView['unit_id']] : 'N/A';
            unset($allUnitData[$dataForView['unit_id']]);
        }*/
        $nonCustomerData = Reference::where('reference_number', $reference_number)->first();

        $row = \App\IssueWorkflow::where('issue_id', $nonCustomerData->issue_id)->first();
        $unit = \App\UserUnit::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();

        if ($isAdminOrLogger == false) {
            //$workflow = \App\IssueGroupWorkflow::where('issue_workflow_id',$row->issue_workflow_id)->where('group_info_id',$unit->subgroup_info_id)->first();
            //$lastworkflow = \App\IssueGroupWorkflow::where('issue_workflow_id',$row->issue_workflow_id)->where('is_touch_point','<>',1)->orderby('issue_group_workflow_id','desc')->first();
            $maker_user = 0;
            $checker_user = 0;
            $unitIdArray = explode(",", $unit->unit_id);
            if (in_array(1, $unitIdArray)) {
                $maker_user = 1;
            } elseif (in_array(2, $unitIdArray)) {
                $checker_user = 1;
            } else {
                $maker_user = 0;
                $checker_user = 0;
            }
            $last_checker = 0;
            $last_maker = 0;
            /*if($lastworkflow->touch_cheker!=0){
                $last_checker=1;

            }else{
                $last_maker=1;
            }*/

        } else {
            $maker_user = 0;
            $checker_user = 0;
            $last_checker = 0;
            $last_maker = 0;
        }
        return view('Supports.non_customer_details', compact('title', 'title_for_layout', 'dataForView', 'allmakers', 'allUnitData', 'loggerCanAssign', 'isAdminOrLogger', 'maker_user', 'checker_user', 'last_maker', 'last_checker'));
    }

    public function complaintDetails(Request $request, $reference_number = "")
    {
        try {
            $reference_number = decrypt($reference_number);

        } catch (DecryptException $e) {
            abort(403, 'Un-Authorize Access!!!');
        }

        $getUnitId = "";
        $getUnitIdArr = array();
        $getSubGroupIdArr = array();
        $subGroupInfoArr = array();
        $getDepartmentId = "";
        $getDivisionId = "";
        $getGroupId = "";
        $get_subgroup_info_id = "";

        $isAdminOrLogger = false;
        $reportView = false;

        if (!empty($request->viewFrom)) {
            $reportView = true;
        }

        if (Auth::user()->hasRole(['superadmin', 'admin'])) {
            $isAdminOrLogger = true;
        } else {
            if (Auth::user()->hasRole(['logger'])) {
                $isAdminOrLogger = true;
            }
            $unitList = Auth::user()->user_unit;
            if (!empty($unitList)) {
                $getUnitId = $unitList->unit_id;
                $getDepartmentId = $unitList->department_id;
                $getDivisionId = $unitList->division_id;
                $get_subgroup_info_id = $unitList->subgroup_info_id;
                $getGroupId = $unitList->group_info_id;

            }
            if ($getUnitId != "1,2" && $getUnitId != "2,1" && $getUnitId != "1" && $getUnitId != "2") {
                $getUnitIdArr = array();
            }
            if (!empty($getUnitId)) {
                $getUnitIdArr = explode(',', $getUnitId);
                if ($getUnitId != "1,2" && $getUnitId != "2,1" && $getUnitId != "1" && $getUnitId != "2") {
                    $getUnitIdArr = array();
                }
                /*$getGroup = SubgroupInfo::find($get_subgroup_info_id);
                $getGroupId='';
                if(!empty($getGroup)){
                    $getGroupId=$getGroup->group_info_id;
                }*/
            }
            if (!empty($getGroupId)) {
                $subGroupInfoModel = new SubgroupInfo;
                $subGroupInfoArr = $subGroupInfoModel->where('group_info_id', $getGroupId)->pluck('id', 'id');
                if (!empty($subGroupInfoArr)) {
                    $getSubGroupIdArr = $subGroupInfoArr->toArray();
                    ;
                }
            }
            if (!empty($getDepartmentId)) {
                $subGroupInfoModel = new SubgroupInfo;
                $subGroupInfoArr = $subGroupInfoModel->where('department_id', $getDepartmentId)->pluck('id', 'id');
                if (!empty($subGroupInfoArr)) {
                    $tmpGetSubGroupIdArr = $subGroupInfoArr->toArray();
                    $getSubGroupIdArr = array_merge($getSubGroupIdArr, $tmpGetSubGroupIdArr);
                }
            }

            /*
            if (!empty($getDepartmentId)) {
                $unitChildModel = new UnitChild;
                $unitChildArr = $unitChildModel->where('department_id',$getDepartmentId)->pluck('unit_id','unit_id')->toArray();
                if (!empty($unitChildArr)) {
                    $getUnitIdArr = array_merge($getUnitIdArr,$unitChildArr);
                }
            }
            */
            /*if (!empty($getDivisionId)) {
                $unitChildModel = new UnitChild;
                $unitChildArr =
                    $unitChildModel
                        ->select('unit_childs.unit_id')
                        ->leftJoin('departments','departments.id','unit_childs.department_id')
                        ->where('departments.division_id',$getDivisionId)
                        ->pluck('unit_childs.unit_id','unit_childs.unit_id')
                        ->toArray();
                if (!empty($unitChildArr)) {
                    $getUnitIdArr = array_merge($getUnitIdArr,$unitChildArr);
                }
            }*/

        }

        $title = "Complaint Details";
        $title_for_layout = "Complaint Details";

        $complaintModelName = new Complaint;
        $dataForViewObj = $complaintModelName
            ->select(
                // "complaint.*",
                "complaint.reference_number",
                "complaint.account_number",
                "complaint.acc_name",
                "complaint.mobile_number",
                "complaint.email_address",
                "complaint.customer_name",
                "complaint.time_and_ext",
                "complaint.SIF_Number",
                "complaint.customer_nid",
                "complaint.passpor_number",
                "complaint.branch_code",
                "complaint.communication",
                "complaint.segment",
                "complaint.card_status",
                "complaint.product_desc",
                "complaint.acc_opening_branch",
                "complaint.account_status",
                "complaint.date_of_birth",
                "complaint.mask_card_no",
                "complaint.priority",
                "complaint.source",
                "complaint.tin_verified",
                "complaint.complaint_details",
                "complaint.caller_id",
                "complaint.product_type",
                "complaint.repeat_complaint",
                "reference.created_by",
                "reference.date",
                "reference.status",
                "reference.form_status",
                "reference.access_by",
                "reference.subgroup_id",
                "reference.unit_id",
                "reference.api_status",
                "reference.memo",
                "product_types.name as product_name",
                "cb_unit_items.auto_unit_id as auto_unit_id",
                "unit_items.name as issue_name",
                "unit_items.id as main_id",
                "unit_items.id as issue_id",
                "subgroup_info.name",
                "complaint_form_type.extra_field",
                "complaint_form_type.check_list"
            )
            ->leftJoin('reference', 'reference.reference_number', '=', 'complaint.reference_number')
            ->leftJoin('complaint_form_type', 'complaint_form_type.reference_number', '=', 'complaint.reference_number')
            ->leftJoin('subgroup_info', 'subgroup_info.id', '=', 'reference.sub_group_info_id')
            ->leftJoin('product_types', 'product_types.id', '=', 'complaint.product_type')
            ->leftJoin('unit_items', function ($join) {
                $join->on('unit_items.master_id', '=', 'complaint.complaint_type');
                $join->on('unit_items.issues_from', '=', DB::raw("'complaint'"));
            })
            ->leftJoin('unit_items AS cb_unit_items', function ($join) {
                $join->on('cb_unit_items.master_id', '=', 'complaint.complaint_type');
                $join->on('cb_unit_items.issues_from', '=', DB::raw("'complaint'"));
                $join->on('cb_unit_items.unit_id', '=', 'reference.unit_id');
            })
            // ->leftJoin('unit_items', 'unit_items.master_id', '=', 'complaint.complaint_type')
            ->where("complaint.reference_number", $reference_number);

        if ($isAdminOrLogger == false) {
            if ($reportView == false) {
                if (!empty($getUnitIdArr)) {
                    $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id", $getUnitIdArr);
                }
                $dataForViewObj = $dataForViewObj->where(function ($q) use ($getUnitIdArr, $getGroupId, $get_subgroup_info_id, $getSubGroupIdArr) {
                    $q->where("reference.sub_group_info_id", $get_subgroup_info_id)
                        ->orWhereIn("reference.sub_group_info_id", $getSubGroupIdArr)
                    ;
                });
            }
            /*$dataForViewObj = $dataForViewObj->where(function($q) use ($getUnitIdArr,$getGroupId,$get_subgroup_info_id,$getSubGroupIdArr) {
                       $q->whereIn("reference.unit_id",$getUnitIdArr)
                         ->orWhere("reference.subgroup_id",$getGroupId)
                         ->orWhere("reference.sub_group_info_id",$get_subgroup_info_id)
                         ->orWhereIn("reference.sub_group_info_id",$getSubGroupIdArr)
                         ;
                   });*/
            /*if (!empty($subGroupInfoArr)) {
                $dataForViewObj = $dataForViewObj->whereIn("reference.sub_group_info_id",$subGroupInfoArr);
            } else {
                $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id",$getUnitIdArr);
            }*/
            /*if (!empty($getSubGroupIdArr)) {
                $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id",$getUnitIdArr);
            } else {
                $dataForViewObj = $dataForViewObj->whereIn("reference.unit_id",$getUnitIdArr);
            }*/

        }

        $dataForViewObj = $dataForViewObj->first();

        //getting the acc opening branch code for the customer
        $company_code = $dataForViewObj->branch_code ?? '';
        $acc_br_code = '';
        if(!empty($company_code)) {
            $br_code = BranchCode::select('mnemonic')
            ->where('company_code', $company_code)
            ->first();

            if(!empty($br_code)) {
                $acc_br_code = $br_code->mnemonic;
            }
        }

        $dataForView = array();
        if (!empty($dataForViewObj)) {
            $dataForView = $dataForViewObj->toArray();
        } else {
            abort(403, 'No Data Found');
        }

        $refNumber = $dataForView['reference_number'];

        $commentData = array();
        $commentsModel = new Comment;
        $commentDataObj = $commentsModel
            ->select(
                // 'comments.*'
                'comments.reference_number'
                ,
                'comments.group_id'
                ,
                'comments.subgroup_id'
                ,
                'comments.unit_id'
                ,
                'comments.user_id'
                ,
                'units.name as unit_name'
            )
            ->leftJoin('units', 'units.id', 'comments.unit_id')
            ->where('comments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($commentDataObj) {
            $commentData = $commentDataObj->toArray();
        }
        $dataForView['comment'] = $commentData;

        $commentInData = array();
        $commentsInModel = new Comment;
        $commentInDataObj = $commentsInModel
            ->select('id', 'reference_number', 'user_id', 'time', 'issendback', 'sendbacksms')
            ->where('comments.reference_number', 'LIKE', $refNumber)
            ->where('isapproved', '1')
            ->orderBy('time', 'DESC')
            ->first();
        if ($commentInDataObj) {
            $commentInData = $commentInDataObj->toArray();
        }
        $dataForView['in_date_time'] = $commentInData;

        $attachmentData = array();
        $attachmentModel = new Attachment;
        $attachmentDataObj = $attachmentModel
            ->select('id', 'file_name', 'reference_number', 'attachment_date', 'uploaded_by', 'created_at', 'updated_at')
            ->where('attachments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($attachmentDataObj) {
            $attachmentData = $attachmentDataObj->toArray();
        }
        $dataForView['complaint_attachment'] = $attachmentData;

        $unitModelName = new Unit;
        $allUnitData = $unitModelName
            ->select("id", "name")
            ->where("status", "1")
            ->pluck("name", "id")
            ->toArray();

        $loggerCanAssign = false;

        if (!empty($dataForView['unit_id'])) {
            if (!empty($getUnitIdArr) && $isAdminOrLogger == true) {
                if (in_array($dataForView['unit_id'], $getUnitIdArr)) {
                    $loggerCanAssign = true;
                }
            }
            $dataForView['current_unit'] = (!empty($allUnitData[$dataForView['unit_id']])) ? $allUnitData[$dataForView['unit_id']] : 'N/A';
            unset($allUnitData[$dataForView['unit_id']]);
        }

        //$dataForView['current_unit'] = "";
        /*$dataForView['current_unit'] = "";
        if (!empty($dataForView['unit_id'])) {
            if (!empty($getUnitIdArr) && $isAdminOrLogger == true) {
                if (in_array($dataForView['unit_id'], $getUnitIdArr)) {
                    $loggerCanAssign = true;
                }
            }
            $dataForView['current_unit'] = (!empty($allUnitData[$dataForView['unit_id']])) ? $allUnitData[$dataForView['unit_id']] : 'N/A';
            unset($allUnitData[$dataForView['unit_id']]);
        }*/

        $complaint = Reference::where('reference_number', $reference_number)->first();

        $allmakers = DB::table('comments')
            ->select('comments.group_id', 'group_info.name as g_name', 'group_info.id', 'comments.unit_id', 'comments.reference_number', 'subgroup_info.name', 'comments.subgroup_id')
            ->join('group_info', 'comments.group_id', '=', 'group_info.id')
            ->join('issue_group_workflows', 'issue_group_workflows.group_info_id', '=', 'group_info.id')
            ->join('issue_workflows', 'issue_workflows.issue_workflow_id', '=', 'issue_group_workflows.issue_workflow_id')
            ->leftjoin('subgroup_info', 'comments.subgroup_id', '=', 'subgroup_info.id')
            ->where('comments.reference_number', $reference_number)
            ->where('issue_workflows.issue_id', $complaint->issue_id)
            ->where('comments.unit_id', 1)
            ->distinct('group_info.name')
            ->get();

        $row = \App\IssueWorkflow::where('issue_id', $complaint->issue_id)->first();
        $unit = \App\UserUnit::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        if ($isAdminOrLogger == false) {
            $workflow = \App\IssueGroupWorkflow::where('issue_workflow_id', $row->issue_workflow_id)->where('group_info_id', $unit->subgroup_info_id)->first();
            $lastworkflow = \App\IssueGroupWorkflow::where('issue_workflow_id', $row->issue_workflow_id)->where('is_touch_point', '<>', 1)->orderby('issue_group_workflow_id', 'desc')->first();
            //$lastworkflow = \App\IssueGroupWorkflow::where('issue_workflow_id', $row->issue_workflow_id)->orderby('issue_group_workflow_id', 'desc')->first();
            //prd($lastworkflow);
            $maker_user = 0;
            $checker_user = 0;
            $unitIdArray = explode(",", $unit->unit_id);
            if (in_array(1, $unitIdArray)) {
                $maker_user = 1;
            } elseif (in_array(2, $unitIdArray)) {
                $checker_user = 1;
            } else {
                $maker_user = 0;
                $checker_user = 0;
            }
            $last_checker = 0;
            $last_maker = 0;
            if (!empty($lastworkflow->touch_cheker) != 0) {
                $last_checker = 1;

            } else {
                $last_maker = 1;
            }
        } else {
            $maker_user = 0;
            $checker_user = 0;
            $last_checker = 0;
            $last_maker = 0;
            $workflow = [];
        }

        $issue_data = IssueConfig::where('issue_id', $complaint->issue_id)->get();
        $check_data = IssueCheckListConfig::where('issue_id', $complaint->issue_id)->get();
        $issue_checklist_status = true;
        if (count($issue_data) == 0 && count($check_data) == 0) {
            $issue_checklist_status = false;
        }
        $cif_workflow = DB::table('cif_workflow')
            ->where('cif_workflow.issue_id', $complaint->issue_id)
            ->where('cif_workflow.status', 1)
            ->first();
        $is_exist = !empty($cif_workflow) ? 1 : 0;
        //prd($isAdminOrLogger);
        return view('Supports.complaint_details', compact(
            'title',
            'is_exist',
            'title_for_layout',
            'dataForView',
            'allmakers',
            'allUnitData',
            'loggerCanAssign',
            'isAdminOrLogger',
            'workflow',
            'maker_user',
            'checker_user',
            'last_maker',
            'last_checker',
            'issue_checklist_status',
            'acc_br_code',
        ));
    }

    public function status($id = null, $status = "")
    {
        $unitModelName = new Unit;
        if ($status != 1 && $status != 0) {
            flash($status . ' is not Allowed!!!', 'danger');
            return Redirect::back();
        }
        $data['status'] = $status;
        $update = $unitModelName->where([['id', $id]])->first();

        $update->update($data);

        if ($status == 1) {
            flash('Sub-Group has been Active', 'success');
        } elseif ($status == 0) {
            flash('Sub-Group has been Inactive', 'danger');
        }
        return Redirect::back();
    }

    // UAT Server
    public function wFormReportDetails($reference_number = "")
    {

        try {
            $reference_number = decrypt($reference_number);
        } catch (DecryptException $e) {
            abort(403, 'Un-Authorize Access!!!');
        }

        $title = "Service Request Report Details";
        $title_for_layout = "Service Request Report Details";

        $wformModelName = new WForm;
        $dataForViewObj = $wformModelName
            ->select(
                "w_form.*",
                "w_form.w_form_type AS depricate_wform_type",
                "unit_items.name as category_name",
                "unit_items.master_id as master_id",
                "unit_items.id as main_id",
                "cb_unit_items.auto_unit_id as auto_unit_id",
                "reference.created_by",
                "reference.date",
                "reference.is_tara",
                "reference.status",
                "reference.form_status",
                "reference.access_by",
                "reference.access_date",
                "reference.sub_group_info_id",
                "reference.unit_id",
                "subgroup_info.name",
                "product_types.name as product_name",
                "unit_items.name as issue_name"
            )
            ->leftJoin('reference', 'reference.reference_number', '=', 'w_form.reference_number')
            ->leftJoin('subgroup_info', 'subgroup_info.id', '=', 'reference.sub_group_info_id')
            ->leftJoin('product_types', 'product_types.id', '=', 'w_form.product_type')
            ->leftJoin('unit_items', function ($join) {
                $join->on('unit_items.master_id', '=', 'w_form.w_form_type');
                $join->on('unit_items.issues_from', '=', DB::raw("'wform'"));
            })
            ->leftJoin('unit_items AS cb_unit_items', function ($join) {
                $join->on('cb_unit_items.master_id', '=', 'w_form.w_form_type');
                $join->on('cb_unit_items.issues_from', '=', DB::raw("'wform'"));
                $join->on('cb_unit_items.unit_id', '=', 'reference.unit_id');
            })
            ->where("w_form.reference_number", $reference_number)
            ->first();

        $dataForView = array();

        if (!empty($dataForViewObj)) {
            $dataForView = $dataForViewObj->toArray();
        } else {
            abort(403, 'No Data Found');
        }

        $refNumber = $dataForView['reference_number'];

        $wformTypeData = array();
        $wformTypeModel = new WFormType;
        $wformTypeDataObj = $wformTypeModel
            ->select('id', 'reference_number', 'extra_field', 'check_list')
            ->where('w_form_type.reference_number', 'LIKE', $refNumber)
            ->first();
        if ($wformTypeDataObj) {
            $wformTypeData = $wformTypeDataObj->toArray();
        }
        $dataForView['w_form_type'] = $wformTypeData;

        $commentData = array();
        $commentsModel = new Comment;
        $commentDataObj = $commentsModel
            ->select('comments.*', 'units.name as unit_name')
            ->leftJoin('units', 'units.id', 'comments.unit_id')
            ->where('comments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($commentDataObj) {
            $commentData = $commentDataObj->toArray();
        }
        $dataForView['comment'] = $commentData;

        $attachmentData = array();
        $attachmentModel = new Attachment;
        $attachmentDataObj = $attachmentModel
            ->select('id', 'file_name', 'name', 'reference_number', 'attachment_date', 'uploaded_by', 'created_at', 'updated_at')
            ->where('attachments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($attachmentDataObj) {
            $attachmentData = $attachmentDataObj->toArray();
        }
        $dataForView['w_form_attachment'] = $attachmentData;

        return view('Supports.wform_report_details', compact('title', 'title_for_layout', 'dataForView'));
    }

    public function complaintReportDetails($reference_number = "")
    {

        try {
            $reference_number = decrypt($reference_number);

        } catch (DecryptException $e) {
            abort(403, 'Un-Authorize Access!!!');
        }

        $title = "Complaint Details";
        $title_for_layout = "Complaint Report Details";

        $complaintModelName = new Complaint;
        $dataForViewObj = $complaintModelName
            ->select(
                "complaint.*",
                "reference.created_by",
                "reference.date",
                "reference.status",
                "reference.form_status",
                "reference.access_by",
                "reference.unit_id",
                "subgroup_info.name",
                "product_types.name as product_name",
                "cb_unit_items.auto_unit_id as auto_unit_id",
                "unit_items.name as issue_name",
                "unit_items.id as main_id",
                "complaint_form_type.extra_field",
                "complaint_form_type.check_list"
            )
            ->leftJoin('reference', 'reference.reference_number', '=', 'complaint.reference_number')
            ->leftJoin('subgroup_info', 'subgroup_info.id', '=', 'reference.sub_group_info_id')
            ->leftJoin('complaint_form_type', 'complaint_form_type.reference_number', '=', 'complaint.reference_number')
            ->leftJoin('product_types', 'product_types.id', '=', 'complaint.product_type')
            ->leftJoin('unit_items', function ($join) {
                $join->on('unit_items.master_id', '=', 'complaint.complaint_type');
                $join->on('unit_items.issues_from', '=', DB::raw("'complaint'"));
            })
            ->leftJoin('unit_items AS cb_unit_items', function ($join) {
                $join->on('cb_unit_items.master_id', '=', 'complaint.complaint_type');
                $join->on('cb_unit_items.issues_from', '=', DB::raw("'complaint'"));
                $join->on('cb_unit_items.unit_id', '=', 'reference.unit_id');
            })
            // ->leftJoin('unit_items', 'unit_items.master_id', '=', 'complaint.complaint_type')
            ->where("complaint.reference_number", $reference_number)
            ->first();

        $dataForView = array();
        if (!empty($dataForViewObj)) {
            $dataForView = $dataForViewObj->toArray();
        } else {
            abort(403, 'No Data Found');
        }

        $refNumber = $dataForView['reference_number'];

        $commentData = array();
        $commentsModel = new Comment;
        $commentDataObj = $commentsModel
            ->select('comments.*', 'units.name as unit_name')
            ->leftJoin('units', 'units.id', 'comments.unit_id')
            ->where('comments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($commentDataObj) {
            $commentData = $commentDataObj->toArray();
        }
        $dataForView['comment'] = $commentData;

        $attachmentData = array();
        $attachmentModel = new Attachment;
        $attachmentDataObj = $attachmentModel
            ->select('id', 'file_name', 'reference_number', 'attachment_date', 'uploaded_by', 'created_at', 'updated_at')
            ->where('attachments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($attachmentDataObj) {
            $attachmentData = $attachmentDataObj->toArray();
        }
        $dataForView['complaint_attachment'] = $attachmentData;

        return view('Supports.complaint_report_details', compact('title', 'title_for_layout', 'dataForView'));
    }

    public function nonCustomerReportDetails($reference_number = "")
    {

        try {
            $reference_number = decrypt($reference_number);
        } catch (DecryptException $e) {
            abort(403, 'Un-Authorize Access!!!');
        }

        $title = "Non Customer Report Details";
        $title_for_layout = "Non Customer Report Details";

        $nonCustomerModelName = new NonCustomer();
        $dataForViewObj = $nonCustomerModelName
            ->select(
                "non_customers.*",
                "reference.created_by",
                "reference.date",
                "reference.status",
                "reference.form_status",
                "reference.access_by",
                "reference.unit_id",
                "subgroup_info.name",
                "profession.name as customer_profession",
                "request_type.name as request_name",
                "sales_lead.name as sales_lead_name"
            )
            ->leftJoin('reference', 'reference.reference_number', '=', 'non_customers.reference_number')
            ->leftJoin('profession', 'profession.id', '=', 'non_customers.customer_profession')
            ->leftJoin('request_type', 'request_type.id', '=', 'non_customers.request_type')
            ->leftJoin('sales_lead', 'sales_lead.id', '=', 'non_customers.sales_lead')
            ->leftJoin('subgroup_info', 'subgroup_info.id', '=', 'reference.sub_group_info_id')

            // ->leftJoin('unit_items', 'unit_items.master_id', '=', 'complaint.complaint_type')
            ->where("non_customers.reference_number", $reference_number)
            ->first();

        $dataForView = array();
        if (!empty($dataForViewObj)) {
            $dataForView = $dataForViewObj->toArray();
        } else {
            abort(403, 'No Data Found');
        }

        $refNumber = $dataForView['reference_number'];

        $commentData = array();
        $commentsModel = new Comment;
        $commentDataObj = $commentsModel
            ->select(
                // 'comments.*'
                'comments.reference_number'
                ,
                'comments.group_id'
                ,
                'comments.subgroup_id'
                ,
                'comments.unit_id'
                ,
                'comments.user_id'
                ,
                'units.name as unit_name'
            )
            ->leftJoin('units', 'units.id', 'comments.unit_id')
            ->where('comments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($commentDataObj) {
            $commentData = $commentDataObj->toArray();
        }
        $dataForView['comment'] = $commentData;

        $attachmentData = array();
        $attachmentModel = new Attachment;
        $attachmentDataObj = $attachmentModel
            ->select('id', 'file_name', 'reference_number', 'attachment_date', 'uploaded_by', 'created_at', 'updated_at')
            ->where('attachments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($attachmentDataObj) {
            $attachmentData = $attachmentDataObj->toArray();
        }
        $dataForView['non_customers_attachment'] = $attachmentData;


        return view('Supports.non_customer_report_details', compact('title', 'title_for_layout', 'dataForView'));
    }
    /*********** End of For Logger Panel *******/

    /************ Handler Queue Group **********/
    public function handler(Request $request)
    {
        $dataForView = array();
        $searchDataForView = $request->all();
        $searchDataForView['reference_number'] = $request->ref_number;
        $searchDataForView['account_number'] = $request->account_number;
        $searchDataForView['date_from'] = $request->date_from;
        $searchDataForView['date_to'] = $request->date_to;
        $searchDataForView['service_category'] = $request->service_category;
        $searchDataForView['service_type'] = $request->service_type;
        $searchDataForView['logger'] = $request->logger;
        //$searchDataForView['last_user'] = $request->last_user;
        if ((!empty($request->active_tab))) {
            $searchDataForView['active_tab'] = $request->active_tab;
        } else {
            $searchDataForView['active_tab'] = 'wform';
        }

        $searchDataForView['cmmn_pgntion'] = (!empty($request->cmmn_pgntion)) ? $request->cmmn_pgntion : 15;
        $searchDataForView['cmmn_search'] = (!empty($request->cmmn_pgntion)) ? $request->cmmn_search : '';

        $tblData = array();

        $wFormData = array();
        $wFormDataObj = array();
        $complaintData = array();
        $complaintDataObj = array();
        $nonCustomerData = array();
        $nonCustomerDataObj = array();

        $unitDeptCondition = array();

        $isAdmin = false;
        $getUnitId = "";
        $getUnitIdArr = array();
        $getSubGroupIdArr = array();
        $getDepartmentId = "";
        $getDivisionId = "";
        $getGroupId = "";
        $get_subgroup_info_id = "";


        $title = "Handler / Queue";
        $title_for_layout = "Handler / Queue";
        if (empty(Auth::user()->user_unit)) {
            $wFormData = array();
            $wFormDataObj = array();
            $complaintData = array();
            $complaintDataObj = array();
            $nonCustomerData = array();
            $workingDays = array();
            $dataForView = array();
            $settingsData = array();
            return view('Supports.handler', compact('title', 'title_for_layout', 'tblData', 'searchDataForView', 'wFormData', 'complaintData', 'nonCustomerData', 'wFormDataObj', 'complaintDataObj', 'workingDays', 'dataForView', 'settingsData'));
        }
        $mostOldDate = "";

        $todayDate = date('Y-m-d');

        if (Auth::user()->hasRole(['superadmin', 'admin'])) {
            $isAdmin = true;
        } else {
            $unitList = Auth::user()->user_unit;

            if (!empty($unitList)) {
                $getUnitId = $unitList->unit_id;
                $getDepartmentId = $unitList->department_id;
                $getDivisionId = $unitList->division_id;
                $get_subgroup_info_id = $unitList->subgroup_info_id;
                $getGroupId = $unitList->group_info_id;
            }
            if (!empty($getUnitId)) {
                $getUnitIdArr = explode(',', $getUnitId);
                if ($getUnitId != "1,2" && $getUnitId != "2,1" && $getUnitId != "1" && $getUnitId != "2") {
                    if ($get_subgroup_info_id == 386){
                        $getUnitIdArr = $getUnitIdArr;
                    }else{
                        $getUnitIdArr = array();
                    }
                }
                /*$getGroup = SubgroupInfo::find($get_subgroup_info_id); $getGroupId=''; if(!empty($getGroup)){$getGroupId=$getGroup->group_info_id; }*/
            }
            if (!empty($getGroupId)) {
                $subGroupInfoModel = new SubgroupInfo;
                $subGroupInfoArr = $subGroupInfoModel->where('group_info_id', $getGroupId)->pluck('id', 'id');
                if (!empty($subGroupInfoArr)) {
                    $getSubGroupIdArr = $subGroupInfoArr->toArray();
                    ;
                }
            }
            if (!empty($getDepartmentId)) {
                $subGroupInfoModel = new SubgroupInfo;
                $subGroupInfoArr = $subGroupInfoModel->where('department_id', $getDepartmentId)->pluck('id', 'id');
                if (!empty($subGroupInfoArr)) {
                    $tmpGetSubGroupIdArr = $subGroupInfoArr->toArray();
                    $getSubGroupIdArr = array_merge($getSubGroupIdArr, $tmpGetSubGroupIdArr);
                }
            }

            /* if (!empty($getDepartmentId)) {$unitChildModel = new UnitChild; $unitChildArr = $unitChildModel->where('department_id',$getDepartmentId)->pluck('unit_id','unit_id')->toArray(); if (!empty($unitChildArr)) {$getUnitIdArr = array_merge($getUnitIdArr,$unitChildArr); } } *//*if (!empty($getDivisionId)) {$unitChildModel = new UnitChild; $unitChildArr = $unitChildModel ->select('unit_childs.unit_id') ->leftJoin('departments','departments.id','unit_childs.department_id') ->where('departments.division_id',$getDivisionId) ->pluck('unit_childs.unit_id','unit_childs.unit_id') ->toArray(); if (!empty($unitChildArr)) {$getUnitIdArr = array_merge($getUnitIdArr,$unitChildArr); } }*/
        }

        if (!empty($searchDataForView['account_number'])) {
        }

        // pr(strtotime($searchDataForView['date_from'])); // prd(strtotime($searchDataForView['date_to'])); // FROM_UNIXTIME(timestamp)

        if ($searchDataForView['active_tab'] == 'wform') {
            $wformModelName = new WForm;
            $wFormDataObj = $wformModelName
                ->select(
                    // "w_form.*"
                    "w_form.account_number"
                    ,
                    "w_form.customer_name"
                    ,
                    "w_form.product_type"
                    ,
                    "w_form.time_and_ext"
                    ,
                    "w_form.reference_number"
                    ,
                    "w_form.priority"
                    ,
                    "w_form.product_type as product_type_ext"
                    ,
                    "w_form.SIF_Number as customer_id"
                    ,
                    "product_types.name as product_type"
                    ,
                    "unit_items.name as category_name"
                    ,
                    "reference.unit_id"
                    ,
                    "reference.created_by"
                    ,
                    "reference.segment_priority"
                    ,
                    "reference.date"
                    ,
                    "reference.status"
                    ,
                    "reference.form_status"
                    ,
                    "reference.access_by"
                    ,
                    "reference.access_date"
                    ,
                    "group_info.id as group_info_id"
                    ,
                    "issue_workflows.flow_type"
                    ,
                    "issue_group_workflows.sla_maker"
                    ,
                    "issue_group_workflows.sla_checker"
                    ,
                    DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d') AS UNXTIME")
                    // ,DB::raw("(SELECT count(dates) dates FROM working_days where dates >FROM_UNIXTIME(reference.date,'%Y-%m-%d') and dates < '{$todayDate}') AS total_working_days")
                )
                ->leftJoin('reference', 'reference.reference_number', '=', 'w_form.reference_number')
                ->leftJoin('group_info', 'group_info.id', '=', 'reference.subgroup_id')
                ->leftJoin('issue_workflows', 'issue_workflows.issue_id', '=', 'reference.issue_id')
                ->leftJoin('issue_group_workflows', function ($join) {
                    $join->on('issue_group_workflows.group_info_id', '=', 'group_info.id');
                    $join->on('issue_group_workflows.issue_workflow_id', '=', 'issue_workflows.issue_workflow_id');
                })
                // ->leftJoin('departments', 'departments.id', '=', 'w_form.product_type')
                ->leftJoin('product_types', 'product_types.id', '=', 'w_form.product_type')
                ->leftJoin('unit_items', function ($join) {
                    $join->on('unit_items.master_id', '=', 'w_form.w_form_type');
                    $join->on('unit_items.issues_from', '=', DB::raw("'wform'"));
                });
            if (!empty($searchDataForView['reference_number'])) {
                $wFormDataObj = $wFormDataObj->where("w_form.reference_number", $searchDataForView['reference_number']);
            }
            if (!empty($searchDataForView['account_number'])) {
                $wFormDataObj = $wFormDataObj->where("w_form.account_number", $searchDataForView['account_number']);
            }
            if (!empty($searchDataForView['service_type'])) {
                $wFormDataObj = $wFormDataObj->where("reference.issue_id", $searchDataForView['service_type']);
            }
            if (!empty($searchDataForView['logger'])) {
                $wFormDataObj = $wFormDataObj->where("reference.created_by", $searchDataForView['logger']);
            }

            /*if (!empty($searchDataForView['last_user'])) {// $wFormDataObj = $wFormDataObj->where("reference.access_by",$searchDataForView['last_user']); // $commentRefNo = DB::select("SELECT reference_number FROM comments WHERE user_id LIKE '".$searchDataForView['last_user']."' GROUP BY reference_number ORDER BY id DESC;"); // $commentRefNo = DB::select(//                     "SELECT c1.reference_number //                         FROM comments c1 LEFT JOIN comments c2 //                           ON (c1.user_id = c2.user_id AND c1.id < c2.id) //                         WHERE c2.id IS NULL AND c1.user_id = '".$searchDataForView['last_user']."'; //                     "//                 ); // $commentRefNo = json_decode(json_encode($commentRefNo),true); // $wFormDataObj = $wFormDataObj->whereIn("reference.reference_number",$commentRefNo); }*/

            if (!empty($searchDataForView['date_from']) && empty($searchDataForView['date_to'])) {
                $wFormDataObj = $wFormDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), ">=", date('Y-m-d', strtotime($searchDataForView['date_from'])));
            } elseif (!empty($searchDataForView['date_to']) && empty($searchDataForView['date_from'])) {
                $wFormDataObj = $wFormDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "<=", date('Y-m-d', strtotime($searchDataForView['date_to'])));
            } elseif (!empty($searchDataForView['date_to']) && !empty($searchDataForView['date_from'])) {
                // $wFormDataObj = $wFormDataObj->whereBetween(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),array($searchDataForView['date_from'],$searchDataForView['date_to']));
                $wFormDataObj = $wFormDataObj->where(function ($q) use ($searchDataForView) {
                    $q->where(
                        DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),
                        ">=",
                        date('Y-m-d', strtotime($searchDataForView['date_from']))
                    )
                        ->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "<=", date('Y-m-d', strtotime($searchDataForView['date_to'])));
                });
            }

            /*if (empty($searchDataForView['reference_number']) && empty($searchDataForView['account_number']) && empty($searchDataForView['date_from']) && empty($searchDataForView['date_to'])) {$wFormDataObj = $wFormDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),">=", date('Y-m-d')); }*//*if ($isAdmin == false && !empty($getUnitId)) {if(in_array(1,$getUnitIdArr)||in_array(2,$getUnitIdArr)){if(in_array(3,$getUnitIdArr)||in_array(4,$getUnitIdArr)){} else {$wFormDataObj = $wFormDataObj->whereIn("reference.unit_id",$getUnitIdArr); } } $wFormDataObj = $wFormDataObj->where("reference.subgroup_id",$getGroupId); //$wFormDataObj = $wFormDataObj->Where('reference.access_by','')->Orwhere("reference.access_by",Auth::user()->user_id); }*//* if (Auth::user()->hasRole(['supervisor', 'admin'])) {$isAdmin = true; } */// pr($getUnitIdArr); // pr($getGroupId); // pr($get_subgroup_info_id); // prd($getSubGroupIdArr);

            if (!empty($searchDataForView['cmmn_search'])) {
                $statusNumb = array();
                if ($searchDataForView['cmmn_search'] == 'Pending') {
                    $statusNumb = [8, 0, NULL];
                } elseif ($searchDataForView['cmmn_search'] == 'Close') {
                    $statusNumb = [11];
                } elseif ($searchDataForView['cmmn_search'] == 'Reject') {
                    $statusNumb = [-1];
                } elseif ($searchDataForView['cmmn_search'] == 'Hold') {
                    $statusNumb = [10];
                } elseif ($searchDataForView['cmmn_search'] == 'Wip') {
                    $statusNumb = [1, 2, 3, 4, 5, 6, 7, 9];
                }

                $commentWhere = " comments.user_id='" . $searchDataForView['cmmn_search'] . "'";
                if ($isAdmin == false) {
                    if (!empty($searchDataForView['curr_user_id'])) {
                        $commentWhere .= ' AND comments.user_id=' . $searchDataForView['curr_user_id'];
                    }
                    if (!empty($getUnitIdArr)) {
                        $getUnitIdArrStr = implode(',', $getUnitIdArr);
                        $commentWhere .= ' AND comments.unit_id in (' . $getUnitIdArrStr . ')';
                    }
                    if (!empty($getSubGroupIdArr) || !empty($get_subgroup_info_id)) {
                        $getSubGroupIdArrStr = implode(',', $getSubGroupIdArr);
                        if ((!empty($getSubGroupIdArr) && !empty($get_subgroup_info_id))) {
                            $commentWhere .= ' AND (comments.subgroup_id=' . $get_subgroup_info_id . ' OR comments.subgroup_id IN (' . $getSubGroupIdArrStr . '))';
                        } elseif (!empty($getSubGroupIdArr)) {
                            $commentWhere .= ' AND comments.subgroup_id IN (' . $getSubGroupIdArrStr . ')';
                        } elseif (!empty($get_subgroup_info_id)) {
                            $commentWhere .= ' AND comments.subgroup_id=' . $get_subgroup_info_id;
                        }
                    }
                }
                $wFormDataObj = $wFormDataObj
                    ->where(function ($query) use ($searchDataForView, $statusNumb, $commentWhere) {
                        $query
                            ->where("reference.reference_number", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("w_form.account_number", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("w_form.customer_name", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("product_types.name", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("unit_items.name", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("w_form.time_and_ext", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhereIn("reference.form_status", $statusNumb)
                            ->orWhere("reference.created_by", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("reference.access_by", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("w_form.SIF_Number", "like", '%' . $searchDataForView['cmmn_search'] . '%');
                        /*->orWhereRaw("reference.reference_number IN ( SELECT comments.reference_number FROM comments LEFT JOIN reference ON (reference.reference_number = comments.reference_number) WHERE $commentWhere AND reference.form_status <> 11 )");*/
                    });

            }

            if ($isAdmin == false) {
                if (!empty($getUnitIdArr)) {
                    $wFormDataObj = $wFormDataObj->whereIn("reference.unit_id", $getUnitIdArr);
                }
                $wFormDataObj = $wFormDataObj->where(function ($q) use ($getUnitIdArr, $getGroupId, $get_subgroup_info_id, $getSubGroupIdArr) {
                    $q->where("reference.sub_group_info_id", $get_subgroup_info_id)
                        ->orWhereIn("reference.sub_group_info_id", $getSubGroupIdArr)
                    ;
                });
            }
            /*if ($isAdmin == false && !empty($getUnitIdArr)) {
                $wFormDataObj = $wFormDataObj->whereIn("reference.unit_id",$getUnitIdArr);
            }
            if ($isAdmin == false && !empty($getGroupId)) {
                $wFormDataObj = $wFormDataObj->where("reference.subgroup_id",$getGroupId);
            }
            if ($isAdmin == false && !empty($get_subgroup_info_id)) {
                $wFormDataObj = $wFormDataObj->where("reference.sub_group_info_id",$get_subgroup_info_id);
            }

            if (!empty($getSubGroupIdArr)) {

                $wFormDataObj = $wFormDataObj->whereIn("reference.sub_group_info_id",$getSubGroupIdArr);
            }*/
            // pr($getUnitIdArr);
            // pr($getGroupId);
            // pr($get_subgroup_info_id);
            // prd($getSubGroupIdArr);
            $wFormDataObj = $wFormDataObj->where("reference.form_status", "<>", -7);
            $wFormDataObj = $wFormDataObj->where("reference.form_status", "<>", 11);
            if (!empty($_GET['orderby'])) {
                $orderByArr = explode('-', $_GET['orderby']);
                $orderName = (!empty($orderByArr[0])) ? $orderByArr[0] : 'DESC';
                if($orderByArr[1] == "reference.date=w_form.time_and_ext") {
                    $orderByRefArr = explode('=', $orderByArr[1]);
                    $columnsNameW = (!empty($orderByRefArr[1])) ? $orderByRefArr[1] : 'reference.reference_number';
                    $columnsName = (!empty($orderByRefArr[0])) ? $orderByRefArr[0] : 'reference.reference_number';
                    $wFormDataObj = $wFormDataObj->orderBy("reference.segment_priority", "DESC");
                    $wFormDataObj = $wFormDataObj->orderBy($columnsName, $orderName);
                    $wFormDataObj = $wFormDataObj->orderBy($columnsNameW, $orderName);
                } else {
                    $columnsName = (!empty($orderByArr[1])) ? $orderByArr[1] : 'reference.reference_number';
                    $wFormDataObj = $wFormDataObj->orderBy("reference.segment_priority", "DESC");
                    $wFormDataObj = $wFormDataObj->orderBy($columnsName, $orderName);
                }

                /*$wFormDataObj = $wFormDataObj->orderBy("reference.segment_priority", "DESC");*/
                /*$wFormDataObj = $wFormDataObj->orderBy($columnsName, $orderName);*/


            } else {
               /* $wFormDataObj = $wFormDataObj->orderBy("reference.date", "DESC");
                $wFormDataObj = $wFormDataObj->orderBy("reference.segment_priority", "DESC");*/

                $wFormDataObj = $wFormDataObj->orderBy("reference.segment_priority", "DESC")
                    ->orderBy("reference.date", "ASC");
            }

            $wFormDataObj = $wFormDataObj
                ->paginate($searchDataForView['cmmn_pgntion']);
            //->get();

            if (!empty($wFormDataObj)) {
                $wFormData = $wFormDataObj->toArray();
                // prd($wFormData);
                $lastRecord = end($wFormData['data']);
                //$lastRecord = end($wFormData);
                if (!empty($lastRecord)) {
                    $mostOldDate = $lastRecord['UNXTIME'];
                }
            }
        } elseif ($searchDataForView['active_tab'] == 'complaint') {
            $complaintModelName = new Complaint;
            $complaintDataObj = $complaintModelName
                ->select(
                    // "complaint.*"
                    "complaint.account_number"
                    ,
                    "complaint.customer_name"
                    ,
                    "complaint.product_type"
                    ,
                    "complaint.time_and_ext"
                    ,
                    "complaint.reference_number"
                    ,
                    "complaint.priority"
                    ,
                    "complaint.product_type as product_type_ext"
                    ,
                    "complaint.SIF_Number as customer_id"
                    ,
                    "product_types.name as product_type"
                    ,
                    "unit_items.name as issue_name"
                    ,
                    "reference.unit_id"
                    ,
                    "reference.created_by"
                    ,
                    "reference.date"
                    ,
                    "reference.segment_priority"
                    ,
                    "reference.status"
                    ,
                    "reference.segment_priority"
                    ,
                    "reference.form_status"
                    ,
                    "reference.access_by"
                    ,
                    "reference.access_date"
                    ,
                    "group_info.id as group_info_id"
                    ,
                    "issue_workflows.flow_type"
                    ,
                    "issue_group_workflows.sla_maker"
                    ,
                    "issue_group_workflows.sla_checker"
                    ,
                    "issue_workflows.complain_sla_time"
                    ,
                    DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d') AS UNXTIME")
                    // ,DB::raw("(SELECT count(dates) dates FROM working_days where dates >FROM_UNIXTIME(reference.date,'%Y-%m-%d') and dates < '{$todayDate}') AS total_working_days")
                )
                ->leftJoin('reference', 'reference.reference_number', '=', 'complaint.reference_number')
                ->leftJoin('group_info', 'group_info.id', '=', 'reference.subgroup_id')
                ->leftJoin('issue_workflows', 'issue_workflows.issue_id', '=', 'reference.issue_id')
                ->leftJoin('issue_group_workflows', function ($join) {
                    $join->on('issue_group_workflows.group_info_id', '=', 'group_info.id');
                    $join->on('issue_group_workflows.issue_workflow_id', '=', 'issue_workflows.issue_workflow_id');
                })
                //->leftJoin('departments', 'departments.id', '=', 'complaint.product_type')
                ->leftJoin('product_types', 'product_types.id', '=', 'complaint.product_type')
                ->leftJoin('unit_items', function ($join) {
                    $join->on('unit_items.master_id', '=', 'complaint.complaint_type');
                    $join->on('unit_items.issues_from', '=', DB::raw("'complaint'"));
                });

            if (!empty($searchDataForView['reference_number'])) {
                $complaintDataObj = $complaintDataObj->where("complaint.reference_number", $searchDataForView['reference_number']);
            }
            if (!empty($searchDataForView['account_number'])) {
                $complaintDataObj = $complaintDataObj->where("complaint.account_number", $searchDataForView['account_number']);
            }
            if (!empty($searchDataForView['service_type'])) {
                $complaintDataObj = $complaintDataObj->where("reference.issue_id", $searchDataForView['service_type']);
            }
            if (!empty($searchDataForView['logger'])) {
                $complaintDataObj = $complaintDataObj->where("reference.created_by", $searchDataForView['logger']);
            }
            // if (!empty($searchDataForView['last_user'])) {
            //     $complaintDataObj = $complaintDataObj->where("reference.access_by",$searchDataForView['last_user']);
            // }

            if (!empty($searchDataForView['date_from']) && empty($searchDataForView['date_to'])) {
                $complaintDataObj = $complaintDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), ">=", date('Y-m-d', strtotime($searchDataForView['date_from'])));
            } elseif (!empty($searchDataForView['date_to']) && empty($searchDataForView['date_from'])) {
                $complaintDataObj = $complaintDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "<=", date('Y-m-d', strtotime($searchDataForView['date_to'])));
            } elseif (!empty($searchDataForView['date_to']) && !empty($searchDataForView['date_from'])) {
                // $complaintDataObj = $complaintDataObj->whereBetween(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),array($searchDataForView['date_from'],$searchDataForView['date_to']));
                $complaintDataObj = $complaintDataObj->where(function ($q) use ($searchDataForView) {
                    $q->where(
                        DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),
                        ">=",
                        date('Y-m-d', strtotime($searchDataForView['date_from']))
                    )
                        ->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "<=", date('Y-m-d', strtotime($searchDataForView['date_to'])));
                });
            } else {
                // $complaintDataObj = $complaintDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),">=", date('Y-m-d'));
            }

            if (!empty($searchDataForView['cmmn_search'])) {

                $statusNumb = array();
                if ($searchDataForView['cmmn_search'] == 'Pending') {
                    $statusNumb = [8, 0, NULL];
                } elseif ($searchDataForView['cmmn_search'] == 'Close') {
                    $statusNumb = [11];
                } elseif ($searchDataForView['cmmn_search'] == 'Reject') {
                    $statusNumb = [-1];
                } elseif ($searchDataForView['cmmn_search'] == 'Hold') {
                    $statusNumb = [10];
                } elseif ($searchDataForView['cmmn_search'] == 'Wip') {
                    $statusNumb = [1, 2, 3, 4, 5, 6, 7, 9];
                }

                $commentWhere = " comments.user_id='" . $searchDataForView['cmmn_search'] . "'";
                if ($isAdmin == false) {
                    if (!empty($searchDataForView['curr_user_id'])) {
                        $commentWhere .= ' AND comments.user_id=' . $searchDataForView['curr_user_id'];
                    }
                    if (!empty($getUnitIdArr)) {
                        $getUnitIdArrStr = implode(',', $getUnitIdArr);
                        $commentWhere .= ' AND comments.unit_id in (' . $getUnitIdArrStr . ')';
                    }
                    if (!empty($getSubGroupIdArr) || !empty($get_subgroup_info_id)) {
                        $getSubGroupIdArrStr = implode(',', $getSubGroupIdArr);
                        if ((!empty($getSubGroupIdArr) && !empty($get_subgroup_info_id))) {
                            $commentWhere .= ' AND (comments.subgroup_id=' . $get_subgroup_info_id . ' OR comments.subgroup_id IN (' . $getSubGroupIdArrStr . '))';
                        } elseif (!empty($getSubGroupIdArr)) {
                            $commentWhere .= ' AND comments.subgroup_id IN (' . $getSubGroupIdArrStr . ')';
                        } elseif (!empty($get_subgroup_info_id)) {
                            $commentWhere .= ' AND comments.subgroup_id=' . $get_subgroup_info_id;
                        }
                    }
                }

                $complaintDataObj = $complaintDataObj
                    ->where(function ($query) use ($searchDataForView, $statusNumb, $commentWhere) {
                        $query
                            ->where("reference.reference_number", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("complaint.account_number", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("complaint.customer_name", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("product_types.name", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("unit_items.name", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("complaint.time_and_ext", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhereIn("reference.form_status", $statusNumb)
                            ->orWhere("reference.created_by", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("reference.access_by", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("complaint.SIF_Number", "like", '%' . $searchDataForView['cmmn_search'] . '%');
                        /*->orWhereRaw("reference.reference_number IN ( SELECT comments.reference_number FROM comments LEFT JOIN reference ON (reference.reference_number = comments.reference_number) WHERE $commentWhere AND reference.form_status <> 11 )")*/
                    });
            }

            if ($isAdmin == false) {
                if (!empty($getUnitIdArr)) {
                    $complaintDataObj = $complaintDataObj->whereIn("reference.unit_id", $getUnitIdArr);
                }
                $complaintDataObj = $complaintDataObj->where(function ($q) use ($getUnitIdArr, $getGroupId, $get_subgroup_info_id, $getSubGroupIdArr) {
                    $q->where("reference.sub_group_info_id", $get_subgroup_info_id)
                        ->orWhereIn("reference.sub_group_info_id", $getSubGroupIdArr)
                    ;
                });
            }

            /*if ($isAdmin == false && !empty($getUnitIdArr)) {
                $complaintDataObj = $complaintDataObj->whereIn("reference.unit_id",$getUnitIdArr);
                // $complaintDataObj = $complaintDataObj->where("reference.subgroup_id",$getGroupId);

            }
            if ($isAdmin == false && !empty($getGroupId)) {
                $complaintDataObj = $complaintDataObj->where("reference.subgroup_id",$getGroupId);
            }
            if ($isAdmin == false && !empty($get_subgroup_info_id)) {
                $complaintDataObj = $complaintDataObj->where("reference.sub_group_info_id",$get_subgroup_info_id);
            }

            if (!empty($getSubGroupIdArr)) {
                $complaintDataObj = $complaintDataObj->whereIn("reference.sub_group_info_id",$getSubGroupIdArr);
            }*/


            $complaintDataObj = $complaintDataObj->where("reference.form_status", "<>", -7);
            $complaintDataObj = $complaintDataObj->where("reference.form_status", "<>", 11);

            if (!empty($_GET['orderby'])) {
                $orderByArr = explode('-', $_GET['orderby']);
                $orderName = (!empty($orderByArr[0])) ? $orderByArr[0] : 'DESC';

                if($orderByArr[1] == "reference.date=complaint.time_and_ext") {
                    $orderByRefArr = explode('=', $orderByArr[1]);
                    $columnsNameC = (!empty($orderByRefArr[1])) ? $orderByRefArr[1] : 'reference.reference_number';
                    $columnsName = (!empty($orderByRefArr[0])) ? $orderByRefArr[0] : 'reference.reference_number';
                    $complaintDataObj = $complaintDataObj->orderBy("reference.segment_priority", "DESC");
                    $complaintDataObj = $complaintDataObj->orderBy($columnsName, $orderName);
                    $complaintDataObj = $complaintDataObj->orderBy($columnsNameC, $orderName);
                } else {
                    $columnsName = (!empty($orderByArr[1])) ? $orderByArr[1] : 'reference.reference_number';
                    $complaintDataObj = $complaintDataObj->orderBy("reference.segment_priority", "DESC");
                    $complaintDataObj = $complaintDataObj->orderBy($columnsName, $orderName);
                }

            } else {
                // $complaintDataObj = $complaintDataObj->orderBy("reference.date", "DESC");
                $complaintDataObj = $complaintDataObj->orderBy("reference.segment_priority", "DESC")
                    ->orderBy("reference.date", "ASC");
            }

            $complaintDataObj = $complaintDataObj
                ->paginate($searchDataForView['cmmn_pgntion']);
            //->get();


            if (!empty($complaintDataObj)) {
                $complaintData = $complaintDataObj->toArray();
                //prd($complaintData);
                //$lastRecord = end($complaintData);
                $lastRecord = end($complaintData['data']);
                if (!empty($lastRecord)) {
                    $mostOldDate = $lastRecord['UNXTIME'];
                }
            }

        } elseif ($searchDataForView['active_tab'] == IssueTypeEnum::NON_CUSTOMER) {
            $nonCustomerModelName = new NonCustomer();
            $nonCustomerDataObj = $nonCustomerModelName
                ->select(
                    // "non_customers.*"
                    "non_customers.customer_name"
                    ,
                    "non_customers.mobile_number"
                    ,
                    "non_customers.customer_email"
                    ,
                    "non_customers.customer_dob"
                    ,
                    "non_customers.customer_profession"
                    ,
                    "non_customers.time_and_ext"
                    ,
                    "non_customers.reference_number"
                    ,
                    "reference.unit_id"
                    ,
                    "reference.created_by"
                    ,
                    "reference.date"
                    ,
                    "reference.status"
                    ,
                    "reference.form_status"
                    ,
                    "reference.access_by"
                    ,
                    "reference.access_date"
                    ,
                    "subgroup_info.group_info_id"
                    ,
                    "issue_workflows.flow_type"
                    ,
                    "issue_group_workflows.sla_maker"
                    ,
                    "issue_group_workflows.sla_checker"
                    ,
                    "profession.name as customer_profession"

                    ,
                    DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d') AS UNXTIME")
                    // ,DB::raw("(SELECT count(dates) dates FROM working_days where dates >FROM_UNIXTIME(reference.date,'%Y-%m-%d') and dates < '{$todayDate}') AS total_working_days")
                )

                ->leftJoin('reference', 'reference.reference_number', '=', 'non_customers.reference_number')
                ->leftJoin('profession', 'profession.id', '=', 'non_customers.customer_profession')
                ->leftJoin('subgroup_info', 'subgroup_info.id', '=', 'reference.subgroup_id')
                ->leftJoin('issue_workflows', 'issue_workflows.issue_id', '=', 'reference.issue_id')
                ->leftJoin('issue_group_workflows', function ($join) {
                    $join->on('issue_group_workflows.group_info_id', '=', 'subgroup_info.group_info_id');
                    $join->on('issue_group_workflows.issue_workflow_id', '=', 'issue_workflows.issue_workflow_id');
                });



            if (!empty($searchDataForView['reference_number'])) {
                $nonCustomerDataObj = $nonCustomerDataObj->where("non_customers.reference_number", $searchDataForView['reference_number']);
            }
            /*if (!empty($searchDataForView['account_number'])) {
                $nonCustomerDataObj = $nonCustomerDataObj->where("non_customers.account_number",$searchDataForView['account_number']);
            }*/
            if (!empty($searchDataForView['logger'])) {
                $nonCustomerDataObj = $nonCustomerDataObj->where("reference.created_by", $searchDataForView['logger']);
            }
            // if (!empty($searchDataForView['last_user'])) {
            //     $nonCustomerDataObj = $nonCustomerDataObj->where("reference.access_by",$searchDataForView['last_user']);
            // }

            if (!empty($searchDataForView['date_from']) && empty($searchDataForView['date_to'])) {
                $nonCustomerDataObj = $nonCustomerDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), ">=", date('Y-m-d', strtotime($searchDataForView['date_from'])));
            } elseif (!empty($searchDataForView['date_to']) && empty($searchDataForView['date_from'])) {
                $nonCustomerDataObj = $nonCustomerDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "<=", date('Y-m-d', strtotime($searchDataForView['date_to'])));
            } elseif (!empty($searchDataForView['date_to']) && !empty($searchDataForView['date_from'])) {
                // $complaintDataObj = $complaintDataObj->whereBetween(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),array($searchDataForView['date_from'],$searchDataForView['date_to']));
                $nonCustomerDataObj = $nonCustomerDataObj->where(function ($q) use ($searchDataForView) {
                    $q->where(
                        DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),
                        ">=",
                        date('Y-m-d', strtotime($searchDataForView['date_from']))
                    )
                        ->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "<=", date('Y-m-d', strtotime($searchDataForView['date_to'])));
                });
            } else {
                // $complaintDataObj = $complaintDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),">=", date('Y-m-d'));
            }

            if (!empty($searchDataForView['cmmn_search'])) {
                $statusNumb = array();
                if ($searchDataForView['cmmn_search'] == 'Pending') {
                    $statusNumb = [8, 0, NULL];
                } elseif ($searchDataForView['cmmn_search'] == 'Close') {
                    $statusNumb = [11];
                } elseif ($searchDataForView['cmmn_search'] == 'Reject') {
                    $statusNumb = [-1];
                } elseif ($searchDataForView['cmmn_search'] == 'Hold') {
                    $statusNumb = [10];
                } elseif ($searchDataForView['cmmn_search'] == 'Wip') {
                    $statusNumb = [1, 2, 3, 4, 5, 6, 7, 9];
                }

                $commentWhere = " comments.user_id='" . $searchDataForView['cmmn_search'] . "'";
                if ($isAdmin == false) {
                    if (!empty($searchDataForView['curr_user_id'])) {
                        $commentWhere .= ' AND comments.user_id=' . $searchDataForView['curr_user_id'];
                    }
                    if (!empty($getUnitIdArr)) {
                        $getUnitIdArrStr = implode(',', $getUnitIdArr);
                        $commentWhere .= ' AND comments.unit_id in (' . $getUnitIdArrStr . ')';
                    }
                    if (!empty($getSubGroupIdArr) || !empty($get_subgroup_info_id)) {
                        $getSubGroupIdArrStr = implode(',', $getSubGroupIdArr);
                        if ((!empty($getSubGroupIdArr) && !empty($get_subgroup_info_id))) {
                            $commentWhere .= ' AND (comments.subgroup_id=' . $get_subgroup_info_id . ' OR comments.subgroup_id IN (' . $getSubGroupIdArrStr . '))';
                        } elseif (!empty($getSubGroupIdArr)) {
                            $commentWhere .= ' AND comments.subgroup_id IN (' . $getSubGroupIdArrStr . ')';
                        } elseif (!empty($get_subgroup_info_id)) {
                            $commentWhere .= ' AND comments.subgroup_id=' . $get_subgroup_info_id;
                        }
                    }
                }

                $nonCustomerDataObj = $nonCustomerDataObj
                    ->where(function ($query) use ($searchDataForView, $statusNumb, $commentWhere) {
                        $query
                            ->where("reference.reference_number", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("non_customers.customer_name", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("non_customers.mobile_number", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("non_customers.customer_email", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("non_customers.customer_dob", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("non_customers.customer_profession", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("non_customers.time_and_ext", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhereIn("reference.form_status", $statusNumb)
                            ->orWhere("reference.created_by", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("reference.access_by", "like", '%' . $searchDataForView['cmmn_search'] . '%');
                        /*->orWhereRaw("reference.reference_number IN ( SELECT comments.reference_number FROM comments LEFT JOIN reference ON (reference.reference_number = comments.reference_number) WHERE $commentWhere AND reference.form_status <> 11 )");*/
                    });
            }


            if ($isAdmin == false) {
                if (!empty($getUnitIdArr)) {
                    $nonCustomerDataObj = $nonCustomerDataObj->whereIn("reference.unit_id", $getUnitIdArr);
                }
                $nonCustomerDataObj = $nonCustomerDataObj->where(function ($q) use ($getUnitIdArr, $getGroupId, $get_subgroup_info_id, $getSubGroupIdArr) {
                    $q->where("reference.sub_group_info_id", $get_subgroup_info_id)
                        ->orWhereIn("reference.sub_group_info_id", $getSubGroupIdArr)
                    ;
                });
            }

            $nonCustomerDataObj = $nonCustomerDataObj->where("reference.form_status", "<>", -7);
            $nonCustomerDataObj = $nonCustomerDataObj->where("reference.form_status", "<>", 11);

            if (!empty($_GET['orderby'])) {
                $orderByArr = explode('-', $_GET['orderby']);
                $orderName = (!empty($orderByArr[0])) ? $orderByArr[0] : 'DESC';
                $columnsName = (!empty($orderByArr[1])) ? $orderByArr[1] : 'reference.reference_number';

                $nonCustomerDataObj = $nonCustomerDataObj->orderBy($columnsName, $orderName);

            } else {
                $nonCustomerDataObj = $nonCustomerDataObj->orderBy("reference.date", "ASC");
            }

            $nonCustomerDataObj = $nonCustomerDataObj
                ->paginate($searchDataForView['cmmn_pgntion']);
            //->get();


            if (!empty($nonCustomerDataObj)) {
                $nonCustomerData = $nonCustomerDataObj->toArray();
                // prd($nonCustomerData);
                $lastRecord = end($nonCustomerData['data']);
                //$lastRecord = end($nonCustomerData);
                if (!empty($lastRecord)) {
                    $mostOldDate = $lastRecord['UNXTIME'];
                }
            }

        }


        $workingDays = array();
        if (!empty($mostOldDate)) {
            $workingDayModel = new WorkingDay;
            $workingDayDataObj = $workingDayModel->where([['dates', '>=', $mostOldDate], ['status', 1]])->pluck('dates', 'dates');
            if (!empty($workingDayDataObj)) {
                $workingDays = $workingDayDataObj->toArray();
            }
        }

        $workingHours = DB::table('working_hours')->first();
        $workingHours = json_decode(json_encode($workingHours), true);

        if (!empty($workingHours['office_from'])) {
            $dataForView['office_from'] = $workingHours['office_from'];
            $dataForView['office_from_str'] = substr($dataForView['office_from'], 0, 2) . ':' . substr($dataForView['office_from'], 2, 2) . ':' . substr($dataForView['office_from'], 4, 2);
        } else {
            $dataForView['office_from'] = '100000';
            $dataForView['office_from_str'] = '10:00:00';
        }
        if (!empty($workingHours['office_to'])) {
            $dataForView['office_to'] = $workingHours['office_to'];
            $dataForView['office_to_str'] = substr($dataForView['office_to'], 0, 2) . ':' . substr($dataForView['office_to'], 2, 2) . ':' . substr($dataForView['office_to'], 4, 2);
        } else {
            $dataForView['office_to'] = '180000';
            $dataForView['office_to_str'] = '18:00:00';
        }

        $settingsData = DB::table('settings')->first();
        // $settingsData = json_decode(json_encode($settingsData),true);
        // prd($settingsData->forward_times);

        //prd($wFormData);

        return view('Supports.handler', compact('title', 'title_for_layout', 'tblData', 'searchDataForView', 'wFormData', 'complaintData', 'nonCustomerData', 'wFormDataObj', 'complaintDataObj', 'workingDays', 'dataForView', 'settingsData'));
    }

    public function assign(Request $request, $reference_number = "")
    {
        // dd($request->all());
        $encryptedRefNo = $reference_number;
        $duration_in_minutes = 0;

        try {
            $reference_number = decrypt($reference_number);
            $duration_in_minutes = decrypt($request->qd);
        } catch (DecryptException $e) {
            abort(403, 'Un-Authorize Access!!!');
        }
        // dd($duration_in_minutes, $reference_number, $comments);
        $user_id = Auth::user()->user_id;
        $subgroup_id = Auth::user()->user_unit->subgroup_info_id;
        $group_info_id = SubgroupInfo::find($subgroup_id);

        $referenceModelName = new Reference;
        $row = $referenceModelName->where("reference_number", $reference_number)->where('access_by', '<>', '')->first();
        //prd($row);
        //pr($subgroup_id);
        //pr($group_info_id->group_info_id);

        if ($row) {
            //echo "<script>window.close();</script>";
            flash("Ticket No: $reference_number already assigned!!!", 'danger');
            return redirect('Supports/handler');
        }
        //Form Status entry
        // $this->form_status($reference_number,0,21);
        $dataForSave = $referenceModelName->where("reference_number", $reference_number)->first();
        //pr($refValue->toArray());
        if ($dataForSave->subgroup_id <> $group_info_id->group_info_id || $dataForSave->sub_group_info_id <> $subgroup_id || (!in_array($dataForSave->unit_id, userUnits()))) {
            //echo "<script>window.close();</script>";
            flash("Ticket No: $reference_number is not available in your queue!!!", 'danger');
            return redirect('Supports/handler');
        }

        //$dataForSave = $referenceModelName->where('reference_number',$reference_number)->first();

        if ($dataForSave->form_status == 11) {
            //echo "<script>window.close();</script>";
            flash("Ticket No: $reference_number already closed!!!", 'danger');
            return redirect('Supports/handler');
        }

        $dataForSave->form_status = 2;
        //$dataForSave->sub_group_info_id = $subgroup_id;
        $dataForSave->access_by = $user_id;
        $dataForSave->access_date = strtotime(date('Y-m-d H:i:s'));
        if ($dataForSave->save()) {
            $this->audit(['reference_number' => $reference_number, 'unit_id' => $dataForSave->unit_id, 'group_id' => $group_info_id->group_info_id, 'user_id' => $user_id, 'action' => 'Assigned', 'comments' => '', 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '0', 'subgroup_id' => $subgroup_id]);
            flash('Successfully Assigned', 'success');
        } else {
            flash('Failed to assign', 'danger');
        }

        $searchDataForView = $request->all();
        // dd($searchDataForView);
        if ($request->activeUrl == 'non-customer') {
            $redirectUrl = "Supports/NonCustomer/" . $encryptedRefNo;
        } elseif ($request->activeUrl == 'complaint' || $request->activeUrl == 'ComplaintClosingDetails') {
            $redirectUrl = "Supports/ComplaintDetails/" . $encryptedRefNo;
        } else {
            $redirectUrl = "Supports/WFormDetails/" . $encryptedRefNo;
        }

        if (!empty($searchDataForView)) {
            $redirectUrl .= '?' . http_build_query($searchDataForView);
        }
        return redirect($redirectUrl);
    }

    public function complaint_closing_assign(Request $request, $reference_number = "")
    {
        // dd("complaint_closing_assign");
        try {
            $reference_number = decrypt($reference_number);
        } catch (DecryptException $e) {
            abort(403,'Un-Authorize Access!!!');
        }

        $referenceModelName = new Reference;
        $row = $referenceModelName->where("reference_number",$reference_number)->where('access_by','!=','')->first();
        if($row){
            flash("Ticket No: $reference_number already assigned!!!", 'danger');
            return redirect('Supports/complaintClosing');
        }
        $dataForSave = $referenceModelName->where("reference_number",$reference_number)->first();
        if($dataForSave->form_status != 12){
            flash("Ticket No: $reference_number has not been resolved !!!", 'danger');
            return redirect('Supports/complaintClosing');
        }
        //$dataForSave->form_status = 2;
        $dataForSave->access_by = Auth::user()->user_id;
        $dataForSave->access_date = strtotime(date('Y-m-d H:i:s'));
        if ($dataForSave->save()) {
            $this->audit(['reference_number'=>$reference_number,'unit_id'=>$dataForSave->unit_id,'group_id'=>195,'user_id'=>Auth::user()->user_id,'action'=>'Assigned','comments'=>'','form_load'=>$request->st, 'isapproved'=>'0', 'subgroup_id'=>385]);
            flash('Successfully Assigned', 'success');
        } else {
            flash('Failed to assign', 'danger');
        }
        return redirect()->back();
    }

    public function unassign(Request $request, $reference_number = "")
    {
        try {
            $reference_number = decrypt($reference_number);
        } catch (DecryptException $e) {
            abort(403, 'Un-Authorize Access!!!');
        }

        $user_id = Auth::user()->user_id;
        $subgroup_id = Auth::user()->user_unit->subgroup_info_id;
        $group_info_id = SubgroupInfo::find($subgroup_id);

        $referenceModelName = new Reference;
        $dataForSave = $referenceModelName->where('reference_number', $reference_number)->first();
        $dataForSave->access_by = NULL;

        $label = "Un-Assigned";

        $auditAction = "unassign";
        if (!empty($request->reqFrom)) {
            $dataForSave->form_status = 0;
            $label = "Unhold";
            $auditAction = "unhold";
        } else {
            $dataForSave->form_status = 0;
            $label = "Un-Assigned";
            $auditAction = "unassign";
        }
        // $dataForSave->access_date = NULL;
        //print_r($dataForSave);die;
        if ($dataForSave->save()) {
            $this->audit(['reference_number' => $reference_number, 'unit_id' => $dataForSave->unit_id, 'group_id' => $group_info_id->group_info_id, 'user_id' => Auth::user()->user_id, 'action' => $auditAction, 'comments' => '', 'isapproved' => '0', 'subgroup_id' => $subgroup_id]);
            flash('Succuessfully ' . $label, 'success');
        } else {
            flash('Failed to ' . $label, 'danger');

        }

        $searchDataForView = $request->all();
        unset($searchDataForView['reqFrom']);
        $redirectUrl = "Supports/handler";
        if (!empty($searchDataForView)) {
            $redirectUrl .= '?' . http_build_query($searchDataForView);
        }
        return redirect($redirectUrl);
    }

    public function uploadNewAttachment(AttachmentUploadRequest $request)
    {

        /* Document Upload Process */
        $reference_number = "";
        try {
            $reference_number = decrypt($request->reference_number);
        } catch (DecryptException $e) {
            abort(403, 'Un-Authorize Access!!!');
        }
        $attachmentModel = new Attachment;
        $attachmentDataObj = $attachmentModel->select('file_name')->where('reference_number', $reference_number)->orderBy('id', 'desc')->first();

        $lastSequence = 0;
        /*if (!empty($attachmentDataObj)) {
            $lastFileName = $attachmentDataObj->file_name;
            $lastSequence = explode('.', explode('_', $lastFileName)[3])[0];
        }*/

        if (!empty($attachmentDataObj)) {
            $lastFileName = $attachmentDataObj->file_name;
            if (!empty($lastFileName)) {
                $lastFileNameArr = explode('.', $lastFileName);
                if (!empty($lastFileNameArr)) {
                    array_pop($lastFileNameArr);
                    $lastDotPartStr = end($lastFileNameArr);
                    $lastFileNameArr2 = explode('_', $lastDotPartStr);
                    if (!empty($lastFileNameArr2)) {
                        $lastSequence = end($lastFileNameArr2);
                    }
                }
            }
        }

        $attachmentHistoryCount = AttachmentHistory::where('reference_number', $reference_number)->where('user_id', Auth::id())->get();

        $docDestPath = 'public/attachments';
        if (!empty($request->file('file_name'))) {
            foreach ($request->file('file_name') as $key => $files) {
                $extension = $files->getClientOriginalExtension();
                $origin_name = pathinfo($files->getClientOriginalName(), PATHINFO_FILENAME);
                $origin_name = str_replace(' ', '_', $origin_name);
                $origin_name = substr($origin_name, 0, 20);
                $fileName = $origin_name . "_attach_nX_" . round(microtime(true) * 10) . "_" . (++$lastSequence) . '.' . $extension;

                $attachment = new Attachment();
                $attachment->file_name = $fileName;
                $attachment->reference_number = $reference_number;
                $attachment->attachment_date = date('Y-m-d');
                $attachment->uploaded_by = Auth::user()->id;
                $attachment->save();
                //$files->move($docDestPath, $fileName);

                $fileContent = File::get($files->getRealPath());
                Storage::disk('custom_storage')->put($fileName, $fileContent);

                /*
                $image                   =       $files;
                $img                     =       ImageResizer::make($image->path());

                // --------- [ Resize Image ] ---------------
                $imgInfo = $img->resize(150, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($docDestPath.'/'.$fileName);
                */

                $attachment_history = AttachmentHistory::create([
                    'reference_number' => $reference_number,
                    'user_id' => Auth::id(),
                    'attachment_count' => 1,

                ]);
            }
            flash('New Attachment have been uploaded', 'success');
        }



        $redirect_url = (!empty($request->redirect_url)) ? $request->redirect_url : "";
        if (!empty($redirect_url)) {
            return redirect($redirect_url);
        } else {
            return Redirect::back();
        }
    }

    public function workingOnHandler(WorkingOnHanderRequest $request)
    {
        // dd($request->all());
        $reference_number = "";
        $duration_in_minutes = 0;
        $redirect_url = "Supports/handler";

        $flash_message = "Status has been changed successfully.";

        try {
            $reference_number = decrypt($request->reference_number);
            $duration_in_minutes = decrypt($request->qd);
        } catch (DecryptException $e) {
            abort(403, 'Un-Authorize Access!!!');
        }

        /*******************************
        Form Action table merged with Comments table. That's why this action go to function audit();

        $commentModel = new Comment;
        $commentModel->reference_number = $reference_number;
        $commentModel->comments = $request->comments;
        $commentModel->time = strtotime(date('Y-m-d H:i:s'));
        $commentModel->comments_person = Auth::user()->user_id;
        $commentModel->save();

         **********************************/

        $referenceModelName = new Reference;
        $referenceModelObj = $referenceModelName->where("reference_number", $reference_number)->first();
        $issueworkflow = \App\IssueWorkflow::where('issue_id', $referenceModelObj->issue_id)->first();

        $referenceModelObj->access_by = Auth::user()->user_id;
        $referenceModelObj->access_date = strtotime(date('Y-m-d H:i:s'));

        //$subgroup_id_check = Auth::user()->user_unit->subgroup_info_id;
        //$group_info_id_check = SubgroupInfo::find($subgroup_id_check);

        $access_by_check = $referenceModelName->where("reference_number", $reference_number)->where('access_by', '<>', Auth::user()->user_id)->first();
        //pr($access_by_check);
        if (!empty($access_by_check)) {
            //echo "<script>window.close();</script>";
            if ($request->submit == "forwardToSource") {
                return 2;
            } else {
                flash("Ticket No: $reference_number is not available in your queue!!!", 'danger');
                return redirect('Supports/handler');
            }
        }

        if ($referenceModelObj->form_status == 11) {
            //echo "<script>window.close();</script>";
            flash("Ticket No: $reference_number already closed!!!", 'danger');
            return redirect('Supports/handler');
        }

        if ($request->submit == "sendBack") {
            $this->validate($request, [
                'group_id' => 'required',
                //'group_id.required' => 'Please select Send Back Group.',
            ]);

            $groupSubgroupID = "";
            if (!empty($request->group_id)) {
                $groupSubgroupID = explode(',', $request->group_id);
            }
            //dd($request->all());
            $flash_message = "Ticket No: $reference_number have been Send Back successfully.";
            if (empty($request->group_id)) {
                return redirect()->back();
            }
            $referenceModelObj->form_status = 0;
            $referenceModelObj->unit_id = 1;
            $referenceModelObj->access_by = "";
            $referenceModelObj->access_date = "";
            $referenceModelObj->subgroup_id = $groupSubgroupID[0];
            $referenceModelObj->sub_group_info_id = $groupSubgroupID[1];

            $redirect_url = "Supports/handler" . $request->searchedParam;
            //Form Status entry

            $sqlFormStatusTime = \Illuminate\Support\Facades\DB::table('group_info')->where('group_info.id', $request->group_id)->pluck('name')->take(1)->toArray();
            $groupName = '';
            foreach ($sqlFormStatusTime as $value) {
                $groupName = $value;
            }
            $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelObj->unit_id, 'group_id' => $groupSubgroupID[0], 'user_id' => Auth::user()->user_id, 'action' => "Send Back to " . $groupName . " Maker", 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '1', 'issendback' => '1', 'subgroup_id' => $groupSubgroupID[1]]);
            // Local Customer Interface Group ID 203 & UAT 190
            if ($groupSubgroupID[0] == 190) {
                $referenceModelObj->form_status = 7;
                if($referenceModelObj->issues_from == 'complaint'){
                    $wformModel = new Complaint;
                } else {
                    $wformModel = new WForm;
                }
                $wformObj = $wformModel->where('reference_number', $reference_number)->first();
                $this->sendBackNotifyWithReason($reference_number, $referenceModelObj->issue_id, $wformObj->mobile_number, $wformObj->email_address);
            }
        }
        if ($request->submit == "sendBackRegular") {


            $flash_message = "Ticket No: $reference_number have been Send Back successfully.";
            if (empty($request->group_id_reqular)) {
                return redirect()->back();
            }
            $referenceModelObj->form_status = 0;
            $referenceModelObj->unit_id = 1;
            $referenceModelObj->access_by = "";
            $referenceModelObj->access_date = "";
            $referenceModelObj->subgroup_id = $request->group_id_reqular;
            $referenceModelObj->sub_group_info_id = $request->subgroup_id;

            // $unitList = Auth::user()->user_unit;
            // if (!empty($unitList)) {
            //     $getSubGroupId = $unitList->subgroup_info_id;
            //     $referenceModelObj->sub_group_info_id = $getSubGroupId;
            // }

            $redirect_url = "Supports/handler" . $request->searchedParam;
            //Form Status entry

            $sqlFormStatusTime = \Illuminate\Support\Facades\DB::table('group_info')->where('group_info.id', $request->group_id_reqular)->pluck('name')->take(1)->toArray();
            $groupName = '';
            foreach ($sqlFormStatusTime as $value) {
                $groupName = $value;
            }

            // $this->form_status($reference_number,0,22);
            $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelObj->unit_id, 'group_id' => $request->group_id_reqular, 'user_id' => Auth::user()->user_id, 'action' => "Send Back to " . $groupName . " Maker", 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '1', 'issendback' => '1', 'subgroup_id' => $request->subgroup_id]);
            if ($request->group_id_reqular == 190) {
                $referenceModelObj->form_status = 7;
                if($referenceModelObj->issues_from == 'complaint'){
                    $wformModel = new Complaint;
                } else {
                    $wformModel = new WForm;
                }
                $wformObj = $wformModel->where('reference_number', $reference_number)->first();
                $this->sendBackNotifyWithReason($reference_number, $referenceModelObj->issue_id, $wformObj->mobile_number, $wformObj->email_address);
            }

        }

        /* =================== Approved =================== */
        if ($request->submit == "approved") {

            $flash_message = "Ticket No: $reference_number have been forwarded successfully.";
            $unit = UserUnit::where('user_id', Auth::id())->first();
            $subgroup_id = Auth::user()->user_unit->subgroup_info_id;
            $group_info_id = SubgroupInfo::find($subgroup_id);

            //dd($request);
            // pr($group_info_id->toArray());
            // pr($subgroup_id);
            //prd(is_priority());
            // prd($request->all());
            if (!empty($request->memo)) {
                $referenceModelObj->memo = $request->memo;
                if ($request->memo == "Other" && (!empty($request->memo_other))) {
                    $referenceModelObj->memo = $request->memo_other;
                }
            }

            $unitIdArray = explode(",", $unit->unit_id);

            if (is_priority() == 1 && in_array(1, $unitIdArray) && $referenceModelObj->unit_id == 1) {
                //echo 'bad'; die;
                $group_id = WorkFlowService::workFlowStep($reference_number, $group_info_id->group_info_id);
                $sub_group_id = WorkFlowService::workFlowSubGroup($reference_number, $group_info_id->group_info_id);
                $unit_id = WorkFlowService::workFlowUnit($reference_number, $group_info_id->group_info_id);

                //pr(is_priority());
                // pr($group_id);
                // pr($sub_group_id);
                // prd($unit_id);

                if (!empty($group_id) && !empty($sub_group_id) && !empty($unit_id) && $group_id != is_priority() && $referenceModelObj->unit_id != 1) {

                    $referenceModelObj->form_status = 0;
                    $referenceModelObj->unit_id = $unit_id;
                    $referenceModelObj->access_by = "";
                    $referenceModelObj->access_date = "";
                    $referenceModelObj->subgroup_id = $group_id;
                    $referenceModelObj->sub_group_info_id = $sub_group_id;

                } else {
                    // Muajjam Hossain if Checker off going to ticket in checker ---- off heare
                    /*$group_id = WorkFlowService::workFlowStep($reference_number, $group_info_id->group_info_id);
                    $sub_group_id = WorkFlowService::workFlowSubGroup($reference_number, $group_info_id->group_info_id);
                    $unit_id = WorkFlowService::workFlowUnit($reference_number, $group_info_id->group_info_id);
                    $referenceModelObj->subgroup_id = $group_id;
                    $referenceModelObj->sub_group_info_id = $sub_group_id;
                    $referenceModelObj->unit_id = $unit_id;


                    // $referenceModelObj->unit_id = 2;
                    $referenceModelObj->access_by = "";
                    $referenceModelObj->access_date = "";*/

                    $referenceModelObj->form_status = 0;
                    $referenceModelObj->unit_id = 2;
                    $referenceModelObj->access_by = "";
                    $referenceModelObj->access_date = "";
                }
                $redirect_url = "Supports/handler" . $request->searchedParam;
                //Form Status entry
                // $this->form_status($reference_number,0,23);
                $this->audit(['reference_number' => $reference_number, 'unit_id' => 2, 'group_id' => $group_info_id->group_info_id, 'user_id' => Auth::user()->user_id, 'action' => Session::get('subgroupStr') . " Approved", 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '1', 'subgroup_id' => $subgroup_id]);

            } else {
                if (!empty($request->subflow_type_group_id)) {
                    $group_id = $request->subflow_type_group_id;
                    $sub_group_id = WorkFlowService::subFlowSubGroup($group_id);
                    $unit_id = WorkFlowService::subFlowUnit($reference_number, $group_id);
                } else {
                    $group_id = WorkFlowService::workFlowStep($reference_number, $group_info_id->group_info_id);
                    $sub_group_id = WorkFlowService::workFlowSubGroup($reference_number, $group_info_id->group_info_id);
                    $unit_id = WorkFlowService::workFlowUnit($reference_number, $group_info_id->group_info_id);
                }

                $referenceModelObj->form_status = 0;
                $referenceModelObj->unit_id = $unit_id;
                $referenceModelObj->access_by = "";
                $referenceModelObj->access_date = "";
                $referenceModelObj->subgroup_id = $group_id;
                $referenceModelObj->sub_group_info_id = $sub_group_id;
                $redirect_url = "Supports/handler" . $request->searchedParam;
                //Form Status entry
                // $this->form_status($reference_number,0,23);
                $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelObj->unit_id, 'group_id' => $group_id, 'user_id' => Auth::user()->user_id, 'action' => Session::get('subgroupStr') . " Approved", 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '1', 'subgroup_id' => $sub_group_id]);
            }

            /* ====================== Auto Assign START ====================== */
            $issueGroup = DB::table('issue_groups')->where('unit_item_id', $referenceModelObj->issue_id)->first();
            // $issueGroup = DB::table('issue_groups')->where('unit_item_id', 000000000)->first();
            if ($issueGroup) {
                $subgroupId = $sub_group_id;
                $unitId     = $referenceModelObj->unit_id;
                // base query (reuse)
                $baseQuery = IssueGroupMember::where('issue_group_id', $issueGroup->id)
                    ->where('subgroup_info_id', $subgroupId)
                    ->where('unit_id', $unitId)
                    // ->where('is_touch_point', 0)
                    ->lockForUpdate();

                // current pointer
                $current = (clone $baseQuery)->where('sequence', 1)->first();
                if ($current) {
                    // next member
                    $next = (clone $baseQuery)->where('id', '>', $current->id)->orderBy('ordering')->first();
                    // wrap around
                    if (!$next) {
                        $next = (clone $baseQuery)->orderBy('ordering')->first();
                    }
                    // rotate pointer
                    $current->update(['sequence' => 0]);
                    $next->update(['sequence' => 1]);
                } else {
                    // first assignment
                    $next = (clone $baseQuery)->orderBy('ordering')->first();
                    if ($next) {
                        $next->update(['sequence' => 1]);
                    }
                }

                if (isset($next)) {
                    $user_id = User::where('id', $next->user_id)->value('user_id');

                    $referenceModelObj->form_status = 2; // Assigned
                    $referenceModelObj->access_by = $user_id;
                    $referenceModelObj->access_date = strtotime(date('Y-m-d H:i:s'));
                    $referenceModelObj->save();

                    // comment store
                    // $subgroup_id = Auth::user()->user_unit->subgroup_info_id;
                    $group_info_id = SubgroupInfo::find($subgroupId);
                    $this->audit([
                        'reference_number' => $referenceModelObj->reference_number,
                        'unit_id' => $referenceModelObj->unit_id,
                        'group_id' => $group_info_id->group_info_id,
                        'user_id' => $user_id,
                        'action' => 'Assigned',
                        'comments' => '',
                        'duration_in_minutes' => 0,
                        // 'form_load' => $request->st,
                        'form_load' => '2025-12-21 20:53:39',
                        'isapproved' => '0',
                        'subgroup_id' => $subgroup_id
                    ]);
                }
            }
            /* ====================== Auto Assign END ====================== */

            if ($request->request_from == "wform") {
                if (!empty($request->bpid)) {
                    $bpId = BpId::where("reference_number", $reference_number)->first();

                    $bpid_number = $request->bpid_type . $request->bpid;
                    // Check duplicate BP ID in other rows
                    $exists = BpId::where('bp_id', $bpid_number)
                        ->where('id', '!=', $bpId->id)  // ignore current row
                        ->exists();

                    if ($exists) {
                        flash('This BP ID already exists. Please use a different BP ID.', 'warning');
                        return Redirect::back();
                    }

                    $bpId->bp_id = $bpid_number;
                    $bpId->save();
                }
            }

        }
        // if ($request->forward == "forward") {

        //     $this->validate($request, [
        //         'group_id' => 'required',
        //         //'group_id.required' => 'Please select Send Back Group.',
        //     ]);

        //     $flash_message = "Ticket No: $reference_number have been forwarded successfully.";
        //     $unit = UserUnit::where('user_id', Auth::id())->first();
        //     //$subgroup_id = Auth::user()->user_unit->subgroup_info_id;

        //     $group_id = $request->group_id;

        //     //echo $group_id;
        //     //$subgroup_info_id = SubgroupInfo::find($group_id);



        //     //$subgroupinfo = \Illuminate\Support\Facades\DB::table('subgroup_info')->where('group_info_id',$request->group_id);

        //     $subgroup_info_id = "";
        //     $subGroupInfoModel = new SubgroupInfo;
        //     $subgroup_info_data = $subGroupInfoModel
        //         ->select('id')
        //         ->where('group_info_id', '=', $request->group_id)
        //         ->pluck('id')
        //         ->toArray();

        //     foreach ($subgroup_info_data as $value) {

        //         $subgroup_info_id = $value;
        //     }

        //     // pr($unit->unit_id);
        //     // pr(Auth::user()->user_unit->subgroup_info_id);
        //     // pr($group_id);
        //     //prd($subgroup_info_id);

        //     $unitIdArray = explode(",", $unit->unit_id);

        //     if ($subgroup_info_id == Auth::user()->user_unit->subgroup_info_id && in_array(1, $unitIdArray)) {
        //         $unit_id = 2;
        //     } else {
        //         $unit_id = 1;
        //     }

        //     $referenceModelObj->form_status = 0;
        //     $referenceModelObj->unit_id = $unit_id;
        //     $referenceModelObj->access_by = "";
        //     $referenceModelObj->access_date = "";
        //     $referenceModelObj->subgroup_id = $group_id;
        //     $referenceModelObj->sub_group_info_id = $subgroup_info_id;

        //     $redirect_url = "Supports/handler" . $request->searchedParam;
        //     //Form Status entry
        //     // $this->form_status($reference_number,0,24);
        //     $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelObj->unit_id, 'group_id' => $group_id, 'user_id' => Auth::user()->user_id, 'action' => Session::get('subgroupStr') . " Forwarded", 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '1', 'subgroup_id' => $subgroup_info_id]);
        // }
        if ($request->forward == "forward") {
            // dd($request->all());
            // return "ok boro vai";

            // prd($request->all());
            if (!empty($request->group_id_new)) {
                $this->validate($request,[
                    'group_id_new' => 'required',
                ]);
            } else {
                $this->validate($request,[
                    'group_id' => 'required',
                ]);
            }

            $flash_message = "Ticket No: $reference_number have been forwarded successfully.";
            $unit = UserUnit::where('user_id',Auth::id())->first();
            $group_id = $request->group_id;
            $subgroup_info_id = "";
            $subGroupInfoModel = new SubgroupInfo;
            $subgroup_info_data = $subGroupInfoModel
                            ->select('id')
                            ->where('group_info_id','=',$group_id)
                            ->pluck('id')
                            ->toArray();

            foreach ($subgroup_info_data as $value) {
                $subgroup_info_id = $value;
            }

            // return $subgroup_info_id;

            if(!empty($request->group_id_new)) {
                $subgroup_info_id = $request->group_id_new;
                $subgroup_info_data = $subGroupInfoModel
                            ->select('group_info_id')
                            ->where('id','=',$request->group_id_new)
                            ->first();
                $group_id = $subgroup_info_data->group_info_id;
            }
            if ($subgroup_info_id == Auth::user()->user_unit->subgroup_info_id && $unit->unit_id == 1) {
                $unit_id = 2;
            } else {
                $unit_id = 1;
            }

            $referenceModelObj->form_status = 0;
            $referenceModelObj->unit_id = $unit_id;
            $referenceModelObj->access_by = "";
            $referenceModelObj->access_date = "";
            $referenceModelObj->subgroup_id = $group_id;
            $referenceModelObj->sub_group_info_id = $subgroup_info_id;

            $redirect_url = "Supports/handler" . $request->searchedParam;
            $action = Session::get('subgroupStr')." Forwarded";




            $this->audit(['reference_number'=>$reference_number,'unit_id'=>$referenceModelObj->unit_id,'group_id'=>$group_id,'user_id'=>Auth::user()->user_id,'action'=> $action,'comments'=>$request->comments,'duration_in_minutes'=>$duration_in_minutes,'form_load'=>$request->st, 'isapproved'=>'1', 'subgroup_id'=>$subgroup_info_id]);
        }

        if ($request->submit == "forwardToSource") {

            $flash_message = "Ticket No: $reference_number have been forwarded successfully.";

            $unit = UserUnit::where('user_id', Auth::id())->first();

            $group_id = $request->group_id;
            $subgroup_info_id = $request->subgroup_id;

            $unitIdArray = explode(",", $unit->unit_id);

            if ($subgroup_info_id == Auth::user()->user_unit->subgroup_info_id && in_array(1, $unitIdArray)) {
                $unit_id = 2;
            } else {
                $unit_id = 1;
            }

            $referenceModelObj->form_status = 0;
            $referenceModelObj->unit_id = $unit_id;
            $referenceModelObj->access_by = "";
            $referenceModelObj->access_date = "";
            $referenceModelObj->subgroup_id = $group_id;
            $referenceModelObj->sub_group_info_id = $subgroup_info_id;


            $sqlFormStatusTime = \Illuminate\Support\Facades\DB::table('group_info')->where('group_info.id', $request->group_id)->pluck('name')->take(1)->toArray();
            $groupName = '';
            foreach ($sqlFormStatusTime as $value) {
                $groupName = $value;
            }

            // $redirect_url = "Supports/handler".$request->searchedParam;
            //Form Status entry
            // $this->form_status($reference_number,0,24);

            // Local CI Group ID 203 & UAT 185
            if ($group_id == 190) {
                $referenceModelObj->form_status = 7;
                if($referenceModelObj->issues_from == 'complaint'){
                    $wformModel = new Complaint;
                } else {
                    $wformModel = new WForm;
                }
                $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelObj->unit_id, 'group_id' => $group_id, 'user_id' => Auth::user()->user_id, 'action' => Session::get('subgroupStr') . " Forward to " .$groupName, 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '1', 'subgroup_id' => $subgroup_info_id]);
                $wformObj = $wformModel->where('reference_number', $reference_number)->first();
                $this->sendBackNotifyWithReason($reference_number, $referenceModelObj->issue_id, $wformObj->mobile_number, $wformObj->email_address);
            }else{
                $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelObj->unit_id, 'group_id' => $group_id, 'user_id' => Auth::user()->user_id, 'action' => Session::get('subgroupStr') . " Forwarded", 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '1', 'subgroup_id' => $subgroup_info_id]);
            }
        }

        if ($request->submit == "non_customer_approved") {

            $flash_message = "Ticket No: $reference_number have been forwarded successfully.";
            $unit = UserUnit::where('user_id', Auth::id())->first();
            $subgroup_id = Auth::user()->user_unit->subgroup_info_id;
            $group_info_id = SubgroupInfo::find($subgroup_id);
            $group_id = $group_info_id->group_info_id;
            $unit_id = 2;
            $referenceModelObj->form_status = 0;
            $referenceModelObj->unit_id = $unit_id;
            $referenceModelObj->access_by = "";
            $referenceModelObj->access_date = "";
            $referenceModelObj->subgroup_id = $group_id;

            $getSubGroupId = "";
            $unitList = Auth::user()->user_unit;
            if (!empty($unitList)) {
                $getSubGroupId = $unitList->subgroup_info_id;
                $referenceModelObj->sub_group_info_id = $getSubGroupId;
            }

            $redirect_url = "Supports/handler" . $request->searchedParam;

            $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelObj->unit_id, 'group_id' => $group_id, 'user_id' => Auth::user()->user_id, 'action' => Session::get('subgroupStr') . " Approved", 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '1', 'subgroup_id' => $getSubGroupId]);
        }

        if ($request->submit == "reject") {
            $flash_message = "Ticket No: $reference_number have been rejected successfully.";

            $referenceModelObj->form_status = -1;
            $redirect_url = "Supports/handler" . $request->searchedParam;
            /*if ($request->request_from == "wform") {
                $redirect_url = "Supports/WFormDetails/".$request->reference_number.$request->searchedParam;
            }
            if ($request->request_from == "complaint") {
                $redirect_url = "Supports/ComplaintDetails/".$request->reference_number.$request->searchedParam;
            }*/
            $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelObj->unit_id, 'user_id' => Auth::user()->user_id, 'action' => "Reject", 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '0']);
        }
        /*if ($request->submit == "approved") {
            $flash_message = "Ref: $reference_number have been approved successfully.";
            $unit= UserUnit::where('user_id',Auth::id())->first();
            if($unit->unit_id==1){
                $unit_id=2;
            }else{
                $unit_id=1;
            }
            $referenceModelObj->unit_id =$unit_id;
            $referenceModelObj->form_status = 0;
            $referenceModelObj->access_by = "";
            $referenceModelObj->access_date = "";
            if ($request->request_from == "wform") {
                $redirect_url = "Supports/WFormDetails/".$request->reference_number.$request->searchedParam;
            }
            if ($request->request_from == "complaint") {
                $redirect_url = "Supports/ComplaintDetails/".$request->reference_number.$request->searchedParam;
            }
            $this->audit(['reference_number'=>$reference_number,'unit_id'=>$referenceModelObj->unit_id,'user_id'=>Auth::user()->user_id,'action'=>"approved",'comments'=>$request->comments,'duration_in_minutes'=>$duration_in_minutes,'form_load'=>$request->st]);
        }*/
        if ($request->submit == "hold") {
            //dd($request);

            if ($referenceModelObj->form_status == 10) {
                //echo "<script>window.close();</script>";
                flash("Ticket No: $reference_number already held up!!!", 'danger');
                return redirect('Supports/handler');
            }

            $flash_message = "Ticket No: $reference_number have been held up successfully.";

            $unitList = Auth::user()->user_unit;

            //$user_id = Auth::user()->user_id;
            $subgroup_id = Auth::user()->user_unit->subgroup_info_id;
            $group_info_id = SubgroupInfo::find($subgroup_id);

            // $getSubGroupId = "";
            // if (!empty($unitList)) {
            //     $getSubGroupId = $unitList->subgroup_info_id;
            //     $referenceModelObj->sub_group_info_id = $getSubGroupId;
            // }

            $referenceModelObj->form_status = 10;

            $redirect_url = "Supports/handler" . $request->searchedParam;

            // if ($request->request_from == "wform") {
            //     $redirect_url = "Supports/WFormDetails/".$request->reference_number.$request->searchedParam;
            // }
            // if ($request->request_from == "complaint") {
            //     $redirect_url = "Supports/ComplaintDetails/".$request->reference_number.$request->searchedParam;
            // }

            //Form Status entry
            // $this->form_status($reference_number,0,25);
            $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelObj->unit_id, 'group_id' => $group_info_id->group_info_id, 'user_id' => Auth::user()->user_id, 'action' => "Hold", 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '0', 'subgroup_id' => $subgroup_id]);
        }
        if ($request->submit == "resolve") {
                $flash_message = "Ticket No: $reference_number have been Resolved successfully.";
                $subgroup_id = Auth::user()->user_unit->subgroup_info_id;
                $group_info_id = SubgroupInfo::find($subgroup_id);
		
		// UAT id subgroup_id = 111 & production id
		// UAT sub_group_info_id = 140 & production id 
                $referenceModelObj->subgroup_id = 111;
                $referenceModelObj->sub_group_info_id = 140;
                $referenceModelObj->access_by = "";
                $referenceModelObj->access_date = "";
                $referenceModelObj->form_status = 12;
                $redirect_url = "Supports/handler" . $request->searchedParam;


                $wFormModelName = new Complaint;
                $dataObj = $wFormModelName->select("mobile_number", "email_address", "complaint_type AS req_type")->where("reference_number", $reference_number)->first();
                $wFormModelName = $wFormModelName->where("reference_number", $reference_number)->first();

                $wFormModelName->com_summary = $request->com_summary;
                $wFormModelName->com_root_cause = $request->com_root_cause;
                $wFormModelName->action_taken = $request->action_taken;
                if (!empty($request->action_date)) {
                    try {
                        $wFormModelName->action_date = Carbon::createFromFormat('d-m-Y', $request->action_date)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $wFormModelName->action_date = null;
                    }
                } else {
                    $wFormModelName->action_date = null;
                }
                $wFormModelName->save();


                if (empty($request->closenotification)) {
                    //do nothing
                    //echo 'not send'; die;
                } else {
                    //echo 'send';die;
                    if ($request->request_from != "non-customer") {
                        if ($issueworkflow->execute == 1) {
                            //echo 'good';die;
                            $outgoingSMSMessage = $this->outgoingSMSEmail($request->request_from, $referenceModelObj->issue_id, $reference_number, "close", "");
                            //print_r($outgoingSMSMessage);
                            if (!empty($outgoingSMSMessage['sms'])) {
                                $this->sendSMS($dataObj->mobile_number, $outgoingSMSMessage['sms'], $reference_number, 11);
                            }
                            if (!empty($outgoingSMSMessage['mail']) && (!empty($dataObj->email_address))) {
                                $this->sendEMAIL($dataObj->email_address, $outgoingSMSMessage['mail'], $reference_number, 11);
                            }
                        }
                    }
                }

                //dd($request);
                //die;

                //Form Status entry
                // $this->form_status($reference_number,0,26);
                // dd($referenceModelObj->form_status);
                $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelObj->unit_id, 'group_id' => $group_info_id->group_info_id, 'user_id' => Auth::user()->user_id, 'action' => "Resolve", 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '1', 'subgroup_id' => $subgroup_id]);

        }
        if ($request->submit == "close") {
            $flash_message = "Ticket No: $reference_number have been closed successfully.";

            //dd($request);
            $subgroup_id = Auth::user()->user_unit->subgroup_info_id;
            $group_info_id = SubgroupInfo::find($subgroup_id);

            // $getSubGroupId = "";
            // if (!empty($unitList)) {
            //     $getSubGroupId = $unitList->subgroup_info_id;
            //     $referenceModelObj->sub_group_info_id = $getSubGroupId;
            // }

            $referenceModelObj->form_status = 11;
            $redirect_url = "Supports/handler" . $request->searchedParam;

            if ($request->request_from == "wform") {
                // $redirect_url = "Supports/WFormDetails/".$request->reference_number.$request->searchedParam;
                $wFormModelName = new WForm;
                $dataObj = $wFormModelName->select("mobile_number", "email_address", "w_form_type AS req_type")->where("reference_number", $reference_number)->first();

            }
            if ($request->request_from == "complaint") {
                // $redirect_url = "Supports/ComplaintDetails/".$request->reference_number.$request->searchedParam;
                $wFormModelName = new Complaint;
                $dataObj = $wFormModelName->select("mobile_number", "email_address", "complaint_type AS req_type")->where("reference_number", $reference_number)->first();

                $wFormModelName = $wFormModelName->where("reference_number", $reference_number)->first();
                //$wFormModelName->attribute_pin = (!empty($request->attribute_pin))? $request->attribute_pin : '';
                // $wFormModelName->is_justified = (!empty($request->is_justified))? $request->is_justified : '0';
                //dd($wFormModelName);
                $wFormModelName->save();
            }

            if (empty($request->closenotification)) {
                //do nothing
                //echo 'not send'; die;
            } else {
                //echo 'send';die;
                if ($request->request_from != "non-customer") {
                    if ($issueworkflow->execute == 1) {
                        //echo 'good';die;
                        $outgoingSMSMessage = $this->outgoingSMSEmail($request->request_from, $referenceModelObj->issue_id, $reference_number, "close", "");
                        //print_r($outgoingSMSMessage);
                        if (!empty($outgoingSMSMessage['sms'])) {
                            $this->sendSMS($dataObj->mobile_number, $outgoingSMSMessage['sms'], $reference_number, 11);
                        }
                        if (!empty($outgoingSMSMessage['mail']) && (!empty($dataObj->email_address))) {
                            $this->sendEMAIL($dataObj->email_address, $outgoingSMSMessage['mail'], $reference_number, 11);
                        }
                    }
                }
            }

            //dd($request);
            //die;

            //Form Status entry
            // $this->form_status($reference_number,0,26);
            $this->audit(['reference_number' => $reference_number, 'unit_id' => $referenceModelObj->unit_id, 'group_id' => $group_info_id->group_info_id, 'user_id' => Auth::user()->user_id, 'action' => "Close", 'comments' => $request->comments, 'duration_in_minutes' => $duration_in_minutes, 'form_load' => $request->st, 'isapproved' => '0', 'subgroup_id' => $subgroup_id]);
        }

        if ($referenceModelObj->save()) {
            //prd($request->submit);
            flash($flash_message, 'success');
            if ($request->submit == "sendBack" || $request->submit == "sendBackRegular" || $request->submit == "approved" || $request->forward == "forward" || $request->submit == "non_customer_approved" || $request->submit == "reject" || $request->submit == "hold" || $request->submit == "close") {
                //echo "<script>window.close();</script>";
                //prd($redirect_url);
                //flash('Successfully Done', 'success');
                return redirect($redirect_url);
            } elseif ($request->submit == "forwardToSource") {
                return 1;
            } else {
                return redirect($redirect_url);
            }

        } else {
            if ($request->submit == "forwardToSource") {
                return 0;
            }
            flash('Failed to change status', 'danger');
            return Redirect::back();
        }
    }

    public function sendSendBackSMS(Request $request)
    {
        $response = array();
        $response['success'] = 0;

        $comment_id = 0;
        $ref_no = '';
        $mobile_no = '';
        $email_address = '';
        $issue_name = '';

        try {
            $encrypted_comment_id = xss_cleaner($request->comment_id);
            $encrypted_ref_no = xss_cleaner($request->ref_no);
            $encrypted_mobile_no = xss_cleaner($request->mobile_no);
            $encrypted_email_address = xss_cleaner($request->email_address);
            $issue_name = xss_cleaner($request->issue_name);


            $comment_id = decrypt($encrypted_comment_id);
            $ref_no = decrypt($encrypted_ref_no);
            $mobile_no = decrypt($encrypted_mobile_no);
            $email_address = decrypt($encrypted_email_address);
        } catch (DecryptException $e) {
            $response['success'] = 0;
            return $response;
        }



        $outgoingSMSMessage = $this->outgoingSMSEmail("sendback", "", $ref_no, "", $issue_name);

        if (!empty($outgoingSMSMessage['sms']) && !empty($mobile_no)) {
            $this->sendSMS($mobile_no, $outgoingSMSMessage['sms'], $ref_no);
        }
        if (!empty($outgoingSMSMessage['mail']) && !empty($email_address)) {
            $this->sendEMAIL($email_address, $outgoingSMSMessage['mail'], $ref_no);
        }


        $commentModel = new Comment();
        $commentData = $commentModel->where('id', $comment_id)->first();
        $commentData->sendbacksms = 1;
        if ($commentData->save()) {
            $response['success'] = 1;
        }

        return $response;
    }

    public function apiUpdate(Request $request)
    {
        $response = array();
        $response['success'] = 0;
        $response['msg'] = '';

        $ref_no = '';
        $memo = '';
        $req_from = '';


        try {
            $encrypted_ref_no = xss_cleaner($request->ref_no);
            // $memo  = xss_cleaner($request->memo);
            $req_from = xss_cleaner($request->req_from);
            $ref_no = decrypt($encrypted_ref_no);
        } catch (DecryptException $e) {
            $response['success'] = 0;
            return $response;
        }

        $referenceModel = new Reference();
        $referenceData = $referenceModel->where('reference_number', $ref_no)->where('api_status', 1)->first();
        if (!empty($referenceData)) {
            $response['success'] = 0;
            $response['msg'] = 'API already updated!!!. Please refresh this page';
            return $response;
        }

        $dataObj = array();
        $dataForView = array();
        if ($req_from == "wform") {
            $wCompModel = new Reference;
            $dataObj = $wCompModel
                ->select('w_form.*', 'w_form_type.extra_field', 'reference.issue_id', 'reference.memo')
                ->with(['issueConfigForApi'])
                ->leftJoin('w_form', 'reference.reference_number', '=', 'w_form.reference_number')
                ->leftJoin('w_form_type', 'w_form_type.reference_number', '=', 'w_form.reference_number')
                ->where('reference.reference_number', $ref_no)
                ->first();

        } elseif ($req_from == "complaint") {
            $wCompModel = new Reference;
            $dataObj = $wCompModel
                ->select('complaint.*', 'complaint_form_type.extra_field', 'reference.issue_id', 'reference.memo')
                ->with(['issueConfigForApi'])
                ->leftJoin('complaint', 'reference.reference_number', '=', 'complaint.reference_number')
                ->leftJoin('complaint_form_type', 'complaint_form_type.reference_number', '=', 'complaint.reference_number')
                ->where('reference.reference_number', $ref_no)
                ->first();
        }

        if (!empty($dataObj)) {
            $dataForSave = array();
            $dataForArr = $dataObj->toArray();

            $extraField = json_decode($dataForArr['extra_field'], true);
            $extraFieldIdx = array();


            $memo = $dataObj->memo;


            for ($i = 0; $i < count($extraField); $i++) {
                foreach ($extraField[$i] as $extFldKey => $extFldVal) {
                    if ($extFldVal != "") {
                    }
                    $extraFieldIdx[$extFldKey] = $extFldVal;
                }
            }


            $dataForApiTemplate = array();
            $restrictNullKey = "";

            $apiTemplate = DB::table('api_template')->where('status', 1)->first();
            if (!empty($apiTemplate->json_node)) {
                $dataForApiTemplate = json_decode($apiTemplate->json_node, true);
                $restrictNullKey = $apiTemplate->restrict_null_key;
            }

            if (!empty($dataForArr['issue_config_for_api'])) {
                foreach ($dataForArr['issue_config_for_api'] as $key => $value) {
                    $labelName = $value['label_name'];
                    $apiKey = $value['api_key'];
                    if (isset($extraFieldIdx[$labelName])) {
                        $extraFieldVal = $extraFieldIdx[$labelName];
                        $apiKeyArr = explode(":", $apiKey);

                        if (strpos($restrictNullKey, $apiKeyArr[0]) !== false) {
                            if (empty($extraFieldIdx[$labelName])) {
                                continue;
                            }
                        }

                        if (!empty($apiKeyArr[0]) && !empty($apiKeyArr[1])) {
                            $dataForSave[$apiKeyArr[0]][$apiKeyArr[1]] = $extraFieldVal;
                        } else {
                            if (!empty($apiKeyArr[0]) && empty($apiKeyArr[1])) {
                                $dataForSave[$apiKeyArr[0]] = $extraFieldVal;
                            }
                        }
                    }
                }
            }

            $dataForSave2 = array();
            $dataForSave2['memo'] = $memo;
            $dataForSave2['account_number'] = $dataObj->account_number;
            $dataForSave2['individual_acct_no'] = $dataObj->individual_acct_no;
            $dataForApi = array();
            if (!empty($dataForApiTemplate)) {
                $dataForApi = json_encode(apiGenerator($dataForApiTemplate, $dataForSave, $dataForSave2), JSON_FORCE_OBJECT);
            }
        }

        // prd($dataForApi);
        // Fetch to update Data
        $returnResponse = 0;
        // $ch = curl_init('//1http:0.5.13.166:7001/bracws/rs/omnicmsws/customerupdate/json');
        // curl_setopt( $ch, CURLOPT_POSTFIELDS, $dataForApi);
        // curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        // curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        // $ch = curl_init('https://testfep.bracbank.com/bracws/rs/omnicmsws/customerupdate/json'); // Test URL
        // $ch = curl_init('https://bbl-fepweb-dc.bracbank.com/bracws/rs/omnicmsws/customerupdate/json'); // Live URL
        // $response['msg'] = $dataForApi;
        // return $response['success']  = 1;
        $api_credential = DB::table('api_credential')->first();
        // $vv = $api_credential->Card_Push_Cert_URL;
        // prd($api_credential);
        // $response['success']  = 0;
        // $response['msg'] = $dataForApi; //$api_credential->Card_Push_Cert_URL;
        // return $response;
        // $good="";
        $this->osbApiRequestResponse(['reference_number' => $ref_no, 'account_number' => $dataObj->account_number, 'type' => 5, 'url' => $api_credential->Card_Push_API_URL, 'json_node' => $dataForApi]);

        $ch = curl_init($api_credential->Card_Push_API_URL);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataForApi);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        //curl_setopt($ch, CURLOPT_VERBOSE , true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        if (empty($api_credential->Card_Push_Cert_URL)) {

        } else {
            curl_setopt($ch, CURLOPT_PORT, "443");
        }
        // curl_setopt ($ch, CURLOPT_CAINFO, "/etc/pki/tls/certs/testfep.bracbank.com.crt"); // Test Cert
        // curl_setopt ($ch, CURLOPT_CAINFO, "/etc/pki/tls/certs/bbl-fepweb-dc.bracbank.com.crt"); //Live Cert
        if (empty($api_credential->Card_Push_Cert_URL)) {
            // $good="hhhhhhhhhhhhhhhhh";
        } else {
            // $good="ooooooooooooooooooo";
            curl_setopt($ch, CURLOPT_CAINFO, $api_credential->Card_Push_Cert_URL); //testfep.bracbank.com.crt
        }

        // $response['success']  = 0;
        // $response['msg'] = $good; //$api_credential->Card_Push_Cert_URL;
        // return $response;
        // $good="";

        $result = curl_exec($ch);
        curl_close($ch);
        $arrayVal = json_decode($result, TRUE);
        // echo 'dgdfgdfgdg<pre>';
        // print_r($arrayVal);
        if (!empty($arrayVal)) {
            if (!empty($arrayVal['customerUpdateDataResp']['customerUpdateDetResp']['customerId'])) {
                $returnResponse = 1;
            } elseif (!empty($arrayVal['headerOut']['errorOutList']['error'][0])) {
                $returnResponse = 0;
                $response['msg'] = $arrayVal['headerOut']['errorOutList']['error'][0]['errorMsg'];
            } else {
                $returnResponse = 0;
                $response['msg'] = "Invalid Response";
            }
            // $returnResponse = $arrayVal['customerUpdateDataResp']['customerUpdateDetResp']['customerId'];
        }
        //end fetch
        $this->osbApiRequestResponse(['reference_number' => $ref_no, 'account_number' => $dataObj->account_number, 'type' => 55, 'url' => $api_credential->Card_Push_API_URL, 'json_node' => $result, 'status_code' => $returnResponse, 'status_msg' => $response['msg']]);

        if ($returnResponse > 0) {
            $referenceModel = new Reference();
            $referenceData = $referenceModel->where('reference_number', $ref_no)->first();
            $referenceData->api_status = 1;
            if ($referenceData->save()) {
                $response['success'] = 1;
            }
        } else {
            $response['success'] = 0;
        }
        return $response;
    }

    public function deleteAttachment(Request $request)
    {
        $response = array();
        $response['success'] = 0;

        $attachmentModel = new Attachment;
        $atdDataObj = $attachmentModel->where([['id', $request->attchid], ['uploaded_by', Auth::user()->id]])->first();
        if (!empty($atdDataObj)) {
            $file_name = $atdDataObj->file_name;
            $uploaded_by = $atdDataObj->uploaded_by;
            $reference_number = $atdDataObj->reference_number;

            $attachmentHistoryModel = new AttachmentHistory;
            $atdDataHistoryObj = $attachmentHistoryModel->where([['reference_number', $reference_number], ['user_id', $uploaded_by]])->first();
            if (!empty($atdDataHistoryObj)) {
                $atdDataHistoryObj->delete();
            }

            if (Storage::disk('custom_storage')->delete($file_name)) {
                $response['success'] = 1;
                flash('Attachment:' . $file_name . ' have been deleted successfully.', 'success');
            }

            /*$removePath = 'public/attachments/'.$file_name;

            if (File::exists($removePath)) {
                File::delete($removePath);
                $response['success'] = 1;
                flash('Attachment:'.$file_name.' have been deleted successfully.','success');
            }*/

            $atdDataObj->delete();
        }

        return $response;
    }

    public function printForm(Request $request, $stype)
    {

        $dataForPrint = $request->all();
        // dd($dataForPrint);
        //prd($stype);
        // 2 array use here. 1 is customer info and another is ticket
        unset($dataForPrint['_token']);
        if (!empty($dataForPrint)) {
            // dd($dataForPrint);
            // session('dataForPrint',$dataForPrint);
            session(['dataForPrint' => $dataForPrint]);
            return 1;
        } else {
            // dd("kdsac");
            return view('Supports.printform', compact('stype'));
        }
    }

    // print Bp Id Btn
    public function printBpIdBtn(Request $request, $stype)
    {

        // dd($request->all());
        // return $request->all();
        // Request data convert into object
        $raw = (object) $request->except('_token');

        $docDestPath = 'public/temp_attachments';

        // Create directory if not exists
        if (!File::exists($docDestPath)) {
            File::makeDirectory($docDestPath, 0755, true);
        }

        // Remove all existing files inside temp_attachments
        $files = File::files($docDestPath);
        foreach ($files as $file) {
            File::delete($file);
        }

        // Dynamic file upload fields detection
        $uploadedFiles = [];
        // Loop through all request data to find uploaded files
        foreach ($request->all() as $key => $value) {
            // Check if this is a file upload field
            if ($request->hasFile($key)) {
                $uploadedFiles[] = $key;
            }
        }

        foreach ($uploadedFiles as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // Clean file name
                $name = substr(str_replace(' ', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 0, 20);

                // Unique file name
                $fileName = $name . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Move file
                $file->move($docDestPath, $fileName);

                // Save path dynamically
                $raw->{$field . '_path_url'} = $docDestPath . '/' . $fileName;
            } else {
                $raw->{$field . '_path_url'} = '';
            }
        }

        return view('Supports.printBpIdform', compact('raw', 'stype'));
    }

    // print Bpid ticket details
    public function printBpIdTicketDetails(Request $request)
    {

        $data = json_decode($request->other_data, true);
        $bpIdData = BpId::where('reference_number', $data['reference_number'])->first();
        
		if (!($bpIdData)) {
             flash('BP ID data not found for the given reference number.', 'danger');
             return redirect()->back();
         } 
		 
        // Raw JSON list
        $extraFields = json_decode($data['extra_field'], true);
        // Custom key rename mapping
        $map = [
            'name_of_the_account' => 'account_name',
            "mother's_name"       => 'mother_name',
            "father's_name"       => 'father_name',
            'date_of_birth'       => 'dob',
        ];

        $nomineeCounter = 0;
        $nomineeStarted = false;
        // Convert array → clean key/value set
        $cleanData = array_reduce($extraFields, function ($carry, $item) use (&$nomineeCounter, &$nomineeStarted) {

            $key = array_key_first($item);
            $value = $item[$key];

            // Normalize
            $normalized = strtolower(trim(preg_replace('/[^A-Za-z0-9 ]/', '', $key)));
            $slug = Str::slug($normalized, '_');

            // Fix: % Payable → percentage_payable
            if ($slug === 'payable') {
                $slug = 'percentage_payable';
            }

            // Nominee fields list
            $isNomineeField = in_array($slug, [
                'nominee_name',
                'nid',
                'passport',
                'birth_certificate_no',
                'address',
                'relation_with_account_holder',
                'date_of_birth',
                'percentage_payable'
            ]);

            // Detect nominee start
            if ($slug === 'routing_no') {
                $nomineeStarted = true;
                $carry['applicant_' . $slug] = $value;
                return $carry;
            }

            if ($nomineeStarted && $slug == 'nominee_name') {
                $nomineeCounter++; // 1st & 2nd nominee
            }

            // Map keys
            if ($nomineeStarted && $isNomineeField) {

                if ($nomineeCounter == 1) {
                    // Primary Nominee
                    $finalKey = 'nominee_' . $slug;
                } elseif ($nomineeCounter == 2) {
                    // Second Nominee
                    $finalKey = 'sec_nominee_' . $slug;
                } else {
                    // In case more nominees exist
                    $finalKey = 'nominee_' . $slug;
                }
            } else {
                // Applicant fields → no override
                $finalKey = 'applicant_' . $slug;
            }

            // Date fix
            if (str_contains($finalKey, 'date_of_birth')) {
                $value = date("Y-m-d", strtotime($value));
            }

            $carry[$finalKey] = $value;
            return $carry;
        }, []);

        $raw = (object) $cleanData;
        $raw->bpid = $bpIdData->bp_id ?? '';
	    $raw->create_time = $bpIdData->created_at->format('Y-m-d');

        // Attachment
        $attachments = Attachment::where('reference_number', $data['reference_number'])->get()->groupBy('name');


        $attachmentMap = [];
        $genericAttachments = [];


        foreach ($attachments as $attachmentName => $attachmentGroup) {
            if (!empty($attachmentGroup) && $attachmentGroup->isNotEmpty()) {
                $file = $attachmentGroup->first();

                $genericAttachments[$attachmentName] = $file->file_name;
                $normalizedName = strtolower(trim($attachmentName));

                if (str_contains($normalizedName, 'passport image')) {
                    if (str_contains($normalizedName, 'two')) {
                        $attachmentMap['applicant_2_passport'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'third')) {
                        $attachmentMap['applicant_3_passport'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'fourth')) {
                        $attachmentMap['applicant_4_passport'] = $file->file_name;
                    } else {
                        $attachmentMap['applicant_1_passport'] = $file->file_name;
                    }
                } elseif (str_contains($normalizedName, 'signature img')) {
                    if (str_contains($normalizedName, 'two')) {
                        $attachmentMap['applicant_2_signature'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'third')) {
                        $attachmentMap['applicant_3_signature'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'fourth')) {
                        $attachmentMap['applicant_4_signature'] = $file->file_name;
                    } else {
                        $attachmentMap['applicant_1_signature'] = $file->file_name;
                    }
                } elseif (str_contains($normalizedName, 'nom signature')) {
                    if (str_contains($normalizedName, 'f nom') || str_contains($normalizedName, 'first')) {
                        $attachmentMap['nominee_1_signature'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 's nom') || str_contains($normalizedName, 'second')) {
                        $attachmentMap['nominee_2_signature'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 't nom') || str_contains($normalizedName, 'third')) {
                        $attachmentMap['nominee_3_signature'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'four nom') || str_contains($normalizedName, 'fourth')) {
                        $attachmentMap['nominee_4_signature'] = $file->file_name;
                    }
                }
		
		elseif (str_contains($normalizedName, 'nom image')) {
                    if (str_contains($normalizedName, 'first nom') || str_contains($normalizedName, 'first')) {
                        $attachmentMap['nominee_1_image'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'second nom') || str_contains($normalizedName, 'second')) {
                        $attachmentMap['nominee_2_image'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'third nom') || str_contains($normalizedName, 'third')) {
                        $attachmentMap['nominee_3_image'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'fourth nom') || str_contains($normalizedName, 'fourth')) {
                        $attachmentMap['nominee_4_image'] = $file->file_name;
                    }
                }


            }
        }

        // raw object attach
        foreach ($attachmentMap as $key => $fileName) {
            $raw->{$key} = $fileName;
        }

        //return $raw;

        return view('Supports.printBpIdTicketDetails', compact('raw'));
    }


    public function printAuctionTicketDetails(Request $request, $stype = null){
        // Main ticket data
        $data = json_decode($request->other_data, true);

        // extra_field is a JSON string containing array of single-key objects
        $extraFields = json_decode($data['extra_field'], true) ?? [];

        // Flatten [{"key":"value"}, {"key2":"value2"}, ...] into ["key" => "value", ...]
        $flat = [];
        foreach ($extraFields as $item) {
            foreach ($item as $key => $value) {
                // normalize key: trim + collapse multiple spaces
                $normalizedKey = preg_replace('/\s+/', ' ', trim($key));
                $flat[$normalizedKey] = $value;
            }
        }

        // Map normalized field labels -> $raw property names used in blade
        $fieldMap = [
            'BP ID'                  => 'bp_id',
            'Account No'             => 'account_number',
            'Branch Name'            => 'branch_name',
            'Account Title'          => 'account_title',
            '1st Applicant Mobile'   => 'first_app_mobile',
            '1st Applicant Email'    => 'first_app_email',
            '2nd Applicant Mobile'   => 'second_app_mobile',
            '2nd Applicant Email'    => 'second_app_email',
            '3rd Applicant Mobile'   => 'third_app_mobile',
            '3rd Applicant Email'    => 'third_app_email',
            '4th Applicant Mobile'   => 'fourth_app_mobile',
            '4th Applicant Email'    => 'fourth_app_email',
            'Treasury Type'          => 'treasury_type', 
            'Bills'                  => 'treasury_bills',
            'Bonds'                  => 'treasury_bonds',
            'Sukuk'                  => 'sukuk',           
            'FRTB'                   => 'frtb', 
            'Bidding Month'          => 'bidding_month',
            'Bidding Date'           => 'bidding_date',
            'Bidding Amount'         => 'bidding_amount',
            'Bidding Type'           => 'bidding_type',
            'Competitive Rate'       => 'competitive_rate',
        ];

        $raw = new \stdClass();
        foreach ($fieldMap as $label => $property) {
            $raw->{$property} = $flat[$label] ?? '';
        }

        // Bidding amount in words
        $raw->bidding_amount_words = $this->numberToWords($raw->bidding_amount);

        // Ticket reference number (for header, if blade needs it)
        $raw->reference_number = $data['reference_number'] ?? '';

        // BPID lookup (same as before)
        $bpid = BpId::where('bp_id', $raw->bp_id)->first();
        if (!$bpid) {
            flash('No BPID found for this account number.', 'danger');
            return redirect()->back();
        }

        // Attachments (signature images)
        // $attachments = Attachment::where('reference_number', $bpid->reference_number)->get()->groupBy('name');
        $attachments = Attachment::where('reference_number', $bpid->reference_number)
        ->get()
        ->groupBy('name')
        ->map(function ($items) {
            return collect([$items->sortByDesc('id')->first()]);
        });

        $attachmentMap = [];
        foreach ($attachments as $attachmentName => $attachmentGroup) {
            if ($attachmentGroup->isNotEmpty()) {
                $file = $attachmentGroup->first();
	        $normalizedName = strtolower(trim($attachmentName));



                if (str_contains($normalizedName, 'signature img')) {
                    if (str_contains($normalizedName, 'two')) {
                        $attachmentMap['applicant_2_signature'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'third')) {
                        $attachmentMap['applicant_3_signature'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'fourth')) {
                        $attachmentMap['applicant_4_signature'] = $file->file_name;
                    } else {
                        $attachmentMap['applicant_1_signature'] = $file->file_name;
                    }
                }
            }
        }

        foreach ($attachmentMap as $key => $fileName) {
            $raw->{$key} = $fileName;
        }

        $treasury_bills = [
            ['id' => 1, 'name' => '91 Days'],
            ['id' => 2, 'name' => '182 Days'],
            ['id' => 3, 'name' => '364 Days'],
            ['id' => 4, 'name' => 'Others'],
        ];
        $treasury_bounds = [
            ['id' => 1, 'name' => '2 Years'],
            ['id' => 2, 'name' => '5 Years'],
            ['id' => 3, 'name' => '10 Years'],
            ['id' => 4, 'name' => '15 Years'],
            ['id' => 5, 'name' => '20 Years'],
        ];

	    //return $raw;

        return view('Supports.printAuctionRequestform', compact('raw', 'treasury_bills', 'treasury_bounds', 'stype'));
    }

    // Auction Print Form
    public function printAuctionRequestBtn(Request $request, $stype)
    {

        //return $request->all();

        // Request data convert into object
        $raw = (object) $request->except('_token');
        $raw->bidding_amount_words = $this->numberToWords($raw->bidding_amount);


        // BPID
        $bpid = BpId::where('bp_id', $raw->bp_id)->first();
        if (!$bpid) {
            flash('No BPID found for this account number.', 'danger');
            return redirect()->back();
        }

        // Attachment
        $attachments = Attachment::where('reference_number', $bpid->reference_number)->get()->groupBy('name');

        $attachmentMap = [];
        $genericAttachments = [];

        foreach ($attachments as $attachmentName => $attachmentGroup) {
            if (!empty($attachmentGroup) && $attachmentGroup->isNotEmpty()) {
                $file = $attachmentGroup->first();

                $genericAttachments[$attachmentName] = $file->file_name;
                $normalizedName = strtolower(trim($attachmentName));

                if (str_contains($normalizedName, 'signature image')) {
                    if (str_contains($normalizedName, 'two')) {
                        $attachmentMap['applicant_2_signature'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'third')) {
                        $attachmentMap['applicant_3_signature'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'fourth')) {
                        $attachmentMap['applicant_4_signature'] = $file->file_name;
                    } else {
                        $attachmentMap['applicant_1_signature'] = $file->file_name;
                    }
                }
            }
        }

        // raw object attach
        foreach ($attachmentMap as $key => $fileName) {
            $raw->{$key} = $fileName;
        }


        $treasury_bills = [
            [
                'id' => 1,
                'name' => '91 Days',
            ],
            [
                'id' => 2,
                'name' => '182 Days',
            ],
            [
                'id' => 3,
                'name' => '364 Days',
            ],
            [
                'id' => 4,
                'name' => 'Others',
            ],
        ];
        $treasury_bounds = [
            [
                'id' => 1,
                'name' => '2 Years',
            ],
            [
                'id' => 2,
                'name' => '5 Years',
            ],
            [
                'id' => 3,
                'name' => '10 Years',
            ],
            [
                'id' => 4,
                'name' => '15 Years',
            ],
            [
                'id' => 5,
                'name' => '20 Years',
            ],
        ];

         //return $raw;

        return view('Supports.printAuction', compact('raw', 'treasury_bills', 'treasury_bounds', 'stype'));
    }

    
    public function numberToWords($number)
    {
        $ones = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven',
            'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen',
            'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
        ];

        $tens = [
            '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty',
            'Sixty', 'Seventy', 'Eighty', 'Ninety'
        ];

        if ($number == 0) {
            return "Zero";
        }

        return trim($this->convertNumber($number, $ones, $tens));
    }

    private function convertNumber($number, $ones, $tens)
    {
        if ($number < 20) {
            return $ones[$number];
        }

        if ($number < 100) {
            return $tens[intval($number / 10)] . ' ' . $ones[$number % 10];
        }

        if ($number < 1000) {
            return $ones[intval($number / 100)] . ' Hundred ' .
                $this->convertNumber($number % 100, $ones, $tens);
        }

        if ($number < 100000) {
            return $this->convertNumber(intval($number / 1000), $ones, $tens) .
                ' Thousand ' .
                $this->convertNumber($number % 1000, $ones, $tens);
        }

        if ($number < 10000000) {
            return $this->convertNumber(intval($number / 100000), $ones, $tens) .
                ' Lakh ' .
                $this->convertNumber($number % 100000, $ones, $tens);
        }

        return $this->convertNumber(intval($number / 10000000), $ones, $tens) .
            ' Crore ' .
            $this->convertNumber($number % 10000000, $ones, $tens);
    }

    // Complaint Closing
    public function complaintClosing(Request $request)
    {
        // dd($request->all());
        $dataForView = array();
        $searchDataForView = $request->all();
        $searchDataForView['reference_number'] = $request->ref_number;
        $searchDataForView['account_number'] = $request->account_number;
        $searchDataForView['date_from'] = $request->date_from;
        $searchDataForView['date_to'] = $request->date_to;
        $searchDataForView['service_category'] = $request->service_category;
        $searchDataForView['service_type'] = $request->service_type;
        $searchDataForView['logger'] = $request->logger;
        $searchDataForView['priority'] = $request->priority;
        $searchDataForView['form_status'] = $request->form_status;
        //$searchDataForView['last_user'] = $request->last_user;
        $searchDataForView['active_tab'] = 'complaintClosing';

        // $dataForView = array();
        // $searchDataForView = $request->all();
        // $searchDataForView['reference_number'] = $request->ref_number;
        // $searchDataForView['account_number'] = $request->account_number;
        // $searchDataForView['date_from'] = $request->date_from;
        // $searchDataForView['date_to'] = $request->date_to;
        // $searchDataForView['service_category'] = $request->service_category;
        // $searchDataForView['service_type'] = $request->service_type;
        // $searchDataForView['logger'] = $request->logger;
        // $searchDataForView['priority'] = $request->priority;
        // $searchDataForView['form_status'] = $request->form_status;
        // //$searchDataForView['last_user'] = $request->last_user;
        // $searchDataForView['active_tab'] = 'complaint';

        $searchDataForView['cmmn_pgntion'] = (!empty($request->cmmn_pgntion)) ? $request->cmmn_pgntion : 15;
        $searchDataForView['cmmn_search'] = (!empty($request->cmmn_pgntion)) ? $request->cmmn_search : '';

        $tblData = array();

        $wFormData = array();
        $wFormDataObj = array();
        $complaintData = array();
        $complaintDataObj = array();
        $nonCustomerData = array();
        $nonCustomerDataObj = array();

        $unitDeptCondition = array();

        $isAdmin = false;
        $getUnitId = "";
        $getUnitIdArr = array();
        $getSubGroupIdArr = array();
        $getDepartmentId = "";
        $getDivisionId = "";
        $getGroupId = "";
        $get_subgroup_info_id = "";


        $title = "Complaint Closing";
        $title_for_layout = "Complaint Closing";

        $mostOldDate = "";

        $todayDate = date('Y-m-d');

        $complaintModelName = new Complaint;
            $complaintDataObj = $complaintModelName
                ->select(
                    "complaint.*"
                    ,
                    "complaint.product_type as product_type_ext"
                    ,
                    "product_types.name as product_type"
                    ,
                    "unit_items.name as issue_name"
                    ,
                    "reference.unit_id"
                    ,
                    "reference.created_by"
                    ,
                    "reference.date"
                    ,
                    "reference.status"
                    ,
                    "reference.form_status"
                    ,
                    "reference.access_by"
                    ,
                    "reference.access_date"
                    ,
                    "group_info.id as group_info_id"
                    ,
                    DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d') AS UNXTIME")
                )
                ->leftJoin('reference', 'reference.reference_number', '=', 'complaint.reference_number')
                ->leftJoin('group_info', 'group_info.id', '=', 'reference.subgroup_id')
                ->leftJoin('issue_workflows', 'issue_workflows.issue_id', '=', 'reference.issue_id')
                ->leftJoin('issue_group_workflows', function ($join) {
                    $join->on('issue_group_workflows.group_info_id', '=', 'group_info.id');
                    $join->on('issue_group_workflows.issue_workflow_id', '=', 'issue_workflows.issue_workflow_id');
                })
                //->leftJoin('departments', 'departments.id', '=', 'complaint.product_type')
                ->leftJoin('product_types', 'product_types.id', '=', 'complaint.product_type')
                ->leftJoin('unit_items', function ($join) {
                    $join->on('unit_items.master_id', '=', 'complaint.complaint_type');
                    $join->on('unit_items.issues_from', '=', DB::raw("'complaint'"));
                });
                // ->whereNull('complaint.is_justified');
                // dd($complaintDataObj->get());

            if (!empty($searchDataForView['reference_number'])) {
                $complaintDataObj = $complaintDataObj->where("complaint.reference_number", $searchDataForView['reference_number']);
            }
            if (!empty($searchDataForView['account_number'])) {
                $complaintDataObj = $complaintDataObj->where("complaint.account_number", $searchDataForView['account_number']);
            }
            if (!empty($searchDataForView['service_type'])) {
                $complaintDataObj = $complaintDataObj->where("reference.issue_id", $searchDataForView['service_type']);
            }
            if (!empty($searchDataForView['logger'])) {
                $complaintDataObj = $complaintDataObj->where("reference.created_by", $searchDataForView['logger']);
            }
            // if (!empty($searchDataForView['last_user'])) {
            //     $complaintDataObj = $complaintDataObj->where("reference.access_by",$searchDataForView['last_user']);
            // }

            if (!empty($searchDataForView['date_from']) && empty($searchDataForView['date_to'])) {
                $complaintDataObj = $complaintDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), ">=", date('Y-m-d', strtotime($searchDataForView['date_from'])));
            } elseif (!empty($searchDataForView['date_to']) && empty($searchDataForView['date_from'])) {
                $complaintDataObj = $complaintDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "<=", date('Y-m-d', strtotime($searchDataForView['date_to'])));
            } elseif (!empty($searchDataForView['date_to']) && !empty($searchDataForView['date_from'])) {
                // $complaintDataObj = $complaintDataObj->whereBetween(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),array($searchDataForView['date_from'],$searchDataForView['date_to']));
                $complaintDataObj = $complaintDataObj->where(function ($q) use ($searchDataForView) {
                    $q->where(
                        DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),
                        ">=",
                        date('Y-m-d', strtotime($searchDataForView['date_from']))
                    )
                        ->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "<=", date('Y-m-d', strtotime($searchDataForView['date_to'])));
                });
            } else {
                // $complaintDataObj = $complaintDataObj->where(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"),">=", date('Y-m-d'));
            }

            if (!empty($searchDataForView['cmmn_search'])) {

                $statusNumb = array();
                if ($searchDataForView['cmmn_search'] == 'Pending') {
                    $statusNumb = [8, 0, NULL];
                } elseif ($searchDataForView['cmmn_search'] == 'Close') {
                    $statusNumb = [11];
                } elseif ($searchDataForView['cmmn_search'] == 'Reject') {
                    $statusNumb = [-1];
                } elseif ($searchDataForView['cmmn_search'] == 'Hold') {
                    $statusNumb = [10];
                } elseif ($searchDataForView['cmmn_search'] == 'Wip') {
                    $statusNumb = [1, 2, 3, 4, 5, 6, 7, 9];
                }

                $commentWhere = " comments.user_id='" . $searchDataForView['cmmn_search'] . "'";
                if ($isAdmin == false) {
                    if (!empty($searchDataForView['curr_user_id'])) {
                        $commentWhere .= ' AND comments.user_id=' . $searchDataForView['curr_user_id'];
                    }
                    if (!empty($getUnitIdArr)) {
                        $getUnitIdArrStr = implode(',', $getUnitIdArr);
                        $commentWhere .= ' AND comments.unit_id in (' . $getUnitIdArrStr . ')';
                    }
                    if (!empty($getSubGroupIdArr) || !empty($get_subgroup_info_id)) {
                        $getSubGroupIdArrStr = implode(',', $getSubGroupIdArr);
                        if ((!empty($getSubGroupIdArr) && !empty($get_subgroup_info_id))) {
                            $commentWhere .= ' AND (comments.subgroup_id=' . $get_subgroup_info_id . ' OR comments.subgroup_id IN (' . $getSubGroupIdArrStr . '))';
                        } elseif (!empty($getSubGroupIdArr)) {
                            $commentWhere .= ' AND comments.subgroup_id IN (' . $getSubGroupIdArrStr . ')';
                        } elseif (!empty($get_subgroup_info_id)) {
                            $commentWhere .= ' AND comments.subgroup_id=' . $get_subgroup_info_id;
                        }
                    }
                }

                $complaintDataObj = $complaintDataObj
                    ->where(function ($query) use ($searchDataForView, $statusNumb, $commentWhere) {
                        $query
                            ->where("reference.reference_number", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("complaint.account_number", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("complaint.customer_name", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("product_types.name", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("unit_items.name", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere(DB::raw("FROM_UNIXTIME(reference.date,'%Y-%m-%d')"), "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("complaint.time_and_ext", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhereIn("reference.form_status", $statusNumb)
                            ->orWhere("reference.created_by", "like", '%' . $searchDataForView['cmmn_search'] . '%')
                            ->orWhere("reference.access_by", "like", '%' . $searchDataForView['cmmn_search'] . '%');
                        /*->orWhereRaw("reference.reference_number IN ( SELECT comments.reference_number FROM comments LEFT JOIN reference ON (reference.reference_number = comments.reference_number) WHERE $commentWhere AND reference.form_status <> 11 )")*/
                });
            }
            // dd($complaintDataObj->get());
            // if ($isAdmin == false) {
            //     if (!empty($getUnitIdArr)) {
            //         $complaintDataObj = $complaintDataObj->whereIn("reference.unit_id", $getUnitIdArr);
            //     }
            //     $complaintDataObj = $complaintDataObj->where(function ($q) use ($getUnitIdArr, $getGroupId, $get_subgroup_info_id, $getSubGroupIdArr) {
            //         $q->where("reference.sub_group_info_id", $get_subgroup_info_id)
            //             ->orWhereIn("reference.sub_group_info_id", $getSubGroupIdArr)
            //         ;
            //     });
            // }


            $complaintDataObj = $complaintDataObj->where("reference.form_status", "<>", -7);
            $complaintDataObj = $complaintDataObj->where("reference.form_status", "<>", 11);

            if (!empty($_GET['orderby'])) {
                $orderByArr = explode('-', $_GET['orderby']);
                $orderName = (!empty($orderByArr[0])) ? $orderByArr[0] : 'DESC';

                if($orderByArr[1] == "reference.date=complaint.time_and_ext") {
                    $orderByRefArr = explode('=', $orderByArr[1]);
                    $columnsNameC = (!empty($orderByRefArr[1])) ? $orderByRefArr[1] : 'reference.reference_number';
                    $columnsName = (!empty($orderByRefArr[0])) ? $orderByRefArr[0] : 'reference.reference_number';
                    $complaintDataObj = $complaintDataObj->orderBy("reference.segment_priority", "DESC");
                    $complaintDataObj = $complaintDataObj->orderBy($columnsName, $orderName);
                    $complaintDataObj = $complaintDataObj->orderBy($columnsNameC, $orderName);
                } else {
                    $columnsName = (!empty($orderByArr[1])) ? $orderByArr[1] : 'reference.reference_number';
                    $complaintDataObj = $complaintDataObj->orderBy("reference.segment_priority", "DESC");
                    $complaintDataObj = $complaintDataObj->orderBy($columnsName, $orderName);
                }

            } else {
                // $complaintDataObj = $complaintDataObj->orderBy("reference.date", "DESC");
                $complaintDataObj = $complaintDataObj->orderBy("reference.segment_priority", "DESC")
                    ->orderBy("reference.date", "ASC");
            }

            // $complaintDataObj = $complaintDataObj
            //     ->paginate($searchDataForView['cmmn_pgntion']);
            //->get();
            // dd($complaintDataObj->get());

            // if (!empty($complaintDataObj)) {
            //     $complaintData = $complaintDataObj->toArray();
            //     //prd($complaintData);
            //     //$lastRecord = end($complaintData);
            //     $lastRecord = end($complaintData['data']);
            //     if (!empty($lastRecord)) {
            //         $mostOldDate = $lastRecord['UNXTIME'];
            //     }
            // }






        if (!empty($searchDataForView['priority'])) {
            $complaintDataObj = $complaintDataObj->where("complaint.priority", $searchDataForView['priority']);
        }
        $complaintDataObj = $complaintDataObj->whereIn("reference.form_status", [11, 12]);
        if (!empty($searchDataForView['form_status'])) {
            $complaintDataObj = $complaintDataObj->where("reference.form_status", $searchDataForView['form_status']);
        }

        $workingDays = array();
        if (!empty($mostOldDate)) {
            $workingDayModel = new WorkingDay;
            $workingDayDataObj = $workingDayModel->where([['dates', '>=', $mostOldDate], ['status', 1]])->pluck('dates', 'dates');
            if (!empty($workingDayDataObj)) {
                $workingDays = $workingDayDataObj->toArray();
            }
        }

        $workingHours = DB::table('working_hours')->first();
        $workingHours = json_decode(json_encode($workingHours), true);

        if (!empty($workingHours['office_from'])) {
            $dataForView['office_from'] = $workingHours['office_from'];
            $dataForView['office_from_str'] = substr($dataForView['office_from'], 0, 2) . ':' . substr($dataForView['office_from'], 2, 2) . ':' . substr($dataForView['office_from'], 4, 2);
        } else {
            $dataForView['office_from'] = '100000';
            $dataForView['office_from_str'] = '10:00:00';
        }
        if (!empty($workingHours['office_to'])) {
            $dataForView['office_to'] = $workingHours['office_to'];
            $dataForView['office_to_str'] = substr($dataForView['office_to'], 0, 2) . ':' . substr($dataForView['office_to'], 2, 2) . ':' . substr($dataForView['office_to'], 4, 2);
        } else {
            $dataForView['office_to'] = '180000';
            $dataForView['office_to_str'] = '18:00:00';
        }

        $settingsData = DB::table('settings')->first();








        // $complaintDataObj = $complaintDataObj
        //     ->orderBy("reference.date", "ASC")
        //     ->paginate(PAGINATION_NUMBER);


        // if (!empty($complaintDataObj)) {
        //     $complaintData = $complaintDataObj->toArray();
        //     $lastRecord = end($complaintData['data']);
        //     if (!empty($lastRecord)) {
        //         $mostOldDate = $lastRecord['UNXTIME'];
        //     }
        // }

        // prd($complaintData);
        // dd($complaintDataObj->get());
        return view('Supports.complaint_closing', compact('title', 'title_for_layout', 'tblData', 'searchDataForView', 'wFormData', 'complaintData', 'nonCustomerData', 'wFormDataObj', 'complaintDataObj', 'workingDays', 'dataForView', 'settingsData'));
    }

    // public function complaintClosingSubmit($reference_number = "", ComplaintClosingRequest $request)
    // {
    //     try {
    //         $reference_number = decrypt($reference_number);
    //     } catch (DecryptException $e) {
    //         abort(403, 'Un-Authorize Access!!!');
    //     }

    //     $complaintModel = new Complaint();
    //     $complaintModelObj = $complaintModel->where('reference_number', $reference_number)->first();

    //     $is_justified = (!empty($request->is_justified)) ? $request->is_justified : 0;
    //     $complaintModelObj->is_justified = $is_justified;

    //     $additionalParams = (!empty($request->additionalParams)) ? $request->additionalParams : "";

    //     if ($complaintModelObj->save()) {

    //         $justificationLabel = ($is_justified == 1) ? 'Justified' : 'No-Justified';

    //         $comments = 'CE-Analysis of Ref:' . $reference_number . ' have been ' . $justificationLabel;

    //         $this->audit(['reference_number' => $reference_number, 'unit_id' => 0, 'group_id' => 0, 'user_id' => Auth::user()->user_id, 'action' => 'CE-Analysis', 'comments' => $comments, 'isapproved' => '1', 'subgroup_id' => 0]);

    //         flash('Ticket No: ' . $reference_number . ' have been closed', 'success');
    //         return redirect('Supports/complaintClosing' . $additionalParams);
    //     } else {
    //         flash('Failed to update Ticket No: ' . $reference_number, 'danger');
    //         return redirect()->back();
    //     }
    // }


    public function complaintClosingSubmit($reference_number = "", ComplaintClosingRequest $request)
    {

        try {
            $reference_number = decrypt($reference_number);
        } catch (DecryptException $e) {
            $this->logToDatabase(Auth::user()->user_id??'null','error', 'Un-Authorize Access!!!');
            abort(403, 'Un-Authorize Access!!!');
        }


            $complaintClosingModel = ComplaintClosing::where('reference_number', $reference_number)->first();
            $existInReferenceModel = Reference::where("reference_number", $reference_number)->where('form_status', 11)->first();

            // ==============================  Ignore Duplicate Closing  ==============================
           if($complaintClosingModel){
               if ($existInReferenceModel) {
                   flash('Ticket No: ' . $reference_number . ' This Ticket Already Closed !!', 'danger');
                   return redirect('Supports/complaintClosing');
               }
           }else{
               $complaintClosingModel = new ComplaintClosing;
           }

            $complaintClosingModel->reference_number = $reference_number;
            $complaintClosingModel->complaint_category = $request->complaint_category;
            $complaintClosingModel->complaint_type = $request->complaint_type;
            $complaintClosingModel->subgroup_id = !empty($request->subgroup_id) ? implode(',', $request->subgroup_id) : null;
            $complaintClosingModel->emplist = $request->emplist;
            // $complaintClosingModel->fi_id = !empty($request->fi_id) ? implode(',', $request->fi_id) : null;
            $complaintClosingModel->fi_id = $request->fi_id;
            $complaintClosingModel->rootcause = $request->rootcause;
            $complaintClosingModel->actiontaken = $request->actiontaken;
            $complaintClosingModel->amountinvoled = $request->amountinvoled;
            $complaintClosingModel->justification = $request->justification;
            $complaintClosingModel->massincident = $request->massincident;
            $complaintClosingModel->impactedcustomer = $request->impactedcustomer;
            $complaintClosingModel->closenotification = $request->closenotification;
            $complaintClosingModel->closureremarks = $request->closureremarks;
            $complaintClosingModel->customerexpectation = $request->customerexpectation;
            $complaintClosingModel->natureofcomp = $request->natureofcomp;
            $complaintClosingModel->unreachable = !empty($request->unreachable) && $request->unreachable == 1 ? 'Yes' : 'No';

            // ========================== complaint closing part ==========================
            if ($request->action == 'close') {
                // Sending SMS and Email notifications
                if ($complaintClosingModel->closenotification == 'Yes') {
                    $outgoingSMSMessage = $this->outgoingSMSEmail("complaint", $request->complaint_type, $reference_number, "close", "");
                    $type = 'close';
                    if (!empty($outgoingSMSMessage['sms']) && !empty($request->mobile_number)) {
                        $this->sendSMS($request->mobile_number, $outgoingSMSMessage['sms'], $type, $reference_number);
                    }
                    if (!empty($outgoingSMSMessage['mail']) && !empty($request->email_address)) {
                        $this->sendEMAIL($request->email_address, $outgoingSMSMessage['mail'], $type, $reference_number);
                    }
                }
                if (!empty($request->unreachable) && $request->unreachable == 1) {
                    $outgoingSMSMessage = $this->outgoingSMSEmail("unreached", $request->complaint_type, $reference_number, "", "");
                    $type = 'Unreachable';
                    if (!empty($outgoingSMSMessage['sms']) && !empty($request->mobile_number)) {
                        $this->sendSMS($request->mobile_number, $outgoingSMSMessage['sms'], $type, $reference_number);
                    }
                    if (!empty($outgoingSMSMessage['mail']) && !empty($request->email_address)) {
                        $this->sendEMAIL($request->email_address, $outgoingSMSMessage['mail'], $type, $reference_number);
                    }
                }
            }

            if ($complaintClosingModel->save()) {
                if (!empty($request->file('file_name'))) {
                    foreach ($request->file('file_name') as $key => $files) {
                        $extension = $files->getClientOriginalExtension();
                        $origin_name = pathinfo($files->getClientOriginalName(), PATHINFO_FILENAME);
                        $origin_name = str_replace(' ', '_', $origin_name);
                        $origin_name = substr($origin_name, 0, 20);
                        $fileName = $origin_name . "_attach_nX_" . round(microtime(true) * 10) . "_" . ($key + 1) . '.' . $extension;
                        $attachment = new Attachment();
                        $attachment->file_name = $fileName;
                        $attachment->reference_number = $reference_number;
                        $attachment->attachment_date = date('Y-m-d');
                        $attachment->uploaded_by = Auth::user()->id;
                        $attachment->save();
                        $fileContent = File::get($files->getRealPath());
                        Storage::disk('custom_storage')->put($fileName, $fileContent);
                    }
                }
                if ($request->action == 'close') {
                    $referenceModelName = new Reference;
                    $referenceModelObj = $referenceModelName->where("reference_number", $reference_number)->first();
                    $referenceModelObj->form_status = 11;
                    $referenceModelObj->save();
                    $this->audit([
                        'reference_number' => $reference_number,
                        'unit_id' => $referenceModelObj->unit_id,
                        'group_id' => 195,
                        'user_id' => Auth::user()->user_id,
                        'action' => "Close",
                        'comments' => $request->closureremarks,
                        'form_load' => $request->st,
                        'isapproved' => '1',
                        'subgroup_id' => 385
                    ]);
                    flash('Ticket No: ' . $reference_number . ' has been closed', 'success');
                    Log::info('ComplaintClosingModel saved successfully for reference number: ' . $reference_number);
                    return redirect('Supports/complaintClosing');

                } elseif ($request->action == 'save_hold') {
                    flash('Ticket No: ' . $reference_number . ' has been saved & hold', 'success');
                    Log::info('ComplaintClosingModel saved & hold successfully for reference number: ' . $reference_number);
                    return redirect()->back();
                }
            } else {
                Log::error('Failed to save ComplaintClosingModel for reference number: ' . $reference_number);
                flash('Failed to update Ticket No: ' . $reference_number, 'danger');
                return redirect()->back();
            }

    }

    public function complaintClosingDetails($reference_number = "")
    {
        try {
            $reference_number = decrypt($reference_number);

        } catch (DecryptException $e) {
            abort(403, 'Un-Authorize Access!!!');
        }

        $title = "Complaint Details";
        $title_for_layout = "Complaint Report Details";

        $complaintModelName = new Complaint;
        $dataForViewObj = $complaintModelName
            ->select(
                "complaint.*",
                "reference.created_by",
                "reference.date",
                "reference.status",
                "reference.form_status",
                "reference.access_by",
                "reference.account_type",
                "reference.unit_id",
                "subgroup_info.name",
                "product_types.name as product_name",
                "cb_unit_items.auto_unit_id as auto_unit_id",
                "unit_items.name as issue_name",
                "unit_items.id as main_id",
                "complaint_form_type.extra_field",
                "complaint_form_type.check_list"
            )
            ->leftJoin('reference', 'reference.reference_number', '=', 'complaint.reference_number')
            ->leftJoin('subgroup_info', 'subgroup_info.id', '=', 'reference.sub_group_info_id')
            ->leftJoin('complaint_form_type', 'complaint_form_type.reference_number', '=', 'complaint.reference_number')
            ->leftJoin('product_types', 'product_types.id', '=', 'complaint.product_type')
            ->leftJoin('unit_items', function ($join) {
                $join->on('unit_items.master_id', '=', 'complaint.complaint_type');
                $join->on('unit_items.issues_from', '=', DB::raw("'complaint'"));
            })
            ->leftJoin('unit_items AS cb_unit_items', function ($join) {
                $join->on('cb_unit_items.master_id', '=', 'complaint.complaint_type');
                $join->on('cb_unit_items.issues_from', '=', DB::raw("'complaint'"));
                $join->on('cb_unit_items.unit_id', '=', 'reference.unit_id');
            })
            // ->leftJoin('unit_items', 'unit_items.master_id', '=', 'complaint.complaint_type')
            ->where("complaint.reference_number", $reference_number)
            ->first();
            // dd($dataForViewObj);

        $dataForView = array();
        if (!empty($dataForViewObj)) {
            $dataForView = $dataForViewObj->toArray();
        } else {
            abort(403, 'No Data Found');
        }

        $refNumber = $dataForView['reference_number'];

        $commentData = array();



        // $dataForView = [];
        $dataForView['account_type'] = isset($dataForViewObj['account_type']) ? $dataForViewObj['account_type'] : null;
        // dd($dataForView['account_type']);
        if (isset($dataForViewObj['account_type'])) {
            if ($dataForViewObj['account_type'] == 1) {
                $dataForView['account_number'] = isset($dataForViewObj['account_number']) ? $dataForViewObj['account_number'] : null;
            } elseif ($dataForViewObj['account_type'] == 4) {
                $dataForView['account_number'] = isset($dataForViewObj['account_number']) ? $dataForViewObj['account_number'] : null;
            } else {
                $dataForView['account_number'] = isset($dataForViewObj['account_number']) ? $dataForViewObj['account_number'] : null;
            }
        }



        $commentsModel = new Comment;
        $commentDataObj = $commentsModel
            ->select('comments.*', 'units.name as unit_name')
            ->leftJoin('units', 'units.id', 'comments.unit_id')
            ->where('comments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($commentDataObj) {
            $commentData = $commentDataObj->toArray();
        }
        $dataForView['comment'] = $commentData;

        $attachmentData = array();
        $attachmentModel = new Attachment;
        $attachmentDataObj = $attachmentModel
            ->select('id', 'file_name', 'reference_number', 'attachment_date', 'uploaded_by', 'created_at', 'updated_at')
            ->where('attachments.reference_number', 'LIKE', $refNumber)
            ->get();
        if ($attachmentDataObj) {
            $attachmentData = $attachmentDataObj->toArray();
        }
        $dataForView['complaint_attachment'] = $attachmentData;
        $issue_fields = [];
        $check_lists = [];

        //  dd($refNumber);
        $complaintClosingData = new ComplaintClosing;
        $complaintClosingData = $complaintClosingData->where('reference_number', $refNumber)->first();
        if(!empty($complaintClosingData)){
            $complaintClosingData = $complaintClosingData;
            // dd($complaintClosingData);
        } else {
            $complaintClosingData = new ComplaintClosing;
        }

        $complaint = Reference::where('reference_number', $reference_number)->first();
        $issue_data = IssueConfig::where('issue_id', $complaint->issue_id)->get();
        $check_data = IssueCheckListConfig::where('issue_id', $complaint->issue_id)->get();
        $issue_checklist_status = true;
        if (count($issue_data) == 0 && count($check_data) == 0) {
            $issue_checklist_status = false;
        }

        $fiIds = DB::table('fi_id')
            ->where('status', 1)
            ->get();

        return view('Supports.complaint_closing_details', compact('title', 'title_for_layout', 'dataForView','issue_fields','check_lists','complaintClosingData','issue_checklist_status', 'fiIds'));
    }

    /************ End of Handler Queue Group **********/
    public function sendBackNotifyWithReason($reference_number, $issue_id, $mobile_no, $email)
    {
        $outgoingSMSMessage = $this->outgoingSMSEmail("sendbackWithReason", $issue_id, $reference_number, "SendBack Notification", "");
	
        if (!empty($outgoingSMSMessage['sms']) && !empty($mobile_no)) {
            $this->sendBackSMS($mobile_no, $outgoingSMSMessage['sms'], $reference_number, 0, 1);
        }
        if (!empty($outgoingSMSMessage['mail']) && !empty($email)) {
            $this->sendBackEMAIL($email, $outgoingSMSMessage['mail'], $reference_number, 0, "SendBack Notification", 1);
        }
    }

    public function outgoingSMSEmail($supportType = "", $masterId = "", $referenceNumber = "", $notificationType = "", $issue_name = "")
    {
        $msg = array();
        $sms = "";
        $mail = "";

        $issues_from = "";
        if(!empty($referenceNumber)){
            $firstLetter = $referenceNumber[0];
            if($firstLetter == 'S'){
            $issues_from = 'wform';
            }elseif($firstLetter == 'C'){
            $issues_from = 'complaint';
            }
        }

        if (empty($issue_name)) {
            $unitItemModelName = new UnitItem;
            $unitItemData = $unitItemModelName
                ->select("master_id", "name")
                ->where("issues_from", $issues_from)
                ->where("master_id", $masterId)
                ->first();
            if (!empty($unitItemData)) {
                $issue_name = $unitItemData['name'];
            }
        }

        if ($supportType == "noncustomer") {

            $smsEmailModel = new SMSEmail();
            $smsEmailData = $smsEmailModel->orderBy('id', 'DESC')->first();

            if (!empty($smsEmailData)) {

                $sms = $smsEmailData['non_cust_sms'];
                $mail = $smsEmailData['non_cust_email'];
            }

            if (!empty($sms)) {
                $sms = str_replace("{reference_no}", $referenceNumber, $sms);
                $sms = str_replace("{form_request}", $issue_name, $sms);
                $msg['sms'] = $sms;
            }
            if (!empty($mail)) {
                $mail = str_replace("{reference_no}", $referenceNumber, $mail);
                $mail = str_replace("{form_request}", $issue_name, $mail);
                $msg['mail'] = $mail;
            }
        }
        if ($supportType == "sendback") {
            $smsEmailModel = new SMSEmail();
            $smsEmailData = $smsEmailModel->orderBy('id', 'DESC')->first();
            if (!empty($smsEmailData)) {
                $sms = $smsEmailData['send_back_sms'];
                $mail = $smsEmailData['send_back_email'];
            }
            if (!empty($sms)) {
                $sms = str_replace("{reference_no}", $referenceNumber, $sms);
                $sms = str_replace("{form_request}", $issue_name, $sms);
                $msg['sms'] = $sms;
            }
            if (!empty($mail)) {
                $mail = str_replace("{reference_no}", $referenceNumber, $mail);
                $mail = str_replace("{form_request}", $issue_name, $mail);
                $msg['mail'] = $mail;
            }
        }

        if ($supportType == "sendbackWithReason") {
            $SendBackReason = DB::table('comments')
                ->select('reference_number', 'action' ,'comments', 'issendback', 'isapproved')
                ->where('reference_number',$referenceNumber)
                ->where('issendback',1)
                ->where('isapproved',1)
                ->latest()
                ->first();
            $smsEmailModel = new SMSEmail();
            $smsEmailData = $smsEmailModel->orderBy('id','DESC')->first();
            if (!empty($smsEmailData)) {
                $sms = $smsEmailData['send_back_sms'];
                $mail = $smsEmailData['send_back_email'];
            }

            if (!empty($sms)) {
                $sms = str_replace("{reference_no}", $referenceNumber, $sms);
                $sms = str_replace("{form_request}", $issue_name, $sms);
                $sms = str_replace("{Send_back_reason}", $SendBackReason->comments ?? '', $sms);
                $msg['sms'] = $sms;
            }

            if (!empty($mail)) {
                $mail = str_replace("{reference_no}", $referenceNumber, $mail);
                $mail = str_replace("{form_request}", $issue_name, $mail);
                $mail = str_replace("{Send_back_reason}", $SendBackReason->comments ?? '', $mail);
                $msg['mail'] = $mail;
            }
        }

        if ($supportType == "sendbackWithOutReason") {
            $smsEmailModel = new SMSEmail();
            $smsEmailData = $smsEmailModel->orderBy('id','DESC')->first();

            if (!empty($smsEmailData)) {
                $sms = $smsEmailData['send_back_auto_sms'];
                $mail = $smsEmailData['send_back_auto_email'];
            }

            if (!empty($sms)) {
                $sms = str_replace("{reference_no}", $referenceNumber, $sms);
                $sms = str_replace("{form_request}", $issue_name, $sms);
                $msg['sms'] = $sms;
            }

            if (!empty($mail)) {
                $mail = str_replace("{reference_no}", $referenceNumber, $mail);
                $mail = str_replace("{form_request}", $issue_name, $mail);
                $msg['mail'] = $mail;
            }
        }

        if (!empty($issue_name)) {

            $smsEmailModel = new SMSEmail();
            $smsEmailData = $smsEmailModel->orderBy('id', 'DESC')->first();

            if (!empty($smsEmailData)) {
                if ($notificationType == "open") {
                    if ($supportType == "wform") {
                        $sms = $smsEmailData['issue_opening_sms_wform'];
                        $mail = $smsEmailData['issue_opening_email_wform'];
                    } elseif ($supportType == "complaint") {
                        $sms = $smsEmailData['issue_opening_sms_complaint'];
                        $mail = $smsEmailData['issue_opening_email_complaint'];
                    }
                } elseif ($notificationType == "close") {
                    if ($supportType == "wform") {
                        $sms = $smsEmailData['issue_closing_sms_wform'];
                        $mail = $smsEmailData['issue_closing_email_wform'];
                    } elseif ($supportType == "complaint") {
                        $sms = $smsEmailData['issue_closing_sms_complaint'];
                        $mail = $smsEmailData['issue_closing_email_complaint'];
                    }
                }
            }

            if (!empty($sms)) {
                $sms = str_replace("{reference_no}", $referenceNumber, $sms);
                $sms = str_replace("{form_request}", $issue_name, $sms);
                $msg['sms'] = $sms;
            }
            if (!empty($mail)) {
                $mail = str_replace("{reference_no}", $referenceNumber, $mail);
                $mail = str_replace("{form_request}", $issue_name, $mail);
                $msg['mail'] = $mail;
            }
        }

        return $msg;
    }

    public function sendSMS($mobile_no, $msg, $ref_no = "", $supp_status = NULL)
    {

        date_default_timezone_set('Asia/Dhaka');
        $savedtime = date("Y-m-d H:i:s");
        $mobile_no_1 = str_replace("+88(00)", "+88", $mobile_no);
        $mnumber = formatMobileNumber($mobile_no_1);
        if ($mnumber != "") {
            if (is_numeric($mnumber) && strlen($mnumber) == 14) {
                $outgoingSMSModel = new OutgoingSMS;
                $outgoingSMSModel->sentSMSid = 0;
                $outgoingSMSModel->message = $msg;
                $outgoingSMSModel->savetime = $savedtime;
                $outgoingSMSModel->senttime = '';
                $outgoingSMSModel->status = '3';
                $outgoingSMSModel->support_status = $supp_status;
                $outgoingSMSModel->mobileNo = $mnumber;
                $outgoingSMSModel->reference_number = $ref_no;
                $outgoingSMSModel->save();
            }
        }
    }

    public function sendBackSMS($mobile_no, $msg, $ref_no = "", $supp_status = NULL, $sendbackStatus = 0)
    {
        date_default_timezone_set('Asia/Dhaka');
        $savedtime = date("Y-m-d H:i:s");
        $mobile_no_1 = str_replace("+88(00)", "+88", $mobile_no);
        $mnumber = formatMobileNumber($mobile_no_1);
        if($mnumber != "") {
            if(is_numeric($mnumber) && strlen($mnumber) == 14) {
                $outgoingSMSModel = new OutgoingSMS;
                $outgoingSMSModel->sentSMSid = 0;
                $outgoingSMSModel->send_back_status = $sendbackStatus;
                $outgoingSMSModel->message = $msg;
                $outgoingSMSModel->savetime = $savedtime;
                $outgoingSMSModel->senttime = '';
                $outgoingSMSModel->status = '3';
                $outgoingSMSModel->support_status = $supp_status;
                $outgoingSMSModel->mobileNo = $mnumber;
                $outgoingSMSModel->reference_number = $ref_no;
                $outgoingSMSModel->save();
            }
        }
    }

    public function sendEMAIL($email_address, $mail, $ref_no = "", $supp_status = NULL)
    {
        date_default_timezone_set('Asia/Dhaka');
        $savedtime = date("Y-m-d H:i:s");
        if ($mail != "") {
            $outgoingEMAILModel = new OutgoingEMAIL;
            $outgoingEMAILModel->subject = 'BBL Support';
            $outgoingEMAILModel->body = $mail;
            $outgoingEMAILModel->savetime = $savedtime;
            $outgoingEMAILModel->senttime = '';
            $outgoingEMAILModel->status = '3';
            $outgoingEMAILModel->support_status = $supp_status;
            $outgoingEMAILModel->email_address = $email_address;
            $outgoingEMAILModel->reference_number = $ref_no;
            $outgoingEMAILModel->save();
        }
    }

    public function sendBackEMAIL($email_address, $mail, $ref_no = "", $supp_status = NULL, $subject = null, $sendbackStatus = 0)
    {
        date_default_timezone_set('Asia/Dhaka');
        $savedtime = date("Y-m-d H:i:s");
        if($mail != "") {
            $outgoingEMAILModel = new OutgoingEMAIL;
            $outgoingEMAILModel->subject = $subject;
            $outgoingEMAILModel->send_back_status = $sendbackStatus;
            $outgoingEMAILModel->body = $mail;
            $outgoingEMAILModel->savetime = $savedtime;
            $outgoingEMAILModel->senttime = '';
            $outgoingEMAILModel->status = '3';
            $outgoingEMAILModel->support_status = $supp_status;
            $outgoingEMAILModel->email_address = $email_address;
            $outgoingEMAILModel->reference_number = $ref_no;
            $outgoingEMAILModel->save();
        }
    }

    public function audit($params = array())
    {
        $params['duration_in_minutes'] = (!empty($params['duration_in_minutes'])) ? $params['duration_in_minutes'] : 0;

        if (!empty($params['form_load'])) {
            $formLoadTimeObj = new DateTime($params['form_load']);
            $currentTimeObj = new DateTime(date('Y-m-d H:i:s'));
            $interval = $formLoadTimeObj->diff($currentTimeObj);

            $hoursPenalty = $interval->format('%h');
            $minutesPenalty = $interval->format('%i');

            $totalPenalty = ($hoursPenalty * 60) + $minutesPenalty;

            $params['duration_in_minutes'] = $params['duration_in_minutes'] + $totalPenalty;

        }

        $commentModel = new Comment;
        $commentModel->reference_number = (!empty($params['reference_number'])) ? $params['reference_number'] : '';
        $commentModel->comments = (!empty($params['comments'])) ? $params['comments'] : '';
        $commentModel->time = strtotime(date('Y-m-d H:i:s'));
        $commentModel->user_id = (!empty($params['user_id'])) ? $params['user_id'] : 0;
        $commentModel->unit_id = (!empty($params['unit_id'])) ? $params['unit_id'] : 0;
        $commentModel->group_id = (!empty($params['group_id'])) ? $params['group_id'] : 0;
        $commentModel->action = (!empty($params['action'])) ? $params['action'] : 'INVALID';
        $commentModel->duration_in_minutes = $params['duration_in_minutes'];
        $commentModel->isapproved = (!empty($params['isapproved'])) ? $params['isapproved'] : 0;
        $commentModel->issendback = (!empty($params['issendback'])) ? $params['issendback'] : 0;
        $commentModel->subgroup_id = (!empty($params['subgroup_id'])) ? $params['subgroup_id'] : 0;
        $commentModel->ip = $this->getClientIp();
        $commentModel->save();
    }

    public function form_status($reference_number, $issue_id = 0, $form_status = 0)
    {
        //Form Status entry
        /*
        $fromStatus = FormStatus::create([
            'reference_number'=>$reference_number,
            'issue_id'=> $issue_id,
            'user_id'=>Auth::id(),
            'in_time'=>strtotime(date("Y-m-d h:i:sa")),
            'form_status'=>$form_status,
            'is_sendback'=>0
        ]);
        return $fromStatus;
        */
        return;
    }

    public function dayWiseSequence($sequenceFrom = "")
    {
        $sequenceModel = new Sequence($sequenceFrom);
        $lastSequence = $sequenceModel->where('created_at', '<', date('Y-m-d'))->orderBy('id', 'DESC')->first();
        if (!empty($lastSequence)) {
            $sequenceModel->truncate();
        }

        $sequenceModel = new Sequence($sequenceFrom);
        $sequenceModel->save();

        return $sequenceModel->id;
    }

    public function getAllTouchSubGroups($sel = '')
    {
        // Local Customer Interface Group ID 162 & UAT 185
        $rows = GroupInfo::select('subgroup_info.id', 'group_info.id AS group_id', 'subgroup_info.name')
            ->join('subgroup_info', 'subgroup_info.group_info_id', 'group_info.id')
            ->where('group_info.group_level_id', '1')
            ->where('subgroup_info.is_active', '1')
            /*->whereNot('group_info.id', 185)*/
            ->orderBy('subgroup_info.name', 'ASC')
            ->get();

        /*$subgroupList = (!empty(Auth::user()->user_unit)) ? Auth::user()->user_unit->subgroup_info_id : '';*/

        $opt = '';
        foreach ($rows as $v) {

            /*if($v->id != $subgroupList){*/
                $attr = ($v->id == $sel) ? ' selected="selected"' : '';
                $opt .= '<option value="' . $v->id . '" group-id="' . $v->group_id . '" ' . $attr . ' >' . $v->name . '</option>';
           /* }*/
        }

        return $opt;
    }

    public function newDummyWForm(Request $request)
    {
        //prd($request->toArray());
        $dataForView = array();
        $title = "New Dummy Service Request";
        $title_for_layout = "New Dummy Service Request";

        $dataForView['account_type'] = 1;
        $dataForView['account_number'] = '123456';
        $dataForView['reference_number'] = 'S00000000000000001';
        $dataForView['customer_name'] = 'Dummy Customer';
        $dataForView['mobile_number'] = '01000000000';
        $dataForView['def_email_addr'] = 'dummy@test.com';
        $dataForView['CIF_number'] = '123';
        $dataForView['SegmentCode'] = '000';
        $dataForView['cb_fin_acctno'] = '54321';
        $dataForView['card_status'] = 'DM';
        $dataForView['date_of_birth'] = '';

        $productTypeModelName = new ProductType;
        $allProductTypeData = $productTypeModelName
            ->select('id', 'name')
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->pluck('name', 'id')
            ->toArray();

        /*$unitModelName = new Unit;
        $allUnitData = $unitModelName
                        ->select("id","name")
                        ->where("status","1")
                        ->whereNotIn('id', [1,2,21])
                        ->pluck("name","id")
                        ->toArray();*/

        $unitItemModelName = new UnitItem;
        $allUnitItemData = $unitItemModelName
            ->select("master_id", "name")
            ->where("status", "1")
            ->where("issues_from", "wform")
            ->orderBy("name")
            ->pluck("name", "master_id")
            ->toArray();

        $sourceModelName = new Source;
        $allSourceData = $sourceModelName
            ->select("id", "source_name")
            ->pluck("source_name", "source_name")
            ->toArray();

        $attachment_item = 0;
        $issue_fields = [];
        $check_lists = [];
        $type = "";

        return view('Supports.new_dummy_wform', compact('title', 'title_for_layout', 'dataForView', 'allProductTypeData', 'allUnitItemData', 'allSourceData', 'attachment_item', 'issue_fields', 'check_lists', 'type'));
    }

    public function submitDummyWform(WFormDummyRequest $request)
    {
        flash('Service Request have been saved successfully. Ticket No: S00000000000000001', 'success');
        return redirect('Supports/NewDummyWForm');
    }

    public function newDummyComplaint(Request $request)
    {
        //dd($request);
        $dataForView = array();
        $title = "New Complaint";
        $title_for_layout = "New Complaint";

        $dataForView['account_type'] = 1;
        $dataForView['account_number'] = '123456';
        $dataForView['reference_number'] = 'C00000000000000001';
        $dataForView['customer_name'] = 'Dummy Customer';
        $dataForView['mobile_number'] = '01000000000';
        $dataForView['def_email_addr'] = 'dummy@test.com';
        $dataForView['CIF_number'] = '123';
        $dataForView['SegmentCode'] = '000';
        $dataForView['cb_fin_acctno'] = '54321';
        $dataForView['card_status'] = 'DM';
        $dataForView['date_of_birth'] = '';


        $productTypeModelName = new ProductType;
        $allProductTypeData = $productTypeModelName
            ->select('id', 'name')
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->pluck('name', 'id')
            ->toArray();

        $sourceModelName = new Source;
        $allSourceData = $sourceModelName
            ->select("id", "source_name")
            ->pluck("source_name", "source_name")
            ->toArray();
        $unitModelName = new Unit;
        $allUnitData = $unitModelName
            ->select("id", "name")
            ->where("status", "1")
            ->whereNotIn('id', [1, 2, 21])
            ->pluck("name", "id")
            ->toArray();

        $unitItemModelName = new UnitItem;
        $allUnitItemData = $unitItemModelName
            ->select("master_id", "name")
            ->where("status", "1")
            ->where("issues_from", "complaint")
            ->pluck("name", "master_id")
            ->toArray();

        $attachment_item = 0;
        $issue_fields = [];
        $check_lists = [];
        $type = "";
        return view('Supports.new_dummy_complaint', compact('title', 'title_for_layout', 'dataForView', 'allProductTypeData', 'allUnitData', 'allUnitItemData', 'allSourceData', 'attachment_item', 'issue_fields', 'check_lists', 'type'));
    }

    public function submitDummyComplaint(ComplaintDummyRequest $request)
    {
        flash('Complaint have been saved successfully. Ticket No: C00000000000000001', 'success');
        return redirect('Supports/NewDummyComplaint');
    }

    public function ccSearchResult(Request $request)
    {
        // dd($request->all());
        $datas = [];
        return view('Supports.customer_multiple_card', compact('datas'));
    }

    public function osbApiRequestResponse($params = array())
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