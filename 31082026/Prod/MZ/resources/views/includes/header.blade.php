<style type="text/css">
    .bd-highlight {
        color: white;
    }
    .bd-highlight a {
        color: white;
        text-decoration: none;
    }
    .bd-highlight a:hover {
        background-color: rgb(104, 175, 246);
        color: rgb(32, 178, 170);
    }
</style>

<div class="d-flex align-items-center main-header-wrap" style="height: 65px; background-color: #03427e;">
    <div class="px-2 flex-grow-1 bd-highlight">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ URL::asset('public/img/logo/logo.png') }}" alt="Prime Bank PLC." style="height: 50px;">
        </a>
    </div>

    <div class="py-3 px-1 bd-highlight">{{ Auth::user()->user_id }}</div>
    <div class="py-3 px-1 bd-highlight">[&nbsp;{{ Auth::user()->name ?? 'N/A' }}&nbsp;]</div>

    @cannot('admin')
        @if(Auth::user()->hasRole(['Executive','logger']) || Auth::user()->canany(['wformComplain','supportExecutive']))
            @php
                $userUnitList = Auth::user()->user_unit->unit_id ?? 'N/A';
                $subgroupList = Auth::user()->user_unit->subgroup_info_id ?? 'N/A';
                $unitListStr = "";
                $subgroupStr = "";
                $groupName = \App\GroupInfo::find(Auth::user()->user_unit->group_info_id ?? null);
                $deptName = \App\Department::find(Auth::user()->user_unit->department_id ?? null);

                if ($userUnitList) {
                    $unitList = DB::table('units')->whereIn('id', explode(',', $userUnitList))->pluck('name')->toArray();
                    $subgroup = DB::table('subgroup_info')->whereIn('id', explode(',', $subgroupList))->pluck('name')->toArray();

                    $unitListStr = implode(',', $unitList);
                    $subgroupStr = implode(',', $subgroup);

                    Session::put('subgroupStr', $subgroupStr.' [ '.$unitListStr.' ]');
                }
            @endphp

            @if(empty($groupName->name) && empty($deptName->name))
                <div class="py-3 px-1 bd-highlight">{{ $subgroupStr.' [ '.$unitListStr.' ]' }}</div>
            @endif
            @if($groupName)
                <div class="py-3 px-1 bd-highlight">{{ $groupName->name.' [ '.$unitListStr.' ]' }}</div>
            @endif
            @if($deptName)
                <div class="py-3 px-1 bd-highlight">{{ $deptName->name.' [ '.$unitListStr.' ]' }}</div>
            @endif
        @else
            <div class="py-3 px-1 bd-highlight">[&nbsp;{{ Auth::user()->roles->pluck("display_name")->first() ?? 'N/A' }}&nbsp;]</div>
        @endif
    @else
        <div class="py-3 px-1 bd-highlight">[&nbsp;{{ Auth::user()->roles->pluck("display_name")->first() ?? 'N/A' }}&nbsp;]</div>
    @endcannot

    <div class="py-3 px-1 bd-highlight">
        <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout&nbsp;<i class="fa fa-sign-out-alt"></i>
        </a>
        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
</div>
