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
                                    <div class="{{ $r->field_name }}_err error-message"></div>
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
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
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
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>
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

                <div class="fieldset-body" id="body_{{ $single['fieldset_id'] ?? '' }}">
                    @foreach($single['fields'] as $key=>$r)
    
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
    
                        <div class="mb-2">
    
                            @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)
                                <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px; {{ $hideStyle }}">
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
    
                                <div class="form-group {{ $r->field_name }}" style="{{ $hideStyle }}">
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
                                </div>
    
                            @endif
                        </div>
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
        @endif

    @endforeach

@push('js')
    <script src="{{ URL::asset('public/BBL_BPID/js/extra_form_field_with_group_bpid.js') }}" nonce="{{ app('csp_nonce') }}"></script>
@endpush

