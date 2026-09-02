@extends('layouts.admin')
@section('content')
<h3>{{$title_for_layout}}</h3>
(<strong class="error">Make sure to add <big>{form_request}</big> for Request / Complaint and <big>{reference_no}</big> for Ticket Number and ((( {group_name} for Group name of Escalation EMAIL )))</strong> )
<div class="clearfix">&nbsp;</div>

   {!!
      Form::open([
        'method'=>'post',
        'action' => ['SMSEmailsController@store',encrypt($id)] ,
        'enctype' => 'multipart/form-data'
      ]);
   !!}
   {!! Form::token(); !!}

   <div class="table-responsive">
      <table class="table table-condensed">
         <tr style="border-top: 2px solid #a7a7a7;">
            <th class="vcenter" rowspan="2" >Issue Opening SMS</th>
            <th>Request</th>
            <td>
               {{
                   Form::textarea('issue_opening_sms_wform',(!empty($dataForView["issue_opening_sms_wform"])) ? $dataForView["issue_opening_sms_wform"] : ''  ,[
                     'rows'=>3,
                     'class' => 'form-control',
                     'placeholder'=>'Issue Opening SMS (Request)'
                   ])
               }}
            </td>
         </tr>
         <tr>
            <th>Complaint</th>
            <td>
               {{
                   Form::textarea('issue_opening_sms_complaint',(!empty($dataForView["issue_opening_sms_complaint"])) ? $dataForView["issue_opening_sms_complaint"] : ''  ,[
                     'rows'=>3,
                     'class' => 'form-control',
                     'placeholder'=>'Issue Opening SMS (Complaint)'
                   ])
               }}
            </td>
         </tr>
         <tr style="border-top: 2px solid #a7a7a7;">
            <th class="vcenter" rowspan="2" >Issue Opening EMAIL</th>
            <th>Request</th>
            <td>
               {{
                   Form::textarea('issue_opening_email_wform',(!empty($dataForView["issue_opening_email_wform"])) ? $dataForView["issue_opening_email_wform"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Issue Opening EMAIL (Request)'
                   ])
               }}
            </td>
         </tr>
         <tr>
            <th>Complaint</th>
            <td>
               {{
                   Form::textarea('issue_opening_email_complaint',(!empty($dataForView["issue_opening_email_complaint"])) ? $dataForView["issue_opening_email_complaint"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Issue Opening EMAIL (Complaint)'
                   ])
               }}
            </td>
         </tr>
         <tr style="border-top: 2px solid #a7a7a7;">
            <th class="vcenter" rowspan="2">Issue Closing SMS</th>
            <th>Request</th>
            <td>
               {{
                   Form::textarea('issue_closing_sms_wform',(!empty($dataForView["issue_closing_sms_wform"])) ? $dataForView["issue_closing_sms_wform"] : ''  ,[
                     'rows'=>3,
                     'class' => 'form-control',
                     'placeholder'=>'Issue Closing SMS (Request)'
                   ])
               }}
            </td>
         </tr>
         <tr>
            <th>Complaint</th>
            <td>
               {{
                  Form::textarea('issue_closing_sms_complaint',(!empty($dataForView["issue_closing_sms_complaint"])) ? $dataForView["issue_closing_sms_complaint"] : ''  ,[
                     'rows'=>3,
                     'class' => 'form-control',
                     'placeholder'=>'Issue Closing SMS (Complaint)'
                  ])
               }}
            </td>
         </tr>
         <tr style="border-top: 2px solid #a7a7a7;">
           <th class="vcenter" rowspan="2">Issue Closing EMAIL</th>
           <th>Request</th>
           <td>
               {{
                  Form::textarea('issue_closing_email_wform',(!empty($dataForView["issue_closing_email_wform"])) ? $dataForView["issue_closing_email_wform"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Issue Closing EMAIL (Request)'
                  ])
               }}
           </td>
         </tr>
         <tr>
           <th>Complaint</th>
           <td>
               {{
                  Form::textarea('issue_closing_email_complaint',(!empty($dataForView["issue_closing_email_complaint"])) ? $dataForView["issue_closing_email_complaint"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Issue Closing EMAIL (Complaint)'
                  ])
               }}
           </td>
         </tr>
         <tr style="border-top: 2px solid #a7a7a7;">
            <th colspan="2" class="vcenter">Ticket Log OTP SMS</th>
            <td>
                {{
                   Form::textarea('otp_sms',(!empty($dataForView["otp_sms"])) ? $dataForView["otp_sms"] : ''  ,[
                      'rows'=>4,
                      'class' => 'form-control',
                      'placeholder'=>'Otp Formate SMS'
                   ])
                }}
            </td>
          </tr>
          <tr style="border-top: 2px solid #a7a7a7;">
            <th colspan="2" class="vcenter">Ticket Log OTP Email</th>
            <td>
                {{
                   Form::textarea('otp_email',(!empty($dataForView["otp_email"])) ? $dataForView["otp_email"] : ''  ,[
                      'rows'=>4,
                      'class' => 'form-control',
                      'placeholder'=>'Otp Formate Mail'
                   ])
                }}
            </td>
          </tr>

         <tr style="border-top: 2px solid #a7a7a7;">
           <th colspan="2" class="vcenter">Send Back SMS (with reason)</th>
           <td>
               {{
                  Form::textarea('send_back_sms',(!empty($dataForView["send_back_sms"])) ? $dataForView["send_back_sms"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Send Back SMS'
                  ])
               }}
           </td>
         </tr>
         <tr style="border-top: 2px solid #a7a7a7;">
           <th colspan="2" class="vcenter">Send Back EMAIL (with reason)</th>
           <td>
               {{
                  Form::textarea('send_back_email',(!empty($dataForView["send_back_email"])) ? $dataForView["send_back_email"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Send Back EMAIL'
                  ])
               }}
           </td>
         </tr>

         <tr style="border-top: 2px solid #a7a7a7;">
           <th colspan="2" class="vcenter">Send Back SMS (second notifications)</th>
           <td>
               {{
                  Form::textarea('send_back_auto_sms',(!empty($dataForView["send_back_auto_sms"])) ? $dataForView["send_back_auto_sms"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Send Back SMS'
                  ])
               }}
           </td>
         </tr>
         <tr style="border-top: 2px solid #a7a7a7;">
           <th colspan="2" class="vcenter">Send Back EMAIL (second notifications)</th>
           <td>
               {{
                  Form::textarea('send_back_auto_email',(!empty($dataForView["send_back_auto_email"])) ? $dataForView["send_back_auto_email"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Send Back EMAIL'
                  ])
               }}
           </td>
         </tr>

         <tr style="border-top: 2px solid #a7a7a7;">
            <th colspan="2" class="vcenter">Send Back SMS (closing notifications)</th>
            <td>
                {{
                   Form::textarea('send_back_closed_sms',(!empty($dataForView["send_back_closed_sms"])) ? $dataForView["send_back_closed_sms"] : ''  ,[
                      'rows'=>4,
                      'class' => 'form-control',
                      'placeholder'=>'Send Back SMS'
                   ])
                }}
            </td>
          </tr>
          <tr style="border-top: 2px solid #a7a7a7;">
            <th colspan="2" class="vcenter">Send Back EMAIL (closing notifications)</th>
            <td>
                {{
                   Form::textarea('send_back_closed_email',(!empty($dataForView["send_back_closed_email"])) ? $dataForView["send_back_closed_email"] : ''  ,[
                      'rows'=>4,
                      'class' => 'form-control',
                      'placeholder'=>'Send Back EMAIL'
                   ])
                }}
            </td>
          </tr>

         <tr style="border-top: 2px solid #a7a7a7;">
           <th colspan="2" class="vcenter">Unreachable Customer SMS</th>
           <td>
               {{
                  Form::textarea('unreachable_cust_sms',(!empty($dataForView["unreachable_cust_sms"])) ? $dataForView["unreachable_cust_sms"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Unreachable Customer SMS'
                  ])
               }}
           </td>
         </tr>
         <tr style="border-top: 2px solid #a7a7a7;">
           <th colspan="2" class="vcenter">Unreachable Customer EMAIL</th>
           <td>
               {{
                  Form::textarea('unreachable_cust_email',(!empty($dataForView["unreachable_cust_email"])) ? $dataForView["unreachable_cust_email"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Unreachable Customer EMAIL'
                  ])
               }}
           </td>
         </tr>
         <tr style="border-top: 2px solid #a7a7a7;">
           <th colspan="2" class="vcenter">Non Customer SMS</th>
           <td>
               {{
                  Form::textarea('non_cust_sms',(!empty($dataForView["non_cust_sms"])) ? $dataForView["non_cust_sms"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Non Customer SMS'
                  ])
               }}
           </td>
         </tr>
         <tr style="border-top: 2px solid #a7a7a7;">
           <th colspan="2" class="vcenter">Non Customer EMAIL</th>
           <td>
               {{
                  Form::textarea('non_cust_email',(!empty($dataForView["non_cust_email"])) ? $dataForView["non_cust_email"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Non Customer EMAIL'
                  ])
               }}
           </td>
         </tr>

         <tr style="border-top: 2px solid #a7a7a7;">
            <th colspan="2" class="vcenter">User Notify SMS</th>
            <td>
                {{
                   Form::textarea('user_notify_sms',(!empty($dataForView["user_notify_sms"])) ? $dataForView["user_notify_sms"] : ''  ,[
                      'rows'=>4,
                      'class' => 'form-control',
                      'placeholder'=>'User Notify SMS'
                   ])
                }}
            </td>
          </tr>
          <tr style="border-top: 2px solid #a7a7a7;">
            <th colspan="2" class="vcenter">User Notify EMAIL</th>
            <td>
                {{
                   Form::textarea('user_notify_email',(!empty($dataForView["user_notify_email"])) ? $dataForView["user_notify_email"] : ''  ,[
                      'rows'=>4,
                      'class' => 'form-control',
                      'placeholder'=>'User Notify EMAIL'
                   ])
                }}
            </td>
          </tr>


         <tr style="border-top: 2px solid #a7a7a7;">
           <th colspan="2" class="vcenter">Escalation EMAIL</th>
           <td>
               {{
                  Form::textarea('escalation_email',(!empty($dataForView["escalation_email"])) ? $dataForView["escalation_email"] : ''  ,[
                     'rows'=>4,
                     'class' => 'form-control',
                     'placeholder'=>'Escalation EMAIL'
                  ])
               }}
           </td>
         </tr>
         @if ($checker == false)
         <tr>
            <th ><button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Save</button></th>
            <th colspan="2"></th>
         </tr>
         @endif
      </table>
   </div>


{!! Form::close(); !!}
@endsection
