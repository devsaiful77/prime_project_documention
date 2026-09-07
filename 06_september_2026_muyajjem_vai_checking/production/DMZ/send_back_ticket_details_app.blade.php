<style>
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

    .fieldset-toggle-btn:focus,
    .fieldset-toggle-btn:focus .fieldset-title-text,
    .fieldset-toggle-btn:focus .toggle-icon {
        color: #ffffff !important;
    }

    .mobile_fieldset fieldset {
        margin: 0 0 5px 0;
        border: 0;
    }

    .fieldset-toggle-box .toggle-icon {
        font-size: 12px;
        line-height: 1;
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
                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) * @endif</span></label>
                    <input type="{{ $r->field_type }}" class="input-field text_eng" name="{{ $r->field_name }}"
                           value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}" placeholder="{{ $r->placeholder }}">
                    <div class="{{ $r->field_name }}_err error-message"></div>
                </div>


            @elseif($r->field_type == \App\Enum\FieldTypeEnum::FILE)
                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                    <input id="{{ $PApiKeyId }}" type="file" class="input-field text_eng" name="{{ $r->field_name }}">
                    <div class="{{ $r->field_name }}_err error-message"></div>
                </div>


            @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                    <input type="text" class="input-field" name="{{ $r->field_name }}" id="datepickerPrev"
                           placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}" readonly>
                    <div class="{{ $r->field_name }}_err error-message"></div>
                    <script type="text/javascript" nonce="{{ app('csp_nonce') }}">
                        $(document).ready(function () {
                            $("#datepickerPrev").datepicker({
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
                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                    <input type="text" class="input-field" name="{{ $r->field_name }}" id="datepickerNext" placeholder="dd-mm-yyyy"
                           maxlength="{{ $r->maximum_length }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}" readonly>
                    <div class="{{ $r->field_name }}_err error-message"></div>
                    <script type="text/javascript" nonce="{{ app('csp_nonce') }}">
                        $(document).ready(function () {
                            $("#datepickerNext").datepicker({
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
                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) * @endif</span></label>
                    <select class="dropdown-select DependantFields fieldset_select2" name="{{ $r->field_name }}" data-id="{{ $r->id }}">

                        @php
                            $options = explode(",", $r->options);
                        @endphp
                        @if(count($options) != 1)
                            <option value="">Please Select</option>
                        @endif
                        @foreach($options as $k => $option)
                            @php
                                $selected  = "";
                                $option = trim($option); // clean whitespace
                                $option_name = str_contains($option, '~') ? substr($option, strpos($option, "~") + 1) : $option;

                                $old = old($r->field_name);
                                if (str_contains($option_name,'~')) {
                                    $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                }
                                $exitValue = array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '';
                                if (!empty($exitValue) && str_contains($exitValue,'~')){
                                    $exitValue = substr($exitValue, strpos($exitValue, "~") + 1);
                                }
                                if($option_name == $exitValue){
                                    $selected  = "selected";
                                }
                            @endphp
                            <option value="{{ $option }}" {{$selected}}>{{ $option_name }}</option>
                            <script nonce="{{ app('csp_nonce') }}">
                                $(document).ready(function() {
                                    $('.fieldset_select2').select2();
                                });
                            </script>
                        @endforeach
                    </select>
                    <div class="{{ $r->field_name }}_err error-message"></div>
                </div>
            @elseif( $r->field_type == \App\Enum\FieldTypeEnum::RADIO )
                <div class="form-check mb-3 p-0 {{ $r->field_name }}">
                    <label class="form-check-label d-block mb-2">
                        {{ $r->label_name }}<span class="required">@if( $r->is_required == 1 ) * @endif</span>
                    </label>
                    @php $options = explode(",", $r->options); @endphp
                    @foreach($options as $key => $option)
                        @php
                            $selected  = "";
                            $exitValue = array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '';
                            if($option == $exitValue){
                                $selected  = "checked";
                            }
                        @endphp

                        <div class="form-check d-inline-block me-2">
                            <input class="form-check-input" type="radio" name="{{ $r->field_name }}" value="{{ $option }}"
                                {{ $selected }}>
                            <label class="form-check-label">
                                {{ $option }}
                            </label>
                        </div>
                    @endforeach
                    <div class="{{ $r->field_name }}_err error-message"></div>
                </div>
            @elseif( $r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA )
                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if( $r->is_required == 1 ) * @endif</span></label>
                    <textarea rows="1" class="input-field text_eng" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" placeholder="{{ $r->placeholder }}">{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}</textarea>
                    <div class="{{ $r->field_name }}_err error-message"></div>
                </div>
            @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)
                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) * @endif</span></label>
                    <input type="text" class="input-field text_eng" name="{{ $r->field_name }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}" placeholder="{{ $r->placeholder }}">
                    <div class="{{ $r->field_name }}_err error-message"></div>
                </div>
            @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)
                <div class="form-check mb-3 p-0 {{ $r->field_name }}">
                    @php
                        $options = '';
                        $input_checkbox = '';
                        $options = explode(",", $r->options);
                    @endphp

                    <label class="form-check-label d-block mb-2" style="color: white;">
                        {{ $r->label_name }}<span class="required">@if($r->is_required == 1) * @endif</span>
                    </label>

                    @foreach($options as $key => $option)
                        @php
                            $exitValue = array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '';
                            $value = $option;
                            if(empty($option)) {
                                $value = 'Yes';
                            }
                            $selected  = "";
                            if($value == $exitValue){
                                $selected  = "checked";
                            }
                        @endphp

                        <div class="form-check d-inline-block me-2">
                            <input class="form-check-input" type="checkbox" name="{{ $r->field_name }}" value="{{ $value }}"
                                {{ $selected }}>
                            <label class="form-check-label" style="color : white;">
                                {{ $option }}
                            </label>
                        </div>
                    @endforeach
                    <div class="{{ $r->field_name }}_err error-message"></div>
                </div>
            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)
                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1) *@endif</span></label>
                    <input type="text" class="input-field datePicker js-date" name="{{ $r->field_name }}"
                           value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"
                           placeholder="dd-mm-yyyy" autocomplete="off" maxlength="10" readonly>
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
                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if( $r->is_required == 1) *  @endif</span></label>
                    <input type="{{ $r->field_type }}" class="input-field number_field" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" placeholder="{{ $r->placeholder }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}">
                    <div class="{{ $r->field_name }}_err error-message"></div>
                </div>
            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)
                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                    <label for="" class="input-label text_eng">{{ $r->label_name }}<span class="required">@if($r->is_required==1) * @endif</span></label>
                    <input type="text" class="input-field" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}">
                    <div class="{{ $r->field_name }}_err error-message"></div>
                </div>
            @elseif($r->field_type==\App\Enum\FieldTypeEnum::ALPHANUMERIC)
                <div class="input-wrapper mb-3">
                    <label class="mb-1" style="color:white !important">{{ $r->label_name }}<span class="required">@if( $r->is_required == 1) {{'*'}} @endif</span></label>
                    <input type="text" class="input-field alpha_numeric_field js-ignore-global" name="{{ $r->field_name }}" placeholder="Enter numbers and text only" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"/>
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

        @php
            // Kon fieldset-er hide/show kon field-er upor depend korbe, tar map
            $conditionalFieldsetMap = [
                'BPID_second_applicant' => 'Second Applicant Name:',
                'BPID_third_applicant'  => 'Third Applicant Name:',
                'BPID_fourth_applicant' => 'Four Applicant Name:',
                'BPID_second_nominee'   => 'Second Nominee Name',
                'BPID_third_nominee'    => 'Third Nominee Name',
                'BPID_fourth_nominee'   => 'Fourth Nominee Name',
            ];

            $currentFieldsetId = $single['fieldset_id'] ?? '';
            $shouldHideFieldset = false;

            if (array_key_exists($currentFieldsetId, $conditionalFieldsetMap)) {
                $checkLabel = $conditionalFieldsetMap[$currentFieldsetId];
                $checkValue = array_key_exists($checkLabel, $arraySingle) ? $arraySingle[$checkLabel] : '';
                $shouldHideFieldset = trim((string) $checkValue) === '';
            }
        @endphp

        <div class="mobile_fieldset" @if($shouldHideFieldset) style="display:none;" @endif>
            <fieldset class="inputTextWrap mt-3" @if(!empty($single['fieldset_id'])) id="{{ $single['fieldset_id'] }}" @endif>
                <legend>
                    @if ($issueId == getId('BPID'))
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
                        @endphp
                        @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)
                            <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) * @endif</span></label>
                                <input type="{{ $r->field_type }}" class="input-field text_eng" placeholder="{{ $r->placeholder }}" name="{{ $r->field_name }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}">
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>

                        @elseif($r->field_type==\App\Enum\FieldTypeEnum::FILE)

                            <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                <input id="{{ $PApiKeyId }}" type="file" class="input-field text_eng" name="{{ $r->field_name }}">
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>


                        @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                            <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                <input type="text" class="input-field" name="{{ $r->field_name }}" id="datepickerPrev1" placeholder="dd-mm-yyyy"
                                       maxlength="{{ $r->maximum_length }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}" readonly>
                                <div class="{{ $r->field_name }}_err error-message"></div>
                                <script type="text/javascript" nonce="{{ app('csp_nonce') }}">
                                    $(document).ready(function () {
                                        $("#datepickerPrev1").datepicker({
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
                            <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                <input type="text" class="input-field" name="{{ $r->field_name }}" id="datepickerNext1" placeholder="dd-mm-yyyy"
                                       maxlength="{{ $r->maximum_length }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}" readonly>
                                <div class="{{ $r->field_name }}_err error-message"></div>
                                <script type="text/javascript" nonce="{{ app('csp_nonce') }}">
                                    $(document).ready(function () {
                                        $("#datepickerNext1").datepicker({
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
                                <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) * @endif</span></label>
                                <select class="dropdown-select DependantFields fieldset_select2" name="{{ $r->field_name }}" data-id="{{ $r->id }}">
                                    @php
                                        $options = explode(",", $r->options);
                                    @endphp
                                    @if(count($options) != 1)
                                        <option value="">Please Select</option>
                                    @endif
                                    @foreach($options as $k => $option)
                                        @php
                                            $selected  = "";
                                            $option = trim($option); // clean whitespace
                                            $option_name = str_contains($option, '~') ? substr($option, strpos($option, "~") + 1) : $option;

                                            $old = old($r->field_name);
                                            if (str_contains($option_name,'~')) {
                                                $option_name = substr($option_name, strpos($option_name, "~") + 1);
                                            }
                                            $exitValue = array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '';
                                            if (!empty($exitValue) && str_contains($exitValue,'~')){
                                                $exitValue = substr($exitValue, strpos($exitValue, "~") + 1);
                                            }
                                            if($option_name == $exitValue){
                                                $selected  = "selected";
                                            }
                                        @endphp
                                        <option value="{{ $option }}" {{$selected}}>{{ $option_name }}</option>
                                        <script nonce="{{ app('csp_nonce') }}">
                                            $(document).ready(function() {
                                                $('.fieldset_select2').select2();
                                            });
                                        </script>
                                    @endforeach
                                </select>
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        @elseif( $r->field_type == \App\Enum\FieldTypeEnum::RADIO )
                            <div class="form-check p-0 mb-3 {{ $r->field_name }}">
                                <label class="form-check-label d-block mb-2">
                                    {{ $r->label_name }}<span class="required">@if( $r->is_required == 1 ) * @endif</span>
                                </label>
                                @php $options = explode(",", $r->options); @endphp
                                @foreach($options as $key => $option)
                                    @php
                                        $selected  = "";
                                        $exitValue = array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '';
                                        if($option == $exitValue){
                                            $selected  = "checked";
                                        }
                                    @endphp

                                    <div class="form-check d-inline-block me-2">
                                        <input class="form-check-input" type="radio" name="{{ $r->field_name }}" value="{{ $option }}"
                                            {{ $selected }}>
                                        <label class="form-check-label">
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        @elseif( $r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA )
                            <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1) * @endif</span></label>
                                <textarea rows="1" class="input-field text_eng" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" placeholder="{{ $r->placeholder }}"> {{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}</textarea>
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)
                            <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) * @endif</span></label>
                                <input type="text" class="input-field text_eng" name="{{ $r->field_name }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}" placeholder="{{ $r->placeholder }}">
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)
                            <div class="form-check p-0 mb-3 {{ $r->field_name }}">
                                @php
                                    $options = '';
                                    $input_checkbox = '';
                                    $options = explode(",", $r->options);
                                @endphp

                                <label class="form-check-label d-block mb-2">
                                    {{ $r->label_name }}<span class="required">@if($r->is_required == 1) * @endif</span>
                                </label>

                                @foreach($options as $key => $option)
                                    @php
                                        $value = $option;
                                        $exitValue = array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '';
                                        if(empty($option)) {
                                            $value = 'Yes';
                                        }
                                        $selected  = "";
                                        if($value == $exitValue){
                                            $selected  = "checked";
                                        }
                                    @endphp

                                    <div class="form-check d-inline-block me-2">
                                        <input class="form-check-input" type="checkbox" name="{{ $r->field_name }}" value="{{ $value }}"
                                            {{ $selected }}>
                                        <label class="form-check-label" style="color: white;">
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                                <div class="{{ $r->field_name }}_err error-message"></div>

                            </div>
                        @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)
                            <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) * @endif</span></label>
                                <input type="text" class="input-field datePicker js-date" name="{{ $r->field_name }}"
                                       placeholder="dd-mm-yyyy" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}" autocomplete="off" maxlength="10" readonly/>
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
                            <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) * @endif</span></label>
                                <input type="{{ $r->field_type }}" class="input-field number_field" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}">
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)
                            <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) * @endif</span></label>
                                <input type="text" class="input-field text_eng" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}">
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        @elseif($r->field_type==\App\Enum\FieldTypeEnum::ALPHANUMERIC)
                            <div class="input-wrapper mb-3">
                                <label class="mb-1" style="color:white !important">{{ $r->label_name }}<span class="required">@if( $r->is_required == 1) {{'*'}} @endif</span></label>
                                <input type="text" class="input-field alpha_numeric_field js-ignore-global" name="{{ $r->field_name }}" placeholder="Enter numbers and text only" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"/>
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
                </div>
            </fieldset>
        </div>
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
        });
    </script>
@endpush
