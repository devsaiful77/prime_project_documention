<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 5/30/2020.
 */
?>
<table class="table table-bordered">
    <thead>
    <tr style="background-color: darkcyan">
        <th style="background-color: darkslategray;color: white">#</th>
        <th style="color: white">Maker</th>
        <th style="color: white">Checker</th>
    </tr>
    </thead>
    <tbody>
    <input type="hidden" @if($row) @if($row['issue_workflow_id']) value="{{$row['issue_workflow_id']}}" @endif @else  @endif name="issue_workflow_id">
    <tr>
        <td id="colorOfTableHeadLightSeaGreenBackground">Touch</td>
        <td id="colorOfRightBorder"></td>
        <td>
            <div class="custom-control custom-radio custom-control-inline">
                <label class="form-check-label">
                    <input type="radio" class="custom-control-input green" name="touch_checker" value="1" @if($row) @if($row['touch_checker']==1)checked @endif @else  @endif>Yes
                </label>

                <label class="form-check-label">
                    <input type="radio" class="custom-control-input red" name="touch_checker" value="0" @if($row) @if($row['touch_checker']==0)checked @endif @else  @endif>No
                </label>
            </div>
        </td>
    </tr>
    <tr>
        <td id="colorOfTableHeadLightSeaGreenBackground">Hold</td>
        <td>
            <div class="custom-control custom-radio custom-control-inline">
                <label class="form-check-label">
                    <input type="radio" class="custom-control-input green" name="hold_maker" value="1" @if($row) @if($row['hold_maker']==1)checked @endif @else  @endif>Yes
                </label>
                <label class="form-check-label">
                    <input type="radio" class="custom-control-input red" name="hold_maker" value="0" @if($row) @if($row['hold_maker']==0)checked @endif @else  @endif>No
                </label>
            </div>
        </td>
        <td>
            <div class="custom-control custom-radio custom-control-inline">
                <label class="form-check-label">
                    <input type="radio" class="custom-control-input green" name="hold_checker" value="1" @if($row) @if($row['hold_checker']==1)checked @endif @else  @endif>Yes
                </label>
                <label class="form-check-label">
                    <input type="radio" class="custom-control-input red" name="hold_checker" value="0" @if($row) @if($row['hold_checker']==0)checked @endif @else  @endif>No
                </label>
            </div>
        </td>
    </tr>
    <tr>
        <td id="colorOfTableHeadLightSeaGreenBackground">SLA</td>
        <td><input type="number" placeholder="" class="workflow-input" name="sla_maker" @if($row) @if($row['sla_maker']) value="{{$row['sla_maker']}}" @endif @else  @endif></td>
        <td>
            <input type="number" placeholder="" class="workflow-input" name="sla_checker" @if($row) @if($row['sla_checker']) value="{{$row['sla_checker']}}" @endif @else  @endif>
        </td>
    </tr>
    <tr>
        <td id="colorOfTableHeadLightSeaGreenBackground">Attach</td>
        <td>
            <div class="custom-control custom-radio custom-control-inline">
                <label class="form-check-label">
                    <input type="radio" class="custom-control-input green" name="attach_maker" value="1" @if($row) @if($row['attach_maker']==1)checked @endif @else  @endif>Yes
                </label>
                <label class="form-check-label">
                    <input type="radio"  class="custom-control-input red" name="attach_maker" value="0" @if($row) @if($row['attach_maker']==0)checked @endif @else  @endif>No
                </label>
            </div>
            <input type="number" placeholder="" class="workflow-input" name="attach_maker_item" @if($row) @if($row['attach_maker_item']) value="{{$row['attach_maker_item']}}" @endif @else  @endif>
        </td>
        <td>
            <div class="custom-control custom-radio custom-control-inline">
                <label class="form-check-label">
                    <input type="radio" class="custom-control-input green" name="attach_checker" value="1" @if($row) @if($row['attach_checker']==1)checked @endif @else  @endif>Yes
                </label>
                <label class="form-check-label">
                    <input type="radio" class="custom-control-input red" name="attach_checker" value="0" @if($row) @if($row['attach_checker']==0)checked @endif @else  @endif>No
                </label>
            </div>
            <input type="number" placeholder="" class="workflow-input" name="attach_checker_item" @if($row) @if($row['attach_checker_item']) value="{{$row['attach_checker_item']}}" @endif @else  @endif>
        </td>
    </tr>
    </tbody>
</table>