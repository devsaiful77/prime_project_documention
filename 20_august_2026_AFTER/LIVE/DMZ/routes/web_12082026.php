<?php
// Auth::routes();
// Authentication Routes...

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\IRISApiController;
use App\Http\Controllers\SegmentCodeController;



Auth::routes([
   'reset' => false,
   'verify' => false,
   'register' => false,
]);


Route::get('/prime-session-clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    return redirect('/');
    // return view('auth/login');
});

Route::get('encrypt-decrypt', 'EncryptDecryptController@index')->name('encrypt-decrypt.index');

 /** CI routes start here **/

Route::group(['namespace' => 'CI'], function() {
    Route::get('/', ['uses' => 'CustomerInterfaceController@index'])->name('CI.index');
    Route::get('/CI-call', ['uses' => 'ApiCallingController@api']);
    //Route::get('/access-CI', ['uses' => 'CustomerInterfaceController@accessCI']);
    Route::post('CI/callback-url', ['uses' => 'CustomerInterfaceController@callbackUrl'])->name('CI.back-to-home');
    Route::get('CI/dashboard', ['uses' => 'CustomerInterfaceController@dashboard'])->name('CI.dashboard');
});

Route::group(['namespace' => 'CI', 'middleware' => 'record_last_activity'], function() {
   
    Route::get('CI/attachment-download/{filename}', ['uses' => 'CustomerInterfaceController@attachmentDownload'])->name('CI.attachment-download');
});

Route::group(['namespace' => 'CI', 'middleware' => ['ci_auth','record_last_activity']], function () {

    Route::get('CI/service', ['uses' => 'CustomerInterfaceController@service'])->name('CI.service');
    Route::get('CI/service-type', ['uses' => 'CustomerInterfaceController@serviceType'])->name('CI.service-type');

    /**** Zihad 8/7/2023 *****/
    Route::post('CI/issue-attachment', ['uses' => 'CustomerInterfaceController@CIissueAttachment']);
    Route::post('CI/newWForm', ['uses' => 'CustomerInterfaceController@CIWFormSubmit'])->middleware(['throttle:5,1']);
    Route::post('CI/newComplaint', ['uses' => 'CustomerInterfaceController@CIComplaintFormSubmit'])->middleware(['throttle:5,1']);
    Route::post('CI/otp-verify', ['uses' => 'CustomerInterfaceController@OtpVerifyPage']);
    Route::post('CI/issue-extra-form', ['uses' => 'CustomerInterfaceController@issueFormField'])->name('CI.issue-extra-form');

    /*** Muajjem ***/
    Route::get('CI/service/details', ['uses' => 'CustomerInterfaceController@serviceDetails'])->name('CI.service-details');
    Route::get('CI/send-back/details', ['uses' => 'CustomerInterfaceController@sendBackDetails'])->name('CI.send-back-details');
    Route::get('CI/ticket/status/details', ['uses' => 'CustomerInterfaceController@ticketStatusDetails'])->name('CI.ticket-status-details');
    Route::get('CI/account/verify/{product_type}/{request_type}', ['uses' => 'CustomerInterfaceController@accountVerify'])->name('CI.account-verify');
    Route::get('CI/send-back/ticket/{issueId}/{refNum}/{viewMode}', ['uses' => 'CustomerInterfaceController@sendBackTicket'])->name('CI.send-back.ticket');
    Route::post('CI/otp/re-generate', ['uses' => 'CustomerInterfaceController@otpReGenerate'])->name('CI.otp.re-generate');
    Route::post('CI/otp/request/submit', ['uses' => 'CustomerInterfaceController@otpSubmit'])->name('CI.otp-submit');
    Route::post('CI/otp/request/submit/page', ['uses' => 'CustomerInterfaceController@otpSubmitPage'])->name('CI.otp-submit-page');

    /**** Abdur Rahman ****/
    Route::post('CI/submit/feedback', ['uses' => 'CustomerInterfaceController@feedbackStore'])->name('CI.submit_feedback');
    Route::get('CI/complaint-sendback-status', ['uses' => 'CustomerInterfaceController@complaintSendBackStatus'])->name('CI.complaint-send-back-status');
    Route::get('CI/complaint-ticket-status', ['uses' => 'CustomerInterfaceController@comaplaintTicketStatus'])->name('CI.comaplaint-ticket-status');
    Route::get('CI/comaplaint-send-back/ticket/{issueId}/{refNum}/{viewMode}', ['uses' => 'CustomerInterfaceController@complaintSendBackTicket'])->name('CI.comaplaint-send-back-ticket');
    Route::post('CI/complaint-update', ['uses' => 'CustomerInterfaceController@CIComplaintUpdate'])->name('CI.complaint-update');
    Route::post('CI/attachment-remover', ['uses' => 'CustomerInterfaceController@attachmentRemover'])->name('CI.attachment.remover');

    /************ Mahdi 30-July-2023 ***********/
    Route::get('CI/issue/conditional/fields/{issue_id}/{id}', ['middleware' => ['auth'], 'uses' => 'ConditionalFieldController@issueConditionalFields']);
    Route::get('CI/issue/dependant/fields/{issue_id}/{value}/{id}', ['middleware' => ['auth'], 'uses' => 'ConditionalFieldController@issueDependantFields']);
    Route::post('CI/updateWForm', ['uses' => 'CustomerInterfaceController@CIWFormUpdate'])->middleware(['throttle:5,1']);
    Route::get('CI/issue/conditional/{issue_id}', ['middleware' => ['auth'], 'uses' => 'ConditionalFieldController@issueConditional']);
});

/** END **/
