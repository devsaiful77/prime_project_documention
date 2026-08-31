@extends('layouts.admin')
@section('content')

{!!
    Form::open([
    'method'=>'post',
    'action' => ['IssueGroupController@store'] ,
    'id'=>'formId',
    'class'=>'form-horizontal form-label-left',
    'enctype' => 'multipart/form-data'
    ]);
!!}
  {!! Form::token(); !!}

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

  <input type="hidden" name="tmpId" value="{{ $tmpId ?? '' }}">
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="workflow_id">Issue <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control select2" name="workflow_id" id="workflowId" required>
                <option value="">Select Issue</option>
                @foreach($workflow as $work)
                    <option value="{{ $work->issue_workflow_id }}">
                        {{ $work->issue->name }}
                    </option>
                @endforeach
            </select>
        </div>
      <div class="error">{{ $errors->first('workflow_id') }}</div>
    </div>


    {{-- subgroup wise user --}}
    <div id="subgroupUserContainer" style="margin-top: 15px"></div>


    <div class="ln_solid">&nbsp;</div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <?php $additionalParams = (!empty($searchDataForView)) ? '?'.http_build_query($searchDataForView) : ""; ?>
            {{ Form::hidden('additionalParams',$additionalParams) }}

            {!!
            Form::submit((!empty($id)) ? 'Update':'Submit',array(
                'class'=>'btn btn-primary gradient',
                'title'=>'Add',
                'escape'=>false
            ));
            !!}
            <button type="button" class="btn btn-info gradient" onclick="cancel('/issue/group{{ $additionalParams}}')">Back</button>
        </div>
    </div>

    <input type="hidden" id="oldSubgroups" value='@json(old("subgroup_info_id", $dataForView["subgroup_info_id"] ?? []))'>
    <input type="hidden" id="oldUsers" value='@json(old("user_ids", $dataForView["user_ids"] ?? []))'>


{!! Form::close(); !!}
@endsection


@section('script')
    <script>

        $(document).ready(function () {

            $('.select2').select2();

            $('#workflowId').on('change', function () {
                let workflowId = $(this).val();

                $.ajax({
                    url: '/workflow-wise/subgroup/user',
                    type: "POST",
                    data: {
                        workflowId: workflowId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {

                        let $container = $('#subgroupUserContainer');
                        $container.html(response); // clear previous data
                    }
                });
            });

        });
    </script>

    {{-- Validation Message --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const form = document.getElementById('formId');

            form.addEventListener('submit', function (e) {

                let valid = true;
                let message = '';

                document.querySelectorAll('#subgroupUserContainer .card').forEach(card => {

                    /* If No Maker / No Checker message exists → stop submit */
                    if (card.querySelector('.no-maker')) {
                        valid = false;
                        message = 'Maker user not found in one of the subgroups';
                        return;
                    }

                    if (card.querySelector('.no-checker')) {
                        valid = false;
                        message = 'Checker user not found in one of the subgroups';
                        return;
                    }

                    function validateRole(checkboxClass, roleName) {

                        const allBoxes     = card.querySelectorAll('.' + checkboxClass);
                        const checkedBoxes = card.querySelectorAll('.' + checkboxClass + ':checked');

                        if (allBoxes.length === 0) return true;

                        if (checkedBoxes.length === 0) {
                            valid = false;
                            message = roleName + ' requires at least one user';
                            return false;
                        }

                        let positions = [];

                        checkedBoxes.forEach(cb => {
                            const row = cb.closest('.align-items-center');
                            const pos = row.querySelector('.position-input');

                            if (!pos || pos.value === '') {
                                valid = false;
                                message = roleName + ' position is required';
                                return;
                            }

                            positions.push(parseInt(pos.value));
                        });

                        if (!valid) return false;

                        if (new Set(positions).size !== positions.length) {
                            valid = false;
                            message = roleName + ' position must be unique';
                            return false;
                        }

                        positions.sort((a, b) => a - b);
                        for (let i = 0; i < positions.length; i++) {
                            if (positions[i] !== i + 1) {
                                valid = false;
                                message = roleName + ' position must be sequential (1,2,3...)';
                                return false;
                            }
                        }

                        return true;
                    }

                    if (
                        !validateRole('maker-checkbox', 'Maker') ||
                        !validateRole('checker-checkbox', 'Checker')
                    ) {
                        return;
                    }

                });

                if (!valid) {
                    e.preventDefault();
                    alert(message);
                }

            });

        });
    </script>



@endsection

