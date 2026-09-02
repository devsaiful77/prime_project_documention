<?php
$input_checkbox = '';
$input_radio = '';
$input_dropdown = '';
$i=1;
?>
@foreach($issue_fields as $single)
    <!-- Field without Fieldset -->
    @if($single['fieldset_title'] == "")
        @foreach($single['fields'] as $key=>$r)
            <div class="normal-field">
                @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)
                    <div class="card card-color {{ $r->field_name }}">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="mb-1">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                <input type="{{ $r->field_type }}" class="form-control text_eng" name="{{ $r->field_name }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"
                                       placeholder="{{ $r->placeholder }}" />
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        </div>
                    </div>

                @elseif($r->field_type==\App\Enum\FieldTypeEnum::DROPDOWN)
                <div class="card card-color {{ $r->field_name }}">
                    <div class="card-body">
                        <div class="form-group" style="padding-bottom: 8px;">
                            <label class="mb-1">
                                {{ $r->label_name }} <span class="required" >@if($r->is_required == 1){{'*'}} @endif </span>
                            </label>
                            <select class="form-select DependantFields fieldset_select2" name="{{ $r->field_name }}" data-id="{{ $r->id }}">

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
                                @endforeach
                            </select>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>
                    </div>
                </div>

                @elseif( $r->field_type == \App\Enum\FieldTypeEnum::RADIO)
                    <div class="card card-color {{ $r->field_name }}">
                        <div class="card-body">
                            <div class="form-check mt-2 p-0">
                                <label class="form-check-label d-block mb-2">
                                    {{ $r->label_name }}<span class="required">@if( $r->is_required == 1 ) {{'*'}} @endif</span> :
                                </label>
                                @php $options = explode(",", $r->options); @endphp
                                @foreach($options as $key => $option)
                                    @php
                                        $option = trim($option);
                                        $selected  = "";
                                        $exitValue = array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '';
                                        if($option == $exitValue){
                                            $selected  = "checked";
                                        }
                                    @endphp

                                    <div class="form-check d-inline-block me-2">
                                        <input class="form-check-input" type="radio" name="{{ $r->field_name }}" id="{{ "radio1" . $key }}" value="{{ $option }}"
                                               {{ $selected }}>
                                        <label class="form-check-label" for="{{ "radio1" . $key }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        </div>
                    </div>

                @elseif( $r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA )

                    <div class="card card-color {{ $r->field_name }}">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="mb-1">
                                    {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                                </label>
                                <textarea rows="1" class="form-control text_eng" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                          maxlength="{{ $r->maximum_length }}">{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}</textarea>
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        </div>
                    </div>

                @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)
                    <div class="card card-color {{ $r->field_name }}">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="mb-1">
                                    {{ $r->label_name }}<span class="required">@if( $r->is_required == 1) {{'*'}} @else @endif</span>
                                </label>
                                <input type="text" class="form-control text_eng" name="{{ $r->field_name }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}" placeholder="{{ $r->placeholder }}" />
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        </div>
                    </div>

                @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)
                    <div class="card card-color {{ $r->field_name }}">
                        <div class="card-body">
                            <div class="form-check mt-2 p-0">
                                @php
                                    $options = '';
                                    $input_checkbox = '';
                                    $options = explode(",", $r->options);
                                @endphp

                                <label class="form-check-label d-block mb-2">
                                    {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span> :
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
                                    <label class="form-check-label ms-2" style="color: white;">
                                        {{ $option }}
                                    </label>
                                </div>
                                @endforeach
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        </div>
                    </div>

                @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)
                    <div class="card card-color {{ $r->field_name }}">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="mb-1">
                                    {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}}  @endif</span>
                                </label>
                                <input type="text" class="form-control datePicker js-date" name="{{ $r->field_name }}"
                                       value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"
                                       autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy" readonly>
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        </div>
                    </div>

                @elseif($r->field_type==\App\Enum\FieldTypeEnum::NUMBER)
                    <div class="card card-color {{ $r->field_name }}">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="mb-1">
                                    {{ $r->label_name }}<span class="required">@if( $r->is_required == 1) {{'*'}} @endif</span>
                                </label>
                                <input type="text" class="form-control" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}"
                                       value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}">
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        </div>
                    </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                        <div class="card card-color">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">{{ $r->label_name }}<span class="required">@if( $r->is_required == 1) {{'*'}} @endif</span></label>
                                    <input type="text" class="form-control datepickerPrev1" name="{{ $r->field_name }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}" placeholder="dd-mm-yyyy" autocomplete="off" readonly/>
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            </div>

                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::NEXT_DATE)
                        <div class="card card-color">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">{{ $r->label_name }}<span class="required">@if( $r->is_required == 1) {{'*'}} @endif</span></label>
                                    <input type="text" class="form-control datepickerNext1" name="{{ $r->field_name }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"  placeholder="dd-mm-yyyy" readonly/>
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
                                <input type="text" class="form-control text_eng" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                       maxlength="{{ $r->maximum_length }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}">
                                <div class="{{ $r->field_name }}_err error-message"></div>
                            </div>
                        </div>
                    </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::ALPHANUMERIC)
                    <div class="input-wrapper mb-3">
                        <label class="mb-1" style="color:white !important">{{ $r->label_name }}<span class="required">@if( $r->is_required == 1) {{'*'}} @endif</span></label>
                        <input type="text" class="input-field alpha_numeric_field js-ignore-global" name="{{ $r->field_name }}" placeholder="Enter numbers and text only" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"/>
                        <div class="{{ $r->field_name }}_err error-message"></div>
                        <span class="invalid-char-error text-danger"></span>
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <!-- Field With Fieldset -->
    @if($single['fieldset_title'] != "")

        

        <fieldset class="" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
            <legend style=" font-size: 18px; font-weight: 500">{{ $single['fieldset_title'] }}</legend>
            @foreach($single['fields'] as $key => $r)

                @php
                    $PApiKey = $r['api_key'];
                    $PApiKeyArr = explode(':', $PApiKey);
                    $PApiKeyId = $PApiKeyArr[0];

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

                    $hideStyle = '';
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

                        <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px; {{ $hideStyle }}" >
                            <label class="mb-1">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                            <input type="{{ $r->field_type }}" class="form-control text_eng" name="{{ $r->field_name }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"
                                   placeholder="{{ $r->placeholder }}"/>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>

                    
                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::FILE)

                            


                        <div class="form-group {{ $r->field_name }}" style="{{ $hideStyle }}" >
                            <label class="mb-1">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                            <input type="{{ $r->field_type }}" id="{{ $PApiKeyId }}" class="form-control text_eng" name="{{ $r->field_name }}"
                                   placeholder="{{ $r->placeholder }}"/>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>



                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::DROPDOWN)

                        <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;" style="{{ $hideStyle }}">
                            <label class="mb-1">{{ $r->label_name }} <span class="required">@if( $r->is_required == 1 ) {{'*'}} @else @endif</span></label>
                            <select class="form-select DependantFields fieldset_select2" name="{{ $r->field_name }}" data-id="{{ $r->id }}">
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
                                @endforeach
                            </select>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::RADIO)

                        <div class="form-check {{ $r->field_name }} mb-2 p-0">
                            @php $options = explode(",", $r->options); @endphp

                            <label class="form-check-label d-block mb-2">
                                {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                            </label>

                            @foreach($options as $key => $option)
                                @php
                                    $option = trim($option);
                                    $selected  = "";
                                    $exitValue = array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '';
                                    if($option == $exitValue){
                                        $selected  = "checked";
                                    }
                                @endphp
                                <div class="form-check d-inline-block me-2">
                                    <input class="form-check-input" type="radio" name="{{ $r->field_name }}" id="{{ "radio1" . $key }}" value="{{ $option }}"
                                           {{ $selected }}>
                                    <label class="form-check-label" for="{{ "radio1" . $key }}">
                                        {{ $option }}
                                    </label>
                                </div>
                            @endforeach
                            <div class="{{ $r->field_name }}_err error-message"></div>

                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::TEXTAREA)

                        <div class="form-group {{ $r->field_name }}">
                            <label class="mb-1" for="imageInput">{{ $r->label_name }}
                                <span class="required"> @if($r->is_required == 1) {{'*'}} @endif </span>
                            </label>
                            <textarea rows="1" class="form-control text_eng" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}">
                                {{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}
                            </textarea>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)

                        <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                            <label class="mb-1" >
                                {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                            </label>
                            <input type="text" class="form-control text_eng" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}"
                                   value="{{old($r->field_name)}}{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"/>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>
                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX)

                        <div class="form-check {{ $r->field_name }} p-0">
                            @php
                                $options = '';
                                $input_checkbox = '';
                                $options = explode(",", $r->options);
                            @endphp
                            <label class="form-check-label d-block mb-2">
                                {{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @else @endif</span>
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
                        <div class="form-group mt-2 {{ $r->field_name }}">
                            <label class="mb-1">
                                {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                            </label>
                            <input type="text" class="form-control datePicker js-date"  name="{{ $r->field_name }}"
                                   value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"
                                   autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy" readonly/>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::NUMBER)
                        <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                            <label class="mb-1">
                                {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                            </label>
                            <input type="text" class="form-control number_field" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                   maxlength="{{ $r->maximum_length }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"/>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>
                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                        <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                            <label class="mb-1">
                                {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                            </label>
                            <input type="text" class="form-control datepickerPrev" name="{{ $r->field_name }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}" autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy" readonly/>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::NEXT_DATE)
                        <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                            <label class="mb-1">
                                {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                            </label>
                            <input type="text" class="form-control datepickerNext" name="{{ $r->field_name }}"
                                   placeholder="{{ $r->placeholder }}" maxlength="{{ $r->maximum_length }}"
                                    value="{{old($r->field_name)}}" readonly/>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)
                        <div class="form-group {{ $r->field_name }}" style="padding-bottom: 8px;">
                            <label class="mb-1">
                                {{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span>
                            </label>
                            <input type="text" class="form-control text_eng" name="{{ $r->field_name }}" placeholder="{{ $r->placeholder }}"
                                   maxlength="{{ $r->maximum_length }}" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"/>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                        </div>
                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::ALPHANUMERIC)
                    <div class="input-wrapper mb-3">
                        <label class="mb-1" style="color:white !important">{{ $r->label_name }}<span class="required">@if( $r->is_required == 1) {{'*'}} @endif</span></label>
                        <input type="text" class="input-field alpha_numeric_field js-ignore-global" name="{{ $r->field_name }}" placeholder="Enter numbers and text only" value="{{ array_key_exists($r->label_name, $arraySingle) ? $arraySingle[$r->label_name] : '' }}"/>
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>
                    @endif
                </div>
            @endforeach
        </fieldset>
    @endif
@endforeach

@push('js')
    <script src="{{ URL::asset('public/BBL_BPID/js/send_back_ticket_details.js') }}" nonce="{{ app('csp_nonce') }}"></script>
@endpush