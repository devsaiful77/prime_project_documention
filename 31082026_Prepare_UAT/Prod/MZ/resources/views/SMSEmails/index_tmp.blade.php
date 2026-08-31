@extends('layouts.admin')
@section('content')
<h3>{{$title_for_layout}}</h3>

(<strong class="error">Make sure to add <big>{form_request}</big> for Request / Complaint and <big>{reference_no}</big> for Ticket Number and ((( {group_name} for Group name of Escalation EMAIL )))</strong> )
<div class="clearfix">&nbsp;</div>
<div class="d-flex">
   <div class="flex-fill text-center">
       <h5>Old Data</h5>
   </div>
   <div class="flex-fill text-center">
       <h5>New Data</h5>
   </div>
</div>
   <div class="d-flex justify-content-between">
      <div class="flex-fill">
         {{-- <h4 class="text-center">Old Data</h4> --}}
         <div class="table-responsive">
            <table class="table table-condensed">
               <tr style="border-top: 2px solid #a7a7a7;">
                  <th class="vcenter" rowspan="2" >Issue Opening SMS</th>
                  <th>Request</th>
                  <td>
                     {{
                        Form::textarea('issue_opening_sms_wform',(!empty($oldDataForView["issue_opening_sms_wform"])) ? $oldDataForView["issue_opening_sms_wform"] : ''  ,[
                           'rows'=>3,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Opening SMS (Request)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr>
                  <th>Complaint</th>
                  <td>
                     {{
                        Form::textarea('issue_opening_sms_complaint',(!empty($oldDataForView["issue_opening_sms_complaint"])) ? $oldDataForView["issue_opening_sms_complaint"] : ''  ,[
                           'rows'=>3,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Opening SMS (Complaint)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
                  <th class="vcenter" rowspan="2" >Issue Opening EMAIL</th>
                  <th>Request</th>
                  <td>
                     {{
                        Form::textarea('issue_opening_email_wform',(!empty($oldDataForView["issue_opening_email_wform"])) ? $oldDataForView["issue_opening_email_wform"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Opening EMAIL (Request)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr>
                  <th>Complaint</th>
                  <td>
                     {{
                        Form::textarea('issue_opening_email_complaint',(!empty($oldDataForView["issue_opening_email_complaint"])) ? $oldDataForView["issue_opening_email_complaint"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Opening EMAIL (Complaint)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
                  <th class="vcenter" rowspan="2">Issue Closing SMS</th>
                  <th>Request</th>
                  <td>
                     {{
                        Form::textarea('issue_closing_sms_wform',(!empty($oldDataForView["issue_closing_sms_wform"])) ? $oldDataForView["issue_closing_sms_wform"] : ''  ,[
                           'rows'=>3,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Closing SMS (Request)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr>
                  <th>Complaint</th>
                  <td>
                     {{
                        Form::textarea('issue_closing_sms_complaint',(!empty($oldDataForView["issue_closing_sms_complaint"])) ? $oldDataForView["issue_closing_sms_complaint"] : ''  ,[
                           'rows'=>3,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Closing SMS (Complaint)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               <th class="vcenter" rowspan="2">Issue Closing EMAIL</th>
               <th>Request</th>
               <td>
                     {{
                        Form::textarea('issue_closing_email_wform',(!empty($oldDataForView["issue_closing_email_wform"])) ? $oldDataForView["issue_closing_email_wform"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Closing EMAIL (Request)',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr>
               <th>Complaint</th>
               <td>
                     {{
                        Form::textarea('issue_closing_email_complaint',(!empty($oldDataForView["issue_closing_email_complaint"])) ? $oldDataForView["issue_closing_email_complaint"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Closing EMAIL (Complaint)',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
                  <th colspan="2" class="vcenter">Ticket Log OTP SMS</th>
                  <td>
                     {{
                        Form::textarea('otp_sms',(!empty($oldDataForView["otp_sms"])) ? $oldDataForView["otp_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Otp Formate SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
                  <th colspan="2" class="vcenter">Ticket Log OTP Email</th>
                  <td>
                     {{
                        Form::textarea('otp_email',(!empty($oldDataForView["otp_email"])) ? $oldDataForView["otp_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Otp Formate Mail',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>

               <tr style="border-top: 2px solid #a7a7a7;">
               <th colspan="2" class="vcenter">Send Back SMS (with reason)</th>
               <td>
                     {{
                        Form::textarea('send_back_sms',(!empty($oldDataForView["send_back_sms"])) ? $oldDataForView["send_back_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               <th colspan="2" class="vcenter">Send Back EMAIL (with reason)</th>
               <td>
                     {{
                        Form::textarea('send_back_email',(!empty($oldDataForView["send_back_email"])) ? $oldDataForView["send_back_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>

               <tr style="border-top: 2px solid #a7a7a7;">
               <th colspan="2" class="vcenter">Send Back SMS (second notifications)</th>
               <td>
                     {{
                        Form::textarea('send_back_auto_sms',(!empty($oldDataForView["send_back_auto_sms"])) ? $oldDataForView["send_back_auto_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               <th colspan="2" class="vcenter">Send Back EMAIL (second notifications)</th>
               <td>
                     {{
                        Form::textarea('send_back_auto_email',(!empty($oldDataForView["send_back_auto_email"])) ? $oldDataForView["send_back_auto_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>

               <tr style="border-top: 2px solid #a7a7a7;">
                  <th colspan="2" class="vcenter">Send Back SMS (closing notifications)</th>
                  <td>
                     {{
                        Form::textarea('send_back_closed_sms',(!empty($oldDataForView["send_back_closed_sms"])) ? $oldDataForView["send_back_closed_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
                  <th colspan="2" class="vcenter">Send Back EMAIL (closing notifications)</th>
                  <td>
                     {{
                        Form::textarea('send_back_closed_email',(!empty($oldDataForView["send_back_closed_email"])) ? $oldDataForView["send_back_closed_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>

               <tr style="border-top: 2px solid #a7a7a7;">
               <th colspan="2" class="vcenter">Unreachable Customer SMS</th>
               <td>
                     {{
                        Form::textarea('unreachable_cust_sms',(!empty($oldDataForView["unreachable_cust_sms"])) ? $oldDataForView["unreachable_cust_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Unreachable Customer SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               <th colspan="2" class="vcenter">Unreachable Customer EMAIL</th>
               <td>
                     {{
                        Form::textarea('unreachable_cust_email',(!empty($oldDataForView["unreachable_cust_email"])) ? $oldDataForView["unreachable_cust_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Unreachable Customer EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               <th colspan="2" class="vcenter">Non Customer SMS</th>
               <td>
                     {{
                        Form::textarea('non_cust_sms',(!empty($oldDataForView["non_cust_sms"])) ? $oldDataForView["non_cust_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Non Customer SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               <th colspan="2" class="vcenter">Non Customer EMAIL</th>
               <td>
                     {{
                        Form::textarea('non_cust_email',(!empty($oldDataForView["non_cust_email"])) ? $oldDataForView["non_cust_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Non Customer EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>

               <tr style="border-top: 2px solid #a7a7a7;">
                <th colspan="2" class="vcenter">User Notify SMS</th>
                <td>
                      {{
                         Form::textarea('user_notify_sms',(!empty($oldDataForView["user_notify_sms"])) ? $oldDataForView["user_notify_sms"] : ''  ,[
                            'rows'=>4,
                            'class' => 'form-control',
                            'placeholder'=>'User Notify SMS',
                            'readonly' => 'readonly'
                         ])
                      }}
                </td>
                </tr>
                <tr style="border-top: 2px solid #a7a7a7;">
                <th colspan="2" class="vcenter">User Notify EMAIL</th>
                <td>
                      {{
                         Form::textarea('user_notify_email',(!empty($oldDataForView["user_notify_email"])) ? $oldDataForView["user_notify_email"] : ''  ,[
                            'rows'=>4,
                            'class' => 'form-control',
                            'placeholder'=>'User Notify EMAIL',
                            'readonly' => 'readonly'
                         ])
                      }}
                </td>
                </tr>

               <tr style="border-top: 2px solid #a7a7a7;">
               <th colspan="2" class="vcenter">Escalation EMAIL</th>
               <td>
                     {{
                        Form::textarea('escalation_email',(!empty($oldDataForView["escalation_email"])) ? $oldDataForView["escalation_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Escalation EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               @if (!empty($dataForView))
                  <tr >
                     <th colspan="2" class=" mt-2vcenter">Button Action</th>
                  </tr>
                  <tr>
                     <th colspan="2" class="vcenter">Created By</th>
                  </tr>
                  <tr>
                     <th colspan="2" class="vcenter">Comment</th>
                  </tr>
               @endif
            </table>
         </div>
      </div>

      <div class="flex-fill">
         {{-- <h4 class="text-center mt-5">New Data</h4> --}}
         <div class="table-responsive">
            <table class="table table-condensed">
               <tr style="border-top: 2px solid #a7a7a7;">
                  {{-- <th class="vcenter" rowspan="2" >Issue Opening SMS</th>
                  <th>Request</th> --}}
                  <td>
                     {{
                        Form::textarea('issue_opening_sms_wform',(!empty($dataForView["issue_opening_sms_wform"])) ? $dataForView["issue_opening_sms_wform"] : ''  ,[
                           'rows'=>3,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Opening SMS (Request)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr>
                  {{-- <th>Complaint</th> --}}
                  <td>
                     {{
                        Form::textarea('issue_opening_sms_complaint',(!empty($dataForView["issue_opening_sms_complaint"])) ? $dataForView["issue_opening_sms_complaint"] : ''  ,[
                           'rows'=>3,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Opening SMS (Complaint)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
                  {{-- <th class="vcenter" rowspan="2" >Issue Opening EMAIL</th>
                  <th>Request</th> --}}
                  <td>
                     {{
                        Form::textarea('issue_opening_email_wform',(!empty($dataForView["issue_opening_email_wform"])) ? $dataForView["issue_opening_email_wform"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Opening EMAIL (Request)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr>
                  {{-- <th>Complaint</th> --}}
                  <td>
                     {{
                        Form::textarea('issue_opening_email_complaint',(!empty($dataForView["issue_opening_email_complaint"])) ? $dataForView["issue_opening_email_complaint"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Opening EMAIL (Complaint)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
                  {{-- <th class="vcenter" rowspan="2">Issue Closing SMS</th>
                  <th>Request</th> --}}
                  <td>
                     {{
                        Form::textarea('issue_closing_sms_wform',(!empty($dataForView["issue_closing_sms_wform"])) ? $dataForView["issue_closing_sms_wform"] : ''  ,[
                           'rows'=>3,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Closing SMS (Request)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr>
                  {{-- <th>Complaint</th> --}}
                  <td>
                     {{
                        Form::textarea('issue_closing_sms_complaint',(!empty($dataForView["issue_closing_sms_complaint"])) ? $dataForView["issue_closing_sms_complaint"] : ''  ,[
                           'rows'=>3,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Closing SMS (Complaint)',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               {{-- <th class="vcenter" rowspan="2">Issue Closing EMAIL</th>
               <th>Request</th> --}}
               <td>
                     {{
                        Form::textarea('issue_closing_email_wform',(!empty($dataForView["issue_closing_email_wform"])) ? $dataForView["issue_closing_email_wform"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Closing EMAIL (Request)',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr>
               {{-- <th>Complaint</th> --}}
               <td>
                     {{
                        Form::textarea('issue_closing_email_complaint',(!empty($dataForView["issue_closing_email_complaint"])) ? $dataForView["issue_closing_email_complaint"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Issue Closing EMAIL (Complaint)',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
                  {{-- <th colspan="2" class="vcenter">Ticket Log OTP SMS</th> --}}
                  <td>
                     {{
                        Form::textarea('otp_sms',(!empty($dataForView["otp_sms"])) ? $dataForView["otp_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Otp Formate SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
                  {{-- <th colspan="2" class="vcenter">Ticket Log OTP Email</th> --}}
                  <td>
                     {{
                        Form::textarea('otp_email',(!empty($dataForView["otp_email"])) ? $dataForView["otp_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Otp Formate Mail',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>

               <tr style="border-top: 2px solid #a7a7a7;">
               {{-- <th colspan="2" class="vcenter">Send Back SMS (with reason)</th> --}}
               <td>
                     {{
                        Form::textarea('send_back_sms',(!empty($dataForView["send_back_sms"])) ? $dataForView["send_back_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               {{-- <th colspan="2" class="vcenter">Send Back EMAIL (with reason)</th> --}}
               <td>
                     {{
                        Form::textarea('send_back_email',(!empty($dataForView["send_back_email"])) ? $dataForView["send_back_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>

               <tr style="border-top: 2px solid #a7a7a7;">
               {{-- <th colspan="2" class="vcenter">Send Back SMS (second notifications)</th> --}}
               <td>
                     {{
                        Form::textarea('send_back_auto_sms',(!empty($dataForView["send_back_auto_sms"])) ? $dataForView["send_back_auto_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               {{-- <th colspan="2" class="vcenter">Send Back EMAIL (second notifications)</th> --}}
               <td>
                     {{
                        Form::textarea('send_back_auto_email',(!empty($dataForView["send_back_auto_email"])) ? $dataForView["send_back_auto_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>

               <tr style="border-top: 2px solid #a7a7a7;">
                  {{-- <th colspan="2" class="vcenter">Send Back SMS (closing notifications)</th> --}}
                  <td>
                     {{
                        Form::textarea('send_back_closed_sms',(!empty($dataForView["send_back_closed_sms"])) ? $dataForView["send_back_closed_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
                  {{-- <th colspan="2" class="vcenter">Send Back EMAIL (closing notifications)</th> --}}
                  <td>
                     {{
                        Form::textarea('send_back_closed_email',(!empty($dataForView["send_back_closed_email"])) ? $dataForView["send_back_closed_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Send Back EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
                  </td>
               </tr>

               <tr style="border-top: 2px solid #a7a7a7;">
               {{-- <th colspan="2" class="vcenter">Unreachable Customer SMS</th> --}}
               <td>
                     {{
                        Form::textarea('unreachable_cust_sms',(!empty($dataForView["unreachable_cust_sms"])) ? $dataForView["unreachable_cust_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Unreachable Customer SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               {{-- <th colspan="2" class="vcenter">Unreachable Customer EMAIL</th> --}}
               <td>
                     {{
                        Form::textarea('unreachable_cust_email',(!empty($dataForView["unreachable_cust_email"])) ? $dataForView["unreachable_cust_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Unreachable Customer EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               {{-- <th colspan="2" class="vcenter">Non Customer SMS</th> --}}
               <td>
                     {{
                        Form::textarea('non_cust_sms',(!empty($dataForView["non_cust_sms"])) ? $dataForView["non_cust_sms"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Non Customer SMS',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               {{-- <th colspan="2" class="vcenter">Non Customer EMAIL</th> --}}
               <td>
                     {{
                        Form::textarea('non_cust_email',(!empty($dataForView["non_cust_email"])) ? $dataForView["non_cust_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Non Customer EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>

               <tr style="border-top: 2px solid #a7a7a7;">
                {{-- <th colspan="2" class="vcenter">Non Customer SMS</th> --}}
                <td>
                      {{
                         Form::textarea('user_notify_sms',(!empty($dataForView["user_notify_sms"])) ? $dataForView["user_notify_sms"] : ''  ,[
                            'rows'=>4,
                            'class' => 'form-control',
                            'placeholder'=>'Non Customer SMS',
                            'readonly' => 'readonly'
                         ])
                      }}
                </td>
                </tr>
                <tr style="border-top: 2px solid #a7a7a7;">
                {{-- <th colspan="2" class="vcenter">Non Customer EMAIL</th> --}}
                <td>
                      {{
                         Form::textarea('user_notify_email',(!empty($dataForView["user_notify_email"])) ? $dataForView["user_notify_email"] : ''  ,[
                            'rows'=>4,
                            'class' => 'form-control',
                            'placeholder'=>'Non Customer EMAIL',
                            'readonly' => 'readonly'
                         ])
                      }}
                </td>
                </tr>
               <tr style="border-top: 2px solid #a7a7a7;">
               {{-- <th colspan="2" class="vcenter">Escalation EMAIL</th> --}}
               <td>
                     {{
                        Form::textarea('escalation_email',(!empty($dataForView["escalation_email"])) ? $dataForView["escalation_email"] : ''  ,[
                           'rows'=>4,
                           'class' => 'form-control',
                           'placeholder'=>'Escalation EMAIL',
                           'readonly' => 'readonly'
                        ])
                     }}
               </td>
               </tr>
               @if (!empty($dataForView))
                  <tr style="border-top: 2px solid #a7a7a7;">
                     {{-- <th colspan="2" class="vcenter">Button Action</th> --}}
                     <td>
                        {{
                        Form::textarea('escalation_email',(!empty($dataForView["action"])) ? $dataForView["action"] : ''  ,[
                              'rows'=>1,
                              'class' => 'form-control',
                              'placeholder'=>'Button Action',
                              'readonly' => 'readonly'
                        ])
                        }}
                     </td>
                  </tr>
                  <tr style="border-top: 2px solid #a7a7a7;">
                     {{-- <th colspan="2" class="vcenter">Created By</th> --}}
                     @php
                        $created_by =  \App\User::where('id', $dataForView['created_by'] )->first();
                        $user = $created_by->user_id;
                     @endphp
                     <td>
                        {{
                        Form::textarea('escalation_email',(!empty($user)) ? $user : ''  ,[
                              'rows'=>1,
                              'class' => 'form-control',
                              'placeholder'=>'Button Action',
                              'readonly' => 'readonly'
                        ])
                        }}
                     </td>
                  </tr>
                  @if (!$isChecker)
                  <tr style="border-top: 2px solid #a7a7a7;">
                     {{-- <th colspan="2" class="vcenter">Comment</th> --}}
                     <td>
                        {{
                        Form::textarea('comments',(!empty($dataForView['comments'])) ? $dataForView['comments'] : ''  ,[
                           'rows'=>1,
                           'class' => 'form-control',
                           'placeholder'=>'Comments',
                           'readonly' => 'readonly'
                        ])
                        }}
                     </td>
                  </tr>
                  @endif
                  <tr style="border-top: 2px solid #a7a7a7;">
                     {{-- <th colspan="2" class="vcenter">Action</th> --}}
                     <td class="text-center">
                        @if($isChecker == false)
                            @if($dataForView['form_status'] == 7)
                                {{-- <a href="{{ url('SMS-Emails/tmp-edit', $dataForView['id']) }}" class="btn btn-success gradient ajax_page" title="Edit" escape="false"> <i class="fa fa-pencil"></i> Edit</a> --}}
                            @endif
                                <a href="{{ url('delete/tmp-data', ['id' => $dataForView['id'], 'table' => 'sms_email_template_tmps']) }}" class="btn btn-danger gradient ajax_page" title="Delete" escape="false">
                                    <i class="fa fa-trash"></i> Delete
                                </a><br>
                        @endif
                        @if($dataForView['form_status'] == 0)
                              <a href="{{ url('SMS-Emails/assign', $dataForView['id']) }}" class="btn btn-primary gradient ajax_page" title="Assign" escape="false"> Assign</a>
                        @else
                              @if($dataForView['modified_by'] == Auth::user()->user_id)
                                 <form id="comments-form" action="" method="post">
                                    @csrf
                                    <textarea name="comments" id="comments" class="form-control" cols="30" rows="5" placeholder="Enter Your comments..." required></textarea>
                                    <span class="text-danger" id="error-container"></span>
                                    @error('comments')
                                       <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                 </form>
                                 <div class="mt-2">
                                    <a id="approve-btn" href="{{ url('SMS-Emails/approve', $dataForView['id']) }}" class="btn btn-success gradient ajax_page" title="Approve" escape="false"> <i class="fa fa-plus"></i> Approve</a>
                                    {{-- <a id="sendback-btn" data-id="{{ $dataForView['id'] }}" class="btn btn-warning gradient ajax_page ml-1" title="Send Back" escape="false">SendBack To Maker</a> --}}
                                    <a id="reject-btn" data-id="{{ $dataForView['id'] }}" class="btn btn-danger gradient ajax_page ml-1" title="Send Back" escape="false">Reject</a>
                                 </div>
                              @else
                                 @if ($dataForView['modified_by'])
                                 <span class="text-danger">Assigned by {{ $dataForView['modified_by'] }} </span>
                                 @endif
                              @endif
                        @endif
                     </td>
                  </tr>
               @else
                  {{-- <tr style="border-top: 2px solid #a7a7a7;"> --}}
                     <td><h6>Currently no new requests are found everything is updated.</h6></td>
                  </tr>
               @endif
            </table>
         </div>
      </div>
   </div>
@endsection

@push('scripts')
   <script>
      $(document).ready(function(){

         $('#sendback-btn').click(function(e){
            e.preventDefault();
            let id = $(this).data('id');
            let url = "{{ url('SMS-Emails/send-back', '') }}";
            url += '/' + id;

            $('#comments-form').attr('action', url);
            $('#comments-form').submit();
         });

         $('#reject-btn').click(function(e){
            e.preventDefault();
            let id = $(this).data('id');
            let url = "{{ url('SMS-Emails/reject', '') }}";
            url += '/' + id;

            $('#comments-form').attr('action', url);
            $('#comments-form').submit();
         });
      })
   </script>
@endpush
