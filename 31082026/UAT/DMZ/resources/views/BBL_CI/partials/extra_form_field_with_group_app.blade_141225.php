<?php
$input_checkbox = '';
$input_radio = '';
$input_dropdown = '';
$i=1;
?>
    @foreach($issue_fields as $single)
        <!-- Field without Fieldset -->
        @if($single['fieldset_title'] == "")
            @foreach($single['fields'] as $key => $r)
                @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)
                    <div class="input-wrapper mb-3 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                        <input type="{{ $r->field_type }}" oninput="textInEnglish(event)" class="input-field" name="{{ $r->field_name }}" value="{{ old($r->field_name )}}"  placeholder="{{ $r->placeholder }}">
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>

                    @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                        <div class="input-wrapper mb-3 {{ $r->field_name }}">
                            <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                            <input type="text" class="input-field" name="{{ $r->field_name }}" placeholder="dd-mm-yyyy" id="datepickerPrev" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                            <script nonce="{{ app('csp_nonce') }}">
                                $(function() {
                                $("#datepickerPrev").datepicker({
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
                        <div class="input-wrapper mb-3 {{ $r->field_name }}">
                            <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                            <input type="text" class="input-field" name="{{ $r->field_name }}" id="datepickerNext" placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                            <div class="{{ $r->field_name }}_err error-message"></div>
                            <script nonce="{{ app('csp_nonce') }}">
                                $(function() {
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
                        <label class="mb-1">
                            {{ $r->label_name }} <span class="required" >@if($r->is_required == 1){{'*'}} @endif </span>
                        </label>
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
                                    $selected  = "selected";
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
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                        <textarea rows="1" oninput="textInEnglish(event)" class="input-field" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" placeholder="{{ $r->placeholder }}">{{ old($r->field_name) }}</textarea>
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::ADDRESS)
                    <div class="input-wrapper mb-3 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required == 1) {{'*'}} @endif</span></label>
                        <input type="text" oninput="textInEnglish(event)" class="input-field" name="{{ $r->field_name }}" value="{{ old($r->field_name )}}" placeholder="{{ $r->placeholder }}">
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
                                <input class="form-check-input" type="checkbox" name="{{ $r->field_name }}" value="{{ $value }}"
                                    {{ $selected }}>
                                <label class="form-check-label" style="color: white !important;">
                                    {{ $option }}
                                </label>
                            </div>
                        @endforeach
                        <div class="{{ $r->field_name }}_err error-message"></div>

                    </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)
                    <div class="input-wrapper mb-3 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                        <input type="text" class="input-field datePicker js-date" name="{{ $r->field_name }}"  value="{{ old($r->field_name) }}"
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
                    <div class="input-wrapper mb-3 {{ $r->field_name }}">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                        <input type="{{ $r->field_type }}" oninput="allowOnlyNumbers(event)" class="input-field" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>
                @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)
                    <div class="input-wrapper mb-3">
                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                        <input type="{{ $r->field_type }}" oninput="textInEnglish(event)" class="input-field" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
                        <div class="{{ $r->field_name }}_err error-message"></div>
                    </div>
                @endif
            @endforeach
        @endif

        <!-- Field With Fieldset -->
        @if($single['fieldset_title'] != "")
            <div class="mobile_fieldset">
                <fieldset class="inputTextWrap mt-3">
                    <legend>{{ $single['fieldset_title'] }}:</legend>
                    <div>
                        @foreach($single['fields'] as $key => $r)
                            @if($r->field_type==\App\Enum\FieldTypeEnum::TEXT)
                                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input type="{{ $r->field_type }}" oninput="textInEnglish(event)" class="input-field" name="{{ $r->field_name }}" value="{{ old($r->field_name )}}" placeholder="{{ $r->placeholder }}">
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>

                                @elseif($r->field_type==\App\Enum\FieldTypeEnum::PREV_DATE)
                                    <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                        <input type="text" class="input-field" name="{{ $r->field_name }}" id="datepickerPrev" placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                                        <div class="{{ $r->field_name }}_err error-message"></div>
                                        <script nonce="{{ app('csp_nonce') }}">
                                            $(function() {
                                            $("#datepickerPrev").datepicker({
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
                                    <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                        <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                        <input type="text" class="input-field" name="{{ $r->field_name }}" id="datepickerNext" placeholder="dd-mm-yyyy" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" readonly>
                                        <div class="{{ $r->field_name }}_err error-message"></div>
                                        <script nonce="{{ app('csp_nonce') }}">
                                            $(function() {
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
                                    <label class="mb-1">
                                        {{ $r->label_name }} <span class="required" >@if($r->is_required == 1){{'*'}} @endif </span>
                                    </label>
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
                            @elseif( $r->field_type == \App\Enum\FieldTypeEnum::RADIO )
                                <div class="form-check p-0 mb-3 {{ $r->field_name }}">
                                    <label class="form-check-label d-block mb-2">
                                        {{ $r->label_name }}<span class="required">@if( $r->is_required == 1 ) {{'*'}} @endif</span>
                                    </label>
                                    @php $options = explode(",", $r->options); @endphp
                                    @foreach($options as $key => $option)
                                        @php
                                            $selected  = "";
                                            if($option == old($r->field_name)){
                                                $selected  = "selected";
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
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <textarea rows="1" oninput="textInEnglish(event)" class="input-field" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" placeholder="{{ $r->placeholder }}">{{ old($r->field_name) }}</textarea>
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            @elseif( $r->field_type==\App\Enum\FieldTypeEnum::ADDRESS )
                                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input type="text" oninput="textInEnglish(event)" class="input-field" name="{{ $r->field_name }}" value="{{ old($r->field_name )}}" placeholder="{{ $r->placeholder }}">
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            @elseif( $r->field_type==\App\Enum\FieldTypeEnum::CHECKBOX )
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
                                            <input class="form-check-input" type="checkbox" name="{{ $r->field_name }}" value="{{ $value }}"
                                                {{ $selected }}>
                                            <label class="form-check-label" style="color: white !important;">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                    <div class="{{ $r->field_name }}_err error-message"></div>

                                </div>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DATE)
                                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input type="text" class="input-field datePicker js-date" name="{{ $r->field_name }}"  value="{{ old($r->field_name) }}"
                                           autocomplete="off" maxlength="10" placeholder="dd-mm-yyyy">
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
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input type="{{ $r->field_type }}" oninput="allowOnlyNumbers(event)" class="input-field" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            @elseif($r->field_type==\App\Enum\FieldTypeEnum::DECIMAL)
                                <div class="input-wrapper mb-3 {{ $r->field_name }}">
                                    <label for="" class="input-label">{{ $r->label_name }}<span class="required">@if($r->is_required==1){{'*'}} @endif</span></label>
                                    <input type="{{ $r->field_type }}" oninput="textInEnglish(event)" class="input-field" name="{{ $r->field_name }}" maxlength="{{ $r->maximum_length }}" value="{{old($r->field_name)}}" placeholder="{{ $r->placeholder }}">
                                    <div class="{{ $r->field_name }}_err error-message"></div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </fieldset>
            </div>
        @endif
    @endforeach
