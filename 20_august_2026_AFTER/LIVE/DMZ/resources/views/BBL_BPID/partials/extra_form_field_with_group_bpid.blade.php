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
if ($issueId == 1449 && !empty($bpid_data)) {
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

        @if($single['fieldset_title'] == "")
            @foreach($single['fields'] as $key=>$r)

                @php
                    $PApiKey = $r['api_key'];
                    $PApiKeyArr = explode(':', $PApiKey);
                    $PApiKeyId = $PApiKeyArr[0];
                @endphp

                <div class="normal-field">

                    @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input type="{{ $r->field_type }}"
                                        class="form-control text_eng"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        value="{{ $bpidValues[$PApiKeyId] ?? old($r->field_name) }}"
                                        placeholder="{{ $r->placeholder }}"
                                        @if($r->is_readonly == 1) readonly @endif />
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::DROPDOWN)

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-group" style="padding-bottom: 8px;">
                                    <label class="mb-1">
                                        {{ $r->label_name }} <span class="required">@if($r->is_required == 1){{'*'}} @endif</span>
                                    </label>
                                    <select class="form-select DependantFields fieldset_select2"
                                            name="{{ $r->field_name }}"
                                            id="{{ $PApiKeyId }}"
                                            data-id="{{ $r->id }}"
                                            @if($r->is_readonly == 1) disabled @endif>
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
                                                    $selected = "selected";
                                                }
                                            @endphp
                                            <option value="{{ $option }}" {{ $selected }}>{{ $option_name }}</option>
                                        @endforeach
                                    </select>
                                    @if($r->is_readonly == 1)
                                        <input type="hidden" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}">
                                    @endif
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
                        </div>

                    @elseif($r->field_type == \App\Enum\FieldTypeEnum::RADIO)

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-check mt-2 p-0">
                                    <label class="form-check-label d-block mb-2">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span> :
                                    </label>
                                    @php $options = explode(",", $r->options); @endphp
                                    @foreach($options as $rKey => $option)
                                        @php
                                            $selected = "";
                                            if($option == old($r->field_name)){
                                                $selected = "checked";
                                            }
                                        @endphp
                                        <div class="form-check d-inline-block me-2">
                                            <input class="form-check-input"
                                                type="radio"
                                                id="{{ $PApiKeyId }}_{{ $rKey }}"
                                                name="{{ $r->field_name }}"
                                                value="{{ $option }}"
                                                {{ $selected }}
                                                @if($r->is_readonly == 1) disabled @endif>
                                            <label class="form-check-label" for="{{ $PApiKeyId }}_{{ $rKey }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                    @if($r->is_readonly == 1)
                                        <input type="hidden" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}">
                                    @endif
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA)

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <textarea rows="1"
                                            class="form-control text_eng"
                                            name="{{ $r->field_name }}"
                                            id="{{ $PApiKeyId }}"
                                            placeholder="{{ $r->placeholder }}"
                                            maxlength="{{ $r->maximum_length }}"
                                            @if($r->is_readonly == 1) readonly @endif>{{ old($r->field_name) }}</textarea>
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
                        </div>

                    @elseif($r->field_type == \App\Enum\FieldTypeEnum::FILE)

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="file"
                                        class="form-control"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        @if($r->is_readonly == 1) disabled @endif>
                                    @if($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="text"
                                        class="form-control text_eng"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        value="{{ old($r->field_name) }}"
                                        placeholder="{{ $r->placeholder }}"
                                        @if($r->is_readonly == 1) readonly @endif />
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-check mt-2 p-0">
                                    @php
                                        $options = explode(",", $r->options);
                                    @endphp
                                    <label class="form-check-label d-block mb-2">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span> :
                                    </label>
                                    @foreach($options as $cKey => $option)
                                        @php
                                            $value = $option;
                                            if(empty($option)) {
                                                $value = 'Yes';
                                            }
                                            $selected = "";
                                            if($value == old($r->field_name)){
                                                $selected = "checked";
                                            }
                                        @endphp
                                        <div class="form-check d-inline-block me-2">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="{{ $PApiKeyId }}_{{ $cKey }}"
                                                name="{{ $r->field_name }}"
                                                value="{{ $value }}"
                                                {{ $selected }}
                                                @if($r->is_readonly == 1) disabled @endif>
                                            <label class="form-check-label ms-2" for="{{ $PApiKeyId }}_{{ $cKey }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                    @if($r->is_readonly == 1)
                                        <input type="hidden" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}">
                                    @endif
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="text"
                                        class="form-control datePicker js-date"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        value="{{ old($r->field_name) }}"
                                        autocomplete="off"
                                        maxlength="10"
                                        placeholder="dd-mm-yyyy"
                                        readonly />
                                    {{-- DATE সবসময় readonly — datepicker এর জন্য --}}
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
                                                if(e.keyCode < 47 || e.keyCode > 57) { e.preventDefault(); }
                                                var len = elm.value.length;
                                                if(len !== 1 || len !== 3) {
                                                    if(e.keyCode == 47) { e.preventDefault(); }
                                                }
                                                if(len === 2) { elm.value += '-'; }
                                                if(len === 5) { elm.value += '-'; }
                                            });
                                        };
                                        dateInputMask(input);
                                    </script>
                                </div>
                            </div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                                    <input type="text"
                                        class="form-control datepickerPrev"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        value="{{ old($r->field_name) }}"
                                        placeholder="dd-mm-yyyy"
                                        readonly />
                                    {{-- PREV_DATE সবসময় readonly — datepicker এর জন্য --}}
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
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

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                                    <input type="text"
                                        class="form-control datepickerNext"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        value="{{ old($r->field_name) }}"
                                        placeholder="dd-mm-yyyy"
                                        readonly />
                                    {{-- NEXT_DATE সবসময় readonly — datepicker এর জন্য --}}
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
                            <script nonce="{{ app('csp_nonce') }}">
                                $(function() {
                                    $(".datepickerNext").datepicker({
                                        minDate: 1,
                                        dateFormat: 'dd-mm-yy',
                                        changeYear: true,
                                        changeMonth: true,
                                        showButtonPanel: true,
                                        yearRange: "1900:2050",
                                    });
                                });
                            </script>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::NUMBER)

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="text"
                                        class="form-control number_field"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}"
                                        value="{{ old($r->field_name) }}"
                                        @if($r->is_readonly == 1) readonly @endif>
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)

                        <div class="card card-color {{ $r->field_name }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="text"
                                        class="form-control text_eng"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}"
                                        value="{{ old($r->field_name) }}"
                                        @if($r->is_readonly == 1) readonly @endif>
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::ALPHANUMERIC)

                        <div class="input-wrapper mb-3 {{ $r->field_name }}">
                            <label class="mb-1" style="color:white !important">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                            <input type="text"
                                class="input-field alpha_numeric_field js-ignore-global"
                                name="{{ $r->field_name }}"
                                id="{{ $PApiKeyId }}"
                                placeholder="{{ $r->placeholder }}"
                                @if($r->is_readonly == 1) readonly @endif />
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
                </div>
            @endforeach
        @endif

        @if($single['fieldset_title'] != "")
            <fieldset onload="validateAllInputs()" class="fieldset-wrap"  @if(!empty($single['fieldset_id']))
                        id="{{ $single['fieldset_id'] }}"
                    @endif>
                <legend>
                    @if ($issueId == 1450)
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

                <div class="fieldset-body" id="body_{{ $single['fieldset_id'] ?? '' }}">
                    @foreach($single['fields'] as $key=>$r)
    
                        @php
                            $PApiKey = $r['api_key'];
                            $PApiKeyArr = explode(':', $PApiKey);
                            $PApiKeyId = $PApiKeyArr[0];
                        @endphp
    
                        <div class="mb-2">
    
                            @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)
    
                                <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                                    <label class="mb-1">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                                    <input type="{{ $r->field_type }}"
                                        class="form-control text_eng"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        value="{{ $bpidValues[$PApiKeyId] ?? old($r->field_name) }}"
                                        placeholder="{{ $r->placeholder }}"
                                        @if($r->is_readonly == 1) readonly @endif />
                                    @if($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
    
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DROPDOWN)
    
                                <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                                    <label class="mb-1">{{ $r->label_name }} <span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                                    <select class="form-select DependantFields fieldset_select2"
                                            name="{{ $r->field_name }}"
                                            id="{{ $PApiKeyId }}"
                                            data-id="{{ $r->id }}"
                                            @if($r->is_readonly == 1) disabled @endif>
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
                                                    $selected = "selected";
                                                }
                                            @endphp
                                            <option value="{{ $option }}" {{ $selected }}>{{ $option_name }}</option>
                                        @endforeach
                                    </select>
                                    @if($r->is_readonly == 1)
                                        <input type="hidden" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}">
                                    @endif
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
    
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::RADIO)
    
                                <div class="form-check {{ $r->field_name }} mb-2 p-0">
                                    @php $options = explode(",", $r->options); @endphp
    
                                    <label class="form-check-label d-block mb-2">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
    
                                    @foreach($options as $rKey => $option)
                                        @php
                                            $selected = "";
                                            if($option == old($r->field_name)){
                                                $selected = "checked";
                                            }
                                        @endphp
                                        <div class="form-check d-inline-block me-2">
                                            <input class="form-check-input"
                                                type="radio"
                                                id="{{ $PApiKeyId }}_{{ $rKey }}"
                                                name="{{ $r->field_name }}"
                                                value="{{ $option }}"
                                                {{ $selected }}
                                                @if($r->is_readonly == 1) disabled @endif>
                                            <label class="form-check-label" for="{{ $PApiKeyId }}_{{ $rKey }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                    @if($r->is_readonly == 1)
                                        <input type="hidden" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}">
                                    @endif
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
    
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA)
    
                                <div class="form-group {{ $r->field_name }}">
                                    <label class="mb-1">{{ $r->label_name }}
                                        <span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <textarea rows="1"
                                            class="form-control text_eng"
                                            name="{{ $r->field_name }}"
                                            id="{{ $PApiKeyId }}"
                                            placeholder="{{ $r->placeholder }}"
                                            maxlength="{{ $r->maximum_length }}"
                                            @if($r->is_readonly == 1) readonly @endif>{{ old($r->field_name) }}</textarea>
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
    
                            @elseif($r->field_type == \App\Enum\FieldTypeEnum::FILE)
    
                                <div class="form-group {{ $r->field_name }}">
                                    <label class="mb-1">{{ $r->label_name }}
                                        <span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="file"
                                        class="form-control"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        @if($r->is_readonly == 1) disabled @endif>
                                    @if($errors->has($r->field_name))
                                        <div class="error-message">{{ $errors->first($r->field_name) }}</div>
                                    @endif
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
    
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)
    
                                <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="text"
                                        class="form-control text_eng"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}"
                                        value="{{ old($r->field_name) }}"
                                        @if($r->is_readonly == 1) readonly @endif />
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
    
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)
    
                                <div class="form-check {{ $r->field_name }} p-0">
                                    @php
                                        $options = explode(",", $r->options);
                                    @endphp
                                    <label class="form-check-label d-block mb-2">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required==1) {{'*'}} @endif</span>
                                    </label>
    
                                    @foreach($options as $cKey => $option)
                                        @php
                                            $value = $option;
                                            if(empty($option)) {
                                                $value = 'Yes';
                                            }
                                            $selected = "";
                                            if($value == old($r->field_name)){
                                                $selected = "checked";
                                            }
                                        @endphp
                                        <div class="form-check d-inline-block me-2">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="{{ $PApiKeyId }}_{{ $cKey }}"
                                                name="{{ $r->field_name }}"
                                                value="{{ $value }}"
                                                {{ $selected }}
                                                @if($r->is_readonly == 1) disabled @endif>
                                            <label class="form-check-label" for="{{ $PApiKeyId }}_{{ $cKey }}" style="color: white">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                    @if($r->is_readonly == 1)
                                        <input type="hidden" name="{{ $r->field_name }}" value="{{ old($r->field_name) }}">
                                    @endif
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
    
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)
    
                                <div class="form-group mt-2 {{ $r->field_name }}">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="text"
                                        class="form-control datePicker js-date"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        value="{{ old($r->field_name) }}"
                                        autocomplete="off"
                                        maxlength="10"
                                        placeholder="dd-mm-yyyy"
                                        readonly />
                                        
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                    <script nonce="{{ app('csp_nonce') }}">
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
                                                if(e.keyCode < 47 || e.keyCode > 57) { e.preventDefault(); }
                                                var len = elm.value.length;
                                                if(len !== 1 || len !== 3) {
                                                    if(e.keyCode == 47) { e.preventDefault(); }
                                                }
                                                if(len === 2) { elm.value += '-'; }
                                                if(len === 5) { elm.value += '-'; }
                                            });
                                        };
                                        dateInputMask(input);
                                    </script>
                                </div>
    
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::NUMBER)
    
                                <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="text"
                                        class="form-control number_field"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}"
                                        value="{{ old($r->field_name) }}"
                                        @if($r->is_readonly == 1) readonly @endif />
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
    
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
    
                                <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="text"
                                        class="form-control datepickerPrev"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        placeholder="dd-mm-yyyy"
                                        maxlength="{{ $r->maximum_length }}"
                                        value="{{ old($r->field_name) }}"
                                        readonly />
                                        
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                    <script nonce="{{ app('csp_nonce') }}">
                                        $(function() {
                                            $(".datepickerPrev").datepicker({
                                                defaultDate: 0,
                                                maxDate: 0,
                                                dateFormat: 'dd-mm-yy',
                                                changeYear: true,
                                                changeMonth: true,
                                                showButtonPanel: true,
                                                yearRange: "1900:2034",
                                            });
                                        });
                                    </script>
                                </div>
    
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::NEXT_DATE)
    
                                <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="text"
                                        class="form-control datepickerNext"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        placeholder="dd-mm-yyyy"
                                        maxlength="{{ $r->maximum_length }}"
                                        value="{{ old($r->field_name) }}"
                                        readonly />
                                        
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
    
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)
    
                                <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                                    <label class="mb-1">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="text"
                                        class="form-control text_eng"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        placeholder="{{ $r->placeholder }}"
                                        maxlength="{{ $r->maximum_length }}"
                                        value="{{ old($r->field_name) }}"
                                        @if($r->is_readonly == 1) readonly @endif />
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
    
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::ALPHANUMERIC)
    
                                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                    <label class="mb-1" style="color:white !important">
                                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                    </label>
                                    <input type="text"
                                        class="input-field alpha_numeric_field js-ignore-global"
                                        name="{{ $r->field_name }}"
                                        id="{{ $PApiKeyId }}"
                                        placeholder="{{ $r->placeholder }}"
                                        @if($r->is_readonly == 1) readonly @endif />
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
                        </div>
                    @endforeach
    
                    {{-- For BpId if issue_id 1450 --}}
                    @if ($issueId == 1450)
                        @if(($single['fieldset_id'] ?? null) === 'BPID_first_applicant')
                            <input type="hidden" name="nominee_count" id="nomineeCount" value="0">
                            <input type="hidden" name="applicant_count" id="applicantCount" value="0">
                        @endif
                    @endif
                    {{-- For BpId if issue_id 1450 --}}

                </div>

            </fieldset>
        @endif

    @endforeach

@push('js')
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
        //hello bangladesh

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

