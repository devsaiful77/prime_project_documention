@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>{{ $issueGroup->issueName->name }}</h4>
                <a href="{{ url('issue/group') }}" class="btn btn-primary btn-sm float-right m-1">Back</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @php
                    $grouped = $issueGroupMember;
                @endphp
                <!-- Tabs Nav -->
                <ul class="nav nav-tabs" id="subgroupTab" role="tablist">
                    @foreach($grouped as $subgroupId => $members)
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link {{ $loop->first ? 'active' : '' }}"
                                id="tab-{{ $subgroupId }}"
                                data-bs-toggle="tab"
                                data-bs-target="#content-{{ $subgroupId }}"
                                type="button"
                                role="tab"
                                aria-controls="content-{{ $subgroupId }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $members[0]->subgroup->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>
                <!-- Tabs Content -->
                <div class="tab-content mt-3" id="subgroupTabContent">
                    @foreach($grouped as $subgroupId => $members)
                        <div
                            class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                            id="content-{{ $subgroupId }}"
                            role="tabpanel"
                            aria-labelledby="tab-{{ $subgroupId }}">

                            <h5>{{ $members[0]->is_touch_point == 1 ? 'Touch Group' : 'Next Level Group' }}</h5>

                            <ul class="list-group">
                                @foreach($members as $member)
                                    <li class="list-group-item">
                                        {{ $member->user->name }}
                                        (User ID: {{ $member->user_id }}, Sequence: {{ $member->ordering ?? '' }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
