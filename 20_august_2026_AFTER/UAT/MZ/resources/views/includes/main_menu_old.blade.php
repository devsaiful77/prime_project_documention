<style type="text/css">
.main-menu-area {
    height: 40px;
}
ul.notika-menu-wrap li a {
    padding:9px 25px;
}
</style>
<?php $viewFrom = (!empty($_GET['viewFrom'])) ? $_GET['viewFrom'] : ''; ?>
<div class="main-menu-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro modified-menu">
                    @ability('superadmin,admin','accessDashboard')
                    <li class="{{($controller == 'DashboardsController') ? 'active' : '' }}" ><a href="{{ url('/Dashboards') }}"><i class="fa fa-tachometer"></i> Dashboard </a></li>
                    @endability
                    {{--
                    <li class="{{($controller == 'HomeController') ? 'active' : '' }}" > <a href="{{ url('/Home') }}"><i class="notika-icon notika-house"></i> Home </a> </li>
                    --}}
                    @ability('superadmin,admin','accessUser')
                        <li class="{{($controller == 'UsersController'  && $action == 'index') ? 'active' : '' }}" ><a href="{{ url('/Users') }}"><i class="notika-icon notika-support"></i> Users </a></li>
                    @endability
                    @ability('superadmin,admin','xyz')
                        <li class="{{($controller == 'LogUsersController') ? 'active' : '' }}" ><a href="{{ url('/logUsers') }}"><i class="notika-icon notika-support"></i> Log Users </a></li>
                    <li class="{{($controller == 'RestrictedIpController') ? 'active' : '' }}" ><a href="{{ url('/restrictedIps') }}"><i class="notika-icon notika-support"></i> Restricted Ip </a></li>
                    <li class="{{($controller == 'RolesController') ? 'active' : '' }}" ><a href="{{ url('/roles') }}"><i class="notika-icon notika-support"></i> Roles </a></li>
                    @endability
                    @role(['superadmin', 'admin'])
                    <li class="{{($controller == 'SettingController') ? 'active' : '' }}" ><a href="{{ url('/settings') }}"><i class="notika-icon notika-support"></i> Settings </a></li>
						{{--<li class="{{($controller == 'RolesController') ? 'active' : '' }}" ><a href="{{ url('/audits') }}"><i class="notika-icon notika-support"></i> Audits </a></li>--}}
                    @endrole
                    @ability('superadmin,admin','accessProductType')
                        <li class="{{($controller == 'ProductTypesController') ? 'active' : '' }}" ><a href="{{ url('/ProductTypes') }}"><i class="fa fa-credit-card"></i> Product Types </a></li>
                    @endability
                    @ability('superadmin,admin','accessDivision')
                        <li class="{{($controller == 'DivisionsController') ? 'active' : '' }}" ><a href="{{ url('/Divisions') }}"><i class="fa fa-building-o"></i> Divisions </a></li>
                    @endability

                    @ability('superadmin,admin','accessDepartment')
                        <li class="{{($controller == 'DepartmentsController') ? 'active' : '' }}" ><a href="{{ url('/Departments') }}"><i class="fa fa-building-o"></i> Departments </a></li>
                    @endability
                    @ability('superadmin,admin','accessGroup')
                    {{--<li><a href="{{ url('group-level') }}" ><i class="fa fa-building-o"></i> Group Level</a></li>--}}
                    <li class="{{($controller == 'GroupInfoController') ? 'active' : '' }}" ><a href="{{ url('/group-info') }}"><i class="fa fa-building-o"></i> Groups </a></li>
                    @endability
                    @ability('superadmin,admin','accessSubgroup')
                    <li class="{{($controller == 'SubgroupInfoController') ? 'active' : '' }}" ><a href="{{ url('/subgroup-info') }}"><i class="fa fa-building-o"></i> Subgroups </a></li>
                    @endability
                    @ability('superadmin,admin','accessUnit')
                        <li class="{{($controller == 'UnitsController') ? 'active' : '' }}" ><a href="{{ url('/Units') }}"><i class="fa fa-building-o"></i> Units </a></li>
                    @endability
                    @ability('superadmin,admin','accessUnit')
                    {{--<li class="{{($controller == 'SubgroupUnitController') ? 'active' : '' }}" ><a href="{{ url('/unit-assign') }}"><i class="fa fa-building-o"></i> Unit Assign </a></li>--}}
                    @endability
                    @ability('superadmin,admin','accessIssueCategories')
					<li class="{{($controller == 'UnitItemsController' && $action == 'issuesCategory') ? 'active' : '' }}" ><a href="{{ url('/Issues-category') }}"><i class="fa fa-cubes"></i> Issue Category </a></li>
                    @endability
                    @ability('superadmin,admin','accessIssues')
                    <li class="{{($controller == 'UnitItemsController' && $action == 'index') ? 'active' : '' }}" ><a href="{{ url('/Issues') }}"><i class="fa fa-cubes"></i> Issues </a></li>
                    @endability
                    @ability('superadmin,admin','accessWorkflow')
                    <li class="{{($controller == 'WorkflowController' && $action == 'index') ? 'active' : '' }}"><a href="{{ url('workflow') }}">Workflow</a></li>
                    @endability
                    @ability('superadmin,admin','accessSubflow')
                    <li class="{{($controller == 'WorkflowController' && $action == 'subflow') ? 'active' : '' }}"><a href="{{ url('workflow/subflow') }}"><i class="fa fa-random" aria-hidden="true"></i> Subflow</a></li>
                    @endability

                    @ability('superadmin,admin','accessBondUpload')
                    <li class="{{($controller == 'BondInfosController' && ($action == 'categoryIndex' || $action == 'categoryAdd' || $action == 'categoryEdit' || $action == 'subCategoryAdd' || $action == 'subCategoryEdit') ) ? 'active' : '' }}"><a href="{{ url('bond-info/category') }}"><i class="fa fa-file-text-o" aria-hidden="true"></i>Product Info Category</a></li>
                    @endability

                    @ability('superadmin,admin','accessBondUpload,accessBondInformation')
                    <li class="{{($controller == 'BondInfosController' && ($action == 'bondInfoList'||$action == 'bondInfoUpload') ) ? 'active' : '' }}"><a href="{{ url('bond-info/list') }}"><i class="fa fa-file-text" aria-hidden="true"></i>Product Info</a></li>
                    @endability

                    {{--
                    <li class="{{($controller == 'WorkflowController' && $action == 'specialPermission') ? 'active' : '' }}"><a href="{{ url('workflow/special-permission') }}">Workflow Special</a></li>
                    --}}

                    @ability('superadmin,admin','wformComplain')
                    @php
                        $priority='';
                            @endphp
                    @ability('logger','wformComplain')

                    <?php
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
                    ?>

                    @endability
                        @if($priority==1)
                        <li class="{{($controller == 'SupportsController' && ($action == 'index' || $viewFrom == 'support')) ? 'active' : '' }}" ><a href="{{ url('/Supports/home') }}"><i class="notika-icon notika-form"></i> Service Request / Complain </a></li>
                        @endif
                        @endability

                    @permission('supportExecutive')
                        <li class="{{($controller == 'SupportsController' && ($action == 'handler' || $viewFrom == 'handler')) ? 'active' : '' }}" ><a href="{{ url('/Supports/handler') }}"><i class="notika-icon notika-form"></i> Handler/Queue </a></li>
                    @endpermission

                    @permission('ceAnalysis')
                        <li class="{{($controller == 'SupportsController' && $action == 'complaintClosing' || $viewFrom == 'compclosing') ? 'active' : '' }}" ><a href="{{ url('/Supports/complaintClosing') }}"><i class="fa fa-file-text-o"></i> CE Analysis </a></li>
                    @endpermission

                    @ability('superadmin,admin','accessHoliday')
                        <li class="{{($controller == 'HolidaysController' && ($action == 'index' || $action == 'edit')) ? 'active' : '' }}" ><a href="{{ url('/Holidays') }}"><i class="fa fa-building-o"></i> Holidays </a></li>
                    @endability
                    @ability('superadmin,admin','accessWorkingDay')
                        <li class="{{($controller == 'HolidaysController' && $action == 'workingDays') ? 'active' : '' }}" ><a href="{{ url('/WorkingDays') }}"><i class="fa fa-building-o"></i> Working Days </a></li>
                    @endability
                    @ability('superadmin,admin','accessWorkingDay')
                        <li class="{{($controller == 'HolidaysController' && $action == 'workingHours') ? 'active' : '' }}" ><a href="{{ url('/WorkingHours') }}"><i class="fa fa-building-o"></i> Working Hour </a></li>
                    @endability
                    @ability('superadmin,admin','accessReport')
                    <li class="{{($controller == 'ReportsController') && $action == 'index' ? 'active' : '' }}" ><a href="{{ url('/Reports') }}"><i class="fa fa-file-text-o"></i> Reports </a></li>
                    {{--
                    <li class="{{($controller == 'ReportsController') && $action=='workingStatusReport' ? 'active' : '' }}" ><a href="{{ url('/working-status-reports') }}"><i class="fa fa-file-text-o"></i> Working Status Reports </a></li>
                    <li class="{{($controller == 'ReportsController') && $action=="complainPendingReport" ? 'active' : '' }}" ><a href="{{ url('/complain-pending-reports') }}"><i class="fa fa-file-text-o"></i> Complain Pending list </a></li>
                    <li class="{{($controller == 'ReportsController') && $action=='dashboardReport' ? 'active' : '' }}" ><a href="{{ url('/dashboard-reports') }}"><i class="fa fa-file-text-o"></i> Dashboard report </a></li> --}}
                    @endability
                    @ability('superadmin,admin','accessSMSEmail')
                        <li class="{{($controller == 'SMSEmailsController') ? 'active' : '' }}" ><a href="{{ url('/SMS-Emails') }}"><i class="fa fa-envelope-o"></i> SMS &amp; Mail </a></li>
                    @endability

					<!-- <li class="{{($controller == 'UsersController' && $action == 'resetPassword') ? 'active' : '' }}" ><a href="{{ url('/ResetPassword') }}"><i class="notika-icon notika-support"></i> Change Password </a></li> -->

                    <!-- <li> <a href="#Forms"><i class="notika-icon notika-form"></i> Forms</a></li>
                    <li><a href="#mailbox"><i class="notika-icon notika-mail"></i> Email</a> </li>
                    <li><a href="#Interface"><i class="notika-icon notika-edit"></i> Interface</a> </li>
                    <li><a href="#Charts"><i class="notika-icon notika-bar-chart"></i> Charts</a> </li>
                    <li><a href="#Tables"><i class="notika-icon notika-windows"></i> Tables</a> </li>
                    <li><a href="#Appviews"><i class="notika-icon notika-app"></i> App views</a> </li>
                    <li><a href="#Page"><i class="notika-icon notika-support"></i> Pages</a> </li> -->
                </ul>

            </div>
        </div>
    </div>
</div>
