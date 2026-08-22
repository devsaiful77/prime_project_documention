<?php $viewFrom = (!empty($_GET['viewFrom'])) ? $_GET['viewFrom'] : ''; ?>
<div class="mobile-menu-area"> <div class="container-fluid"> <div class="row"> <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="mobile-menu">
        <nav id="dropdown">
            <ul class="mobile-menu-nav">
                {{--
                    <li class="{{($controller == 'HomeController') ? 'active' : '' }}" > <a href="{{ url('/Home') }}"><i class="notika-icon notika-house"></i> Home </a> </li>
                --}}
                @role('superadmin')
                    <li class="{{($controller == 'UsersController') ? 'active' : '' }}" ><a href="{{ url('/Users') }}"><i class="notika-icon notika-support"></i> Users </a></li>
                    <li class="{{($controller == 'RolesController') ? 'active' : '' }}" ><a href="{{ url('/roles') }}"><i class="notika-icon notika-support"></i> Roles </a></li>
                @endrole
                @can('accessProductType')
                    <li class="{{($controller == 'ProductTypesController') ? 'active' : '' }}" ><a href="{{ url('/ProductTypes') }}"><i class="fa fa-credit-card"></i> Product Types </a></li>
                @endcan
                @can('accessDivision')
                    <li class="{{($controller == 'DivisionsController') ? 'active' : '' }}" ><a href="{{ url('/Divisions') }}"><i class="fa fa-building-o"></i> Divisions </a></li>
                @endcan
                @can('accessDepartment')
                    <li class="{{($controller == 'DepartmentsController') ? 'active' : '' }}" ><a href="{{ url('/Departments') }}"><i class="fa fa-building-o"></i> Departments </a></li>
                @endcan
                @can('accessGroup')
                    {{--<li><a href="{{ url('group-level') }}" ><i class="fa fa-building-o"></i> Group Level</a></li>--}}
                    <li class="{{($controller == 'GroupInfoController') ? 'active' : '' }}" ><a href="{{ url('/group-info') }}"><i class="fa fa-building-o"></i> Groups </a></li>
                @endcan
                @can('accessSubgroup')
                    <li class="{{($controller == 'SubgroupInfoController') ? 'active' : '' }}" ><a href="{{ url('/subgroup-info') }}"><i class="fa fa-building-o"></i> Subgroups </a></li>
                @endcan
                @can('accessUnit')
                    <li class="{{($controller == 'UnitsController') ? 'active' : '' }}" ><a href="{{ url('/Units') }}"><i class="fa fa-building-o"></i> Units </a></li>
                @endcan
                @can('accessItem')
                    <li class="{{($controller == 'UnitItemsController') ? 'active' : '' }}" ><a href="{{ url('/Issues') }}"><i class="fa fa-cubes"></i> Issues </a></li>
                @endcan
                @can('wformComplain')
                    <li class="{{($controller == 'SupportsController' && ($action == 'index' || $viewFrom == 'support')) ? 'active' : '' }}" ><a href="{{ url('/Supports/home') }}"><i class="notika-icon notika-form"></i> W-From / Complain </a></li>
                @endcan

                @can('supportExecutive')
                    <li class="{{($controller == 'SupportsController' && ($action == 'handler' || $viewFrom == 'handler')) ? 'active' : '' }}" ><a href="{{ url('/Supports/handler') }}"><i class="notika-icon notika-form"></i> Handler/Queue </a></li>
                @endcan
                @can('accessHoliday')
                    <li class="{{($controller == 'HolidaysController' && ($action == 'index' || $action == 'edit')) ? 'active' : '' }}" ><a href="{{ url('/Holidays') }}"><i class="fa fa-building-o"></i> Holidays </a></li>
                @endcan
                @can('accessWorkingDay')
                    <li class="{{($controller == 'HolidaysController' && $action == 'workingDays') ? 'active' : '' }}" ><a href="{{ url('/WorkingDays') }}"><i class="fa fa-building-o"></i> Working Days </a></li>
                @endcan
                @can('accessWorkingDay')
                    <li class="{{($controller == 'HolidaysController' && $action == 'workingHours') ? 'active' : '' }}" ><a href="{{ url('/WorkingHours') }}"><i class="fa fa-building-o"></i> Working Hour </a></li>
                @endcan

                @can('accessReport')
                    <li class="{{($controller == 'ReportsController') ? 'active' : '' }}" ><a href="{{ url('/Reports') }}"><i class="fa fa-file-text-o"></i> Reports </a></li>
                @endcan
            </ul>
        </nav>
    </div>
</div> </div> </div> </div>
