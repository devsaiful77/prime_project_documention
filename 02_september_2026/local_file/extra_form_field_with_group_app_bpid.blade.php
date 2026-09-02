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
if ($issueId == getId('AUCTION_REQUEST') && !empty($bpid_data)) {
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
                        <input id="{{ $PApiKeyId }}" type="{{ $r->field_type }}" class="input-field text_eng" name="{{ $r->field_name }}" value="{{ $bpidValues[$PApiKeyId] ?? old($r->field_name) }}"  placeholder="{{ $r->placeholder }}" @if($r->is_readonly == 1) readonly @endif>
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>

                    @elseif($r->field_type == \App\Enum\FieldTypeEnum::FILE)
                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                        <input id="{{ $PApiKeyId }}" type="file" class="input-field file-upload" name="{{ $r->field_name }}" @if($r->is_readonly == 1) readonly @endif>
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>


                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                        <div class="input-wrapper mb-1 {{ $r->field_name }}">
                            <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                            <input id="{{ $PApiKeyId }}" type="text" class="input-field datepickerPrev" name="{{ $r->field_name }}" placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::NEXT_DATE)
                        <div class="input-wrapper mb-1 {{ $r->field_name }}">
                            <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                            <input id="{{ $PApiKeyId }}" type="text" class="input-field datepickerNext" name="{{ $r->field_name }}" placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                            <div class="{{ $r->field_name }}_err error-message"></div>
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
                                    {{ $selected }} @if($r->is_readonly == 1) readonly @endif>
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
                        <textarea id="{{ $PApiKeyId }}" rows="1" class="input-field text_eng" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" placeholder="{{ $r->placeholder }}" @if($r->is_readonly == 1) readonly @endif>{{ old($r->field_name) }}</textarea>
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)
                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                        <input id="{{ $PApiKeyId }}" type="text" class="input-field text_eng" name="{{ $r->field_name }}" value="{{ old($r->field_name )}}" placeholder="{{ $r->placeholder }}" @if($r->is_readonly == 1) disabled @endif>
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
                                $hideStyle = ($PApiKeyId == 'customerMobile' || $PApiKeyId == 'customerPhone' || $PApiKey == 'customerEmail') ? 'display: none;' : '';

                                // Applicant-count wise field hide logic
                                $applicantFieldMap = [
                                    'second_app_mobile' => 2,
                                    'second_app_email'  => 2,
                                    'third_app_mobile'  => 3,
                                    'third_app_email'   => 3,
                                    'fourth_app_mobile' => 4,
                                    'fourth_app_email'  => 4,

                                    'signature_image_two' => 2,
                                    'signature_image_third' => 3,
                                    'signature_image_fourth' => 4,

                                    
                                ];

                                if (array_key_exists($r->field_name, $applicantFieldMap)) {
                                    $requiredCount = $applicantFieldMap[$r->field_name];
                                    $currentApplicantCount = $applicant_count ?? 1;

                                    if ($currentApplicantCount < $requiredCount) {
                                        $hideStyle = 'display: none;';
                                    }
                                }
                            @endphp

                            @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)
                                <div class="input-wrapper mb-1 {{ $r->field_name }}" style="{{ $hideStyle }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input id="{{ $PApiKeyId }}" type="{{ $r->field_type }}" class="input-field text_eng" name="{{ $r->field_name }}" value="{{ $bpidValues[$PApiKeyId] ?? old($r->field_name) }}" placeholder="{{ $r->placeholder }}" @if($r->is_readonly == 1) readonly @endif>
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::FILE)

                                <div class="input-wrapper mb-1 {{ $r->field_name }}" style="{{ $hideStyle }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input id="{{ $PApiKeyId }}" type="file" class="input-field file-upload" name="{{ $r->field_name }}">
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>


                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                        <input id="{{ $PApiKeyId }}" type="text" class="input-field datepickerPrev" name="{{ $r->field_name }}" placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                                        <div class="{{ $r->field_name }}_err error-message"></div>
                                    </div>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::NEXT_DATE)
                                    <div class="input-wrapper mb-1 {{ $r->field_name }}">
                                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                        <input id="{{ $PApiKeyId }}" type="text" class="input-field datepickerNext" name="{{ $r->field_name }}" placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                                        <div class="{{ $r->field_name }}_err error-message"></div>
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
                                </div>
                            @endif
                        @endforeach

                        {{-- For BpId if issue_id BPID --}}
                        @if ($issueId == getId('BPID'))
                            @if(($single['fieldset_id'] ?? null) === 'BPID_first_applicant')
                                <input type="hidden" name="nominee_count" id="nomineeCount" value="0">
                                <input type="hidden" name="applicant_count" id="applicantCount" value="0">
                            @endif
                        @endif
                        {{-- For BpId if issue_id BPID --}}
                    </div>
                </fieldset>
            </div>
        @endif
    @endforeach

    @push('js')
    <script src="{{ URL::asset('public/BBL_CI/js/image-compression.js') }}"></script>
    <script src="{{ URL::asset('public/BBL_BPID/js/extra_form_field_with_group_app_bpid.js') }}" nonce="{{ app('csp_nonce') }}"></script>
@endpush
