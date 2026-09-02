<tr>
    <th style="vertical-align:top;">Correspondence </th>
    <td style="vertical-align:top;"> {{ (!empty($dataForView['w_form_type']['category'])) ? $dataForView['w_form_type']['category'] : "" }} </td>
    <th style="vertical-align:top;">Residence Address</th>
    <td style="vertical-align:top;"> {{ (!empty($dataForView['w_form_type']["data_to_be_insert"])) ? $dataForView['w_form_type']["data_to_be_insert"] : '' }} </td>
    <td style="vertical-align:top;" colspan="2">
    	@IF(!empty($dataForView['w_form_type']["data_tobe_insert2"]))
    	<?php
    		$officeAddrArr = explode("#@$", $dataForView['w_form_type']["data_tobe_insert2"]);
    		$officeAddr = (!empty($officeAddrArr[0])) ? $officeAddrArr[0] : '';
    		$officeDesig = (!empty($officeAddrArr[1])) ? $officeAddrArr[1] : '';
    		$officeDept = (!empty($officeAddrArr[2])) ? $officeAddrArr[2] : '';
    		$officeComp = (!empty($officeAddrArr[3])) ? $officeAddrArr[3] : '';
    	?>
	 	<ul style="list-style-type:none">
	    	<li><label>Office Address</label></li>
	    	<li><label>Designation</label>:{{$officeDesig}}</li>
	    	<li><label>Department</label>:{{$officeDept}}</li>
            <li><label>Company Name</label>:{{$officeComp}}</li>
	    	<li><label>Details Address</label>:{{$officeAddr}}</li>
	    </ul>
    	{{-- (!empty($dataForView['w_form_type']["data_tobe_insert2"])) ? $dataForView['w_form_type']["data_tobe_insert2"] : '' --}} 
    	@ENDIF
    </td>
</tr>

