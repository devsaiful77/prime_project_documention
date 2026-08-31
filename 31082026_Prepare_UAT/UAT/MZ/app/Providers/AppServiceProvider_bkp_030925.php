<?php

namespace App\Providers;

use finfo;
use \Validator;
use App\UserTmp;
use App\HolidayTmp;
use App\SettingTmp;
use App\DivisionTmp;
use App\SMSEmailTmp;
use App\UnitItemTmp;

use App\GroupInfoTmp;
use App\DepartmentTmp;

use App\ProductTypeTmp;
use App\WorkingHourTmp;
use App\SubgroupInfoTmp;
use App\ApiCredentialTmp;
use App\IssueWorkflowTmp;
use App\BondInformationTmp;
use App\IssueCategoriesTmp;
use App\BondInfoCategoryTmp;
use App\BranchCodeTmp;
// use App\Enum\AccessApp;
use App\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();
        Schema::defaultStringLength(191);

        DB::whenQueryingForLongerThan(500, function (Connection $connection) {
            Log::warning("Database queries exceeded 5 seconds on {$connection->getName()}");

            // or notify the development team...
        });

        // Log a warning if we spend more than 1000ms on a single query.
        DB::listen(function ($query) {
            if ($query->time > 1000) {
                Log::warning("An individual database query exceeded 1 second.", [
                    'sql' => $query->sql
                ]);
            }
        });

        app('view')->composer('*', function ($view) {

            $request = app(\Illuminate\Http\Request::class);

            $isAjaxRequest = false;
            if ($request->ajax()) {
                $isAjaxRequest = true;
            }
            if ($appRoute = app('request')->route()) {


                $action = $appRoute->getAction();
                $currentUrl = app('request')->url();
                $roleId = (!empty(Auth::user()->role_id)) ? Auth::user()->role_id : 0;
                $userId = (!empty(Auth::user()->id)) ? Auth::user()->id : 0;

                if (!empty($action['controller'])) {
                    $controller = (class_basename($action['controller'])) ? class_basename($action['controller']) : 'HomeController@index';
                    list($controller, $action) = explode('@', $controller);
                } else {
                    $controller = "HomeController";
                    $action = "index";
                }

                $view->with(compact('controller', 'action', 'currentUrl', 'roleId', 'userId', 'isAjaxRequest'));
            }
        });

        /* Custom Validator for PHONE Number */
        Validator::extend('phone_number', function ($attribute, $value, $parameters) {
            // if (!preg_match("/(01)[0-9]{9}/",$value)) {
            if ((!preg_match("/^[0-9]*$/", $value)) || (strlen($value) > 11)) {
                return false;
            }
            return true;
        });

        /* Custom Validator for Double Number */
        Validator::extend('float', function ($attribute, $value, $parameters) {

            if ((!preg_match("/^[0-9.]*$/", $value)) || ($value == ".") || (substr_count($value, ".") > 1)) {
                return false;
            }
            return true;
        });

        /* Custom Validator for Double Number */
        Validator::extend('float_twodigit', function ($attribute, $value, $parameters) {

            if ((!preg_match("/^[0-9]*\.[0-9][0-9]$/", $value))) {
                return false;
            }
            return true;
        });



        /* Custom Validator for Date dd-mm-YYYY */
        Validator::extend('custom_date', function ($attribute, $value, $parameters) {
            if (preg_match("/^[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}$/", $value) === 0) {
                return false;
            } else {
                list($dd, $mm, $yyyy) = explode('-', $value);
                if (!checkdate($mm, $dd, $yyyy)) {
                    return false;
                }
            }

            return true;
        });
        /* Custom Validator for Mime Except Check */
        Validator::extend('mimes_except', function ($attribute, $value, $parameters) {
            if (empty($value)) {
                return true;
            }

            $mimeList = array(
                'exe' => 'application/x-msdownload',
                'ext' => 'application/vnd.novadigm.ext',
                'bat' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'dll' => 'application/x-msdownload',
                'dmg' => 'application/octet-stream',
            );

            $fileMimeType = $value->getClientMimeType();

            if (!empty($parameters)) {
                foreach ($parameters as $key => $value) {
                    if (!empty($mimeList[$value])) {
                        $preventMime = $mimeList[$value];
                        if ($preventMime == $fileMimeType) {
                            return false;
                        }
                    }
                }
            } else {
                return true;
            }

            return true;
        });

        /* Custom Validator for Mime Except Check */
        Validator::extend('fixed_len', function ($attribute, $value, $parameters) {
            if (empty($value)) {
                return true;
            }
            $successFlag = 0;
            $valueLength = strlen($value);

            if (!empty($parameters)) {
                foreach ($parameters as $key => $params) {
                    if ($valueLength == $params) {
                        $successFlag = 1;
                        break;
                    }
                }
                if ($successFlag == 1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }

            return true;
        });


        Blade::directive('encode', function ($expression) {
            return "<?php echo htmlentities($expression, ENT_QUOTES, 'UTF-8'); ?>";
        });

        $divisionTmp = DivisionTmp::count();
        View::share('divisionTmp', $divisionTmp);

        $workflowTmp = IssueWorkflowTmp::count();
        View::share('workflowTmp', $workflowTmp);

        $departmentsTmp = DepartmentTmp::count();
        View::share('departmentsTmp', $departmentsTmp);

        $issueCategoriesTmp = IssueCategoriesTmp::count();
        View::share('issueCategoriesTmp', $issueCategoriesTmp);

        $groupTmpCount = GroupInfoTmp::count();
        View::share('groupTmpCount', $groupTmpCount);

        $subGroupTmpCount = SubgroupInfoTmp::count();
        View::share('subGroupTmpCount', $subGroupTmpCount);

        $holidayTmpCount = HolidayTmp::count();
        View::share('holidayTmpCount', $holidayTmpCount);

        $workingHourTmpCount = WorkingHourTmp::count();
        View::share('workingHourTmpCount', $workingHourTmpCount);

        $bondInfoTmpCount = BondInformationTmp::count();
        View::share('bondInfoTmpCount', $bondInfoTmpCount);

        $smsAndMailTmpCount = SMSEmailTmp::count();
        View::share('smsAndMailTmpCount', $smsAndMailTmpCount);

        $settingTmpCount = SettingTmp::count();
        View::share('settingTmpCount', $settingTmpCount);

        $apiCredentialTmp = ApiCredentialTmp::count();
        View::share('apiCredentialTmp', $apiCredentialTmp);

        $bondCategoryTmpCount = BondInfoCategoryTmp::count();
        // dd($bondCategoryTmpCount);
        View::share('bondCategoryTmpCount', $bondCategoryTmpCount);

        $productTypesTmpCount = ProductTypeTmp::count();
        View::share('productTypesTmpCount', $productTypesTmpCount);

        // $tmpUsersCount = UserTmp::where('module', AccessApp::ServiceComplaint)->count();
        // View::share('tmpUsersCount', $tmpUsersCount);

        // $tmpRequUsersCount = UserTmp::where('module', AccessApp::Requisition)->count();
        // View::share('tmpRequUsersCount', $tmpRequUsersCount);

	 $tmpUsersCount = UserTmp::count();
         View::share('tmpUsersCount', $tmpUsersCount);


        $smsEmailTmpCount = SMSEmailTmp::count();
        View::share('smsEmailTmpCount', $smsEmailTmpCount);

        $branchCodeTmpCount = BranchCodeTmp::count();
        View::share('branchCodeTmpCount', $branchCodeTmpCount);

        $workingHourTmpCount = WorkingHourTmp::count();
        View::share('workingHourTmpCount', $workingHourTmpCount);

        $issueTmpCount = UnitItemTmp::count();
        View::share('issueTmpCount', $issueTmpCount);
    }
}
