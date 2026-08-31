@extends('layouts.admin')
@section('content')
    @php
        $formData = session()->get('bp_form_data');
        use Illuminate\Support\Str;
    @endphp

    <style media="print">
        @page {
            size: auto;
            margin-left: 0;
            margin-right: 0
        }

        .bp-print-wrap {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .double-wrap,
        .doc-wrap {
            display: block;
        }

        .item-left-wrap,
        .item-right-wrap,
        .doc-item {
            width: 100%;
        }

        .item-section-wrap,
        .doc-item {
            page-break-inside: avoid;
        }

        .photo-item {
            height: auto;
            min-height: 120px;
        }
    </style>
    <link rel="stylesheet" href="{{ URL::asset('public/css/printBpId.css') }}">

    <div class="bp-print-wrap">

        {{-- Multiple Applications Loop --}}
        @php
            $applicantCount = isset($raw->applicant_count) ? (int)$raw->applicant_count : 1;
            $nomineeCount = isset($raw->nominee_count) ? (int)$raw->nominee_count : 1;

        @endphp

        {{-- first Application --}}
        @if ($applicantCount >= 1)
            <div>
                <div class="bp-header-wrap">
                    <div class="header-img">
                        <img class="img-fluid" src="{{ URL::asset('public/img/logo/Logo_new.png') }}" alt="Prime Bank PLC"
                            style="height: 38px;" />
                        <img class="img-fluid" src="{{ URL::asset('public/img/logo/bp-mngm.png') }}" alt="Prime Bank PLC"
                            style="height: 38px;" />
                    </div>
                    <p class="head-title">BP (Business Partner) ID OPENING FORM</p>
                </div>
                <div class="note-wrap">
                    Please complete all details in <b>BLOCK</b> Letters. Fill all names correctly and mark <b>(√)</b> the relevant
                    fields.
                    All Communication shall be sent only to the First Named Account Holder’s correspondence address.
                </div>
                <div class="bp-date-wrap">
                    <div class="bp-id-wrap">
                        <div class="bp-id">BPID</div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                    </div>
                    <div class="date-wrap">
                        @php
                            $date = \Carbon\Carbon::now()->format('dmY');
                            $digits = str_split($date);
                        @endphp

                        <div class="bp-id">Date</div>
                        @foreach ($digits as $digit)
                            <div class="bp-id" style="text-align: center">{{ $digit }}</div>
                        @endforeach

                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $bpType = 'Individual';
                        $bpTypes = [
                            'Individual',
                            'Mutual Fund',
                            'General Insurance',
                            'Foreign Investors',
                            'Life Insurance',
                            'Provident/Pension/Trust/Gratuity Fund',
                            'Corporate Bodies',
                            'Others',
                            'Investment Companies',
                        ];
                    @endphp

                    <div class="item-title">1. BP Type:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($bpTypes as $type)
                                <label class="status-option">
                                    <input type="checkbox" name="bp_type[]" value="{{ $type }}"
                                        {{ strtolower($bpType) == strtolower($type) ? 'checked' : '' }}
                                        disabled>
                                    {{ $type }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $residencyStatus = $raw->residency ?? '';
                        $residencyOptions = ['Resident', 'Non-Resident'];
                    @endphp
                    <div class="item-title">2. Residency of the Applicant:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($residencyOptions as $option)
                                <label class="status-option">
                                    <input type="checkbox" name="residency[]" value="{{ $option }}"
                                        {{ strtolower($residencyStatus) == strtolower($option) ? 'checked' : '' }} disabled>
                                    {{ $option }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $accountTypeStatus = $raw->type ?? '';
                        $accountTypeOptions = ['First', 'Second'];
                    @endphp
                    <div class="item-title">3. Applicant's Detail:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($accountTypeOptions as $option)
                                <label class="status-option">
                                    <input type="checkbox" name="residency[]" value="{{ $option }}"
                                        {{ strtolower($accountTypeStatus) == strtolower($option) ? 'checked' : '' }} disabled>
                                    {{ $option == 'First' ? 'Single/First Applicant' : 'Second Applicant' }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    <div class="item-title">4. Name of the Account:</div>
                    <div class="item-wrap">
                        <div class="bp-id-item">{{ $raw->first_app_name ?? '' }}</div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $genders = ['Male', 'Female', 'Other'];
                        $gender = $raw->gender ?? '';
                    @endphp
                    <div class="item-title">5. Applicable for Individual:</div>
                    <div class="item-wrap">

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div class="type-applicant-wrap">
                                    <div class="type-checkbox-wrap">
                                        @foreach ($genders as $g)
                                            <label class="status-option">
                                                <input type="checkbox" name="gender" value="{{ $g }}"
                                                    {{ strtolower($gender) == strtolower($g) ? 'checked' : '' }} disabled>
                                                {{ $g }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Date of Birth:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->dob ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Mother's Name:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->mother_name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Father's Name:</p>
                                    <p style="opacity: .5 ; margin-bottom: 0">{{ $raw->father_name ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">NID/Passport No:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->nid ? $raw->nid : $raw->passport ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">e-TIN No. (if any):</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->e_tin ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Occupation</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->occupation ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="item-section-wrap">
                    <div class="item-title">6. Applicable for Non-Individual:</div>
                    <div class="item-wrap">

                        <div class="type-applicant-wrap">
                            <div class="info-text">Type of Applicant:</div>
                            <div class="type-checkbox-wrap">
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Limited Company" disabled>
                                    Limited Company
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Pension/Provident/Gratuity/Mutual Fund" disabled>
                                    Pension/Provident/<br>Gratuity/Mutual Fund
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Proprietorship" disabled>
                                    Proprietorship
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Partnership" disabled>
                                    Partnership
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Others" disabled>
                                    Others
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                Trade License No:
                            </div>
                            <div class="col-4">
                                Issue Date:
                            </div>
                            <div class="col-4">
                                Issuing Authority:
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">Registration No:</div>
                            <div class="col-4">Issue Date:</div>
                            <div class="col-4">Issuing Authority:</div>
                        </div>

                        <div class="row">
                            <div class="col-4">VAT Registration No. (If Any):</div>
                            <div class="col-4">e-TIN No. (if any):</div>
                        </div>

                    </div>
                </div>
            </div>
        @endif

        {{-- Second Application --}}
        @if ($applicantCount >= 2)
            <div class="applicant-wrap">
                <div class="bp-header-wrap">
                    <div class="header-img">
                        <img class="img-fluid" src="{{ URL::asset('public/img/logo/Logo_new.png') }}" alt="Prime Bank PLC"
                            style="height: 38px;" />
                        <img class="img-fluid" src="{{ URL::asset('public/img/logo/bp-mngm.png') }}" alt="Prime Bank PLC"
                            style="height: 38px;" />
                    </div>
                    <p class="head-title">BP (Business Partner) ID OPENING FORM</p>
                </div>
                <div class="note-wrap">
                    Please complete all details in <b>BLOCK</b> Letters. Fill all names correctly and mark <b>(√)</b> the relevant
                    fields.
                    All Communication shall be sent only to the First Named Account Holder’s correspondence address.
                </div>
                <div class="bp-date-wrap">
                    <div class="bp-id-wrap">
                        <div class="bp-id">BPID</div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                    </div>
                    <div class="date-wrap">
                        @php
                            $date = \Carbon\Carbon::now()->format('dmY');
                            $digits = str_split($date);
                        @endphp

                        <div class="bp-id">Date</div>
                        @foreach ($digits as $digit)
                            <div class="bp-id" style="text-align: center">{{ $digit }}</div>
                        @endforeach

                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $bpType = 'Individual';
                        $bpTypes = [
                            'Individual',
                            'Mutual Fund',
                            'General Insurance',
                            'Foreign Investors',
                            'Life Insurance',
                            'Provident/Pension/Trust/Gratuity Fund',
                            'Corporate Bodies',
                            'Others',
                            'Investment Companies',
                        ];
                    @endphp

                    <div class="item-title">1. BP Type:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($bpTypes as $type)
                                <label class="status-option">
                                    <input type="checkbox" name="bp_type[]" value="{{ $type }}"
                                        {{ strtolower($bpType) == strtolower($type) ? 'checked' : '' }}
                                        disabled>
                                    {{ $type }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $residencyStatus = $raw->residency ?? '';
                        $residencyOptions = ['Resident', 'Non-Resident'];
                    @endphp
                    <div class="item-title">2. Residency of the Applicant:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($residencyOptions as $option)
                                <label class="status-option">
                                    <input type="checkbox" name="residency[]" value="{{ $option }}"
                                        {{ strtolower($residencyStatus) == strtolower($option) ? 'checked' : '' }} disabled>
                                    {{ $option }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $accountTypeStatus = $raw->type ?? '';
                        $accountTypeOptions = ['First', 'Second'];
                    @endphp
                    <div class="item-title">3. Applicant's Detail:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($accountTypeOptions as $option)
                                <label class="status-option">
                                    <input type="checkbox" name="residency[]" value="{{ $option }}"
                                        {{ strtolower($accountTypeStatus) == strtolower($option) ? 'checked' : '' }} disabled>
                                    {{ $option == 'First' ? 'Single/First Applicant' : 'Second Applicant' }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    <div class="item-title">4. Name of the Account:</div>
                    <div class="item-wrap">
                        <div class="bp-id-item">{{ $raw->second_app_name ?? '' }}</div>
                    </div>
                </div>
            
                <div class="item-section-wrap">
                    @php
                        $genders = ['Male', 'Female', 'Other'];
                        $gender = $raw->gender_two ?? '';
                    @endphp
                    <div class="item-title">5. Applicable for Individual:</div>
                    <div class="item-wrap">

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div class="type-applicant-wrap">
                                    <div class="type-checkbox-wrap">
                                        @foreach ($genders as $g)
                                            <label class="status-option">
                                                <input type="checkbox" name="gender" value="{{ $g }}"
                                                    {{ strtolower($gender) == strtolower($g) ? 'checked' : '' }} disabled>
                                                {{ $g }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Date of Birth:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->dob_two ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Mother's Name:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->mother_name_two ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Father's Name:</p>
                                    <p style="opacity: .5 ; margin-bottom: 0">{{ $raw->father_name_two ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">NID/Passport No:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->nid_two ? $raw->nid_two : $raw->passport_two ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">e-TIN No. (if any):</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->e_tin_two ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Occupation</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->occupation_two ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="item-section-wrap">
                    <div class="item-title">6. Applicable for Non-Individual:</div>
                    <div class="item-wrap">

                        <div class="type-applicant-wrap">
                            <div class="info-text">Type of Applicant:</div>
                            <div class="type-checkbox-wrap">
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Limited Company" disabled>
                                    Limited Company
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Pension/Provident/Gratuity/Mutual Fund" disabled>
                                    Pension/Provident/<br>Gratuity/Mutual Fund
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Proprietorship" disabled>
                                    Proprietorship
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Partnership" disabled>
                                    Partnership
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Others" disabled>
                                    Others
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                Trade License No:
                            </div>
                            <div class="col-4">
                                Issue Date:
                            </div>
                            <div class="col-4">
                                Issuing Authority:
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">Registration No:</div>
                            <div class="col-4">Issue Date:</div>
                            <div class="col-4">Issuing Authority:</div>
                        </div>

                        <div class="row">
                            <div class="col-4">VAT Registration No. (If Any):</div>
                            <div class="col-4">e-TIN No. (if any):</div>
                        </div>

                    </div>
                </div>
            </div>
        @endif

        {{-- Third Application --}}
        @if ($applicantCount >= 3)
            <div class="applicant-wrap">
                <div class="bp-header-wrap">
                    <div class="header-img">
                        <img class="img-fluid" src="{{ URL::asset('public/img/logo/Logo_new.png') }}" alt="Prime Bank PLC"
                            style="height: 38px;" />
                        <img class="img-fluid" src="{{ URL::asset('public/img/logo/bp-mngm.png') }}" alt="Prime Bank PLC"
                            style="height: 38px;" />
                    </div>
                    <p class="head-title">BP (Business Partner) ID OPENING FORM</p>
                </div>
                <div class="note-wrap">
                    Please complete all details in <b>BLOCK</b> Letters. Fill all names correctly and mark <b>(√)</b> the relevant
                    fields.
                    All Communication shall be sent only to the First Named Account Holder’s correspondence address.
                </div>
                <div class="bp-date-wrap">
                    <div class="bp-id-wrap">
                        <div class="bp-id">BPID</div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                    </div>
                    <div class="date-wrap">
                        @php
                            $date = \Carbon\Carbon::now()->format('dmY');
                            $digits = str_split($date);
                        @endphp

                        <div class="bp-id">Date</div>
                        @foreach ($digits as $digit)
                            <div class="bp-id" style="text-align: center">{{ $digit }}</div>
                        @endforeach

                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $bpType = 'Individual';
                        $bpTypes = [
                            'Individual',
                            'Mutual Fund',
                            'General Insurance',
                            'Foreign Investors',
                            'Life Insurance',
                            'Provident/Pension/Trust/Gratuity Fund',
                            'Corporate Bodies',
                            'Others',
                            'Investment Companies',
                        ];
                    @endphp

                    <div class="item-title">1. BP Type:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($bpTypes as $type)
                                <label class="status-option">
                                    <input type="checkbox" name="bp_type[]" value="{{ $type }}"
                                        {{ strtolower($bpType) == strtolower($type) ? 'checked' : '' }}
                                        disabled>
                                    {{ $type }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $residencyStatus = $raw->residency ?? '';
                        $residencyOptions = ['Resident', 'Non-Resident'];
                    @endphp
                    <div class="item-title">2. Residency of the Applicant:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($residencyOptions as $option)
                                <label class="status-option">
                                    <input type="checkbox" name="residency[]" value="{{ $option }}"
                                        {{ strtolower($residencyStatus) == strtolower($option) ? 'checked' : '' }} disabled>
                                    {{ $option }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $accountTypeStatus = $raw->type ?? '';
                        $accountTypeOptions = ['First', 'Second'];
                    @endphp
                    <div class="item-title">3. Applicant's Detail:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($accountTypeOptions as $option)
                                <label class="status-option">
                                    <input type="checkbox" name="residency[]" value="{{ $option }}"
                                        {{ strtolower($accountTypeStatus) == strtolower($option) ? 'checked' : '' }} disabled>
                                    {{ $option == 'First' ? 'Single/First Applicant' : 'Second Applicant' }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    <div class="item-title">4. Name of the Account:</div>
                    <div class="item-wrap">
                        <div class="bp-id-item">{{ $raw->third_app_name ?? '' }}</div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $genders = ['Male', 'Female', 'Other'];
                        $gender = $raw->gender_third ?? '';
                    @endphp
                    <div class="item-title">5. Applicable for Individual:</div>
                    <div class="item-wrap">

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div class="type-applicant-wrap">
                                    <div class="type-checkbox-wrap">
                                        @foreach ($genders as $g)
                                            <label class="status-option">
                                                <input type="checkbox" name="gender" value="{{ $g }}"
                                                    {{ strtolower($gender) == strtolower($g) ? 'checked' : '' }} disabled>
                                                {{ $g }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Date of Birth:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->dob_third ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Mother's Name:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->mother_name_third ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Father's Name:</p>
                                    <p style="opacity: .5 ; margin-bottom: 0">{{ $raw->father_name_third ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">NID/Passport No:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->nid_third ? $raw->nid_third : $raw->passport_third ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">e-TIN No. (if any):</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->e_tin_third ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Occupation</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->occupation_third ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="item-section-wrap">
                    <div class="item-title">6. Applicable for Non-Individual:</div>
                    <div class="item-wrap">

                        <div class="type-applicant-wrap">
                            <div class="info-text">Type of Applicant:</div>
                            <div class="type-checkbox-wrap">
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Limited Company" disabled>
                                    Limited Company
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Pension/Provident/Gratuity/Mutual Fund" disabled>
                                    Pension/Provident/<br>Gratuity/Mutual Fund
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Proprietorship" disabled>
                                    Proprietorship
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Partnership" disabled>
                                    Partnership
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Others" disabled>
                                    Others
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                Trade License No:
                            </div>
                            <div class="col-4">
                                Issue Date:
                            </div>
                            <div class="col-4">
                                Issuing Authority:
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">Registration No:</div>
                            <div class="col-4">Issue Date:</div>
                            <div class="col-4">Issuing Authority:</div>
                        </div>

                        <div class="row">
                            <div class="col-4">VAT Registration No. (If Any):</div>
                            <div class="col-4">e-TIN No. (if any):</div>
                        </div>

                    </div>
                </div>
            </div>
        @endif

        {{-- Fourth Application --}}
        @if ($applicantCount >= 4)
            <div class="applicant-wrap">
                <div class="bp-header-wrap">
                    <div class="header-img">
                        <img class="img-fluid" src="{{ URL::asset('public/img/logo/Logo_new.png') }}" alt="Prime Bank PLC"
                            style="height: 38px;" />
                        <img class="img-fluid" src="{{ URL::asset('public/img/logo/bp-mngm.png') }}" alt="Prime Bank PLC"
                            style="height: 38px;" />
                    </div>
                    <p class="head-title">BP (Business Partner) ID OPENING FORM</p>
                </div>
                <div class="note-wrap">
                    Please complete all details in <b>BLOCK</b> Letters. Fill all names correctly and mark <b>(√)</b> the relevant
                    fields.
                    All Communication shall be sent only to the First Named Account Holder’s correspondence address.
                </div>
                <div class="bp-date-wrap">
                    <div class="bp-id-wrap">
                        <div class="bp-id">BPID</div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                        <div class="bp-id"></div>
                    </div>
                    <div class="date-wrap">
                        @php
                            $date = \Carbon\Carbon::now()->format('dmY');
                            $digits = str_split($date);
                        @endphp

                        <div class="bp-id">Date</div>
                        @foreach ($digits as $digit)
                            <div class="bp-id" style="text-align: center">{{ $digit }}</div>
                        @endforeach

                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $bpType = 'Individual';
                        $bpTypes = [
                            'Individual',
                            'Mutual Fund',
                            'General Insurance',
                            'Foreign Investors',
                            'Life Insurance',
                            'Provident/Pension/Trust/Gratuity Fund',
                            'Corporate Bodies',
                            'Others',
                            'Investment Companies',
                        ];
                    @endphp

                    <div class="item-title">1. BP Type:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($bpTypes as $type)
                                <label class="status-option">
                                    <input type="checkbox" name="bp_type[]" value="{{ $type }}"
                                        {{ strtolower($bpType) == strtolower($type) ? 'checked' : '' }}
                                        disabled>
                                    {{ $type }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $residencyStatus = $raw->residency ?? '';
                        $residencyOptions = ['Resident', 'Non-Resident'];
                    @endphp
                    <div class="item-title">2. Residency of the Applicant:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($residencyOptions as $option)
                                <label class="status-option">
                                    <input type="checkbox" name="residency[]" value="{{ $option }}"
                                        {{ strtolower($residencyStatus) == strtolower($option) ? 'checked' : '' }} disabled>
                                    {{ $option }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $accountTypeStatus = $raw->type ?? '';
                        $accountTypeOptions = ['First', 'Second'];
                    @endphp
                    <div class="item-title">3. Applicant's Detail:</div>
                    <div class="item-wrap">
                        <div class="item-middle-wrap">
                            @foreach ($accountTypeOptions as $option)
                                <label class="status-option">
                                    <input type="checkbox" name="residency[]" value="{{ $option }}"
                                        {{ strtolower($accountTypeStatus) == strtolower($option) ? 'checked' : '' }} disabled>
                                    {{ $option == 'First' ? 'Single/First Applicant' : 'Second Applicant' }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    <div class="item-title">4. Name of the Account:</div>
                    <div class="item-wrap">
                        <div class="bp-id-item">{{ $raw->four_app_name ?? '' }}</div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $genders = ['Male', 'Female', 'Other'];
                        $gender = $raw->gender_fourth ?? '';
                    @endphp
                    <div class="item-title">5. Applicable for Individual:</div>
                    <div class="item-wrap">

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div class="type-applicant-wrap">
                                    <div class="type-checkbox-wrap">
                                        @foreach ($genders as $g)
                                            <label class="status-option">
                                                <input type="checkbox" name="gender" value="{{ $g }}"
                                                    {{ strtolower($gender) == strtolower($g) ? 'checked' : '' }} disabled>
                                                {{ $g }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Date of Birth:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->dob_fourth ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Mother's Name:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->mother_name_fourth ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Father's Name:</p>
                                    <p style="opacity: .5 ; margin-bottom: 0">{{ $raw->father_name_fourth ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">NID/Passport No:</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->nid_fourth ? $raw->nid_fourth : $raw->passport_fourth ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">e-TIN No. (if any):</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->e_tin_fourth ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 30px; ">
                                    <p style="margin-bottom: 0">Occupation</p>
                                    <p style="opacity: .5; margin-bottom: 0">{{ $raw->occupation_fourth ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="item-section-wrap">
                    <div class="item-title">6. Applicable for Non-Individual:</div>
                    <div class="item-wrap">

                        <div class="type-applicant-wrap">
                            <div class="info-text">Type of Applicant:</div>
                            <div class="type-checkbox-wrap">
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Limited Company" disabled>
                                    Limited Company
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Pension/Provident/Gratuity/Mutual Fund" disabled>
                                    Pension/Provident/<br>Gratuity/Mutual Fund
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Proprietorship" disabled>
                                    Proprietorship
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Partnership" disabled>
                                    Partnership
                                </label>
                                <label class="status-option">
                                    <input type="checkbox" name="applicant_type" value="Others" disabled>
                                    Others
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                Trade License No:
                            </div>
                            <div class="col-4">
                                Issue Date:
                            </div>
                            <div class="col-4">
                                Issuing Authority:
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">Registration No:</div>
                            <div class="col-4">Issue Date:</div>
                            <div class="col-4">Issuing Authority:</div>
                        </div>

                        <div class="row">
                            <div class="col-4">VAT Registration No. (If Any):</div>
                            <div class="col-4">e-TIN No. (if any):</div>
                        </div>

                    </div>
                </div>
            </div>
        @endif



        {{-- common Information --}}
        <div class="item-section-wrap item-section-wrap-6 d-none">
            <div class="item-title">7. Contact Details:</div>
            <div class="item-wrap">
                <div class="item-left-wrap">
                    <div class="app-info-wrap">
                        <div class="info-text">Present Address/ Business Address:</div>
                        <div class="info-text">{{ $raw->present_address ?? '' }}</div>
                        <div class="info-text">Permanent Address:</div>
                        <div class="info-text">{{ $raw->permanent_address ?? '' }}</div>

                        {{-- <div class="info-text"></div> --}}
                    </div>
                    <div class="app-info-wrap" style="margin: 5px 0px">
                        <div class="info-text">Phone No: <span style="margin-left: 10px">{{ $raw->phone_number ?? '' }}</span></div>
                        <div class="info-text">Mobile No: <span style="margin-left: 10px">{{ $raw->mobile_number }}</span></div>
                    </div>

                    <div class="info-text">
                        <div style="display: flex; gap: 10px">
                            Email: <span>{{ $raw->email ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="item-section-wrap d-none">

            <div class="item-title">8. Bank Details:</div>
            <div class="item-wrap">
                <div class="item-left-wrap">
                    <div class="app-info-wrap" style="margin-top: 0">
                        <div class="info-text">Bank Name: <span style="margin-left: 10px">{{ $raw->bank_name ?? '' }}</span></div>

                        <div class="info-text">Branch Name: <span style="margin-left: 10px">{{ $raw->branch_name ?? '' }}</span></div>

                        <div class="info-text">Account Number: <span style="margin-left: 10px">{{ $raw->account_number ?? '' }}</span></div>

                        <div class="info-text">Account Type: <span style="margin-left: 10px">{{ $raw->customer_type ?? '' }}</span></div>

                        <div class="info-text">Routing No.: <span style="margin-left: 10px">{{ $raw->branch_code ?? '' }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="item-section-wrap item-section-wrap-6">
            <div class="item-title">7. Contact Details:</div>
            <div class="item-wrap">

                <div class="row">
                    <div class="col-6">Present Address/ Business Address:</div>
                    <div class="col-6">{{ $raw->present_address ?? '' }}</div>
                </div>

                <div class="row">
                    <div class="col-6">Permanent Address:</div>
                    <div class="col-6">{{ $raw->permanent_address ?? '' }}</div>
                </div>

                <div class="row">
                    <div class="col-3">Phone No:</div>
                    <div class="col-3">{{ $raw->phone_number ?? '' }}</div>
                    <div class="col-3">Mobile No:</div>
                    <div class="col-3">{{ $raw->mobile_number ?? '' }}</div>
                </div>

                <div class="row">
                    <div class="col-3">Email:</div>
                    <div class="col-3">{{ $raw->email ?? '' }}</div>
                </div>

            </div>
        </div>

        <div class="item-section-wrap">
            <div class="item-title">8. Bank Details:</div>
            <div class="item-wrap">

                <div class="row">
                    <div class="col-3">Bank Name:</div>
                    <div class="col-3">{{ $raw->bank_name ?? '' }}</div>
                    <div class="col-3">Branch Name:</div>
                    <div class="col-3">{{ $raw->branch_name ?? '' }}</div>
                </div>

                <div class="row">
                    <div class="col-3">Account Number:</div>
                    <div class="col-3">{{ $raw->account_number ?? '' }}</div>
                    <div class="col-3">Account Type:</div>
                    <div class="col-3">{{ $raw->customer_type ?? '' }}</div>
                </div>

                <div class="row">
                    <div class="col-3">Routing No.:</div>
                    <div class="col-3">{{ $raw->branch_code ?? '' }}</div>
                </div>

            </div>
        </div>



        <div class="item-section-wrap">
            <div class="item-title">9. Nominee(s) [Applicable for Individual Account Holder]:</div>
            <div class="sub-text"> I/we authorize the following person(s) as nominee(s) to receive/draw the amount in
                my/our account in the event of my/our
                death.</div>
            <div class="">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="vcenter text-center">Name</th>
                            <th class="vcenter text-center">NID/Passport/ Birth Certificate No</th>
                            <th class="vcenter text-center">Address</th>
                            <th class="vcenter text-center">Relation with Account Holder</th>
                            <th class="vcenter text-center"> Date of Birth</th>
                            <th class="vcenter text-center">% Payable</th>
                            <th class="vcenter text-center">Signature of the Nominee</th>
                        </tr>
                    </thead>
                    <tbody style="word-break: break-all;">
                        @if ($nomineeCount >= 1)
                            <tr>
                                <td class="vcenter text-center">{{ $raw->f_nominee_name ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->f_nominee_nid ? $raw->f_nominee_nid : $raw->f_nom_passport }}</td>
                                <td class="vcenter text-center">{{ $raw->f_nom_address ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->f_nom_relations ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->f_nom_dob ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->f_nom_payable ?? '' }}</td>
                                <td class="vcenter text-center">
                                    @if(!empty($raw->f_nom_signature_path_url))
                                        <div class="img-box">
                                            <img src="{{ asset($raw->f_nom_signature_path_url) }}" width="100">
                                        </div>
                                    @else
                                        ---
                                    @endif
                                </td>
                            </tr>
                        @endif

                        @if ($nomineeCount >= 2)
                            <tr>
                                <td class="vcenter text-center">{{ $raw->s_nom_name ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->s_nom_nid ? $raw->s_nom_nid : $raw->s_nom_passport }}</td>
                                <td class="vcenter text-center">{{ $raw->s_nom_address ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->s_nom_relations ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->s_nom_dob ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->s_nom_payable ?? '' }}</td>
                                <td class="vcenter text-center">
                                    @if(!empty($raw->s_nom_signature_path_url))
                                        <div class="img-box">
                                            <img src="{{ asset($raw->s_nom_signature_path_url) }}" width="100">
                                        </div>
                                    @else
                                        ---
                                    @endif
                                </td>
                            </tr>
                        @endif

                        @if ($nomineeCount >= 3)
                            <tr>
                                <td class="vcenter text-center">{{ $raw->t_nom_name ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->t_nom_nid ? $raw->t_nom_nid : $raw->t_nom_passport }}</td>
                                <td class="vcenter text-center">{{ $raw->t_nom_address ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->t_nom_relations ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->t_nom_dob ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->t_nom_payable ?? '' }}</td>
                                <td class="vcenter text-center">
                                    @if(!empty($raw->t_nom_signature_path_url))
                                        <div class="img-box">
                                            <img src="{{ asset($raw->t_nom_signature_path_url) }}" width="100">
                                        </div>
                                    @else
                                        ---
                                    @endif
                                </td>
                            </tr>
                        @endif

                        @if ($nomineeCount >= 4)
                            <tr>
                                <td class="vcenter text-center">{{ $raw->four_nom_name ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->four_nom_nid ? $raw->four_nom_nid : $raw->four_nom_passport }}</td>
                                <td class="vcenter text-center">{{ $raw->four_nom_address ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->four_nom_relations ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->four_nom_dob ?? '' }}</td>
                                <td class="vcenter text-center">{{ $raw->four_nom_payable ?? '' }}</td>
                                <td class="vcenter text-center">
                                    @if(!empty($raw->four_nom_signature_path_url))
                                        <div class="img-box">
                                            <img src="{{ asset($raw->four_nom_signature_path_url) }}" width="100">
                                        </div>
                                    @else
                                        ---
                                    @endif
                                </td>
                            </tr>
                        @endif



                    </tbody>
                </table>
            </div>
        </div>


        <div class="item-section-wrap">
            <div class="item-title">10. Signatory Details (Applicable for Non-Individual)</div>
            <div class="">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="vcenter text-center">Name</th>
                            <th class="vcenter text-center">Designation and Department</th>
                            <th class="vcenter text-center">Personal Details</th>
                        </tr>
                    </thead>
                    <tbody style="word-break: break-all;">
                        <tr>
                            <td class="vcenter text-center">Md. Rakibul Hasan</td>
                            <td class="vcenter text-center"></td>
                            <td class="vcenter text-left">
                                <div class="info-text">Father's Name: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">Mother's Name: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">NID/Passport No: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">Date of Birth: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">Contact No: <span style="margin-left: 5px"></span></div>
                            </td>
                        </tr>

                        <tr>
                            <td class="vcenter text-center">Nusrat Jahan</td>
                            <td class="vcenter text-center"></td>
                            <td class="vcenter text-left">
                                <div class="info-text">Father's Name: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">Mother's Name: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">NID/Passport No: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">Date of Birth: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">Contact No: <span style="margin-left: 5px"></span></div>
                            </td>
                        </tr>

                        <tr>
                            <td class="vcenter text-center">Kamal Ahmed</td>
                            <td class="vcenter text-center"></td>
                            <td class="vcenter text-left">
                                <div class="info-text">Father's Name: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">Mother's Name: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">NID/Passport No: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">Date of Birth: <span style="margin-left: 5px"></span></div>
                                <div class="info-text">Contact No: <span style="margin-left: 5px"></span></div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Fallback when no files uploaded --}}
        <div class="item-section-wrap">
            <div class="item-title">11. Photographs:</div>

            <div class="item-wrap">
                <div class="item-photo-wrap">

                    @if ($applicantCount >= 1)
                        <div class="photo-item">
                            @php
                                $uploadFields = [
                                    'passport_image_path_url' => 'Passport Image',
                                    'signature_image_path_url' => 'Signature Image',
                                ];
                            @endphp

                            @php $hasAnyFile = false; @endphp

                            {{-- <div> --}}

                            @foreach ($uploadFields as $field => $label)
                                @if (!empty($raw->{$field}))
                                    <div class="img-box" style="margin-bottom: 10px">
                                        <img style="width: 100px; height: 90" src="{{ asset($raw->{$field}) }}" alt="{{ $label }}">
                                    </div>
                                    @php $hasAnyFile = true; @endphp
                                @endif
                            @endforeach
                            {{-- </div> --}}

                            @unless ($hasAnyFile)
                                Please attach a Recent Passport Size Color Photograph of 1st Applicant/Authorized Signatory
                            @endunless
                        </div>
                    @else
                        <div class="photo-item">
                            Please attach a Recent Passport Size Color Photograph of 1st Applicant/Authorized Signatory
                        </div>
                    @endif

                    @if ($applicantCount >= 2)
                        <div class="photo-item">
                            @php
                                $uploadFields = [
                                    'passport_image_two_path_url' => 'Passport Image',
                                    'signature_image_two_path_url' => 'Signature Image',
                                ];
                            @endphp

                            @php $hasAnyFile = false; @endphp

                            {{-- <div> --}}

                            @foreach ($uploadFields as $field => $label)
                                @if (!empty($raw->{$field}))
                                    <div class="img-box" style="margin-bottom: 10px">
                                        <img style="width: 100px; height: 90" src="{{ asset($raw->{$field}) }}" alt="{{ $label }}">
                                    </div>
                                    @php $hasAnyFile = true; @endphp
                                @endif
                            @endforeach
                            {{-- </div> --}}

                            @unless ($hasAnyFile)
                                Please attach a Recent Passport Size Color Photograph of 2nd Applicant/Authorized Signatory
                            @endunless
                        </div>
                    @else
                        <div class="photo-item">
                            Please attach a Recent Passport Size Color Photograph of 2nd Applicant/Authorized Signatory
                        </div>
                    @endif

                    @if ($applicantCount >= 3)
                        <div class="photo-item">
                            @php
                                $uploadFields = [
                                    'passport_image_third_path_url' => 'Passport Image',
                                    'signature_image_third_path_url' => 'Signature Image',
                                ];
                            @endphp

                            @php $hasAnyFile = false; @endphp

                            {{-- <div> --}}

                            @foreach ($uploadFields as $field => $label)
                                @if (!empty($raw->{$field}))
                                    <div class="img-box" style="margin-bottom: 10px">
                                        <img style="width: 100px; height: 90" src="{{ asset($raw->{$field}) }}" alt="{{ $label }}">
                                    </div>
                                    @php $hasAnyFile = true; @endphp
                                @endif
                            @endforeach
                            {{-- </div> --}}

                            @unless ($hasAnyFile)
                                Please attach a Recent Passport Size Color Photograph of 3rd Applicant/Authorized Signatory
                            @endunless
                        </div>
                    @else
                        <div class="photo-item">
                            Please attach a Recent Passport Size Color Photograph of 3rd Applicant/Authorized Signatory
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="item-section-wrap">
            <div class="item-title">12. Specimen Signature:</div>
            <div class="">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="vcenter text-center">Applicants</th>
                            <th class="vcenter text-center">Name of Applicant/Authorized Signatory</th>
                            <th class="vcenter text-center">Signature with Date <br> (Official Seal is Mandatory for
                                Signatory)</th>
                        </tr>
                    </thead>
                    <tbody style="word-break: break-all;">
                        <tr>
                            <td class="vcenter text-center"> - </td>
                            <td class="vcenter text-center"> - </td>
                            <td class="vcenter text-center"> - </td>
                        </tr>
                        <tr>
                            <td class="vcenter text-center"> - </td>
                            <td class="vcenter text-center"> - </td>
                            <td class="vcenter text-center"> - </td>
                        </tr>
                        <tr>
                            <td class="vcenter text-center"> - </td>
                            <td class="vcenter text-center"> - </td>
                            <td class="vcenter text-center"> - </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- <div class="item-section-wrap">
            <div class="item-title">13. Special Instruction on Operation of Account (If Applicable):</div>
            <div class="item-wrap">
                <div class="item-left-wrap">
                    <div class="gender-wrap">
                        <label class="status-option"> <input type="checkbox" name="residence" value="resident" disabled> Either
                            or Survivor </label>
                        <label class="status-option"> <input type="checkbox" name="residence" value="resident" disabled> Anyone
                            Can Operate </label>
                        <label class="status-option"> <input type="checkbox" name="residence" value="resident" disabled> Any Two
                            Will Operate </label>
                    </div>
                    <div class="gender-wrap">
                        <label class="status-option"> <input type="checkbox" name="residence" value="resident" disabled> Only
                            _____________________ </label>
                    </div>
                    <div class="gender-wrap">
                        <label class="status-option"> <input type="checkbox" name="residence" value="resident" disabled> Account
                            will be operated by _____________________ Account will be operated by </label>
                    </div>
                </div>
            </div>
            <div class="item-wrap">
                <div class="sub-text" style="text-align: center;margin-bottom: 0">For the Use of Bank Only</div>
            </div>
            <div class="item-wrap">
                <div class="double-wrap" style="padding-top: 48px">
                    <div class="item-left-wrap">
                        <div class="sub-text" style="text-align: center;margin-bottom: 0;white-space: nowrap">
                            ---------------------------------</div>
                        <div class="sub-text" style="text-align: center;margin-bottom: 0">Initiated By</div>
                    </div>
                    <div class="item-right-wrap">
                        <div class="sub-text" style="text-align: center;margin-bottom: 0;white-space: nowrap">
                            ---------------------------------</div>
                        <div class="sub-text" style="text-align: center;margin-bottom: 0">Authorized OŸcer of Government
                            Securities Investment Window/Manager/Head of Treasury</div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="item-section-wrap">
            <div class="item-title">13. Special Instruction on Operation of Account (If Applicable):</div>
            <div class="item-wrap">
                <div class="item-left-wrap">
                    <div class="gender-wrap">
                        <label class="status-option">
                            <input type="checkbox" name="operation_instruction" value="either_or_survivor" disabled>
                            Either or Survivor
                        </label>
                        <label class="status-option">
                            <input type="checkbox" name="operation_instruction" value="anyone_can_operate" disabled>
                            Anyone Can Operate
                        </label>
                        <label class="status-option">
                            <input type="checkbox" name="operation_instruction" value="any_two_will_operate" disabled>
                            Any Two Will Operate
                        </label>
                    </div>
                    <div class="gender-wrap">
                        <label class="status-option">
                            <input type="checkbox" name="operation_instruction" value="only" disabled>
                            Only _____________________
                        </label>
                    </div>
                    <div class="gender-wrap">
                        <label class="status-option">
                            <input type="checkbox" name="operation_instruction" value="operated_by" disabled>
                            Account will be operated by _____________________
                        </label>
                    </div>
                </div>
            </div>

            <div class="item-wrap">
                <div class="sub-text" style="text-align: center; margin-bottom: 0">For the Use of Bank Only</div>
            </div>

            <div class="item-wrap">
                <div class="row" style="padding-top: 48px">
                    <div class="col-6">
                        <div class="sub-text" style="text-align: center; margin-bottom: 0; white-space: nowrap">
                            ---------------------------------
                        </div>
                        <div class="sub-text" style="text-align: center; margin-bottom: 0">Initiated By</div>
                    </div>
                    <div class="col-6">
                        <div class="sub-text" style="text-align: center; margin-bottom: 0; white-space: nowrap">
                            ---------------------------------
                        </div>
                        <div class="sub-text" style="text-align: center; margin-bottom: 0">
                            Authorized Officer of Government Securities Investment Window/Manager/Head of Treasury
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sub-text" style="margin-bottom: 0; margin-top: 16px"><b>N.B.</b></div>
        <div class="sub-text" style="margin-bottom: 5px;">1) Certificate of Incorporation no. or Internal Revenue Service
            (IRS) or relevant document no. may be
            used instead of trade license in SL no. 6 for Non-Resident individuals and institutional investors.</div>
        <div class="sub-text">2) Based on relevant SWIFT messages, the information of the authorized signatories of the
            custodian
            bank may be used in SL. No. 10, 11, 12 and 13 for Non-Resident individuals and institutional investors.</div>

        
        <br><br><br><br>

        <div class="bp-header-wrap">
            <p class="head-title">Required Documents</p>
        </div>

        <div class="doc-wrap">
    <div class="doc-item">
        <b> For Banks/FIs/Limited Company:</b>
        <ol>
            <li style="margin-bottom: 5px">Application Form</li>
            <li style="margin-bottom: 5px">Bank Account Details/DAB Account Details (for Banks/FIs)</li>
            <li style="margin-bottom: 5px">Certificate of Incorporation</li>
            <li style="margin-bottom: 5px">Certificate of Commencement (if applicable)</li>
            <li style="margin-bottom: 5px">License From the Respective Authority (if applicable)</li>
            <li style="margin-bottom: 5px">Memorandum/Articles of Association</li>
            <li style="margin-bottom: 5px">Board Resolution</li>
            <li style="margin-bottom: 5px">e-TIN</li>
            <li style="margin-bottom: 5px">Registered Address</li>
            <li style="margin-bottom: 5px">Contact Details of Relevant Personnel</li>
            <li style="margin-bottom: 5px">Photo and NID of the authorized signatory(s)</li>
        </ol>
    </div>
    <div class="doc-item">
        <b>For Pension/Provident/Gratuity/Mutual Fund:</b>
        <ol>
            <li style="margin-bottom: 5px">Application Form</li>
            <li style="margin-bottom: 5px">Bank Account Details</li>
            <li style="margin-bottom: 5px">Registration/Approval Certificate</li>
            <li style="margin-bottom: 5px">Deed of Trust (if applicable)</li>
            <li style="margin-bottom: 5px">Resolution/Meeting Minutes of Board of Trustees</li>
            <li style="margin-bottom: 5px">NBR's Certificate (if applicable)</li>
            <li style="margin-bottom: 5px">Contact details of Authorized Signatory</li>
            <li style="margin-bottom: 5px">Photo and NID of the authorized signatory(s)</li>
        </ol>
    </div>

    <div class="doc-item">
        <b>For Sole Proprietorship (SP)/Partnership Business:</b>
        <ol>
            <li style="margin-bottom: 5px">Application Form</li>
            <li style="margin-bottom: 5px">Bank Account Details</li>
            <li style="margin-bottom: 5px">Trade License</li>
            <li style="margin-bottom: 5px">Partnership Deed (for Partnership Business)</li>
            <li style="margin-bottom: 5px">e-TIN</li>
            <li style="margin-bottom: 5px">NID of proprietor/partners</li>
            <li style="margin-bottom: 5px">Contact details of proprietor/partners</li>
            <li style="margin-bottom: 5px">Photo and Information of the Nominee(s) (for SP)</li>
            <li style="margin-bottom: 5px">NID/Passport of the Nominee(s) (for SP)</li>
        </ol>
    </div>

    <div class="doc-item">
        <b>For Individuals:</b>
        <ol>
            <li style="margin-bottom: 5px">Application Form</li>
            <li style="margin-bottom: 5px">Bank Account Details</li>
            <li style="margin-bottom: 5px">NID/Passport</li>
            <li style="margin-bottom: 5px">Photo</li>
            <li style="margin-bottom: 5px">e-TIN</li>
            <li style="margin-bottom: 5px">Contact details</li>
            <li style="margin-bottom: 5px">Photo and Information of the Nominee(s)</li>
            <li style="margin-bottom: 5px">NID/Passport/Birth Certificate of the Nominee(s)</li>
        </ol>
    </div>

    <div class="doc-item">
        <b>Foreign/Non-Resident Individuals:</b>
        <ol>
            <li style="margin-bottom: 5px">Application Form</li>
            <li style="margin-bottom: 5px">Bank Details for Investor's NFCA/NITA account</li>
            <li style="margin-bottom: 5px">Photo and Passport</li>
            <li style="margin-bottom: 5px">TIN/Tax Certificate/Related Certificate (if applicable)</li>
            <li style="margin-bottom: 5px">Contact details</li>
            <li style="margin-bottom: 5px">Photo and Passport/ID/Birth Certificate of the Nominee(s)</li>
        </ol>
    </div>

    <div class="doc-item">
        <b>Foreign/Non-Resident Institutions:</b>
        <ol>
            <li style="margin-bottom: 5px">Application Form</li>
            <li style="margin-bottom: 5px">Bank Details for Investor's NFCA/NITA account</li>
            <li style="margin-bottom: 5px">Certificate of Incorporation/or Relevant Document</li>
            <li style="margin-bottom: 5px">Memorandum/Articles of Association (if applicable)</li>
            <li style="margin-bottom: 5px">Partnership Deed (for Partnership Business) (if applicable)</li>
            <li style="margin-bottom: 5px">Resolution/Meeting Minutes of Board of Trustees (if applicable)</li>
            <li style="margin-bottom: 5px">TIN/Tax Certificate/Related Certificate (if applicable)</li>
            <li style="margin-bottom: 5px">Registered Address</li>
            <li style="margin-bottom: 5px">Contact Details of Relevant Personnel (Official of the Custodian Bank)</li>
            <li style="margin-bottom: 5px">Photo and NID of the authorized signatory(s) (Official of the Custodian Bank)</li>
        </ol>
    </div>
</div>

    </div>
@endsection
<script type="text/javascript">
    window.print();
</script>
