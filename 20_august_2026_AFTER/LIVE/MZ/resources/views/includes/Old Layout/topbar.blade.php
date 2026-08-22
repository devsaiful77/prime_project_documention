<style type="text/css">
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    height: 300px;
    overflow-y: auto;
}
</style>
 <!-- prd(Auth::user()->branch_users->branches->name); -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li>  
                    <a href="{{ url('/logout') }}"onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> Logout <i class="glyphicon glyphicon-log-out"></i></a> <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;"> {{ csrf_field() }} </form> 
                </li>
                <li class="">
                    <a href="javascript:;" class="user-profile" style="pointer-events: none; ">
                        <span><b>Welcome,</b></span>
                        {{ Auth::user()->name }} 
                        @IF(!empty(Auth::user()->profile_picture))                           
                            <img src="{{ URL::asset('public/img/profile_picture/')}}/{{ Auth::user()->profile_picture }}" alt="{{ Auth::user()->name }}" class="img-circle"/> 
                        @ELSE
                            <img src="{{ URL::asset('public/img/profile_picture/default-user.png') }}" alt="{{ Auth::user()->name }}"/>
                        @ENDIF
                      
                    </a>
                </li>
                                
                <li role="presentation" class="dropdown">
                    
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="modal" aria-expanded="false" data-target="#viewNotifications">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge bg-green" id="noOfUNotifications"></span>
                    </a>
                    <div class="modal fade" id="viewNotifications" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Notifications</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <thead>
                                                
                                            </thead>
                                            <tbody id="notificationsData">                                        
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>    
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="pull-left"><a  style=" pointer-events: none; cursor: default;"><strong>Branch:</strong> {{$branchName}}</a></li>
                <li class="pull-left"><a  style=" pointer-events: none; cursor: default;"><strong>Role:</strong> {{$roleName}}</a></li>
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->

<script type="text/javascript">
/*$(document).ready(function(e){
    var url = "{{ url('/getNotifications') }}";
    var dataObj = {'_token':'<?php echo csrf_token() ?>'};
    $.ajax({
        type: "POST",
        url: url,
        data: dataObj,
        async:false,
        dataType : "json",
    }).done(function(data){  
        var noOfUnreadNotifications = 0;     
        var notificationRoleId = data.roleId;
        if (data.dataForView != undefined) {
            $('#notificationsData').append(
                "<tr>"
                    +"<td class='vcenter text-center'>Business Name</td>"
                    +"<td class='vcenter text-center'>Mobile No</td>"
                    +"<td class='vcenter text-center'>Submitted By</td>"
                    +"<td class='vcenter text-center'>Action</td>"
                +"</tr>"
            );
            if (notificationRoleId == 2) {
                var notificationReadUrlPHP = "{{ url('/EditCustomerInfos') }}"+"/";
            } else if (notificationRoleId == 3) {
                var notificationReadUrlPHP = "{{ url('/DetailCustomerInfos') }}"+"/";
            } else if (notificationRoleId == 4) {
                var notificationReadUrlPHP = "{{ url('/HOApproval') }}"+"/";
            };
            

            $.each(data.dataForView, function(index, value){
                var notificationReadUrl = notificationReadUrlPHP+value.CustomerCreationID;
                $('#notificationsData').append(
                    "<tr>"
                        +"<td class='vcenter text-center'>"+value.BusinessName+"</td>"
                        +"<td class='vcenter text-center'>"+value.CellPhone+"</td>"
                        +"<td class='vcenter text-center'>"+value.EmpName+"</td>"
                        +"<td class='vcenter text-center'><a href='"+notificationReadUrl+"'><i class='fa fa-eye'></i></a></td>"
                    +"</tr>"
                );
                ++noOfUnreadNotifications;
            });
        };
        if(noOfUnreadNotifications>0){
            $('#noOfUNotifications').text(noOfUnreadNotifications);
        }
        
    });
})*/
</script>