<style type="text/css">
    .navbar {
        padding-top: 2px;
        padding-bottom: 2px;
    }
    .navbar button {
        background-color: rgb(32, 178, 170);
        color: #ffffff;
    }
    .navbar-nav li a {
        padding: 7px 20px;
        color: #ffffff;
        background-color: #375eb1;
        border: 1px solid #333;
        font-size: 15px;
        margin: 2px 2px;
    }
    .navbar-nav li a:hover{
        color: #ffffff;
        background-color: #166834;
        border-color: #E2E2E2;
    }
    .active > a {
        border-style: solid;
        border-width: 1px;
        color: #ffffff;
        background-color: #166834 !important;
        border-color: #333 !important;
    }

    /* for dropdown menu styling  */
    .dropdown-menu .dropdown-item {
        padding: 7px 20px;
        color: #ffffff;
        background-color: #375eb1;
        border: 1px solid #333;
        font-size: 15px;
        margin: 2px 2px;
    }

    .dropdown-menu .dropdown-item:hover {
        color: #ffffff;
        background-color: #166834;
        border-color: #E2E2E2;
    }

    .dropdown-menu .active {
        background-color: #166834 !important;
        border-color: #333 !important;
        color: #ffffff;
    }
    .navbar .dropdown-toggle {
        color: #ffffff;
        background-color: #375eb1;
        border: 1px solid #333;
        padding: 7px 20px;
        font-size: 15px;
        margin: 2px 2px;
    }

    .navbar .dropdown-toggle:hover {
        background-color: #166834;
        border-color: #E2E2E2;
    }

    button.active {
        background-color: #166834 !important;
        border-color: #333 !important;
        color: #fff !important;
    }

    /* Hover dropdown behavior for all dropdowns EXCEPT click-only ones */
    .dropdown:not(.dropdown-click-only):hover > .dropdown-menu {
        display: block;
        margin-top: 0;
    }

    /* Ensure click-only dropdowns don't show on hover */
    .dropdown-click-only:hover > .dropdown-menu:not(.show) {
        display: none !important;
    }

    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
        display: none;
    }

    .dropdown-submenu:hover .dropdown-menu {
        display: block;
    }

</style>

<?php $viewFrom = (!empty($_GET['viewFrom'])) ? $_GET['viewFrom'] : ''; ?>
<nav class="navbar navbar-expand-lg">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent" style="margin: 5px 0px">
        <ul class="navbar-nav mr-auto nav nav-pills">

            @hasanyrole('appAdminChecker|userAdminChecker')
                @can('accessUser')
                    <li class="{{($controller == 'UsersController'  && $action == 'index') ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'UsersController'  && $action == 'index') ? 'active' : '' }}" href="{{ url('/Users') }}"><i class="notika-icon notika-support"></i> Users</a>
                    </li>
                @endcan

                @can('accessProductType')
                    <li class="{{($controller == 'ProductTypesController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'ProductTypesController') ? 'active' : '' }}" href="{{ url('/ProductTypes') }}">
                            <i class="fa fa-credit-card"></i> Product Types
                            <span id="productTypesBadge" onclick="checkerList('{{ url('/ProductTypes/action-queue-list/') }}', 'productTypesBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{$productTypesTmpCount > 0 ? $productTypesTmpCount : ""}}
                            </span>
                        </a>
                    </li>
                @endcan

                @can('accessDivision')
                    <li class="{{($controller == 'DivisionsController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'DivisionsController') ? 'active' : '' }}" href="{{ url('/Divisions') }}">
                            <i class="fa fa-building-o"></i> Divisions
                            <span id="divisionBadge" onclick="checkerList('{{ url('/Divisions/tmp-list/') }}', 'divisionBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{$divisionTmp > 0 ? $divisionTmp : ""}}
                            </span>
                        </a>
                    </li>
                @endcan


                @can('accessDepartment')
                    <li class="{{($controller == 'DepartmentsController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'DepartmentsController') ? 'active' : '' }}" href="{{ url('/Departments') }}">
                            <i class="fa fa-building-o"></i> Departments
                        </a>
                    </li>
                @endcan

                @can('accessGroup')
                    <li class="{{($controller == 'GroupInfoController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'GroupInfoController') ? 'active' : '' }}" href="{{ url('/group-info') }}">
                            <i class="fa fa-building-o"></i> Groups
                            <span id="groupBadge" onclick="checkerList('{{ url('/group-info/action-queue-list') }}', 'groupBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{$groupTmpCount > 0 ? $groupTmpCount : ""}}
                            </span>
                        </a>
                    </li>
                @endcan

                @can('accessSubgroup')
                    <li class="{{($controller == 'SubgroupInfoController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'SubgroupInfoController') ? 'active' : '' }}" href="{{ url('/subgroup-info') }}">
                            <i class="fa fa-building-o"></i> Subgroups
                            <span id="subGroupBadge" onclick="checkerList('{{ url('/subgroup-info/action-queue-list') }}', 'subGroupBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{$subGroupTmpCount > 0 ? $subGroupTmpCount : ""}}
                            </span>
                        </a>
                    </li>
                @endcan

                @can('accessUnit')
                    <li class="{{($controller == 'UnitsController') ? 'active' : '' }}">
                            <a class="nav-link {{($controller == 'UnitsController') ? 'active' : '' }}" href="{{ url('/Units') }}">
                                <i class="fa fa-building-o"></i> Units
                        </a>
                    </li>
                @endcan

                @can('accessIssueCategories')
                    <li class="{{($controller == 'UnitItemsController' && $action == 'issuesCategory') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'UnitItemsController' && $action == 'issuesCategory') ? 'active' : '' }}" href="{{ url('/Issues-category') }}">
                            <i class="fa fa-cubes"></i> Issue Category
                            <span id="issueCategoriesBadge" onclick="checkerList('{{ url('/Issues-category/checker') }}', 'issueCategoriesBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{$issueCategoriesTmp > 0 ? $issueCategoriesTmp : ""}}
                            </span>
                        </a>
                    </li>
                @endcan
                @can('accessIssues')
                    <li class="{{($controller == 'UnitItemsController' && $action == 'index') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'UnitItemsController' && $action == 'index') ? 'active' : '' }}" href="{{ url('/Issues') }}">
                            <i class="fa fa-cubes"></i> Issues
                            @if ($issueTmpCount > 0)
                            <span class="badge bg-danger" id="issueBadge" onclick="checkerList('{{ url('/Issues/action-queue-list') }}', 'issueBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{ $issueTmpCount }}
                            </span>
                            @endif
                        </a>
                    </li>
                @endcan
                @can('accessIssueGroup')
                    <li class="{{($controller == 'IssueGroupController' && $action == 'index') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'IssueGroupController' && $action == 'index') ? 'active' : '' }}" href="{{ url('/issue/group') }}">
                            <i class="fa fa-cubes"></i> Issue Group
                        </a>
                    </li>
                @endcan
                @can('accessWorkflow')
                    <li class="{{($controller == 'WorkflowController' && $action == 'index') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'WorkflowController' && $action == 'index') ? 'active' : '' }}" href="{{ url('workflow') }}">
                            <i class="fa fa-random" aria-hidden="true"></i> Workflow
                            @if ($workflowTmp > 0)
                                <span id="workflowBadge" onclick="checkerList('{{ url('/workflow/checker') }}', 'workflowBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{$workflowTmp}}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('accessBondUpload')
                    <li class="{{($controller == 'BondInfosController' && ($action == 'categoryIndex' || $action == 'categoryAdd' || $action == 'categoryEdit' || $action == 'subCategoryAdd' || $action == 'subCategoryEdit')) ? 'active' : '' }}">
                        <a class="{{($controller == 'BondInfosController' && ($action == 'categoryIndex' || $action == 'categoryAdd' || $action == 'categoryEdit' || $action == 'subCategoryAdd' || $action == 'subCategoryEdit')) ? 'active' : '' }} nav-link" href="{{ url('bond-info/category') }}">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i> Product Info Category

                        </a>
                    </li>
                @endcan

                @can([ 'accessBondInformation'])
                    <li class="{{($controller == 'BondInfosController' && ($action == 'bondInfoList' || $action == 'bondInfoUpload')) ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'BondInfosController' && ($action == 'bondInfoList' || $action == 'bondInfoUpload')) ? 'active' : '' }}" href="{{ url('bond-info/list') }}">
                            <i class="fa fa-file-text" aria-hidden="true"></i> Product Info
                            <span id="bondInfoBadge" onclick="checkerList('{{ url('/bond-info/action-queue-list') }}', 'bondInfoBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{$bondInfoTmpCount > 0 ? $bondInfoTmpCount : ""}}
                            </span>
                        </a>
                    </li>
                @endcan

                @can('accessHoliday')
                    <li class="{{($controller == 'HolidaysController' && ($action == 'index' || $action == 'edit')) ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'HolidaysController' && ($action == 'index' || $action == 'edit')) ? 'active' : '' }}" href="{{ url('/Holidays') }}">
                            <i class="fa fa-building-o"></i> Holidays
                            <span id="holidayBadge" onclick="checkerList('{{ url('/Holidays/action-queue-list') }}', 'holidayBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{$holidayTmpCount > 0 ? $holidayTmpCount : ""}}
                            </span>
                        </a>
                    </li>
                @endcan

                @can('accessWorkingDay')
                    <li class="{{($controller == 'HolidaysController' && $action == 'workingDays') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'HolidaysController' && $action == 'workingDays') ? 'active' : '' }}" href="{{ url('/WorkingDays') }}">
                            <i class="fa fa-building-o"></i> Working Days
                        </a>
                    </li>
                @endcan

                @can('accessWorkingDay')
                    <li class="{{($controller == 'HolidaysController' && $action == 'workingHours') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'HolidaysController' && $action == 'workingHours') ? 'active' : '' }}" href="{{ url('/WorkingHours') }}">
                            <i class="fa fa-building-o"></i> Working Hour
                            <span id="workingHoursBadge" onclick="checkerList('{{ url('/WorkingHours/action-queue-list/') }}', 'workingHoursBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{$workingHourTmpCount > 0 ? $workingHourTmpCount : ""}}
                            </span>
                        </a>
                    </li>
                @endcan

                @can('accessSMSEmail')
                    <li class="{{($controller == 'SMSEmailsController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'SMSEmailsController') ? 'active' : '' }}" href="{{ url('/SMS-Emails') }}">
                            <i class="fa fa-envelope-o"></i> SMS &amp; Mail
                            <span id="smsEmailBadge" onclick="checkerList('{{ url('/SMS-Emails/action-queue-list/') }}', 'smsEmailBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{$smsEmailTmpCount > 0 ? $smsEmailTmpCount : ""}}
                            </span>
                        </a>
                    </li>
                @endcan



            @else
                @can('accessDashboard')
                    <li class="{{($controller == 'DashboardsController') ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'DashboardsController') ? 'active' : '' }}" href="{{ url('/Dashboards') }}"><i class="fa fa-tachometer"></i> Dashboard </a>
                    </li>
                @endcan
                @can('accessUser')
                    <li class="{{($controller == 'UsersController'  && $action == 'index') ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'UsersController'  && $action == 'index') ? 'active' : '' }}" href="{{ url('/Users') }}"><i class="notika-icon notika-support"></i> Users
                            @if ($tmpUsersCount > 0)
                            <span id="tmpUsersCount" onclick="checkerList('{{ url('/Users/action-queue-list/') }}', 'tmpUsersCount')" class="badge bg-danger" style="cursor: pointer;">
                                {{ $tmpUsersCount }}
                            </span>
                            @endif
                        </a>
                    </li>
                @endcan

                @hasanyrole('audit')
                    <li class="{{($controller == 'LogUsersController') ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'LogUsersController') ? 'active' : '' }}" href="{{ url('/logUsers') }}"><i class="notika-icon notika-support"></i> Log
                            Users </a>
                    </li>
                @endhasanyrole

                @can('xyz')
                    <li class="{{($controller == 'LogUsersController') ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'LogUsersController') ? 'active' : '' }}" href="{{ url('/logUsers') }}"><i class="notika-icon notika-support"></i> Log
                            Users </a>
                    </li>
                    <li class="{{($controller == 'RestrictedIpController') ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'RestrictedIpController') ? 'active' : '' }}" href="{{ url('/restrictedIps') }}"><i class="notika-icon notika-support"></i>
                            Restricted Ip </a>
                    </li>
                    <li class="{{($controller == 'RolesController') ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'RolesController') ? 'active' : '' }}" href="{{ url('/roles') }}"><i class="notika-icon notika-support"></i> Roles </a>
                    </li>
                @endcan


                @can('accessSetting')
                    <li class="{{($controller == 'SettingController') ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'SettingController') ? 'active' : '' }}" href="{{ url('/settings') }}"><i class="notika-icon notika-support"></i>
                            Settings
                            @if ($settingTmpCount > 0)
                                <span id="settingsBadge" onclick="checkerList('{{ url('settings/action-queue-list/') }}', 'settingsBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{ $settingTmpCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan

                    @hasanyrole('superadmin|admin')

                    <li class="{{($controller == 'CIBackendController' && $action == 'ci_api_index' ) ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'CIBackendController' && $action == 'ci_api_index') ? 'active' : '' }}" href="{{ url('/ci_apis/list') }}"><i class="fa fa-file-text-o"></i>
                            CI APIs </a>
                    </li>

                    @endhasanyrole

                @can('accessApiCredential')
                    <li class="{{($controller == 'ApiCredentialController') ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'ApiCredentialController') ? 'active' : '' }}" href="{{ url('/api-credential') }}"><i class="notika-icon notika-wifi"></i></i>
                            API Credential
                            @if ($apiCredentialTmp > 0)
                                <span id="apiCredentialsBadge" onclick="checkerList('{{ url('api-credential/action-queue-list/') }}', 'apiCredentialsBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{ $apiCredentialTmp }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan
                {{-- @endhasanyrole --}}
                @can('accessProductType')
                    <li class="{{($controller == 'ProductTypesController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'ProductTypesController') ? 'active' : '' }}" href="{{ url('/ProductTypes') }}">
                            <i class="fa fa-credit-card"></i> Product Types
                            @if ($productTypesTmpCount > 0)
                            <span id="productTypesBadge" onclick="checkerList('{{ url('/ProductTypes/action-queue-list/') }}', 'productTypesBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{ $productTypesTmpCount }}
                            </span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('accessDivision')
                    <li class="{{($controller == 'DivisionsController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'DivisionsController') ? 'active' : '' }}" href="{{ url('/Divisions') }}">
                            <i class="fa fa-building-o"></i> Divisions
                            @if ($divisionTmp > 0)
                            <span id="divisionBadge" onclick="checkerList('{{ url('/Divisions/tmp-list/') }}', 'divisionBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{ $divisionTmp }}
                            </span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('accessDepartment')
                    <li class="{{($controller == 'DepartmentsController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'DepartmentsController') ? 'active' : '' }}" href="{{ url('/Departments') }}">
                            <i class="fa fa-building-o"></i> Departments
                            @if ($departmentsTmp > 0)
                                <span id="departmentsBadge" onclick="checkerList('{{ url('/Departments/action-queue-list') }}', 'departmentsBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{ $departmentsTmp }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('accessGroup')
                    <li class="{{($controller == 'GroupInfoController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'GroupInfoController') ? 'active' : '' }}" href="{{ url('/group-info') }}">
                            <i class="fa fa-building-o"></i> Groups
                            @if ($groupTmpCount > 0)
                            <span id="groupBadge" onclick="checkerList('{{ url('/group-info/action-queue-list') }}', 'groupBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{ $groupTmpCount }}
                            </span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('accessSubgroup')
                    <li class="{{($controller == 'SubgroupInfoController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'SubgroupInfoController') ? 'active' : '' }}" href="{{ url('/subgroup-info') }}">
                            <i class="fa fa-building-o"></i> Subgroups
                            @if ($subGroupTmpCount > 0)
                            <span id="subGroupBadge" onclick="checkerList('{{ url('/subgroup-info/action-queue-list') }}', 'subGroupBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{ $subGroupTmpCount }}
                            </span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('accessUnit')
                    <li class="{{($controller == 'UnitsController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'UnitsController') ? 'active' : '' }}" href="{{ url('/Units') }}">
                            <i class="fa fa-building-o"></i> Units
                        </a>
                    </li>
                @endcan

                @can('accessIssueCategories')
                    <li class="{{($controller == 'UnitItemsController' && $action == 'issuesCategory') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'UnitItemsController' && $action == 'issuesCategory') ? 'active' : '' }}" href="{{ url('/Issues-category') }}">
                            <i class="fa fa-cubes"></i> Issue Category
                            @if ($issueCategoriesTmp > 0)
                                <span id="issueCategoriesBadge" onclick="checkerList('{{ url('/Issues-category/action-queue-list') }}', 'issueCategoriesBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{ $issueCategoriesTmp }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('accessIssues')
                    <li class="{{($controller == 'UnitItemsController' && $action == 'index') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'UnitItemsController' && $action == 'index') ? 'active' : '' }}" href="{{ url('/Issues') }}">
                            <i class="fa fa-cubes"></i> Issues
                            @if ($issueTmpCount > 0)
                            <span class="badge bg-danger" id="issueBadge" onclick="checkerList('{{ url('/Issues/action-queue-list') }}', 'issueBadge')" class="badge bg-danger" style="cursor: pointer;">
                                {{ $issueTmpCount }}
                            </span>
                            @endif
                        </a>
                    </li>
                @endcan
                @can('accessIssueGroup')
                    <li class="{{($controller == 'IssueGroupController' && $action == 'index') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'IssueGroupController' && $action == 'index') ? 'active' : '' }}" href="{{ url('/issue/group') }}">
                            <i class="fa fa-cubes"></i> Issue Group
                        </a>
                    </li>
                @endcan

                @can('accessWorkflow')
                    <li class="{{($controller == 'WorkflowController' && $action == 'index') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'WorkflowController' && $action == 'index') ? 'active' : '' }}" href="{{ url('workflow') }}">
                            <i class="fa fa-random" aria-hidden="true"></i> Workflow
                            @if ($workflowTmp > 0)
                                <span id="workflowBadge" onclick="checkerList('{{ url('/workflow/checker') }}', 'workflowBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{$workflowTmp}}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('accessBondUpload')
                    <li class="{{($controller == 'BondInfosController' && ($action == 'categoryIndex' || $action == 'categoryAdd' || $action == 'categoryEdit' || $action == 'subCategoryAdd' || $action == 'subCategoryEdit') ) ? 'active' : '' }}">
                        <a class="{{($controller == 'BondInfosController' && ($action == 'categoryIndex' || $action == 'categoryAdd' || $action == 'categoryEdit' || $action == 'subCategoryAdd' || $action == 'subCategoryEdit') ) ? 'active' : '' }} nav-link" href="{{ url('bond-info/category') }}"><i class="fa fa-file-text-o"aria-hidden="true"></i>
                            Product Info Category
                            @if ($bondCategoryTmpCount > 0)
                                <span id="productInfoCategoryBadge" onclick="checkerList('{{ url('bond-info/category/action-queue-list') }}', 'productInfoCategoryBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{ $bondCategoryTmpCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan
                @can(['accessBondInformation'])
                    <li class="{{($controller == 'BondInfosController' && ($action == 'bondInfoList'||$action == 'bondInfoUpload') ) ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'BondInfosController' && ($action == 'bondInfoList'||$action == 'bondInfoUpload') ) ? 'active' : '' }}" href="{{ url('bond-info/list') }}"><i class="fa fa-file-text" aria-hidden="true"></i> Product Info
                            @if ($bondInfoTmpCount > 0)
                                <span id="bondInfoBadge" onclick="checkerList('{{ url('bond-info/action-queue-list/') }}', 'bondInfoBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{ $bondInfoTmpCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('wformComplain')
                    @php
                        $priority='';
                    @endphp

                    @if( Auth::user()->hasRole('CEX_ADMIN') || Auth::user()->can('wformComplain') )
                        @php
                            if(!empty(Auth::user()->user_unit)){
                                $subgroupList = (!empty(Auth::user()->user_unit)) ? Auth::user()->user_unit->subgroup_info_id : 'N/A' ;
                                $subgroupArr = array();

                                $subgroupStr = "";
                                if (!empty($subgroupList)) {

                                    $subgroupArr = explode(',', $subgroupList);
                                    $subgroup = DB::table('subgroup_info')->select('id','name','group_info_id')->whereIn('id',$subgroupArr)->pluck('name')->toArray();
                                    $group = DB::table('subgroup_info')
                                            ->join('group_info','subgroup_info.group_info_id','=','group_info.id')
                                            ->where('subgroup_info.id',$subgroupList)
                                            ->first();
                                   $priority = $group->group_level_id;

                                }
                            }
                        @endphp
                    @endif
                    @if($priority==1)
                        <li class="{{($controller == 'SupportsController' && ($action == 'index' || $viewFrom == 'support')) ? 'active' : '' }}" >
                            <a class="nav-link {{($controller == 'SupportsController' && ($action == 'index' || $viewFrom == 'support')) ? 'active' : '' }}" href="{{ url('/Supports/home') }}"><i class="notika-icon notika-form"></i>
                                Service Request / Complain </a>
                        </li>
                    @endif
                @endcan

                @if( Auth::user()->cannot('superadmin','admin') && Auth::user()->can('supportExecutive') )
                    <li class="{{($controller == 'SupportsController' && ($action == 'handler' || $viewFrom == 'handler')) ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'SupportsController' && ($action == 'handler' || $viewFrom == 'handler')) ? 'active' : '' }}" href="{{ url('/Supports/handler') }}"><i class="notika-icon notika-form"></i>
                            Handler/Queue </a>
                    </li>

                    <li class="{{($controller == 'UserHandlerController' && ($action == 'userHandler' || $viewFrom == 'userHandler')) ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'UserHandlerController' && ($action == 'userHandler' || $viewFrom == 'userHandler')) ? 'active' : '' }}" href="{{ url('/Supports/user/handler') }}"><i class="notika-icon notika-form"></i>
                            User/Handler </a>
                    </li>
                @endif

                @if(Auth::user()->cannot('superadmin','admin') && Auth::user()->can('ceAnalysis') )
                    <li class="{{($controller == 'SupportsController' && $action == 'complaintClosing' || $viewFrom == 'compclosing') ? 'active' : '' }}" >
                        <a class="nav-link {{($controller == 'SupportsController' && $action == 'complaintClosing' || $viewFrom == 'compclosing') ? 'active' : '' }}" href="{{ url('/Supports/complaintClosing') }}"><i class="fa fa-file-text-o"></i>
                            CE Analysis </a>
                    </li>
                @endif

                @if(Auth::user()->hasRole('complaint_closing'))
                        <li class="{{($controller == 'SupportsController' && $action == 'complaintClosing' || $viewFrom == 'compclosing') ? 'active' : '' }}" >
                            <a class="nav-link {{($controller == 'SupportsController' && $action == 'complaintClosing' || $viewFrom == 'compclosing') ? 'active' : '' }}" href="{{ url('/Supports/complaintClosing') }}"><i class="fa fa-file-text-o"></i>
                                Complaint Closing </a>
                        </li>
                @endif

                @can('accessHoliday')
                    <li class="{{($controller == 'HolidaysController' && ($action == 'index' || $action == 'edit')) ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'HolidaysController' && ($action == 'index' || $action == 'edit')) ? 'active' : '' }}" href="{{ url('/Holidays') }}">
                            <i class="fa fa-building-o"></i> Holidays
                            @if ($holidayTmpCount > 0)
                                <span id="holidayBadge" onclick="checkerList('{{ url('/Holidays/action-queue-list') }}', 'holidayBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{ $holidayTmpCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan
                {{--@can('segment')
                <li class="{{($controller == 'SegmentCodeController' && ($action == 'index' || $action == 'edit')) ? 'active' : '' }}" >
                    <a class="nav-link {{($controller == 'SegmentCodeController' && ($action == 'index' || $action == 'edit')) ? 'active' : '' }}" href="{{ url('/segment/index') }}"><i class="fa fa-building-o"></i> Segment </a>
                </li>
                @endcan--}}
                @can('accessWorkingDay')
                    <li class="{{($controller == 'HolidaysController' && $action == 'workingDays') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'HolidaysController' && $action == 'workingDays') ? 'active' : '' }}" href="{{ url('/WorkingDays') }}">
                            <i class="fa fa-building-o"></i> Working Days
                        </a>
                    </li>
                @endcan

                @can('accessWorkingDay')
                    <li class="{{($controller == 'HolidaysController' && $action == 'workingHours') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'HolidaysController' && $action == 'workingHours') ? 'active' : '' }}" href="{{ url('/WorkingHours') }}">
                            <i class="fa fa-building-o"></i> Working Hour
                            @if ($workingHourTmpCount > 0)
                                <span id="workingHoursBadge" onclick="checkerList('{{ url('/WorkingHours/action-queue-list/') }}', 'workingHoursBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{$workingHourTmpCount}}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan

                @can('accessSMSEmail')
                    <li class="{{($controller == 'SMSEmailsController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'SMSEmailsController') ? 'active' : '' }}" href="{{ url('/SMS-Emails') }}">
                            <i class="fa fa-envelope-o"></i> SMS &amp; Mail
                            @if ($smsEmailTmpCount > 0)
                                <span id="smsEmailBadge" onclick="checkerList('{{ url('/SMS-Emails/action-queue-list/') }}', 'smsEmailBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{$smsEmailTmpCount}}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan


                @can('accessReport')
                    <li class="{{($controller == 'ReportsController' && $action == 'index') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'ReportsController' && $action == 'index') ? 'active' : '' }}" href="{{ url('/Reports') }}">
                            <i class="fa fa-file-text-o"></i> Reports
                        </a>
                    </li>
                @endcan

                @hasanyrole(['CEX_ADMIN'])
                    <li class="{{($controller == 'FeedbackController') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'FeedbackController') ? 'active' : '' }}" href="{{ route('feedback.index') }}">
                            <i class="fa fa-file-text-o"></i> Feedback
                        </a>
                    </li>
                @endhasanyrole

                @can('accessBranchCode')
                    <li class="{{($controller == 'BranchCodeController' && $action == 'index') ? 'active' : '' }}">
                        <a class="nav-link {{($controller == 'BranchCodeController' && $action == 'index') ? 'active' : '' }}" href="{{ url('branchcode') }}">
                            <i class="fa fa-file-text-o"></i> Branch Code
                            @if ($branchCodeTmpCount > 0)
                                <span id="branchCodeBadge" onclick="checkerList('{{ url('/branchcode/tmp-list') }}', 'branchCodeBadge')" class="badge bg-danger" style="cursor: pointer;">
                                    {{$branchCodeTmpCount}}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan

                
                

                @can('accessRequisitionMenu')
                    <li class="nav-item dropdown {{($controller == 'RequRolesController' || $controller == 'TableStationeryController' || $controller == 'ProductUnitController' || $controller == 'PrintingStationeryController' || $controller == 'SupportServiceController' || $controller == 'VendorController' || $controller == 'RegionController' || $controller == 'StockController'|| $controller == 'RequisitionSMSEmailsController') ? 'active' : '' }}">
                        <a href="#" class="nav-link dropdown-toggle" id="dropdownButton">
                            <i class="fa fa-building-o"></i> Requisition Module
                        </a>
                        <ul class="dropdown-menu p-1" aria-labelledby="dropdownButton">
                            @hasanyrole(['superadmin', 'admin'])
                                <li>
                                    <a class="ms-0 dropdown-item {{($controller == 'RequRolesController' && $action == 'index') ? 'active' : '' }}" href="{{ url('/requisition/roles') }}">
                                        <i class="fa fa-key"></i> Roles
                                    </a>
                                </li>
                            @endhasanyrole

                            <li>
                                <a class="ms-0 dropdown-item {{($controller == 'VendorController' && $action == 'index') ? 'active' : '' }}" href="{{ route('requisition.vendor.index') }}">
                                    <i class="fa fa-file-text-o"></i> Vendors
                                </a>
                            </li>

                            <li>
                                <a class="ms-0 dropdown-item {{($controller == 'RegionController' && $action == 'index') ? 'active' : '' }}" href="{{ route('requisition.region.index') }}">
                                    <i class="fa fa-file-text-o"></i> Regions
                                </a>
                            </li>

                            <li>
                                <a class="ms-0 dropdown-item {{($controller == 'RequisitionSMSEmailsController' && $action == 'index') ? 'active' : '' }}" href="{{ route('requisition.sms-email.index') }}">
                                    <i class="fa fa-file-text-o"></i> SMS &amp; Mail
                                </a>
                            </li>

                            <!-- Submenu for Manage Items -->
                            <li class="dropdown-submenu">
                                <a href="#" class="ms-0 dropdown-item dropdown-toggle {{
                                    in_array($controller, ['SupportServiceController', 'PrintingStationeryController', 'ProductUnitController', 'TableStationeryController']) ? 'active' : ''
                                }}">
                                    <i class="fa fa-building-o"></i> Manage Items
                                </a>
                                <ul class="dropdown-menu p-1">
                                    <li>
                                        <a class="ms-0 dropdown-item {{($controller == 'SupportServiceController') ? 'active' : '' }}" href="{{ route('requisition.support-service.index') }}">
                                            <i class="fa fa-file-text-o"></i> General Support Service
                                        </a>
                                    </li>
                                    <li>
                                        <a class="ms-0 dropdown-item {{($controller == 'PrintingStationeryController') ? 'active' : '' }}" href="{{ route('requisition.printing-stationery.index') }}">
                                            <i class="fa fa-file-text-o"></i> Printing Stationeries
                                        </a>
                                    </li>
                                    <li>
                                        <a class="ms-0 dropdown-item {{($controller == 'TableStationeryController') ? 'active' : '' }}" href="{{ route('requisition.table-stationery-item.index') }}">
                                            <i class="fa fa-file-text-o"></i> Table Stationeries
                                        </a>
                                    </li>
                                    <li>
                                        <a class=" ms-0 dropdown-item {{($controller == 'ProductUnitController') ? 'active' : '' }}" href="{{ route('requisition.unit.index') }}">
                                            <i class="fa fa-file-text-o"></i> Product Unit
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a class="ms-0 dropdown-item {{($controller == 'StockController' && $action == 'list') ? 'active' : '' }}" href="{{ route('requisition.stock.list') }}">
                                    <i class="fa fa-file-text-o"></i> Stock Management
                                </a>
                            </li>

                            <li>
                                <a class="ms-0 dropdown-item {{($controller == 'SlaConfigController' && $action == 'index') ? 'active' : '' }}" href="{{ route('requisition.sla.gss.index') }}">
                                    <i class="fa fa-file-text-o"></i> SLA Config
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endhasanyrole
        </ul>
    </div>
</nav>

<script>
    function checkerList(url, elementId) {
        var workflowTmpValue = $('#' + elementId).text().trim();
        if (workflowTmpValue && parseInt(workflowTmpValue) > 0) {
            window.location.href = url;
        }
    }
</script>
