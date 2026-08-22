<style type="text/css">
.header-top-area {
    height: 40px;
}
.logo-area {
    padding: 0px 0px;
}
.nav.navbar-nav.notika-top-nav li a {
    padding:10px 0px 10px 10px;
    font-size: 14px;
}
</style>

<div class="header-top-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <div class="logo-area">
                    <a href="{{ url('/') }}" class="site_title" width="204"><img class="img-responsive" src="{{ URL::asset('public/img/logo/logo.png') }}"  alt="BRAC Bank Limited" style="height: 38px; width: 50%;"/> </a>
                </div>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                <div class="header-top-menu">
                    <ul class="nav navbar-nav notika-top-nav">
                        <li class="nav-item">
                            <a aria-expanded="false" href="javascript:void(0);">{{ Auth::user()->user_id }}</a>
                        </li>
                        <li class="nav-item">
                            <a aria-expanded="false" href="javascript:void(0);">[ {{ userRoles() }} ] </a>
                        </li>
                        @ability('Executive,logger','wformComplain,supportExecutive')
                            {{--{{ dd(Auth::user()->ability(array('logger', 'Admin','X'),'')) }}--}}
                            <?php
                            $userUnitList = (!empty(Auth::user()->user_unit)) ? Auth::user()->user_unit->unit_id : 'N/A' ;
                            $subgroupList = (!empty(Auth::user()->user_unit)) ? Auth::user()->user_unit->subgroup_info_id : 'N/A' ;
                            $userUnitArr = array();
                            $subgroupArr = array();
                            $unitListStr = "";
                            $subgroupStr = "";
                            $groupName = "";
                            $deptName = "";
                            
                            if(!empty(Auth::user()->user_unit->group_info_id)){
                            $groupName = \App\GroupInfo::where('id', Auth::user()->user_unit->group_info_id)->first();
                            }

                            if(!empty(Auth::user()->user_unit->department_id)){
                            $deptName = \App\Department::where('id', Auth::user()->user_unit->department_id)->first();
                            }
                            //dd($deptName);
                            if (!empty($userUnitList)) {
                                $userUnitArr = explode(',', $userUnitList);
                                $subgroupArr = explode(',', $subgroupList);
                                $unitList = DB::table('units')->select('id','name')->whereIn('id',$userUnitArr)->pluck('name')->toArray();
                                
                                $subgroup = DB::table('subgroup_info')->select('id','name')->whereIn('id',$subgroupArr)->pluck('name')->toArray();
                                if (!empty($unitList)) {
                                    $unitListStr = implode(',', $unitList);
                                }
                                if(!empty($subgroup)){
                                    $subgroupStr = implode(',', $subgroup);
									Session::put('subgroupStr', $subgroupStr.' [ '.$unitListStr.' ]');
                                }
                            }
                            ?>
                            <li class="nav-item">

                                @if(empty($groupName->name) && empty($deptName->name))
                                <a aria-expanded="false" href="javascript:void(0);">{{ $subgroupStr.' [ '.$unitListStr.' ]' }} </a>
                                @endif
                                @if(!empty($groupName->name))
                                    <a aria-expanded="false" href="javascript:void(0);">{{ $groupName->name.' [ '.$unitListStr.' ]' }} </a>
                                @endif
                                @if(!empty($deptName->name))
                                <a aria-expanded="false" href="javascript:void(0);">{{ $deptName->name.' [ '.$unitListStr.' ]' }} </a>
                                @endif
                            </li>
                        @endability
                        <li class="nav-item">
                            <a role="button" class="nav-link" href="{{ url('/logout') }}"onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> Logout <i class="glyphicon glyphicon-log-out"></i></a> <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;"> {{ csrf_field() }} </form>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
