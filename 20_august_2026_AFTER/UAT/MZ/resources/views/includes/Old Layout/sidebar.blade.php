<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ url('/') }}" class="site_title" width="204"><img class="img-responsive" src="{{ URL::asset('public/img/img_logo.png') }}"  alt="LANKA BANGLA" style="height: 50px; width: 100%;"/> 
          </a> 
        </div>
        <div class="clearfix"></div>
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                    <li class="{{($controller == 'HomeController') ? 'active' : '' }}" ><a href="{{ url('/Home') }}"><i class="fa fa-home"></i> Manage Home </a></li>

                    @role('admin')
                    <li class="{{($controller == 'UsersController') ? 'active' : '' }}" ><a href="{{ url('/Users') }}"><i class="fa fa-user"></i> Manage Users </a></li>
                    <li class="{{($controller == 'RolesController') ? 'active' : '' }}" ><a href="{{ url('/roles') }}"><i class="fa fa-users"></i> Manage Roles </a></li>
                    @endrole

                    <li class="{{($controller == 'DepartmentsController') ? 'active' : '' }}" ><a href="{{ url('/Departments') }}"><i class="fa fa-building"></i> Manage Departments </a></li>
                    <li class="{{($controller == 'GroupsController') ? 'active' : '' }}" ><a href="{{ url('/Groups') }}"><i class="fa fa-building"></i> Manage Groups </a></li>
                    <li class="{{($controller == 'SubGroupsController') ? 'active' : '' }}" ><a href="{{ url('/SubGroups') }}"><i class="fa fa-building"></i> Manage Sub-Groups </a></li>

                    @permission(['CIReqView'])
                    <li class="{{($controller == 'CustomerInfosController') ? 'active' : '' }}" ><a href="{{ url('/CustomerInfos') }}"><i class="fa fa-user-secret"></i> Customer Information </a></li>
                    @endpermission

                    <li class="{{($controller == 'SupportsController') ? 'active' : '' }}" ><a href="{{ url('/Supports/home') }}"><i class="fa fa-gear"></i> W-From / Complain </a></li>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>