@extends('layouts.admin')
@section('content')
    <style media="print">
        @page {
            size: auto;
            margin-left: 0;
            margin-right: 0
        }
    </style>

    <style>
        td .underline-paragraph {
            width: 100% !important;
            display: block;
            border-bottom: 2px dashed #000;
            margin-bottom: 2px;
            padding-bottom: 4px;
        }
    </style>
    <link rel="stylesheet" href="{{ URL::asset('public/css/printBpId.css') }}">

    <div class="action-print-wrap">
        <div class="bp-header-wrap">
            <div class="header-img">
                <img class="img-fluid" src="{{ URL::asset('public/img/logo/Logo_new.png') }}" alt="Prime Bank PLC"
                    style="height: 38px;" />
                <img class="img-fluid" src="{{ URL::asset('public/img/logo/bp-mngm.png') }}" alt="Prime Bank PLC"
                    style="height: 38px;" />
            </div>
        </div>
        <div class="title-header-wrap">
            <div class="title-header">
                <div class="title-header-left">
                    <p>Head of Branch</p>
                    <p style="margin-bottom: 5px">{{ Str::title(strtolower($raw->branch_name ?? '')) }}</p>
                    <p>Prime Bank PLC.</p>
                </div>
                <div class="title-header-right">
                    <p><b>Primary Auction Request Form</b></p>
                    <p><strong>Treasury Bill & Treasury Bond</strong></p>
                    <p>Individual Customer</p>
                    <div class="ac-date-wrap">
                        <p>Date:</p>

                        @php
                            $date = \Carbon\Carbon::now()->format('dmY');
                            $digits = str_split($date);
                        @endphp

                        <div class="date-box-wrap">
                            @foreach ($digits as $digit)
                                <div class="ac-box">{{ $digit }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-tab-wrap">
            <table class="table table-condensed table-bordered">

                @php
                    $bp = str_split($raw->bp_id) ?? [];
                    $accountDigits = str_split($raw->account_number) ?? [];

                    $total = 14;
                    $emptyCount = $total - count($bp);
                    $bpDigits = array_merge(array_fill(0, $emptyCount, ""), $bp);

                @endphp

                <tr>
                    <th style="width: 35%">BP ID</th>

                    @foreach ($bpDigits as $digit)
                        <td class="vcenter text-center">{{ $digit }}</td>
                    @endforeach


                </tr>

                <tr>
                    <th style="width: 35%">Account No. <small>(linked with BP ID)</small></th>
                    <td class="vcenter text-center"></td>
                    @foreach ($accountDigits as $digit)
                        <td class="vcenter text-center">{{ $digit }}</td>
                    @endforeach


                </tr>


                <tr>
                    <th style="width: 35%">Branch Name</th>
                    <td class="vcenter" colspan="14"> {{ $raw->branch_name ?? '' }} </td>
                </tr>
                <tr>
                    <th style="width: 35%">Account Title</th>
                    <td class="vcenter" colspan="14"> {{ $raw->account_title ?? '' }}</td>
                </tr>
            </table>
        </div>

        <div class="item-section-wrap">
            <div class="item-title">A. BP Type:</div>
            <div class="item-bp-wrap">
                <div class="item-1st-wrap">
                    <p style="margin-bottom: 10px"><b>Applicant</b></p>
                    <p>1st Applicant:</p>
                    <p>2nd Applicant:</p>
                    <p>3rd Applicant:</p>
                    <p>4th Applicant:</p>
                </div>
                <div class="item-2st-wrap">
                    <p style="text-align: center;margin-bottom: 10px"><b>Contact No.</b></p>
                    <p> {{ $raw->first_app_mobile ?? '_________________________.' }}</p>
                    <p> {{ $raw->second_app_mobile ?? '_________________________.' }}</p>
                    <p> {{ $raw->third_app_mobile ?? '_________________________.' }}</p>
                    <p> {{ $raw->fourth_app_mobile ?? '_________________________.' }}</p>
                </div>
                <div class="item-3st-wrap">
                    <p style="text-align: center;margin-bottom: 10px"><b>Email</b></p>
                    <p> {{ $raw->first_app_email ?? '_________________________.' }}</p>
                    <p> {{ $raw->second_app_email ?? '_________________________.' }}</p>
                    <p> {{ $raw->third_app_email ?? '_________________________.' }}</p>
                    <p> {{ $raw->fourth_app_email ?? '_________________________.' }}</p>
                </div>
            </div>



        </div>

        <div class="item-section-wrap">
            <div class="item-title">B. Desired Security</div>
            <div class="item-des-wrap">
                <table class="table">
                    <tr>
                        <th style="width: 20%">Treasury Bill:</th>

                        @foreach ($treasury_bills as $item)
                            <td class="vcenter">
                                <label class="status-option">
                                    <input type="checkbox" name="treasury_bills[]" value="{{ $item['name'] }}"
                                        {{ $item['name'] == ($raw->treasury_bills ?? '') ? 'checked' : '' }} disabled>
                                    {{ $item['name'] }}
                                </label>
                            </td>
                        @endforeach
                    </tr>

                    <tr>
                        <th style="width: 20%">Treasury Bond:</th>

                        @foreach ($treasury_bounds as $item)
                            <td class="vcenter">
                                <label class="status-option">
                                    <input type="checkbox" name="treasury_bonds[]" value="{{ $item['name'] }}"
                                        {{ $item['name'] == ($raw->treasury_bonds ?? '') ? 'checked' : '' }} disabled>
                                    {{ $item['name'] }}
                                </label>
                            </td>

                            @if ($loop->iteration == 4 && !$loop->last)
                                </tr><tr>
                                <th></th>
                            @endif
                        @endforeach

                        <td class="vcenter" colspan="2">
                            <label class="status-option">
                                <input type="checkbox" disabled @if($raw->treasury_type == 'sukuk' || $raw->treasury_type == 'frtb') checked  @endif>
                                Others 

                                @if($raw->treasury_type == 'sukuk')
                                <span> {{ strtoupper($raw->treasury_type) }} </span>
                                <span> {{ $raw->sukuk }} </span>
                                @endif

                                @if($raw->treasury_type == 'frtb')
                                <span> {{ strtoupper($raw->treasury_type) }} </span>
                                <span> {{ $raw->frtb }} </span>
                                @endif
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="item-section-wrap act-par-wrap">
            <div class="item-title">C. Auction Particulars</div>
            <div class="item-des-wrap">
                <table class="table">
                    <tr>
                        <th style="width: 30%">Bidding Amount:In Figure</th>
                        <td class="vcenter" colspan="4">
                            <p class="underline-paragraph">{{ $raw->bidding_amount ?? '' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 30%">In Words</th>
                        <td class="vcenter" colspan="4"> <p class="underline-paragraph">{{ $raw->bidding_amount_words ?? '' }}</p>  </td>
                    </tr>
                    <tr>
                        <th style="width: 22%">Bidding Month:</th>
                        <td colspan="2"> <p class="underline-paragraph">{{ $raw->bidding_month ?? '' }}</p>  </td>
                        <th class="">Bidding Date:</th>
                        <td class="vcenter" colspan="2"> <p class="underline-paragraph">{{ $raw->bidding_date ?? '' }}</p> </td>
                    </tr>


                    <tr>
                        <th style="width: 22%">Bidding Type:</th>
                        <td colspan="2"><label class="status-option">
                            <input type="checkbox" name="residence" value="resident" disabled {{ $raw->bidding_type == 'Non-Competitive' ? 'checked' : '' }}>
                            Non-Competitive </label></td>
                        <td class="vcenter text-right" colspan="3">
                            <label class="status-option">
                                <input type="checkbox" name="residence" value="resident" disabled {{ $raw->bidding_type == 'Competitive' ? 'checked' : '' }}>
                                <div style="font-size: 13px">Competitive <small style="font-size: 8px">(please mention
                                        interest rate)</small>: {{ $raw->competitive_rate ?? '______' }} % p.a.</div>
                            </label>
                        </td>
                    </tr>

                </table>
            </div>
        </div>



        <div class="item-section-wrap">
            <div class="item-title">D. Declaration & Authorization</div>
            <div class="sub-text" style="margin-bottom: 5px;font-size: 12px"> a. I/We hereby confirm that I/We have
                carefully read, understood, and agreed to the
                terms and
                conditions governing the auction request process as presently applicable, and have completed this form
                to the best of my/our knowledge and understanding.</div>
            <div class="sub-text" style="margin-bottom: 5px;font-size: 12px"> b. I/We authorize the Bank to debit the
                settlement amount along with all applicable fees
                and
                charges from my/our BP Linked Account with Prime Bank PLC.</div>

            <div class="item-des-wrap item-decla-wrap">
                <table class="table" style="margin-bottom: 5px">
                    <tr class="sign-wrap">
                        <td>
                            <div class="sign-col-wrap">
                                @if(!empty($raw->applicant_1_signature))
                                    <img src="{{ asset('public/attachments/'.$raw->applicant_1_signature) }}" class="signature-image" style="width: 150px;height: 50px;object-fit: contain">
                                @endif
                                -------------------
                                <p><b>Signature</b></p>
                                <p>(1st Applicant)</p>
                            </div>
                        </td>
                        <td>
                            <div class="sign-col-wrap">
                                @if(!empty($raw->applicant_2_signature))
                                    <img src="{{ asset('public/attachments/'.$raw->applicant_2_signature) }}" class="signature-image" style="width: 150px;height: 50px;object-fit: contain">
                                @endif
                                -------------------
                                <p><b>Signature</b></p>
                                <p>(2nd Applicant)</p>
                            </div>
                        </td>
                        <td>
                            <div class="sign-col-wrap">
                                @if(!empty($raw->applicant_3_signature))
                                    <img src="{{ asset('public/attachments/'.$raw->applicant_3_signature) }}" class="signature-image" style="width: 150px;height: 50px;object-fit: contain">
                                @endif
                                -------------------
                                <p><b>Signature</b></p>
                                <p>(3rd Applicant)</p>
                            </div>
                        </td>
                        <td>
                            <div class="sign-col-wrap">
                                @if(!empty($raw->applicant_4_signature))
                                    <img src="{{ asset('public/attachments/'.$raw->applicant_4_signature) }}" class="signature-image" style="width: 150px;height: 50px;object-fit: contain">
                                @endif
                                -------------------
                                <p><b>Signature</b></p>
                                <p>(4th Applicant)</p>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="sign-footer">
                    <div class="sign-footer-left">
                        CONTACT DETAILS
                    </div>
                    <div class="sign-footer-right">
                        <p> 16218, 02223383837 <small>(Locally)</small> , +88 09610016218 <small>(From
                                overseas)</small></p>
                        <p>info@primebank.com.bd | www.primebank.com.bd</p>
                    </div>
                </div>
            </div>
        </div>

        <br><br>

        <div class="item-section-wrap item-terms-wrap">
            <div class="item-title">F. Terms & Conditions</div>
            <ol>
                <li>
                    Auction Request Form must be filled in and duly signed by all account holders of BP ID & BP ID linked
                    account with Prime Bank PLC.
                </li>
                <li>
                    Applicant name and contact details must be identical with BP ID & BP ID linked account with Prime
                    Bank PLC.
                </li>
                <li>
                    Auction Request Form must be submitted at desired Prime Bank PLC. branch at least 01 day prior to
                    bidding date.
                </li>
                <li>
                    Auction allotment process is governed by Bangladesh Bank (BB) as per prevailing policy of BB.
                </li>
                <li>
                    Face Value of bidding amount may be any amount multiple of BDT 1 Lac.
                </li>
                <li>
                    Bidding month and date must be declared clearly in the form. The Auctioned Calendar is available
                    under the webpage of Wealth Management of the official website of Prime Bank PLC. 
                    <a href="#">www.primebank.com.bd</a>
                </li>
                <li>
                    Prime Bank PLC will participate in the bidding (according to your instruction) subject to availability of
                    the settlement amount in the BP linked account with Prime Bank PLC. from the day before the
                    bidding date and the amount must remain in the account until realization. Settlement amount may
                    differ according to the allotment confirmation by BB. 
                </li>
                <li>
                    Bidding date may be rescheduled by Bangladesh Bank (BB). In such cases, your instruction through this
                    Auction Request Form will be executed by the Bank on the revised date, and no further intimation is
                    required from you.
                </li>
            </ol>
        </div>


        <div class="bank-use-wrap">
            <p>FOR BANK’S INTERNAL USE ONLY</p>

            <div class="item-des-wrap">
                <table class="table table-condensed table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Branch</th>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            <div class="use-body">
                                <label class="status-option"> <input type="checkbox" name="residence" value="resident" disabled>
                                    Applicant Name, Signature & Contact Details Verified With BP ID Linked Account
                                </label>
                            </div>
                            <div class="use-foot">
                                <p style="margin-bottom: 0;font-weight: 300">---------------------</p>
                                Initiated By
                            </div>
                        </td>
                        <td style="width: 50%">
                            <div class="use-body">
                                <label class="status-option"> <input type="checkbox" name="residence" value="resident" disabled>
                                    Captured Original Form in Register no. _________
                                </label>
                                <label class="status-option"> <input type="checkbox" name="residence" value="resident" disabled>
                                    Forwarded the Form to Wealth Management Division by Email
                                </label>
                            </div>
                            <div class="use-foot">
                                <p style="margin-bottom: 0;font-weight: 300">---------------------</p>
                                Authorized By
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="item-des-wrap">
                <table class="table table-condensed table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Wealth Management Division</th>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            <div class="use-body">
                                <label class="status-option"> <input type="checkbox" name="residence" value="resident" disabled>
                                    Applicant Static Information Verified
                                </label>
                                <label class="status-option"> <input type="checkbox" name="residence" value="resident" disabled>
                                    Settlement Amount Found in the BP Linked Account
                                </label>
                            </div>
                            <div class="use-foot">
                                <p style="margin-bottom: 0;font-weight: 300">---------------------</p>
                                Initiated By
                            </div>
                        </td>
                        <td style="width: 50%">
                            <div class="use-body">

                            </div>
                            <div class="use-foot">
                                <p style="margin-bottom: 0;font-weight: 300">---------------------</p>
                                Authorized By
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="item-des-wrap">
                <table class="table table-condensed table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Treasury Operation Division</th>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            <div class="use-body">
                                <label class="status-option"> <input type="checkbox" name="residence" value="resident" disabled>
                                    Settlement Amount Realized
                                </label>

                            </div>
                            <div class="use-foot">
                                <p style="margin-bottom: 0;font-weight: 300">---------------------</p>
                                Initiated By
                            </div>
                        </td>
                        <td style="width: 50%">
                            <div class="use-body">

                            </div>
                            <div class="use-foot">
                                <p style="margin-bottom: 0;font-weight: 300">---------------------</p>
                                Authorized By
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

        </div>

    </div>
@endsection
<script type="text/javascript">
    window.print();
</script>
