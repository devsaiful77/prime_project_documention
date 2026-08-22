@extends('layouts.admin')

@section('content')

{!! Form::open([
    'method' => 'post',
    'action' => ['IssueGroupController@update', $issueGroup->id],
    'class' => 'form-horizontal form-label-left',
    'id' => 'formId'
]) !!}

{!! Form::token() !!}

<div class="row">
    <div class="col-md-12">

        <div class="mb-3">
            <h4>{{ $issueGroup->issueName->name }}</h4>
        </div>

        @php
            $touchGroups     = $subgroups->where('is_touch_point', 1);
            $nextLevelGroups = $subgroups->where('is_touch_point', 0);
        @endphp

        <div class="row">
            {{-- ================= NEXT LEVEL GROUPS ================= --}}
            <div class="col-md-6">
                @if($nextLevelGroups->isNotEmpty())
                    <h5 class="text-success border-bottom pb-1 mb-3">Next Level Groups</h5>

                    <div id="subgroupUserContainer">
                        @foreach($nextLevelGroups as $subgroup)

                            @php
                                $issueUsers = collect($subgroup->issueGroupMembers)->keyBy('user_id');
                            @endphp

                            <div class="card mb-3">
                                <div class="card-header fw-bold">
                                    {{ $subgroup->name }}
                                </div>

                                <div class="card-body">
                                    <div class="row">

                                        {{-- MAKER --}}
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-primary">Maker</h6>

                                            @foreach($subgroup->userUnits as $unit)
                                                @php
                                                    $roles = $unit->user?->roles->pluck('display_name')->toArray() ?? [];
                                                    $issueUser = $issueUsers[$unit->user->id] ?? null;
                                                @endphp

                                                @if(in_array('Maker', $roles))
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col-md-6">
                                                            <label class="form-check d-block">
                                                                <input type="checkbox"
                                                                    class="form-check-input maker-checkbox"
                                                                    name="users[{{ $unit->user->id }}][id]"
                                                                    value="{{ $unit->user->id }}"
                                                                    {{ $issueUser ? 'checked' : '' }}>
                                                                {{ $unit->user->name }} [{{ $unit->user->user_id }}]
                                                            </label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="number"
                                                                class="form-control form-control-sm position-input"
                                                                name="users[{{ $unit->user->id }}][position]"
                                                                value="{{ $issueUser->ordering ?? '' }}"
                                                                placeholder="Position">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        {{-- CHECKER --}}
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-success">Checker</h6>

                                            @foreach($subgroup->userUnits as $unit)
                                                @php
                                                    $roles = $unit->user?->roles->pluck('display_name')->toArray() ?? [];
                                                    $issueUser = $issueUsers[$unit->user->id] ?? null;
                                                @endphp

                                                @if(in_array('Checker', $roles))
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col-md-6">
                                                            <label class="form-check d-block">
                                                                <input type="checkbox"
                                                                    class="form-check-input checker-checkbox"
                                                                    name="users[{{ $unit->user->id }}][id]"
                                                                    value="{{ $unit->user->id }}"
                                                                    {{ $issueUser ? 'checked' : '' }}>
                                                                {{ $unit->user->name }} [{{ $unit->user->user_id }}]
                                                            </label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="number"
                                                                class="form-control form-control-sm position-input"
                                                                name="users[{{ $unit->user->id }}][position]"
                                                                value="{{ $issueUser->ordering ?? '' }}"
                                                                placeholder="Position">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ================= TOUCH GROUPS ================= --}}
            <div class="col-md-6">
                @if($touchGroups->isNotEmpty())
                    <h5 class="text-primary border-bottom pb-1 mb-3">Touch Groups</h5>

                    <div id="subgroupUserContainer">
                        @foreach($touchGroups as $subgroup)

                            @php
                                $issueUsers = collect($subgroup->issueGroupMembers)->keyBy('user_id');
                            @endphp

                            <div class="card mb-3">
                                <div class="card-header fw-bold">
                                    {{ $subgroup->name }}
                                </div>

                                @php
                                    $makerUsers = $subgroup->userUnits->filter(fn ($u) =>
                                        in_array('Maker', $u->user?->roles->pluck('display_name')->toArray() ?? [])
                                    );

                                    $checkerUsers = $subgroup->userUnits->filter(fn ($u) =>
                                        in_array('Checker', $u->user?->roles->pluck('display_name')->toArray() ?? [])
                                    );
                                @endphp

                                <div class="card-body">
                                    <div class="row">

                                        {{-- MAKER --}}
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-primary">Maker</h6>

                                            @if($makerUsers->isEmpty())
                                                <span class="text-danger no-maker">No Maker user found</span>
                                            @else
                                                @foreach($makerUsers as $unit)
                                                    {{ $unit->user->name }} [{{ $unit->user->user_id }}] <br>

                                                    {{-- <label class="form-check d-block">
                                                        <input type="checkbox"
                                                            class="form-check-input maker-checkbox"
                                                            name="users[{{ $unit->user->id }}][id]"
                                                            value="{{ $unit->user->id }}"
                                                            {{ isset($issueUsers[$unit->user->id]) ? 'checked' : '' }}>
                                                        {{ $unit->user->name }} [{{ $unit->user->user_id }}]
                                                    </label> --}}
                                                @endforeach
                                            @endif
                                        </div>

                                        {{-- CHECKER --}}
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-success">Checker</h6>

                                            @if($subgroup->touch_checker != 1)
                                                <span class="text-warning">Checker skipped</span>
                                            @elseif($checkerUsers->isEmpty())
                                                <span class="text-danger no-checker">No Checker user found</span>
                                            @else
                                                @foreach($checkerUsers as $unit)
                                                    <label class="form-check d-block">
                                                        <input type="checkbox"
                                                            class="form-check-input checker-checkbox"
                                                            name="users[{{ $unit->user->id }}][id]"
                                                            value="{{ $unit->user->id }}"
                                                            {{ isset($issueUsers[$unit->user->id]) ? 'checked' : '' }}>
                                                        {{ $unit->user->name }} [{{ $unit->user->user_id }}]
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>





    </div>
</div>

<div class="ln_solid"></div>
<div class="form-group mt-2">
    <button type="submit" class="btn btn-primary">Update</button>
    <button type="button" class="btn btn-info" onclick="cancel('/issue/group')">Back</button>
</div>

{!! Form::close() !!}
@endsection
