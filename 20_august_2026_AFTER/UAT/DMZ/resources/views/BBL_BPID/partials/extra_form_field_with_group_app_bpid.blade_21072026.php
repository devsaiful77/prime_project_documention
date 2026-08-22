<style>
    label{
        color: white !important;
    }

    .fieldset-toggle-box {
        display: flex !important;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        width: 100% !important;
        min-height: 36px;
        padding: 8px 12px !important;
        border: 1px solid #cfd6df !important;
        border-radius: 6px;
        background: #f8fafc;
        color: #1f2937 !important;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.2;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    }

    .mobile_fieldset fieldset {
        margin: 0 0 5px 0;
        border: 0;
    }

    .fieldset-toggle-box .toggle-icon {
        font-size: 12px;
        line-height: 1;
    }

    #body_BPID_second_applicant,
    #body_BPID_third_applicant,
    #body_BPID_fourth_applicant,
    #body_BPID_second_nominee,
    #body_BPID_third_nominee,
    #body_BPID_fourth_nominee {
        display: none;
    }

    .mobile_fieldset legend{
        margin-bottom: 0px;
    }

</style>
<?php
$input_checkbox = '';
$input_radio = '';
$input_dropdown = '';
$i=1;

$issueId = '';
if (!empty($issue_id)) {
    $issueId = $issue_id;
}

$bpidValues = [];
if ($issueId == 1193 && !empty($bpid_data)) {
    $bpidValues = [
        'bpId'            => $bpid_data->bp_id,
        'accountNumber'   => $bpid_data->account_number,
        'branchName'      => $bpid_data->branch_name,
        'accountTitle'    => $bpid_data->account_title,
        'firstAppMobile'  => $bpid_data->contact_no_1,
        'firstAppEmail'   => $bpid_data->email_1,
        'secondAppMobile' => $bpid_data->contact_no_2,
        'secondAppEmail'  => $bpid_data->email_2,
        'thirdAppMobile'  => $bpid_data->contact_no_3,
        'thirdAppEmail'   => $bpid_data->email_3,
        'fourthAppMobile' => $bpid_data->contact_no_4,
        'fourthAppEmail'  => $bpid_data->email_4,
    ];

    //  dd($bpidValues);
}
?>
    @foreach($issue_fields as $single)
        <!-- Field without Fieldset -->
        @if($single['fieldset_title'] == "")
            @foreach($single['fields'] as $key => $r)
                @php
                    $PApiKey = $r['api_key'];
                    $PApiKeyArr = explode(':', $PApiKey);
                    $PApiKeyId = $PApiKeyArr[0];
                @endphp

                @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)
                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                        <input id="{{ $PApiKeyId }}" type="{{ $r->field_type }}" class="input-field text_eng" name="{{ $r->field_name }}" value="{{ $bpidValues[$PApiKeyId] ?? old($r->field_name) }}"  placeholder="{{ $r->placeholder }}">
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>

                    @elseif($r->field_type == \App\Enum\FieldTypeEnum::FILE)
                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                        <input id="{{ $PApiKeyId }}" type="file" class="input-field text_eng" name="{{ $r->field_name }}">
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>


                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                        <div class="input-wrapper mb-1 {{ $r->field_name }}">
                            <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                            <input id="{{ $PApiKeyId }}" type="text" class="input-field datepickerPrev" name="{{ $r->field_name }}" placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                            <script nonce="{{ app('csp_nonce') }}">
                                $(function() {
                                $(".datepickerPrev").datepicker({
                                    defaultDate: 0,
                                    maxDate: 0,
                                    dateFormat: 'dd-mm-yy',
                                    showButtonPanel: true,
                                    changeYear: true,
                                    changeMonth: true,
                                    yearRange: "1900:2033",
                                });
                                });
                            </script>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::NEXT_DATE)
                        <div class="input-wrapper mb-1 {{ $r->field_name }}">
                            <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                            <input id="{{ $PApiKeyId }}" type="text" class="input-field datepickerNext" name="{{ $r->field_name }}" placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                            <script nonce="{{ app('csp_nonce') }}">
                                $(function() {
                                    $(".datepickerNext").datepicker({
                                        minDate: 1,
                                        dateFormat: 'dd-mm-yy',
                                        showButtonPanel: true,
                                        changeYear: true,
                                        changeMonth: true,
                                        yearRange: "1900:2050",
                                    });
                                });
                            </script>
                        </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::DROPDOWN)
                    <div class="dropdown-wrapper mb-3 {{ $r->field_name }}">
                        <label class="mb-1">
                            {{ $r->label_name }} <span class="required" >@if($r->is_required == 1){{'*'}} @endif </span>
                        </label>
                        <select id="{{ $PApiKeyId }}" class="dropdown-select DependantFields fieldset_select2" name="{{ $r->field_name }}" data-id="{{ $r->id }}">
                            @php
                                $options = explode(",", $r->options);
                            @endphp
                            @if(count($options) != 1)
                                <option value="">Please Select</option>
                            @endif
                            @foreach($options as $k => $option)
                                @php
                                    $selected  = "";
                                    $option_name = $option;
                                    $old = old($r->field_name);
                                    if (str_contains($option_name,'~')) {
                                        $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                    }
                                    if (!empty($old) && str_contains($old,'~')) {
                                        $old = substr($old, strpos($old, "~") + 1);
                                    }
                                    if($option_name == $old){
                                        $selected  = "selected";
                                    }
                                @endphp
                                <option value="{{ $option }}" {{$selected}}>{{ $option_name }}</option>
                            @endforeach
                        </select>
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>
                @elseif( $r->field_type == \App\Enum\FieldTypeEnum::RADIO)
                    <div class="form-check mb-3 p-0 {{ $r->field_name }}">
                        <label class="form-check-label d-block mb-2" style="color : white">
                            {{ $r->label_name }}<span class="required">@if( $r->is_required == 1 ) {{'*'}} @endif</span>
                        </label>
                        @php $options = explode(",", $r->options); @endphp
                        @foreach($options as $key => $option)
                            @php
                                $selected  = "";
                                if($option == old($r->field_name)){
                                    $selected  = "checked";
                                }
                            @endphp

                            <div class="form-check d-inline-block me-2">
                                <input id="{{ $PApiKeyId }}" class="form-check-input" type="radio" name="{{ $r->field_name }}" id="{{ "radio" . $key }}" value="{{ $option }}"
                                    {{ $selected }}>
                                <label class="form-check-label" for="{{ "radio" . $key }}">
                                    {{ $option }}
                                </label>
                            </div>
                        @endforeach
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>
                @elseif( $r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA )
                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                        <textarea id="{{ $PApiKeyId }}" rows="1" class="input-field text_eng" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" placeholder="{{ $r->placeholder }}">{{ old($r->field_name) }}</textarea>
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)
                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                        <input id="{{ $PApiKeyId }}" type="text" class="input-field text_eng" name="{{ $r->field_name }}" value="{{ old($r->field_name )}}" placeholder="{{ $r->placeholder }}">
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)
                    <div class="form-check mb-3 p-0 {{ $r->field_name }}">
                        @php
                            $options = '';
                            $input_checkbox = '';
                            $options = explode(",", $r->options);
                        @endphp

                        <label class="form-check-label d-block mb-2" style="color : white">
                            {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                        </label>

                        @foreach($options as $key => $option)
                            @php
                                $value = $option;
                                if(empty($option)) {
                                    $value = 'Yes';
                                }
                                $selected  = "";
                                if($value == old($r->field_name)){
                                    $selected  = "checked";
                                }
                            @endphp

                            <div class="form-check d-inline-block me-2">
                                <input id="{{ $PApiKeyId }}" class="form-check-input" type="checkbox" name="{{ $r->field_name }}" value="{{ $value }}"
                                    {{ $selected }}>
                                <label class="form-check-label" style="color: white !important;">
                                    {{ $option }}
                                </label>
                            </div>
                        @endforeach
                        <div class="{{ $r->field_name }}_err error-message"></div>

                    </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)
                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                        <input id="{{ $PApiKeyId }}" type="text" class="input-field datePicker js-date" name="{{ $r->field_name }}"  value="{{ old($r->field_name) }}"
                               autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy" readonly>
                        <div class="{{ $r->field_name }}_err error-message"></div>

                    <script type="text/javascript" nonce="{{ app('csp_nonce') }}">
                        $(document).ready(function () {
                            $('.datePicker').datepicker({
                                dateFormat: 'dd-mm-yy',
                                changeYear: true,
                                changeMonth: true,
                                yearRange: "1900:2050",
                            });
                        });
                        var input = document.querySelectorAll('.js-date')[0];
                        var dateInputMask = function dateInputMask(elm) {
                            elm.addEventListener('keypress', function(e) {
                                if(e.keyCode < 47 || e.keyCode > 57) {
                                    e.preventDefault();
                                }
                                var len = elm.value.length;
                                // If we're at a particular place, let the user type the slash
                                // i.e., 12/12/1212
                                if(len !== 1 || len !== 3) {
                                    if(e.keyCode == 47) {
                                        e.preventDefault();
                                    }
                                }
                                // If they don't add the slash, do it for them...
                                if(len === 2) {
                                    elm.value += '-';
                                }
                                // If they don't add the slash, do it for them...
                                if(len === 5) {
                                    elm.value += '-';
                                }
                            });
                        };
                        dateInputMask(input);
                    </script>

                    </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::NUMBER)
                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                        <input id="{{ $PApiKeyId }}" type="{{ $r->field_type }}" class="input-field number_field" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)
                    <div class="input-wrapper mb-1">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                        <input id="{{ $PApiKeyId }}" type="text" class="input-field text_eng" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::ALPHANUMERIC)
                    <div class="input-wrapper mb-1">
                        <label class="mb-1" style="color:white !important">{{ $r->label_name }}<span class="required">@if( $r->is_required == 1) {{'*'}} @endif</span></label>
                        <input id="{{ $PApiKeyId }}" type="text" class="input-field alpha_numeric_field js-ignore-global" name="{{ $r->field_name }}" placeholder="Enter numbers and text only"/>
                        <div class="{{ $r->field_name }}_err error-message"></div>
                        <script nonce="{{ app('csp_nonce') }}">
                            $(function () {
                                $(document).on('input', '.alpha_numeric_field', function () {
                                    const $input = $(this);
                                    const value = $input.val();
                                    const $formGroup = $input.closest('.input-wrapper');
                                    $formGroup.find('.alpha-num-error').remove();
                                    if (/[^a-zA-Z0-9 ]/.test(value)) {
                                        $input.val(value.replace(/[^a-zA-Z0-9 ]/g, ''));
                                        $('<span>', {
                                            class: 'alpha-num-error text-danger mt-1 d-block',
                                            text: 'Only letters and numbers are allowed.'
                                        }).appendTo($formGroup);
                                    }
                                });
                            });
                        </script>
                    </div>
                @endif
            @endforeach
        @endif

        <!-- Field With Fieldset -->
        @if($single['fieldset_title'] != "")
            <div class="mobile_fieldset">
                <fieldset class="inputTextWrap mt-1"  onload="validateAllInputs()" class="fieldset-wrap"  @if(!empty($single['fieldset_id']))
                        id="{{ $single['fieldset_id'] }}"
                    @endif>
                    <legend>
                        @if ($issueId == 1192)
                            <button type="button"
                                    class="btn btn-sm fieldset-toggle-btn fieldset-toggle-box"
                                    data-fieldset-id="{{ $single['fieldset_id'] ?? '' }}">
                                <span class="fieldset-title-text">{{ $single['fieldset_title'] }}:</span>
                                <span class="toggle-icon">▼</span>
                            </button>
                        @else
                            {{ $single['fieldset_title'] }}:
                        @endif
                    </legend>

                    <div id="body_{{ $single['fieldset_id'] ?? '' }}" class="fieldset-body">
                        @foreach($single['fields'] as $key => $r)
                            @php
                                $PApiKey = $r['api_key'];
                                $PApiKeyArr = explode(':', $PApiKey);
                                $PApiKeyId = $PApiKeyArr[0];
                                $hideStyle = ($PApiKeyId == 'customerMobile' || $PApiKeyId == 'customerPhone' || $PApiKey == 'customerEmail') ? 'display: none;' : '';
                            @endphp

                            @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)
                                <div class="input-wrapper mb-1 {{ $r->field_name }}" style="{{ $hideStyle }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input id="{{ $PApiKeyId }}" type="{{ $r->field_type }}" class="input-field text_eng" name="{{ $r->field_name }}" value="{{ $bpidValues[$PApiKeyId] ?? old($r->field_name) }}" placeholder="{{ $r->placeholder }}">
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::FILE)

                                <div class="input-wrapper mb-1 {{ $r->field_name }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input id="{{ $PApiKeyId }}" type="file" class="input-field text_eng" name="{{ $r->field_name }}">
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>


                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                        <input id="{{ $PApiKeyId }}" type="text" class="input-field datepickerPrev" name="{{ $r->field_name }}" placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                                        <div class="{{ $r->field_name }}_err error-message"></div>
                                        <script nonce="{{ app('csp_nonce') }}">
                                            $(function() {
                                            $(".datepickerPrev").datepicker({
                                                defaultDate: 0,
                                                maxDate: 0,
                                                dateFormat: 'dd-mm-yy',
                                                showButtonPanel: true,
                                                changeYear: true,
                                                changeMonth: true,
                                                yearRange: "1900:2033",
                                            });
                                            });
                                        </script>
                                    </div>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::NEXT_DATE)
                                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                        <input id="{{ $PApiKeyId }}" type="text" class="input-field datepickerNext" name="{{ $r->field_name }}" placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                                        <div class="{{ $r->field_name }}_err error-message"></div>
                                        <script nonce="{{ app('csp_nonce') }}">
                                            $(function() {
                                                $(".datepickerNext").datepicker({
                                                    minDate: 1,
                                                    dateFormat: 'dd-mm-yy',
                                                    showButtonPanel: true,
                                                    changeYear: true,
                                                    changeMonth: true,
                                                    yearRange: "1900:2050",
                                                });
                                            });
                                        </script>
                                    </div>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DROPDOWN)
                                <div class="dropdown-wrapper mb-3 {{ $r->field_name }}">
                                    <label class="mb-1">
                                        {{ $r->label_name }} <span class="required" >@if($r->is_required == 1){{'*'}} @endif </span>
                                    </label>
                                    <select id="{{ $PApiKeyId }}" class="dropdown-select DependantFields fieldset_select2" name="{{ $r->field_name }}" data-id="{{ $r->id }}">
                                        @php
                                            $options = explode(",", $r->options);
                                        @endphp
                                        @if(count($options) != 1)
                                            <option value="">Please Select</option>
                                        @endif
                                        @foreach($options as $k => $option)
                                            @php
                                                $selected  = "";
                                                $option_name = $option;
                                                $old = old($r->field_name);
                                                if (str_contains($option_name,'~')) {
                                                    $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                                }
                                                if (!empty($old) && str_contains($old,'~')) {
                                                    $old = substr($old, strpos($old, "~") + 1);
                                                }
                                                if($option_name == $old){
                                                    $selected  = "selected";
                                                }
                                            @endphp
                                            <option value="{{ $option }}" {{$selected}}>{{ $option_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            @elseif( $r->field_type == \App\Enum\FieldTypeEnum::RADIO)
                                <div class="form-check mb-3 p-0 {{ $r->field_name }}">
                                    <label class="form-check-label d-block mb-2" style="color : white">
                                        {{ $r->label_name }}<span class="required">@if( $r->is_required == 1 ) {{'*'}} @endif</span>
                                    </label>
                                    @php $options = explode(",", $r->options); @endphp
                                    @foreach($options as $key => $option)
                                        @php
                                            $selected  = "";
                                            if($option == old($r->field_name)){
                                                $selected  = "checked";
                                            }
                                        @endphp

                                        <div class="form-check d-inline-block me-2">
                                            <input id="{{ $PApiKeyId }}" class="form-check-input" type="radio" name="{{ $r->field_name }}" id="{{ "radio" . $key }}" value="{{ $option }}"
                                                {{ $selected }}>
                                            <label class="form-check-label" for="{{ "radio" . $key }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            @elseif( $r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA )
                                <div class="input-wrapper mb-1 {{ $r->field_name }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <textarea rows="1" class="input-field text_eng" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" placeholder="{{ $r->placeholder }}">{{ old($r->field_name) }}</textarea>
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            @elseif( $r->field_type==\App\Enum\FieldTypeEnum::ADDRESS )
                                <div class="input-wrapper mb-1 {{ $r->field_name }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input type="text" class="input-field text_eng" name="{{ $r->field_name }}" value="{{ old($r->field_name )}}" placeholder="{{ $r->placeholder }}">
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            @elseif( $r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)
                                <div class="form-check p-0 mb-3 {{ $r->field_name }}">
                                    @php
                                        $options = '';
                                        $input_checkbox = '';
                                        $options = explode(",", $r->options);
                                    @endphp

                                    <label class="form-check-label d-block mb-2">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>

                                    @foreach($options as $key => $option)
                                        @php
                                            $value = $option;
                                            if(empty($option)) {
                                                $value = 'Yes';
                                            }
                                            $selected  = "";
                                            if($value == old($r->field_name)){
                                                $selected  = "checked";
                                            }
                                        @endphp

                                        <div class="form-check d-inline-block me-2">
                                            <input id="{{ $PApiKeyId }}" class="form-check-input" type="checkbox" name="{{ $r->field_name }}" value="{{ $value }}"
                                                {{ $selected }}>
                                            <label class="form-check-label" style="color : white !important;">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                    <div class="{{ $r->field_name }}_err error-message"></div>

                                </div>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)
                                <div class="input-wrapper mb-1 {{ $r->field_name }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input id="{{ $PApiKeyId }}" type="text" class="input-field datePicker js-date" name="{{ $r->field_name }}"  value="{{ old($r->field_name) }}"
                                           autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy">
                                    <div class="{{ $r->field_name }}_err error-message"></div>

                                    <script id="{{ $PApiKeyId }}" type="text/javascript" nonce="{{ app('csp_nonce') }}">
                                        $(document).ready(function () {
                                            $('.datePicker').datepicker({
                                                dateFormat: 'dd-mm-yy',
                                                changeYear: true,
                                                changeMonth: true,
                                                yearRange: "1900:2050",
                                            });
                                        });
                                        var input = document.querySelectorAll('.js-date')[0];
                                        var dateInputMask = function dateInputMask(elm) {
                                            elm.addEventListener('keypress', function(e) {
                                                if(e.keyCode < 47 || e.keyCode > 57) {
                                                    e.preventDefault();
                                                }
                                                var len = elm.value.length;
                                                // If we're at a particular place, let the user type the slash
                                                // i.e., 12/12/1212
                                                if(len !== 1 || len !== 3) {
                                                    if(e.keyCode == 47) {
                                                        e.preventDefault();
                                                    }
                                                }
                                                // If they don't add the slash, do it for them...
                                                if(len === 2) {
                                                    elm.value += '-';
                                                }
                                                // If they don't add the slash, do it for them...
                                                if(len === 5) {
                                                    elm.value += '-';
                                                }
                                            });
                                        };
                                        dateInputMask(input);
                                    </script>

                                </div>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::NUMBER)
                                <div class="input-wrapper mb-1 {{ $r->field_name }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input id="{{ $PApiKeyId }}" type="{{ $r->field_type }}" class="input-field number_field" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)
                                <div class="input-wrapper mb-1 {{ $r->field_name }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input id="{{ $PApiKeyId }}" type="text" class="input-field text_eng" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::ALPHANUMERIC)
                                <div class="input-wrapper mb-1">
                                    <label class="mb-1" style="color:white !important">{{ $r->label_name }}<span class="required">@if( $r->is_required == 1) {{'*'}} @endif</span></label>
                                    <input id="{{ $PApiKeyId }}" type="text" class="input-field alpha_numeric_field js-ignore-global" name="{{ $r->field_name }}" placeholder="Enter numbers and text only"/>
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                    <script id="{{ $PApiKeyId }}" nonce="{{ app('csp_nonce') }}">
                                        $(function () {
                                            $(document).on('input', '.alpha_numeric_field', function () {
                                                const $input = $(this);
                                                const value = $input.val();
                                                const $formGroup = $input.closest('.input-wrapper');
                                                $formGroup.find('.alpha-num-error').remove();
                                                if (/[^a-zA-Z0-9 ]/.test(value)) {
                                                    $input.val(value.replace(/[^a-zA-Z0-9 ]/g, ''));
                                                    $('<span>', {
                                                        class: 'alpha-num-error text-danger mt-1 d-block',
                                                        text: 'Only letters and numbers are allowed.'
                                                    }).appendTo($formGroup);
                                                }
                                            });
                                        });
                                    </script>
                                </div>
                            @endif
                        @endforeach

                        {{-- For BpId if issue_id 1192 --}}
                        @if ($issueId == 1192)
                            @if(($single['fieldset_id'] ?? null) === 'BPID_first_applicant')
                                <input type="hidden" name="nominee_count" id="nomineeCount" value="0">
                                <input type="hidden" name="applicant_count" id="applicantCount" value="0">
                            @endif
                        @endif
                        {{-- For BpId if issue_id 1192 --}}
                    </div>
                </fieldset>
            </div>
        @endif
    @endforeach

    @push('js')
        {{-- BP ID Form --}}
        <script nonce="{{ app('csp_nonce') }}">
            // by default hidden second nominee
            $(document).ready(function () {
                $("input[type='checkbox'][name='bp_type']").each(function () {
                    let value = $(this).val();
                    // generate disabled checkbox + hidden input
                    let replacedHtml = `
                        <input type="checkbox" value="${value}" disabled checked>
                        <input type="hidden" name="bp_type" value="${value}">
                    `;

                    $(this).replaceWith(replacedHtml);
                });
            });

        </script>


        <script nonce="{{ app('csp_nonce') }}">
            $(document).ready(function() {
                // Bind all existing number fields
                $(document).on('input', '.number_field', function(event) {
                    allowOnlyNumbers(event);
                });

                $(document).on('input', '.text_eng', function(event){
                    textInEnglish(event);
                });
            });
        </script>

        <script nonce="{{ app('csp_nonce') }}">

            $(document).ready(function () {

                // Toggle on legend button click
                $(document).on('click', '.fieldset-toggle-btn', function () {
                    const fieldsetId = $(this).data('fieldset-id');
                    const $body = $('#body_' + fieldsetId);
                    const $icon = $(this).find('.toggle-icon');

                    if ($body.is(':visible')) {
                        $body.slideUp(200);
                        $icon.text('▼');
                    } else {
                        $body.slideDown(200);
                        $icon.text('▲');
                    }
                });

                // showHideBpidApplicants — collapse/expand এর সাথে sync
            const origShowApplicants = window.showHideBpidApplicants;
            window.showHideBpidApplicants = function(count) {
                ['BPID_second_applicant','BPID_third_applicant','BPID_fourth_applicant']
                    .forEach(function(id, i) {
                        const needed = i + 2; // 2,3,4
                        const $fieldset = $('#' + id);
                        const $body     = $('#body_' + id);
                        const $btn      = $('[data-fieldset-id="' + id + '"]');

                        if (count >= needed) {
                            $fieldset.show();
                            $body.slideDown(200);
                            $btn.find('.toggle-icon').text('▲');
                        } else {
                            $fieldset.hide();
                            $body.hide();
                        }
                    });

                if ($('#applicantCount').length) {
                    $('#applicantCount').val(count);
                }
            };

            // showHideBpidNominees — collapse/expand এর সাথে sync
            window.showHideBpidNominees = function(count) {
                ['BPID_second_nominee','BPID_third_nominee','BPID_fourth_nominee']
                    .forEach(function(id, i) {
                        const needed = i + 2;
                        const $fieldset = $('#' + id);
                        const $body     = $('#body_' + id);
                        const $btn      = $('[data-fieldset-id="' + id + '"]');

                        if (count >= needed) {
                            $fieldset.show();
                            $body.slideDown(200);
                            $btn.find('.toggle-icon').text('▲');
                        } else {
                            $fieldset.hide();
                            $body.hide();
                        }
                    });

                if ($('#nomineeCount').length) {
                    $('#nomineeCount').val(count);
                }
            };


            });
        </script>
    @endpush
