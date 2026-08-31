<?php
// Auth::routes();
// Authentication Routes...

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\IRISApiController;
use App\Http\Controllers\SegmentCodeController;
use App\Http\Controllers\BpIdController;
use Illuminate\Support\Facades\Session;

/** Ballance Inquery  API start here **/
Route::post('/ballance/inquery', 'BallanceInQueryController@inquery')->middleware('auth')->name('ballance.inquery');
// Route::post('/ballance/inquery', ['middleware' => ['auth'], 'uses' => 'BallanceInQueryController@inquery'])->name('ballance.inquery');

Route::get('/sendEscalationEmail', ['middleware' => [], 'uses' => 'EscalationEmailController@sendEscalationEmail']);
Route::get('/sendBackNotification', ['middleware' => [], 'uses' => 'SendBackNotificationController@sendBackNotification']);

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => false,
]);


Route::get('/login', function () {
    return view('auth.login_update'); // Updated view
});

Route::get('/', function () {
    if (Auth::check()) {
        $title = "Home";
        $title_for_layout = "Home";
        $home_menu_icon = "fa fa-home";
        return view('Home/home')->with(compact("title", "title_for_layout", "home_menu_icon"));
    } else {
        return view('auth/login_update');
    }
})->name('login');

Route::get('/prime-session-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('optimize:clear');
    // Artisan::call('config:cache');
    return redirect('/');
});

Route::get('/images', function () {
    return view('errors/images');
    // return redirect("/Home/images");
})->middleware('auth');

//Route::middleware(['module.access_service'])->group(function () {
    /************* ACL ***************************************/
    Route::get('/Permissions', ['middleware' => ['auth'], 'uses' => 'PermissionsController@index']);
    Route::get('/Permissions/add', ['middleware' => ['auth'], 'uses' => 'PermissionsController@add']);
    Route::post('/Permissions/Store', ['middleware' => ['auth'], 'uses' => 'PermissionsController@store']);

    Route::get('/roles', ['middleware' => ['auth'], 'uses' => 'RolesController@index']);
    Route::get('/rolesCreate', ['middleware' => ['auth'], 'uses' => 'RolesController@create']);
    Route::post('/rolesStore', ['middleware' => ['auth'], 'uses' => 'RolesController@store']);
    Route::get('/roleEdit/{id}', ['middleware' => ['auth'], 'uses' => 'RolesController@edit']);
    Route::patch('/roleUpdate/{id}', ['middleware' => ['auth'], 'uses' => 'RolesController@update']);
    // Route::delete('/roleDelete/{id}', ['middleware' => ['auth'], 'uses' => 'RolesController@destroy']);
    Route::get('/roleDelete/{id}', ['middleware' => ['auth'], 'uses' => 'RolesController@destroy']);
    //////////////// Manage User Roles //////////////////
    Route::patch('/userRoleUpdate/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@setRole']);
    /************* ACL ***************************************/



    /************* LogUsersController ***************************************/
    Route::get('/logUsers', ['middleware' => ['auth'], 'uses' => 'LogUsersController@index']);
    /************* End of LogUsersController ***************************************/

    /************* RestrictedIpsController ***************************************/
    Route::get('/restrictedIps', ['middleware' => ['auth'], 'uses' => 'RestrictedIpController@index']);
    Route::get('/restrictedIpsCreate', ['middleware' => ['auth'], 'uses' => 'RestrictedIpController@create']);
    Route::post('/restrictedIpsStore', ['middleware' => ['auth'], 'uses' => 'RestrictedIpController@store']);
    Route::get('/restrictedIpEdit/{id}', ['middleware' => ['auth'], 'uses' => 'RestrictedIpController@edit']);
    Route::patch('/restrictedIpUpdate/{id}', ['middleware' => ['auth'], 'uses' => 'RestrictedIpController@update']);
    Route::get('/restrictedIpsDelete/{id}', ['middleware' => ['auth'], 'uses' => 'RestrictedIpController@destroy']);
    /***************** HomeController ******************/
    Route::get('/Home', ['middleware' => ['auth'], 'uses' => 'HomeController@index']);
    Route::get('/Period', ['middleware' => ['auth'], 'uses' => 'HomeController@period']);
    Route::get('/MigrateDBToServer', ['middleware' => ['auth'], 'uses' => 'HomeController@migrateDBToServer']);
    Route::get('/ChangeProfilePhoto', ['middleware' => ['auth'], 'uses' => 'HomeController@changeProfilePhoto']);
    Route::post('/ChangeProfilePhoto', ['middleware' => ['auth'], 'uses' => 'HomeController@uploadProfilePhoto']);
    /************** End of HomeController **************/

    /***************** DashboardsController ******************/
    Route::get('/Dashboards', ['middleware' => ['auth'], 'uses' => 'DashboardsController@index']);

    Route::post('/Dashboards/GetWStatusCompRate', ['middleware' => ['auth'], 'uses' => 'DashboardsController@getWStatCompRate']);
    Route::get('/Dashboards/GetWStatusCompRate', ['middleware' => ['auth'], 'uses' => 'DashboardsController@getWStatCompRate']);

    Route::post('/Dashboards/ServiceRequestType', ['middleware' => ['auth'], 'uses' => 'DashboardsController@serviceRequestType']);
    Route::get('/Dashboards/ServiceRequestType', ['middleware' => ['auth'], 'uses' => 'DashboardsController@serviceRequestType']);

    Route::post('/Dashboards/ComplaintType', ['middleware' => ['auth'], 'uses' => 'DashboardsController@complaintType']);
    Route::get('/Dashboards/ComplaintType', ['middleware' => ['auth'], 'uses' => 'DashboardsController@complaintType']);

    Route::post('/Dashboards/ServiceRequestPendingList', ['middleware' => ['auth'], 'uses' => 'DashboardsController@serviceRequestPendingList']);
    Route::get('/Dashboards/ServiceRequestPendingList', ['middleware' => ['auth'], 'uses' => 'DashboardsController@serviceRequestPendingList']);

    Route::post('/Dashboards/ComplaintPendingList', ['middleware' => ['auth'], 'uses' => 'DashboardsController@complaintPendingList']);
    Route::get('/Dashboards/ComplaintPendingList', ['middleware' => ['auth'], 'uses' => 'DashboardsController@complaintPendingList']);

    Route::post('/Dashboards/ServiceRequestSLABreach', ['middleware' => ['auth'], 'uses' => 'DashboardsController@serviceRequestSLABreach']);
    Route::get('/Dashboards/ServiceRequestSLABreach', ['middleware' => ['auth'], 'uses' => 'DashboardsController@serviceRequestSLABreach']);

    Route::post('/Dashboards/ComplaintSLABreach', ['middleware' => ['auth'], 'uses' => 'DashboardsController@complaintSLABreach']);
    Route::get('/Dashboards/ComplaintSLABreach', ['middleware' => ['auth'], 'uses' => 'DashboardsController@complaintSLABreach']);

    Route::get('/Dashboards/DetailsReport', ['middleware' => ['auth'], 'uses' => 'DashboardsController@getDetailsReport']);

    /************** End of DashboardsController **************/

    /************** Company Information *****************/
    Route::get('/CompanyProfiles', ['middleware' => ['auth'], 'uses' => 'CompanyProfilesController@add']);
    Route::post('/CompanyProfiles', ['middleware' => ['auth'], 'uses' => 'CompanyProfilesController@add']);
    Route::post('/CompanyProfiles', ['middleware' => ['auth'], 'uses' => 'CompanyProfilesController@store']);
    /*************** End of Company Information ****************/

    /***************** UsersController ******************/
    Route::get('/Users', ['middleware' => ['auth'], 'uses' => 'UsersController@index'])->name('users.index');
    // Route::get('/Users/export', ['middleware' => ['auth'], 'uses' => 'UsersController@index'])->name('');
    Route::get('/Users/add', ['middleware' => ['auth'], 'uses' => 'UsersController@add']);
    Route::post('/Users/add', ['middleware' => ['auth'], 'uses' => 'UsersController@store']);
    Route::get('/Users/edit/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@edit']);
    Route::post('/Users/edit/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@update']);
    Route::get('/Users/SetPassword/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@setPassword']);
    // Route::post('/Users/SetPassword/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@updatePassword']);
    Route::get('/Users/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'UsersController@status']);
    Route::get('/Users/un-assign/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@userUnassign']);
    Route::get('Users/approve/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@approve']);
    // Route::get('Users/approve/with/role/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@approve']);
    Route::get('remove/tmp-data/{id}/{table}', ['middleware' => ['auth'], 'uses' => 'UsersController@deleteTmpData']);

    Route::get('Users/action-queue-list/', ['middleware' => ['auth'], 'uses' => 'UsersController@userTmpList'])->name('users.action.queue.list');
    Route::get('Users/assign/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@userAssign']);
    Route::get('Users/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@userSendback']);
    Route::get('Users/reject/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@userReject']);
    Route::get('Users/fetch/checker-table/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@checkerTableData']);
    Route::get('Users/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@buttonControl']);
    /* Change Password for All User */
    Route::get('/ChangePassword', ['middleware' => ['auth'], 'uses' => 'UsersController@setPassword']);
    Route::post('/ChangePassword/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@updatePassword']);
    /* End of Change Password for All User */

    //Start Zakir
    //Route::get('/ResetPassword', ['middleware' => ['auth'], 'uses' => 'UsersController@resetPassword']);
    //Route::post('/ResetPassword', ['middleware' => ['auth'], 'uses' => 'UsersController@resetPasswordSubmit']);
    //End Zakir

    /* Access Control For User */
    Route::get('/Users/AccessControl/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@accessControl']);
    Route::post('/Users/AccessControl/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@updateAccessControl']);
    /* End of Access Control For User */
    /* Set Unit / Department /  Division For User */
    // Route::get('/Users/SetUnit/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@setUnit']);
    Route::post('/Users/SetUnit/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@updateUnit']);

    Route::get('/Users/SetCallBackUnit/{id}', ['middleware' => ['auth'], 'uses' => 'UsersController@setCallBackUnit']);

    /* End of Set Unit / Department /  Division For User */

    //
    // Route::get('/get/user/info', ['middleware' => ['auth'], 'uses' => 'UsersController@getUserInfo'])->name('getUserInfo');
    Route::get('/get/user/info', ['middleware' => ['auth'], 'uses' => 'Auth\LoginController@getUserInfo'])->name('getUserInfo');

    /************** End of UsersController **************/

    /***************** ProductTypesController ******************/
    Route::get('ProductTypes', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@index']);
    Route::get('ProductTypes/add', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@add']);
    Route::post('ProductTypes/add', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@store']);
    Route::get('ProductTypes/edit/{id}', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@edit']);
    Route::post('ProductTypes/edit/{id}', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@update']);
    Route::get('ProductTypes/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@status']);
    Route::get('ProductTypes/approve/{id}', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@approveProductTypes']);

    Route::get('ProductTypes/action-queue-list/', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@productTypesTmpList']);
    Route::get('ProductTypes/assign/{id}', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@productTypesAssign']);
    Route::get('ProductTypes/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@productTypesSendback']);
    Route::post('ProductTypes/reject/{id}', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@productTypesReject']);
    Route::get('ProductTypes/fetch/checker-table/{id}', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@checkerTableData']);
    Route::get('ProductTypes/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@buttonControl']);
    Route::get('ProductTypes/tmp-edit/{id}', ['middleware' => ['auth'], 'uses' => 'ProductTypesController@productTypesTmpEdit']);


    /************** End of ProductTypesController **************/

    /***************** DivisionsController ******************/
    Route::get('Divisions', ['middleware' => ['auth'], 'uses' => 'DivisionsController@index']);
    Route::get('Divisions/add', ['middleware' => ['auth'], 'uses' => 'DivisionsController@add']);
    Route::post('Divisions/add', ['middleware' => ['auth'], 'uses' => 'DivisionsController@store']);
    Route::get('Divisions/edit/{id}', ['middleware' => ['auth'], 'uses' => 'DivisionsController@edit']);
    Route::post('Divisions/edit/{id}', ['middleware' => ['auth'], 'uses' => 'DivisionsController@update']);
    Route::get('Divisions/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'DivisionsController@status']);

    Route::get('Divisions/approve/{id}', ['middleware' => ['auth'], 'uses' => 'DivisionsController@approveDivision']);
    // by zihad
    Route::get('Divisions/tmp-list/', ['middleware' => ['auth'], 'uses' => 'DivisionsController@divisionTmpList']);
    Route::get('Divisions/assign/{id}', ['middleware' => ['auth'], 'uses' => 'DivisionsController@divisionAssign']);
    Route::get('Divisions/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'DivisionsController@divisionSendback']);
    Route::get('Divisions/reject/{id}', ['middleware' => ['auth'], 'uses' => 'DivisionsController@divisionReject']);
    Route::get('Divisions/fetch/checker-table/{id}', ['middleware' => ['auth'], 'uses' => 'DivisionsController@checkerTableData']);
    Route::get('Divisions/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'DivisionsController@buttonControl']);
    Route::get('Divisions/tmp-edit/{id}', ['middleware' => ['auth'], 'uses' => 'DivisionsController@divisionTmpEdit']);


    Route::get('delete/tmp-data/{id}/{table}', ['middleware' => ['auth'], 'uses' => 'DivisionsController@deleteTmpData']);
    /************** End of DivisionsController **************/

    /***************** DepartmentsController ******************/
    Route::get('Departments', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@index']);
    Route::get('Departments/add', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@add']);
    Route::post('Departments', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@store']);
    Route::get('Departments/edit/{id}', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@edit']);
    Route::post('Departments/{id}', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@update']);
    Route::get('Departments/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@status']);

    Route::get('Departments/approve/{id}', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@approveDepartment']);

    Route::get('Departments/action-queue-list/', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@departmentTmpList']);
    Route::get('Departments/assign/{id}', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@departmentAssign']);
    Route::get('Departments/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@departmentSendback']);
    Route::get('Departments/reject/{id}', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@departmentReject']);
    Route::get('Departments/fetch/checker-table/{id}', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@checkerTableData']);
    Route::get('Departments/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@buttonControl']);
    Route::get('Departments/tmp-edit/{id}', ['middleware' => ['auth'], 'uses' => 'DepartmentsController@departmentTmpEdit']);


    /************** End of DepartmentsController **************/

    /***************** GroupInfoController ******************/
    Route::get('group-info', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@index']);
    Route::get('group-info/create', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@create']);
    Route::post('group-info', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@store']);
    Route::get('group-info/edit/{id}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@edit']);
    Route::post('group-info/{id}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@update']);
    Route::get('group-info/destroy/{id}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@destroy']);
    Route::get('group-info/activate/{id}/{state}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@activate']);
    Route::get('/group-list/{id}', 'GroupInfoController@groupList');
    Route::get('group-info/approve/{id}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@approveGroupInfo']);
    Route::get('group-info/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@status']);
    Route::get('group-info/action-queue-list/', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@groupInfoQueueList']);
    Route::get('group-info/assign/{id}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@groupInfoAssign']);
    Route::get('group-info/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@groupInfoSendback']);
    Route::get('group-info/reject/{id}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@groupInfoReject']);
    Route::get('group-info/fetch/checker-table/{id}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@checkerTableData']);
    Route::get('group-info/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@buttonControl']);
    Route::get('group-info/tmp-edit/{id}', ['middleware' => ['auth'], 'uses' => 'GroupInfoController@groupInfoTmpEdit']);


    Route::get('subgroup-info', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@index']);
    Route::get('subgroup-info/create', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@create']);
    Route::post('subgroup-info', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@store']);
    Route::get('subgroup-info/edit/{id}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@edit']);
    Route::post('subgroup-info/{id}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@update']);
    Route::get('subgroup-info/destroy{id}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@destroy']);
    Route::get('subgroup-info/activate/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@activate']);
    Route::get('subgroup-list/{id}', 'SubgroupInfoController@subgroupList');
    Route::get('subgroup-info/approve/{id}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@approveSubGroupInfo']);
    Route::get('subgroup-info/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@status']);
    Route::get('subgroup-info/action-queue-list/', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@subGroupInfoQueueList']);
    Route::get('subgroup-info/assign/{id}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@subGroupInfoAssign']);
    Route::get('subgroup-info/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@subGroupInfoSendback']);
    Route::get('subgroup-info/reject/{id}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@subgroupInfoReject']);
    Route::get('subgroup-info/fetch/checker-table/{id}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@checkerTableData']);
    Route::get('subgroup-info/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@buttonControl']);
    Route::get('subgroup-info/tmp-edit/{id}', ['middleware' => ['auth'], 'uses' => 'SubgroupInfoController@subGroupInfoTmpEdit']);
    /************** End of UnitsController **************/

    /***************** UnitsController ******************/
    Route::get('Units', ['middleware' => ['auth'], 'uses' => 'UnitsController@index']);
    Route::get('Units/add', ['middleware' => ['auth'], 'uses' => 'UnitsController@add']);
    Route::post('Units', ['middleware' => ['auth'], 'uses' => 'UnitsController@store']);
    Route::get('Units/edit/{id}', ['middleware' => ['auth'], 'uses' => 'UnitsController@edit']);
    Route::post('Units/{id}', ['middleware' => ['auth'], 'uses' => 'UnitsController@update']);
    Route::get('Units/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'UnitsController@status']);


    Route::get('unit-assign', 'SubgroupUnitController@index');
    Route::get('unit-assign/create', 'SubgroupUnitController@create');
    Route::post('unit-assign', 'SubgroupUnitController@store');
    Route::get('unit-assign/edit/{id}', 'SubgroupUnitController@edit');
    Route::post('unit-assign/{id}', 'SubgroupUnitController@update');
    Route::get('unit-assign/activate/{id}/{state}', 'SubgroupUnitController@activate');
    /************** End of UnitsController **************/

    /***************** UnitItemsController ******************/
    Route::get('Issues-category', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issuesCategory']);
    Route::get('Issues-category/addcategory', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@addCategory']);
    Route::post('Issues-category/addcategory', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@storeCategory']);
    Route::get('Issues-category/editcategory/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@editCategory']);
    Route::post('Issues-category/updateCategory/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@updateCategory']);
    Route::get('Issues-category/statuscategory/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@statusCategory']);
    Route::get('get-issue-wise-category/{issues_from}/{product_type}', 'UnitItemsController@getIssueWiseCategory');

    Route::get('Issues-category/approve/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@approveIssueCategory']);
    Route::get('Issues-category/action-queue-list/', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@categoryTmpList']);
    Route::get('Issues-category/assign/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@categoryAssign']);
    Route::get('Issues-category/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@categorySendback']);
    Route::get('Issues-category/reject/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@categoryReject']);
    Route::get('Issues-category/fetch/checker-table/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@checkerTableData']);
    Route::get('Issues-category/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@buttonControl']);
    Route::get('Issues-category/tmp-edit/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueCategoryTmpEdit']);


    Route::get('Issues', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@index']);
    Route::get('Issues/add', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@add']);
    Route::post('Issues/add', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@store']);
    Route::get('Issues/edit/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@edit']);
    Route::post('Issues/edit/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@update']);
    Route::get('Issues/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@status']);
    Route::get('Issues/sms_status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@sms_status']);
    Route::get('Issues/approve/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@approveIssue']);
    Route::get('Issues/action-queue-list', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueQueueList']);
    Route::get('Issues/tmp-status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@tmpStatus']);
    Route::get('Issues/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@status']);
    Route::get('Issues/fetch/checker-table-issues/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@checkerTableDataForIssues']);
    Route::get('Issues/assign/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueAssign']);
    Route::get('Issues/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueSendback']);
    Route::get('Issues/reject/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueReject']);
    Route::get('Issues/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@buttonControlForIssue']);

    Route::get('issues/config/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@config']);
    Route::post('issues-config/store', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@store']);
    Route::post('issues-config/update/{id}', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@update']);
    Route::post('issue-extra-form', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@issueFormField']);
    Route::post('issue-check-list', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@issueCheckList']);

    Route::post('edit-issue-extra-form', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@editIssueFormField']);
    Route::post('edit-issue-check-list', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@editIssueCheckList']);
    Route::post('update-issue-wform/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@wFormUpdate']);
    Route::post('update-issue-complain/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@complainFormUpdate']);

    Route::get('issues/check-list/config/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@checkListConfig']);
    Route::post('issues-check-list/store', ['middleware' => ['auth'], 'uses' => 'IssueCheckListConfigController@store']);
    Route::post('issues-check-list/update/{id}', ['middleware' => ['auth'], 'uses' => 'IssueCheckListConfigController@update']);
    Route::post('issue-check-list-extra-form', ['middleware' => ['auth'], 'uses' => 'IssueCheckListConfigController@issueFormField']);
    //** 30-05-2022 **//
    Route::get('issues/common/config/{id}', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@commonConfig']);
    Route::post('issues/common/config/store', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@commonConfigStore']);

    //** 16-07-2023 **//
    Route::get('/issue/conditional/field/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueConditionalField']);
    Route::post('/issue/conditional/field', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueConditionalFieldStore']);
    Route::get('/issue/conditional/field/value/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueConditionalFieldValue']);
    Route::get('/issue/conditional/field/options/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueConditionalFieldOptions']);
    Route::get('/issue/dependant/fields/{issue_id}/{value}/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueDependantFields']);
    Route::get('/issue/conditional/{issue_id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueConditional']);
    Route::get('/issue/conditional/fields/{issue_id}/{id}', ['middleware' => ['auth'], 'uses' => 'UnitItemsController@issueConditionalFields']);

    /************** End of UnitItemsController **************/
    // Route::get('BranchCode', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@index'])->name('branchcode.index');
    // Route::get('BranchCode/add', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@create'])->name('branchcode.add');
    // Route::post('BranchCode/add', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@store'])->name('branchcode.store');
    // Route::get('BranchCode/edit/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@edit'])->name('branchcode.edit');
    // Route::post('BranchCode/edit/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@update'])->name('branchcode.update');
    // Route::get('BranchCode/delete/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@delete'])->name('branchcode.delete');
    // Route::post('BranchCode/up', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@import'])->name('branchcode.import');

    /***************** BranchCodeController ******************/
    Route::get('branchcode', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@index']);
    Route::get('branchcode/add', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@add']);
    Route::post('branchcode/add', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@store']);
    Route::get('branchcode/edit/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@edit']);
    Route::post('branchcode/edit/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@update']);
    Route::get('branchcode/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@status']);

    Route::get('branchcode/approve/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@approveDivision']);

    Route::get('branchcode/tmp-list/', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@divisionTmpList']);
    Route::get('branchcode/assign/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@divisionAssign']);
    Route::get('branchcode/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@branchcodeSendback']);
    Route::get('branchcode/reject/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@divisionReject']);
    Route::get('branchcode/fetch/checker-table/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@checkerTableData']);
    Route::get('branchcode/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@buttonControl']);
    Route::get('branchcode/tmp-edit/{id}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@divisionTmpEdit']);


    Route::get('delete/tmp-data/{id}/{table}', ['middleware' => ['auth'], 'uses' => 'BranchCodeController@deleteTmpData']);


    /***************** HolidaysController ******************/
    Route::get('Holidays', ['middleware' => ['auth'], 'uses' => 'HolidaysController@index']);
    Route::get('Holidays/add', ['middleware' => ['auth'], 'uses' => 'HolidaysController@add']);
    Route::post('Holidays/add', ['middleware' => ['auth'], 'uses' => 'HolidaysController@store']);
    Route::get('Holidays/edit/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@edit']);
    Route::post('Holidays/edit/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@update']);
    Route::get('Holidays/delete/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@delete']);
    Route::post('Holidays/UploadHolidays', ['middleware' => ['auth'], 'uses' => 'HolidaysController@uploadHolidays']);
    Route::get('Holidays/tmp-edit/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@holidayTmpEdit']);
    Route::get('WorkingDays', ['middleware' => ['auth'], 'uses' => 'HolidaysController@workingDays']);
    Route::get('WorkingHours', ['middleware' => ['auth'], 'uses' => 'HolidaysController@workingHours']);
    Route::get('Holidays/approve/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@approveHoliday']);
    Route::get('approve/working-hour/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@approveWorkingHours']);
    Route::get('Holidays/action-queue-list/', ['middleware' => ['auth'], 'uses' => 'HolidaysController@holidayQueueList']);
    Route::get('Holidays/assign/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@holidayAssign']);
    Route::get('Holidays/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@holidaySendback']);
    Route::get('Holidays/reject/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@holidayReject']);
    Route::get('Holidays/fetch/checker-table/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@checkerTableData']);
    Route::get('Holidays/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@buttonControl']);
    Route::get('Holidays/download/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@holidayDownloadFile']);
    Route::get('WorkingHours/action-queue-list', ['middleware' => ['auth'], 'uses' => 'HolidaysController@workingHourQueueList']);
    Route::get('WorkingHours/assign/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@workingHourAssign']);
    Route::get('WorkingHours/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@workingHourSendback']);
    Route::post('WorkingHours/reject/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@workingHourReject']);
    Route::get('WorkingHours/fetch/checker-table/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@checkerTableDataWH']);
    Route::get('WorkingHours/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'HolidaysController@buttonControlWH']);
    /************** End of HolidaysController **************/

    /***************** SegmentController ******************/
    Route::group(['prefix' => 'segment', 'middleware' => ['auth']], function () {
        Route::get('/index', [SegmentCodeController::class, 'index'])->name('segment.index');
        Route::post('/store', [SegmentCodeController::class, 'store'])->name('segment.store');
        Route::get('/edit/{id}', [SegmentCodeController::class, 'edit'])->name('segment.edit');
        Route::post('/update/{id}', [SegmentCodeController::class, 'update'])->name('segment.update');
        Route::get('/delete/{id}', [SegmentCodeController::class, 'delete'])->name('segment.delete');
        Route::get('/status/{id}/{status}', [SegmentCodeController::class, 'segmentstatus'])->name('segment.status');
        Route::post('/excel/import', [SegmentCodeController::class, 'segmentExcelUpload'])->name('segment.excel');
    });
    /************** End of HolidaysController **************/

    /***************** SMSEmailsController ******************/
    Route::get('SMS-Emails', ['middleware' => ['auth'], 'uses' => 'SMSEmailsController@index']);
    Route::post('SMS-Emails/store/{id}', ['middleware' => ['auth'], 'uses' => 'SMSEmailsController@store']);
    Route::get('SMS-Emails/approve/{id}', ['middleware' => ['auth'], 'uses' => 'SMSEmailsController@approveSMSMail']);
    Route::get('SMS-Emails/action-queue-list/', ['middleware' => ['auth'], 'uses' => 'SMSEmailsController@smsEmailQueueList']);
    Route::get('SMS-Emails/assign/{id}', ['middleware' => ['auth'], 'uses' => 'SMSEmailsController@smsEmailAssign']);
    Route::get('SMS-Emails/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'SMSEmailsController@smsEmailSendback']);
    Route::post('SMS-Emails/reject/{id}', ['middleware' => ['auth'], 'uses' => 'SMSEmailsController@smsEmailReject']);
    /************** End of SMSEmailsController **************/

    /***************** SupportsController ******************/
    Route::get('Supports/home', ['middleware' => ['auth'], 'uses' => 'SupportsController@index']);
    /**************** Mahdi 28-06-2022 *******************/
    Route::get('Supports/ccSearchResult', ['middleware' => ['auth'], 'uses' => 'SupportsController@ccSearchResult']);
    /**************** Mahdi 30-06-2022 *******************/
    Route::get('issues/inquiry/config/{id}', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@inquiryConfig']);
    Route::get('issues/inquiry/config/add/{id}', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@inquiryConfigAdd']);
    Route::post('issues/inquiry/config/store', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@inquiryConfigStore']);
    Route::get('issues/inquiry/config/edit/{id}', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@inquiryConfigEdit']);
    Route::post('issues/inquiry/config/update/{id}', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@inquiryConfigUpdate']);
    Route::get('issues/inquiry/config/child/{id}', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@inquiryConfigChild']);
    Route::post('issues/inquiry/config/child/{id}', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@inquiryConfigChildStore']);
    Route::get('issues/inquiry/config/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'IssueConfigController@inquiryConfigStatus']);

    Route::get('Supports/handler', ['middleware' => ['auth'], 'uses' => 'SupportsController@handler']);
    Route::get('Supports/assign/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@assign']);
    Route::get('Supports/complaint_closing_assign/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@complaint_closing_assign']);

    Route::get('Supports/unassign/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@unassign']);

    Route::get('Supports/complaintClosing', ['middleware' => ['auth'], 'uses' => 'SupportsController@complaintClosing']);
    Route::post('Supports/complaintClosingSubmit/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@complaintClosingSubmit']);
    Route::get('Supports/ComplaintClosingDetails/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@complaintClosingDetails']);

    Route::get('Supports/NewWForm', ['middleware' => ['auth'], 'uses' => 'SupportsController@newWForm'])->name('newWForm');
    Route::post('Supports/NewWForm', ['middleware' => ['auth'], 'uses' => 'SupportsController@submitWform']);
    Route::get('Supports/NewComplaint', ['middleware' => ['auth'], 'uses' => 'SupportsController@newComplaint']);
    Route::post('Supports/NewComplaint', ['middleware' => ['auth'], 'uses' => 'SupportsController@submitComplaint']);
    Route::get('/download/{filename}', ['middleware' => ['auth'], 'uses' => 'DownloadController@download'])->name('download');

    Route::post('newForm-data-store', ['middleware' => ['auth'], 'uses' => 'SupportsController@newFormDataSession'])->name('newFormDataSession');

    Route::get('Supports/NewDummyWForm', ['middleware' => ['auth'], 'uses' => 'SupportsController@newDummyWForm']);
    Route::post('Supports/NewDummyWForm', ['middleware' => ['auth'], 'uses' => 'SupportsController@submitDummyWform']);
    Route::get('Supports/NewDummyComplaint', ['middleware' => ['auth'], 'uses' => 'SupportsController@newDummyComplaint']);
    Route::post('Supports/NewDummyComplaint', ['middleware' => ['auth'], 'uses' => 'SupportsController@submitDummyComplaint']);

    Route::get('Supports/NonCustomer', ['middleware' => ['auth'], 'uses' => 'SupportsController@newNonCustomer']);
    Route::post('Supports/NonCustomer', ['middleware' => ['auth'], 'uses' => 'SupportsController@submitNonCustomer']);
    Route::get('Supports/NonCustomer/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@nonCustomerDetails']);

    Route::get('Supports/status/{id}/{status}', ['middleware' => ['auth'], 'uses' => 'SupportsController@status']);
    Route::get('Supports/WFormDetails/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@wFormDetails']);
    Route::get('Supports/ComplaintDetails/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@complaintDetails']);

    Route::get('Supports/NonCustomerReportDetails/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@nonCustomerReportDetails']);
    Route::get('Supports/WFormReportDetails/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@wFormReportDetails']);
    Route::get('Supports/ComplaintReportDetails/{reference_number}', ['middleware' => ['auth'], 'uses' => 'SupportsController@complaintReportDetails']);


    Route::post('Supports/WorkingOnHandler', ['middleware' => ['auth'], 'uses' => 'SupportsController@workingOnHandler']);
    Route::post('Supports/UploadNewAttachment', ['middleware' => ['auth'], 'uses' => 'SupportsController@uploadNewAttachment']);

    Route::post('Supports/DeleteAttachment', ['middleware' => ['auth'], 'uses' => 'SupportsController@deleteAttachment']);

    Route::post('Supports/SendSendBackSMS', ['middleware' => ['auth'], 'uses' => 'SupportsController@sendSendBackSMS']);
    Route::post('Supports/ApiUpdate', ['middleware' => ['auth'], 'uses' => 'SupportsController@apiUpdate']);

    Route::get('Supports/GetTouchSubGroups', ['middleware' => ['auth'], 'uses' => 'SupportsController@getAllTouchSubGroups']);


    Route::get('Supports/PrintForm/{stype}', ['middleware' => ['auth'], 'uses' => 'SupportsController@printForm']);
    Route::post('Supports/PrintForm/{stype}', ['middleware' => ['auth'], 'uses' => 'SupportsController@printForm']);

    Route::post('newForm-data-store', ['middleware' => ['auth'], 'uses' => 'SupportsController@newFormDataSession'])->name('newFormDataSession');

    /************** End of SupportsController **************/


    /***************** ReportsController ******************/

    Route::get('/Reports', ['middleware' => ['auth'], 'uses' => 'ReportsController@index']);
    Route::get('/working-status-reports', ['middleware' => ['auth'], 'uses' => 'ReportsController@workingStatusReport']);
    Route::get('/complain-pending-reports', ['middleware' => ['auth'], 'uses' => 'ReportsController@complainPendingReport']);
    Route::get('/dashboard-reports', ['middleware' => ['auth'], 'uses' => 'ReportsController@dashboardReport']);

    /************** End of ReportsController **************/

    /***************** NotificationsController ******************/
    Route::get('/SendMail', ['middleware' => [], 'uses' => 'NotificationsController@sendMail']);
    Route::get('/SendSMS', ['middleware' => [], 'uses' => 'NotificationsController@sendSms']);
    // Route::post('SMS-Emails/store/{id}', ['middleware' => ['auth'], 'uses' => 'NotificationsController@store']);
    /************** End of NotificationsController **************/

    /***************** TATBreachesController ******************/
    Route::get('/TATBreach', ['middleware' => [], 'uses' => 'TATBreachesController@handler']);
    Route::get('/TATBreachSendMail', ['middleware' => [], 'uses' => 'TATBreachesController@sentMailOnTatBreach']);
    /************** End of TATBreachesController **************/
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/Users/block/{id}/{state}', 'UsersController@isBlock');

        Route::get('settings', 'SettingController@index');
        Route::get('settings/create', 'SettingController@create');
        Route::post('settings', 'SettingController@store');
        Route::get('settings/edit/{id}', 'SettingController@edit');
        Route::post('settings/{id}', 'SettingController@update');
        Route::get('settings/approve/{id}', 'SettingController@approveSetting');

        Route::get('settings/action-queue-list/', 'SettingController@settingTmpList');
        Route::get('settings/assign/{id}', 'SettingController@settingAssign');
        Route::get('settings/send-back/{id}', 'SettingController@settingSendback');
        Route::get('settings/reject/{id}', 'SettingController@settingReject');
        Route::get('settings/fetch/checker-table/{id}', 'SettingController@checkerTableData');
        Route::get('settings/fetch/button-status/{id}', 'SettingController@buttonControl');
        Route::get('settings/tmp-edit/{id}', 'SettingController@settingTmpEdit');


        Route::get('audits', 'LogActivityController@index');
        Route::get('audits/create', 'LogActivityController@create');
        Route::post('audits', 'LogActivityController@store');


        Route::get('workflow', 'WorkflowController@index');
        Route::get('workflow/show/{id}', 'WorkflowController@show');

        Route::get('workflow/create', 'WorkflowController@create');
        Route::post('workflow', 'WorkflowController@store');
        Route::get('workflow/edit/{id}', 'WorkflowController@edit');
        Route::post('workflow/{id}', 'WorkflowController@update');
        Route::get('workflow/destroy/{id}', 'WorkflowController@destroy');

        Route::get('workflow/checker', 'WorkflowController@indexTmp');
        Route::get('workflow/checker/show/{id}', 'WorkflowController@showTmp');
        Route::get('workflow/approve/{id}', ['middleware' => ['auth'], 'uses' => 'WorkflowController@approveWorkflow']);
        Route::get('workflow/reject/{id}', ['middleware' => ['auth'], 'uses' => 'WorkflowController@rejectWorkflow']);

        Route::get('workflow/checker/destroy/{id}', 'WorkflowController@destroyFinal');

        Route::get('workflow/special-permission', 'WorkflowController@specialPermission');
        Route::get('workflow/special-permission/create', 'WorkflowController@createSpecialPermission');
        Route::post('workflow-spacial', 'WorkflowController@storeSpecialPermission');
        Route::get('workflow/special-permission/destroy/{id}', 'WorkflowController@destroySpecialPermission');

        Route::get('workflow/subflow', 'WorkflowController@subflowIndex');
        Route::get('workflow/subflow/set/{id}', 'WorkflowController@subflowEdit');
        Route::post('workflow/subflow/set/{id}', 'WorkflowController@subflowUpdate');


        Route::get('group-clear', 'WorkflowController@groupClear');
        Route::get('delete-to-group', 'WorkflowController@deleteGroup');
        Route::get('delete-to-group-edit', 'WorkflowController@deleteGroupEdit');

        Route::post('add-to-group', 'WorkflowController@addToGroup');
        Route::post('add-to-group-edit', 'WorkflowController@addToGroupEdit');
        Route::get('groups/get-groups', 'GroupInfoController@getGroup');
        Route::get('group-list', 'WorkflowController@getGroups');
        Route::get('group-list-edit', 'WorkflowController@getGroupsEdit');

        Route::get('type-wise-issue/{type}', 'WorkflowController@getTypeWiseIssue');
        Route::get('type-wise-issue-for-special/{type}', 'WorkflowController@getTypeWiseIssueForSpecial');


        Route::get('group-wise-workflow/{id}/{issue}', 'WorkflowController@getGroupwiseWorkflow');

        Route::post('workflow-attachment', 'WorkflowController@issueAttachment');

        Route::get('group-level', 'GroupLevelController@index');
        Route::get('group-level/create', 'GroupLevelController@create');
        Route::post('group-level', 'GroupLevelController@store');


        Route::get('get-category-wise-complaint/{product_id}/{category_id}', 'UnitItemsController@getCategoryWiseComplaint');
        Route::get('get-category-wise-service/{product_id}/{category_id}', 'UnitItemsController@getCategoryWiseService');
        Route::get('get-cat-wise-services/{category_id}', 'UnitItemsController@getCatWiseServices');

        /* Bond information System */
        Route::get('bond-info/category', 'BondInfosController@categoryIndex');
        Route::get('bond-info/category/add', 'BondInfosController@categoryAdd');
        Route::post('bond-info/category/add', 'BondInfosController@categoryStore');
        Route::get('bond-info/category/edit/{id}', 'BondInfosController@categoryEdit');
        Route::post('bond-info/category/edit/{id}', 'BondInfosController@categoryUpdate');
        Route::get('bond-info/category/status/{id}', 'BondInfosController@categoryStatus');
        Route::get('bond-info/category/approve/{id}', 'BondInfosController@approveCategory');
        Route::get('bond-info/tmp-cat-edit/{id}', 'BondInfosController@bondInfocategoryTmpEdit');


        Route::get('bond-info/category/action-queue-list/', 'BondInfosController@bondInfosTmpList');
        Route::get('bond-info/category/assign/{id}', 'BondInfosController@bondInfosAssign');
        Route::get('bond-info/category/send-back/{id}', 'BondInfosController@bondInfosSendback');
        Route::get('bond-info/category/reject/{id}', 'BondInfosController@bondInfosReject');
        Route::get('bond-info/category/fetch/checker-table/{id}', 'BondInfosController@checkerCategoryTableData');
        Route::get('bond-info/category/fetch/button-status/{id}', 'BondInfosController@buttonCategoryControl');

        Route::get('bond-info/sub-category/add', 'BondInfosController@subCategoryAdd');

        Route::get('bond-info/sub-category', 'BondInfosController@subCategoryIndex');
        Route::post('bond-info/sub-category/add', 'BondInfosController@subCategoryStore');
        Route::get('bond-info/sub-category/edit/{id}', 'BondInfosController@subCategoryEdit');
        Route::post('bond-info/sub-category/edit/{id}', 'BondInfosController@subCategoryUpdate');
        Route::get('bond-info/sub-category/status/{id}', 'BondInfosController@subCategoryStatus');
        Route::get('bond-info/sub-category/approve/{id}', 'BondInfosController@approveSubCategory');
        Route::get('bond-info/cat-tmp-edit/{id}', 'BondInfosController@bondInfoCatTmpEdit');

        Route::get('bond-info/list', 'BondInfosController@bondInfoList');
        Route::get('bond-info/upload', 'BondInfosController@bondInfoUpload');
        Route::post('bond-info/upload', 'BondInfosController@bondInfoUploadSubmit');

        Route::get('bond-info/upload-edit/{id}', 'BondInfosController@bondInfoUploadEdit');
        Route::post('bond-info/upload-edit/{id}', 'BondInfosController@bondInfoUploadUpdate');
        Route::get('bond-info/tmp-edit/{id}', 'BondInfosController@bondInfoUploadTmpEdit');

        Route::get('bond-info/status/{id}', 'BondInfosController@bondInfoStatus');
        Route::get('bond-info/approve/{id}', 'BondInfosController@approveBondInfoUpload');
        Route::get('bond-info/status/{id}/{status}', 'BondInfosController@status');
        Route::get('bond-info/action-queue-list/', 'BondInfosController@productInfoQueueList');
        Route::get('bond-info/assign/{id}', 'BondInfosController@productInfoAssign');
        Route::get('bond-info/send-back/{id}', 'BondInfosController@productInfoSendback');
        Route::get('bond-info/reject/{id}', 'BondInfosController@productInfoReject');
        Route::get('bond-info/preview/{id}', 'BondInfosController@previewTmpFile');
        Route::get('bond-info/fetch/checker-table/{id}', 'BondInfosController@checkerTableData');
        Route::get('bond-info/fetch/button-status/{id}', 'BondInfosController@buttonControl');



        // Muajjam Hossain
        Route::get('/api-credential', 'ApiCredentialController@edit')->name('apiCredential.edit');
        Route::post('/api-credential/{id}', 'ApiCredentialController@update')->name('apiCredential.update');

        Route::get('/api-credential/approve/{id}', 'ApiCredentialController@approveApiCredential');

        Route::get('api-credential/action-queue-list/', ['middleware' => ['auth'], 'uses' => 'ApiCredentialController@apiCredentialTmpList']);
        Route::get('api-credential/assign/{id}', ['middleware' => ['auth'], 'uses' => 'ApiCredentialController@apiCredentialAssign']);
        Route::get('api-credential/send-back/{id}', ['middleware' => ['auth'], 'uses' => 'ApiCredentialController@apiCredentialSendback']);
        Route::get('api-credential/reject/{id}', ['middleware' => ['auth'], 'uses' => 'ApiCredentialController@apiCredentialReject']);
        Route::get('api-credential/fetch/checker-table/{id}', ['middleware' => ['auth'], 'uses' => 'ApiCredentialController@checkerTableData']);
        Route::get('api-credential/fetch/button-status/{id}', ['middleware' => ['auth'], 'uses' => 'ApiCredentialController@buttonControl']);
        Route::get('api-credential/tmp-edit/{id}', 'ApiCredentialController@apiCredentialsTmpEdit')->name('apiCredential.apiCredentialsTmpEdit');






        /* End of Bond information system */
    //});
    /*Route::post('/groups/getGroups','GroupInfoController@getGroup')->name('groupInfo.getGroup');*/

    /***************** DMSController ******************/
    Route::get('/DMSAPIServices/CIFIndexing', 'DMSController@CIFIndexing');
    Route::get('/DMSAPIServices/DOCUploading', 'DMSController@DOCUploading');
    Route::get('/DMSAPIServices/DocCounting', 'DMSController@DocCounting');

    Route::get('/DMSAPIServices/{para}', 'DMSController@DMSUrlRequest');
    Route::get('/DMSAPIServices/DMSUrl/list', 'DMSController@DMSUrlList')->middleware('auth');
    Route::get('/DMSAPIServices/DMSUrl/add', 'DMSController@DMSUrlAdd')->middleware('auth');
    Route::post('/DMSAPIServices/DMSUrl/store', 'DMSController@DMSUrlStore')->middleware('auth');
    Route::get('/DMSAPIServices/DMSUrl/edit/{id}', 'DMSController@DMSUrlEdit')->middleware('auth');
    Route::post('/DMSAPIServices/DMSUrl/update/{id}', 'DMSController@DMSUrlUpdate')->middleware('auth');
    Route::get('/DMSAPIServices/DMSUrl/status/{id}/{value}', 'DMSController@DMSUrlStatus')->middleware('auth');
    /* 1-04-2023 Zihad */
    Route::get('/DMSRetry', 'DMSController@DMSRetry')->middleware('auth');
    /* 01-04-2023 Mahdi */
    Route::get('/DMSDocRetry/{ref}/{id}', 'DMSController@DMSDocRetry')->middleware('auth');
    Route::post('/DMSDocRetryBulk', 'DMSController@DMSDocRetryBulk')->middleware('auth');

    /***************** CIFModificationController ******************/
    // Route::get('/CIFModification','CIFModificationController@index');

    // Route::get('/CIFModification/CIFUrl/list','CIFModificationController@CIFUrlList')->middleware('auth');
    // Route::get('/CIFModification/CIFUrl/add','CIFModificationController@CIFUrlAdd')->middleware('auth');
    // Route::post('/CIFModification/CIFUrl/store','CIFModificationController@CIFUrlStore')->middleware('auth');
    // Route::get('/CIFModification/CIFUrl/edit/{id}','CIFModificationController@CIFUrlEdit')->middleware('auth');
    // Route::post('/CIFModification/CIFUrl/update/{id}','CIFModificationController@CIFUrlUpdate')->middleware('auth');
    // Route::get('/CIFModification/CIFUrl/status/{id}/{value}','CIFModificationController@CIFUrlStatus')->middleware('auth');

    // Route::get('/CIFModification/cif-workflow', ['middleware' => ['auth'], 'uses' => 'CIFModificationController@cifModificationWorkFlow']);
    // Route::get('/CIFModification/setcif-workflow/{id}', ['middleware' => ['auth'], 'uses' => 'CIFModificationController@setCIFModificationWorkFlow']);
    // Route::post('/CIFModification/setcif-workflow/{id}', ['middleware' => ['auth'], 'uses' => 'CIFModificationController@updateCIFModificationWorkFlow']);
    // Route::get('/CIFModification/set-api/{id}', ['middleware' => ['auth'], 'uses' => 'CIFModificationController@setCIFModificationAPI']);
    // Route::post('/CIFModification/set-api/{id}', ['middleware' => ['auth'], 'uses' => 'CIFModificationController@updateCIFModificationAPI']);
    // Route::get('/CIFModification/inquiry-api/{id}', ['middleware' => ['auth'], 'uses' => 'CIFModificationController@setCIFInquiryAPI']);
    // Route::post('/CIFModification/inquiry-api/{id}', ['middleware' => ['auth'], 'uses' => 'CIFModificationController@updateCIFInquiryAPI']);
    // Route::get('/CIFModification/is-inquiry-api/{id}', ['middleware' => ['auth'], 'uses' => 'CIFModificationController@isInquiryApi']);
    // Route::get('/CIFModification/inquiry/{issue_id}/{acc_no}/{ref_no}/{cif_no}', ['middleware' => ['auth'], 'uses' => 'CIFModificationController@inquiryApi']);

    // Route::get('/CIFParentUrl/list','CIFModificationController@CIFParentUrlList')->middleware('auth');
    // Route::get('/CIFParentUrl/add','CIFModificationController@CIFParentUrlAdd')->middleware('auth');
    // Route::post('/CIFParentUrl/store','CIFModificationController@CIFParentUrlStore')->middleware('auth');
    // Route::get('/CIFParentUrl/edit/{id}','CIFModificationController@CIFParentUrlEdit')->middleware('auth');
    // Route::post('/CIFParentUrl/update/{id}','CIFModificationController@CIFParentUrlUpdate')->middleware('auth');
    // Route::get('/CIFParentUrl/status/{id}/{value}','CIFModificationController@CIFParentUrlStatus')->middleware('auth');

    // Route::post('/CIFModification/ApiUpdate/','CIFModificationController@apiUpdate')->middleware('auth');
    // /**************** Mahdi 29-06-2022 *******************/
    // Route::get('CIFModification/Api-Update-check', ['middleware' => ['auth'], 'uses' => 'CIFModificationController@api_update_check']);

    Route::get('/DynamicAPICredential', 'DynamicAPICredentialController@index')->middleware('auth');
    Route::get('/DynamicAPICredential/add', 'DynamicAPICredentialController@add')->middleware('auth');
    Route::post('/DynamicAPICredential/store', 'DynamicAPICredentialController@store')->middleware('auth');
    Route::get('/DynamicAPICredential/edit/{id}', 'DynamicAPICredentialController@edit')->middleware('auth');
    Route::post('/DynamicAPICredential/update/{id}', 'DynamicAPICredentialController@update')->middleware('auth');

    /** Issue fieldset admin route (Zihad) **/
    Route::get('issues/fieldset/group/{id}', ['uses' => 'IssueFieldsetController@fieldsetGroup']);
    Route::get('fieldset/destroy/{id}', ['uses' => 'IssueFieldsetController@fieldsetGroupDelete']);
    Route::post('fieldset-store/store', ['uses' => 'IssueFieldsetController@fieldsetGroupStore']);
    Route::get('issues/attachment/{id}', ['uses' => 'IssueFieldsetController@issueAttachmentConfig']);
    Route::post('issues/attachment/store', ['uses' => 'IssueFieldsetController@issueAttachmentStore']);
    Route::post('issue-attachment', ['uses' => 'IssueFieldsetController@issueAttachment']);

    /** CI APIs start here **/
    Route::get('/ci_apis/list', 'CIBackendController@ci_api_index')->middleware('auth');
    Route::get('/ci_apis/create', 'CIBackendController@ci_api_create')->middleware('auth');
    Route::post('/ci_apis/store', 'CIBackendController@ci_api_store')->middleware('auth');
    Route::get('/ci_apis/edit/{id}', 'CIBackendController@ci_api_edit')->middleware('auth');
    Route::post('/ci_apis/update/{id}', 'CIBackendController@ci_api_update')->middleware('auth');
    Route::get('/ci_apis/status/{id}/{status}', 'CIBackendController@ci_api_status')->middleware('auth');

    /***************** Feedback admin-index ******************/
    Route::get('feedback-index', ['middleware' => ['auth'], 'uses' => 'FeedbackController@index'])->name('feedback.index');
    Route::post('feedback/store', ['middleware' => ['auth'], 'uses' => 'FeedbackController@bulkStore'])->name('feedback.bulk_store');
    Route::get('feedback/status/{id}', ['middleware' => ['auth'], 'uses' => 'FeedbackController@singleStatus'])->name('feedback.read');

    /** BPID  */
    Route::post('bpid/calling-account-api', [BpIdController::class, 'firstAccountApi'])
        ->name('bpid.call_first_account_api')
        ->middleware('auth');

    Route::post('issue-bpid-with-treasury', ['middleware' => ['auth'], 'uses' => 'BpIdController@getBpIdWithTreasury']);
    Route::post('bpid/bidding-date', ['middleware' => ['auth'], 'uses' => 'BpIdController@getBiddingDate']);

    
});

/** END **/
