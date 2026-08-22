<?php
/**
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com>.
 * User: Tanay
 * Date: 6/15/2020
 * Time: 3:06 AM
 */
?>
<!---->
<?php

$sqlFormStatus = \Illuminate\Support\Facades\DB::table('form_status')
    ->join('users','form_status.user_id','=','users.id')
    ->leftjoin('comments','form_status.reference_number','comments.reference_number')
    ->where('form_status.reference_number',$dataForView['reference_number'])
    ->get();

if (count($sqlFormStatus)> 0) {
?>
<br/>
<table style="width: 100%; margin: 0 auto; border-spacing: 4px; border-collapse: separate;" border="0" >
    <tr>

        <td class="topandbottom" style="padding-top:5px;padding-bottom:5px; border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Person</td>
        <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Start / In Time</td>
        <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Working Time</td>
        <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold; font-family: serif;padding-left: 5px;">Status</td>
        <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold;font-family: serif;padding-left: 5px;">Out Time</td>
        <td class="topandbottom" style="border:1px solid #9acd32; font-weight: bold;font-family: serif;padding-left: 5px;">Remarks</td>

    </tr>
    <?php
    $i = 0;
    $j = 0;
    $models = array();

    while ($row = mysql_fetch_array($result)) {

        $groupID = $row['group_id'];
        $userID = $row['user_id'];
        $groupName = $row['user_name'];
        $form_status = $row['form_status'];
        $comments = $row['comments'];
        /*
        if($groupID == $prevgID){

            //$i--;
            $models[$i]['group_id'] = $groupID;
            if($j >= 0){
                //echo 'prev gid'.$groupID;
                //$models[$i]['in_time'] = $models[$i-1]['out_time'];
                $models[$i]['in_time'] = $models[$i-1]['in_time'];
            }
            $models[$i]['form_status'] = $form_status;
            $models[$i]['out_time'] = $row['out_time'];
            $models[$i]['comments'] = $comments;
            $j++;
        }else{
            */
        $models[$i]['group_id'] = $groupID;

        if($prevgID == $groupID){
            if($prevName != $userID)
                $models[$i]['user_name'] = $groupName;
        }else{
            $models[$i]['user_name'] = $groupName;
        }

        if($i == 0){
            $models[$i]['in_time'] = $sqlResultDateValueFF; //$row['in_time']; //$models[$i-1]['in_time']; //$prevTime;
            $models[$i]['work_time'] = $row['in_time'];
            $models[$i]['out_time'] = $row['in_time'];
        }elseif($prevgID != $groupID){
            $models[$i]['in_time'] = $models[$i-1]['out_time'];
            $models[$i]['work_time'] = $row['in_time'];
            $models[$i]['out_time'] = 0;
        }elseif($prevgID == $groupID && $i > 0){
            $models[$i]['in_time'] = 0;
            $models[$i]['work_time'] = $row['in_time'];
            $models[$i]['out_time'] = $row['out_time'];
        }

        $models[$i]['form_status'] = $form_status;

        $models[$i]['comments'] = $comments;

        //}

        $prevgID = $groupID;
        $prevName = $userID;
        $i++;
    }

    //echo '<pre>';
    //print_r($models);

    foreach($models as $key=>$rowFormVal)
    {

    $resultComm = mysql_query("select name from sub_group_info where id= $rowFormVal[group_id]");
    $resultVComm = mysql_fetch_assoc($resultComm);
    $resultGName = $resultVComm['name'];

    ?>

    <tr>
        <?php if($rowFormVal['user_name'] != "") {?>
        <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php echo $rowFormVal['user_name']." ( ".$resultGName." ) "; ?></td>
        <?php }else{?>
        <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;">&nbsp;</td>
        <?php }?>
        <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if($rowFormVal['in_time'] > 0) echo date("d.m.Y ## h:i a",$rowFormVal['in_time']); ?></td>
        <td class="topandbottom" style="padding-top:5px;padding-bottom:5px; border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if($rowFormVal['work_time'] > 0) echo date("d.m.Y ## h:i a",$rowFormVal['work_time']); ?></td>
        <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php echo $rowFormVal['form_status']; ?></td>
        <td class="topandbottom" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php if($rowFormVal['out_time'] > 0) echo date("d.m.Y ## h:i a",$rowFormVal['out_time']); ?></td>
        <td class="topandbottom" colspan="2" style="border:1px solid #9acd32; font-family: serif; color: #0072BB;padding-left: 5px;"><?php echo $rowFormVal['comments'];  ?>&nbsp;</td>
    </tr>


    <?php } ?>
</table>
<?php } ?>

<!-- -->
