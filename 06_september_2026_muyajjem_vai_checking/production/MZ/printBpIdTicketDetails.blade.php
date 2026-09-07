<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Prime Bank PLC. # PrimeServe | {{ (!empty($title)) ? $title : "" }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('public/img/favicon.ico') }}">
    {{-- Stylesheets --}}
    <link rel="stylesheet" href="{{ URL::asset('public/css/latest-v/bootstrap-5.3.1.min.css') }}">
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
    
</head>
<body id="page-container">
    @php
        $formData = session()->get('bp_form_data');
        use Illuminate\Support\Str;
    @endphp
    <div class="bp-print-wrap">

        {{-- first Application --}}
        @if ($raw->applicant_first_applicant_gender)
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
                    @if ($raw->bpid)
                        @php
                            $digits = str_split($raw->bpid);
                            $digits = array_pad($digits, 14, '');
                        @endphp
                        <div class="bp-id-wrap">
                            <div class="bp-id">BPID</div>
                            @foreach($digits as $d)
                                <div class="bp-id">{{ $d }}</div>
                            @endforeach
                        </div>
                    @else
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
                    @endif

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
                        $bpType = $raw->applicant_bp_type ?? 'Individual';
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
                        $residencyStatus = $raw->applicant_residency ?? '';
                        $residencyOptions = ['Resident', 'Non-Resident'];
                    @endphp
                    <div class="item-title">2. Residency of the Applicant:</div>
                    <div class="item-wrap" style="padding: 5px 1rem">
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
                        $accountTypeStatus = $raw->applicant_applicant_type ?? 'First';
                        $accountTypeOptions = ['First', 'Second'];
                    @endphp
                    <div class="item-title">3. Applicant's Detail:</div>
                    <div class="item-wrap" style="padding: 5px 1rem">
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
                        <div class="bp-id-item value-text">{{ $raw->applicant_name_of_the_account ?? '' }}</div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $genders = ['Male', 'Female', 'Other'];
                        $gender = $raw->applicant_first_applicant_gender ?? '';
                    @endphp
                    <div class="item-title">5. Applicable for Individual:</div>
                    <div class="item-wrap" style="padding: 1rem 0px">

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
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Date of Birth:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_first_applicant_date_of_birth ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Mother's Name:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_first_applicant_mothers_name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Father's Name:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_first_applicant_fathers_name ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">NID/Passport No:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_first_applicant_nidpassport ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">e-TIN No. (if any):</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_first_applicant_etin_no_if_any ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Occupation:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_first_applicant_occupation ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="item-section-wrap" style="page-break-after: always;">
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
        @if ($raw->applicant_second_applicant_gender)
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
                    @if ($raw->bpid)
                        @php
                            $digits = str_split($raw->bpid);
                            $digits = array_pad($digits, 14, '');
                        @endphp
                        <div class="bp-id-wrap">
                            <div class="bp-id">BPID</div>
                            @foreach($digits as $d)
                                <div class="bp-id">{{ $d }}</div>
                            @endforeach
                        </div>
                    @else
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
                    @endif

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
                        $bpType = $raw->applicant_bp_type ?? 'Individual';
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
                        $residencyStatus = $raw->applicant_residency ?? '';
                        $residencyOptions = ['Resident', 'Non-Resident'];
                    @endphp
                    <div class="item-title">2. Residency of the Applicant:</div>
                    <div class="item-wrap" style="padding: 5px 1rem">
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
                        $accountTypeStatus = $raw->applicant_applicant_type ?? 'First';
                        $accountTypeOptions = ['First', 'Second'];
                    @endphp
                    <div class="item-title">3. Applicant's Detail:</div>
                    <div class="item-wrap" style="padding: 5px 1rem">
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
                        <div class="bp-id-item value-text">{{ $raw->applicant_second_applicant_name ?? '' }}</div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $genders = ['Male', 'Female', 'Other'];
                        $gender = $raw->applicant_second_applicant_gender ?? '';
                    @endphp
                    <div class="item-title">5. Applicable for Individual:</div>
                    <div class="item-wrap" style="padding: 1rem 0px">

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
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Date of Birth:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_second_applicant_date_of_birth ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Mother's Name:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_second_applicant_mothers_name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Father's Name:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_second_applicant_fathers_name ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">NID/Passport No:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_second_applicant_nidpassport ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">e-TIN No. (if any):</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_second_applicant_etin_no_if_any ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Occupation:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_second_applicant_occupation ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="item-section-wrap" style="page-break-after: always;">
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
        @if ($raw->applicant_third_applicant_gender)
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
                    @if ($raw->bpid)
                        @php
                            $digits = str_split($raw->bpid);
                            $digits = array_pad($digits, 14, '');
                        @endphp
                        <div class="bp-id-wrap">
                            <div class="bp-id">BPID</div>
                            @foreach($digits as $d)
                                <div class="bp-id">{{ $d }}</div>
                            @endforeach
                        </div>
                    @else
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
                    @endif

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
                        $bpType = $raw->applicant_bp_type ?? 'Individual';
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
                        $residencyStatus = $raw->applicant_residency ?? '';
                        $residencyOptions = ['Resident', 'Non-Resident'];
                    @endphp
                    <div class="item-title">2. Residency of the Applicant:</div>
                    <div class="item-wrap" style="padding: 5px 1rem">
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
                        $accountTypeStatus = $raw->applicant_applicant_type ?? '';
                        $accountTypeOptions = ['First', 'Second'];
                    @endphp
                    <div class="item-title">3. Applicant's Detail:</div>
                    <div class="item-wrap" style="padding: 5px 1rem">
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
                        <div class="bp-id-item value-text">{{ $raw->applicant_third_applicant_name ?? '' }}</div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $genders = ['Male', 'Female', 'Other'];
                        $gender = $raw->applicant_third_applicant_gender ?? '';
                    @endphp
                    <div class="item-title">5. Applicable for Individual:</div>
                    <div class="item-wrap" style="padding: 1rem 0px">

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
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Date of Birth:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_third_applicant_date_of_birth ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Mother's Name:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_third_applicant_mothers_name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Father's Name:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_third_applicant_fathers_name ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">NID/Passport No:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_third_applicant_nidpassport ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">e-TIN No. (if any):</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_third_applicant_etin_no_if_any ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Occupation:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_third_applicant_occupation ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="item-section-wrap" style="page-break-after: always;">
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
        @if ($raw->applicant_fourth_applicant_gender)
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
                    @if ($raw->bpid)
                        @php
                            $digits = str_split($raw->bpid);
                            $digits = array_pad($digits, 14, '');
                        @endphp
                        <div class="bp-id-wrap">
                            <div class="bp-id">BPID</div>
                            @foreach($digits as $d)
                                <div class="bp-id">{{ $d }}</div>
                            @endforeach
                        </div>
                    @else
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
                    @endif

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
                        $bpType = $raw->applicant_bp_type ?? 'Individual';
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
                        $residencyStatus = $raw->applicant_residency ?? '';
                        $residencyOptions = ['Resident', 'Non-Resident'];
                    @endphp
                    <div class="item-title">2. Residency of the Applicant:</div>
                    <div class="item-wrap" style="padding: 5px 1rem">
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
                        $accountTypeStatus = $raw->applicant_applicant_type ?? '';
                        $accountTypeOptions = ['First', 'Second'];
                    @endphp
                    <div class="item-title">3. Applicant's Detail:</div>
                    <div class="item-wrap" style="padding: 5px 1rem">
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
                        <div class="bp-id-item value-text">{{ $raw->applicant_fourth_applicant_name ?? '' }}</div>
                    </div>
                </div>

                <div class="item-section-wrap">
                    @php
                        $genders = ['Male', 'Female', 'Other'];
                        $gender = $raw->applicant_fourth_applicant_gender ?? '';
                    @endphp
                    <div class="item-title">5. Applicable for Individual:</div>
                    <div class="item-wrap" style="padding: 1rem 0px">

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
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Date of Birth:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_fourth_applicant_date_of_birth ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Mother's Name:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_fourth_applicant_mothers_name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Father's Name:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_fourth_applicant_fathers_name ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">NID/Passport No:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_fourth_applicant_nidpassport ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">e-TIN No. (if any):</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_fourth_applicant_etin_no_if_any ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin: 5px; 0px;">
                            <div class="col-6">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    <p style="margin-bottom: 0">Occupation:</p>
                                    <p class="value-text" style="margin-bottom: 0">{{ $raw->applicant_fourth_applicant_occupation ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="item-section-wrap" style="page-break-after: always;">
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
        <div class="item-section-wrap item-section-wrap-6">
            <div class="item-title">7. Contact Details:</div>
            <div class="item-wrap">

                <div class="row">
                    <div class="col-12">
                        Present Address/ Business Address:
                        <span class="value-text" style="margin-left: 15px">{{ $raw->applicant_present_address ?? '' }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        Permanent Address:
                        <span class="value-text" style="margin-left:15px">{{ $raw->applicant_permanent_address ?? '' }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        Phone No:
                        <span class="value-text" style="margin-left: 15px">{{ $raw->applicant_phone_no ?? '' }}</span>
                    </div>
                    <div class="col-6">
                        Mobile No: 
                        <span class="value-text" style="margin-left: 15px">{{ $raw->applicant_mobile_no ?? '' }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        Email:
                        <span class="value-text" style="margin-left: 15px">{{ $raw->applicant_email ?? '' }}</span>
                    </div>
                </div>

            </div>
        </div>

        <div class="item-section-wrap">
            <div class="item-title">8. Bank Details:</div>
            <div class="item-wrap">

                <div class="row">
                    <div class="col-6">
                        Bank Name:
                        <span class="value-text" style="margin-left: 15px">{{ $raw->applicant_bank_name ?? '' }}</span>
                    </div>
                    <div class="col-6">
                        Branch Name:
                        <span class="value-text" style="margin-left: 15px">{{ $raw->applicant_branch_name ?? '' }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        Account Number:
                        <span class="value-text" style="margin-left: 15px">{{ $raw->applicant_account_number ?? '' }}</span>
                    </div>
                    <div class="col-6">
                        Account Type:
                        <span class="value-text" style="margin-left: 15px">{{ $raw->applicant_account_type ?? '' }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        Routing No.:
                        <span class="value-text" style="margin-left: 15px">{{ $raw->applicant_routing_no ?? '' }}</span>
                    </div>
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
                            <th class="vcenter text-center" style="font-size: 10px">Name</th>
                            <th class="vcenter text-center" style="font-size: 10px">NID/Passport/Birth Certificate No</th>
                            <th class="vcenter text-center" style="font-size: 10px">Address</th>
                            <th class="vcenter text-center" style="font-size: 10px">Relation with  Account Holder</th>
                            <th class="vcenter text-center" style="font-size: 10px"> Date of Birth</th>
                            <th class="vcenter text-center" style="font-size: 10px">% Payable</th>
                            <th class="vcenter text-center" style="font-size: 10px">Signature of the Nominee</th>
                        </tr>
                    </thead>
                    <tbody style="word-break: break-all;">
                        @if ($raw->applicant_first_nominee_name)
                            <tr>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_first_nominee_name ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_first_nominee_nidpassportbirth_no ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:7px">{{ $raw->applicant_first_nominee_address ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_first_nominee_relation_with_account_holder ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_first_nominee_dob ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_first_nominee_payable ?? '' }}</td>
                                <td class="vcenter text-center">
                                    @if(!empty($raw->nominee_1_signature))
                                        <div class="img-box">
                                            <img src="{{ asset('public/attachments/'.$raw->nominee_1_signature) }}" width="60">
                                        </div>
                                    @else
                                        ---
                                    @endif
                                </td>
                            </tr>
                        @endif

                        @if ($raw->applicant_second_nominee_name)
                            <tr>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_second_nominee_name ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_second_nominee_nidpassportbirth_no ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_second_nominee_address ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_second_nominee_relation_with_account_holder ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_second_nominee_dob ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_second_nominee_payable ?? '' }}</td>
                                <td class="vcenter text-center">
                                    @if(!empty($raw->nominee_2_signature))
                                        <div class="img-box">
                                            <img src="{{ asset('public/attachments/'.$raw->nominee_2_signature) }}" width="60">
                                        </div>
                                    @else
                                        ---
                                    @endif
                                </td>
                            </tr>
                        @endif

                        @if (optional($raw)->applicant_third_nominee_name)
                            <tr>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_third_nominee_name ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_third_nominee_nidpassportbirth_no ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_third_nominee_address ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_third_nominee_relation_with_account_holder ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_third_nominee_dob ?? '' }}</td>
                                <td class="vcenter text-center value-text" style="font-size:9px">{{ $raw->applicant_third_nominee_payable ?? '' }}</td>
                                <td class="vcenter text-center">
                                    @if(!empty($raw->nominee_3_signature))
                                        <div class="img-box">
                                            <img src="{{ asset('public/attachments/'.$raw->nominee_3_signature) }}" width="60">
                                        </div>
                                    @else
                                        ---
                                    @endif
                                </td>
                            </tr>
                        @endif

                        @if (optional($raw)->applicant_fourth_nominee_name)
                            <tr>
                                <td class="vcenter text-center value-text">{{ $raw->applicant_fourth_nominee_name ?? '' }}</td>
                                <td class="vcenter text-center value-text">{{ $raw->applicant_fourth_nominee_nidpassportbirth_no ?? '' }}</td>
                                <td class="vcenter text-center value-text">{{ $raw->applicant_fourth_nominee_address ?? '' }}</td>
                                <td class="vcenter text-center value-text">{{ $raw->applicant_fourth_nominee_relation_with_account_holder ?? '' }}</td>
                                <td class="vcenter text-center value-text">{{ $raw->applicant_fourth_nominee_dob ?? '' }}</td>
                                <td class="vcenter text-center value-text">{{ $raw->applicant_fourth_nominee_payable ?? '' }}</td>
                                <td class="vcenter text-center">
                                    @if(!empty($raw->nominee_4_signature))
                                        <div class="img-box">
                                            <img src="{{ asset('public/attachments/'.$raw->nominee_4_signature) }}" width="60">
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

        <div class="item-section-wrap" >
            <div class="item-title">10. Signatory Details (Applicable for Non-Individual)</div>
            <div class="">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="vcenter text-center" width="30%">Name</th>
                            <th class="vcenter text-center" width="30%">Designation and Department</th>
                            <th class="vcenter text-center">Personal Details</th>
                        </tr>
                    </thead>


                    <tbody style="word-break: break-all;">
                        <tr>
                            <td class="vcenter text-center"></td>
                            <td class="vcenter text-center"></td>
                            <td class="text-left" style="padding: 0">
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">Father's Name: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">Mother's Name: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">NID/Passport No: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">Date of Birth: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="padding: 3px">Contact No: <span class="value-text" style="margin-left: 5px"></span></div>
                            </td>
                        </tr>

                        <tr>
                            <td class="vcenter text-center"></td>
                            <td class="vcenter text-center"></td>
                            <td class="text-left" style="padding: 0">
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">Father's Name: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">Mother's Name: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">NID/Passport No: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">Date of Birth: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="padding: 3px">Contact No: <span class="value-text" style="margin-left: 5px"></span></div>
                            </td>
                        </tr>

                        <tr>
                            <td class="vcenter text-center"></td>
                            <td class="vcenter text-center"></td>
                            <td class="text-left" style="padding: 0">
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">Father's Name: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">Mother's Name: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">NID/Passport No: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="border-bottom: 1px solid #126c38; padding: 3px">Date of Birth: <span class="value-text" style="margin-left: 5px"></span></div>
                                <div class="info-text" style="padding: 3px">Contact No: <span class="value-text" style="margin-left: 5px"></span></div>
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

                    @if (!empty($raw->applicant_1_passport))
                        <div class="photo-item">
                            @php $hasAnyFile = false; @endphp

                            @if (!empty($raw->applicant_1_passport))
                                <div class="img-box" style="margin-bottom: 10px">
                                    <img style="width: 100px; height: 90" src="{{ asset('public/attachments/' . $raw->applicant_1_passport) }}" alt="Passport Image">
                                </div>
                                @php $hasAnyFile = true; @endphp
                            @endif

                            @unless ($hasAnyFile)
                                Please attach a Recent Passport Size Color Photograph of 1st Applicant/Authorized Signatory
                            @endunless
                        </div>
                    @else
                        <div class="photo-item">
                            Please attach a Recent Passport Size Color Photograph of 1st Applicant/Authorized Signatory
                        </div>
                    @endif

                    @if (!empty($raw->applicant_2_passport))
                        <div class="photo-item">
                            @php $hasAnyFile = false; @endphp

                            @if (!empty($raw->applicant_2_passport))
                                <div class="img-box" style="margin-bottom: 10px">
                                    <img style="width: 100px; height: 90" src="{{ asset('public/attachments/' . $raw->applicant_2_passport) }}" alt="Passport Image Two">
                                </div>
                                @php $hasAnyFile = true; @endphp
                            @endif

                            @unless ($hasAnyFile)
                                Please attach a Recent Passport Size Color Photograph of 2nd Applicant/Authorized Signatory
                            @endunless
                        </div>
                    @else
                        <div class="photo-item">
                            Please attach a Recent Passport Size Color Photograph of 2nd Applicant/Authorized Signatory
                        </div>
                    @endif

                    @if (!empty($raw->applicant_3_passport))
                        <div class="photo-item">
                            @php $hasAnyFile = false; @endphp

                            @if (!empty($raw->applicant_3_passport))
                                <div class="img-box" style="margin-bottom: 10px">
                                    <img style="width: 100px; height: 90" src="{{ asset('public/attachments/' . $raw->applicant_3_passport) }}" alt="Passport Image Third">
                                </div>
                                @php $hasAnyFile = true; @endphp
                            @endif

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
                            <td class="vcenter text-center value-text"> 
                                {{ $raw->applicant_first_applicant_name ? 1 : '-' }}  
                            </td>
                            <td class="vcenter text-center value-text"> 
                                {{ $raw->applicant_first_applicant_name ?? '-' }}    
                            </td>
                            <td class="vcenter text-center">     
                                @if (!empty($raw->applicant_1_signature))
                                    <img style="width: 60px;" src="{{ asset('public/attachments/' . $raw->applicant_1_signature) }}" alt="Signature Image">
                                    <span class="value-text" style="margin-left: 10px">{{$raw->create_time}}</span>
                                @else
                                    -
                                @endif     
                            </td>
                        </tr>
                        <tr>
                            <td class="vcenter text-center value-text"> 
                                {{ $raw->applicant_second_applicant_name ? 2 : '-' }}  
                            </td>
                            <td class="vcenter text-center value-text"> 
                                {{ $raw->applicant_second_applicant_name  ?? '-' }}    
                            </td>
                            <td class="vcenter text-center">     
                                @if (!empty($raw->applicant_2_signature))
                                    <img style="width: 60px;" src="{{ asset('public/attachments/' . $raw->applicant_2_signature) }}" alt="Signature Image">
                                    <span class="value-text" style="margin-left: 10px">{{$raw->create_time}}</span>
                                @else
                                    -
                                @endif     
                            </td>
                        </tr>
                        <tr>
                            <td class="vcenter text-center value-text"> 
                                {{ $raw->applicant_second_applicant_name ? 3 : '-' }}  
                            </td>
                            <td class="vcenter text-center value-text"> 
                                {{ $raw->applicant_third_applicant_name  ?? '-' }}    
                            </td>
                            <td class="vcenter text-center">     
                                @if (!empty($raw->applicant_3_signature))
                                    <img style="width: 60px;" src="{{ asset('public/attachments/' . $raw->applicant_3_signature) }}" alt="Signature Image">
                                    <span class="value-text" style="margin-left: 10px">{{$raw->create_time}}</span>
                                @else
                                    -
                                @endif     
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

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
        <div class="sub-text" style="page-break-after: always;">2) Based on relevant SWIFT messages, the information of the authorized signatories of the
            custodian
            bank may be used in SL. No. 10, 11, 12 and 13 for Non-Resident individuals and institutional investors.</div>

        

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

<script type="text/javascript">
    window.print();
</script>

</body>
</html>