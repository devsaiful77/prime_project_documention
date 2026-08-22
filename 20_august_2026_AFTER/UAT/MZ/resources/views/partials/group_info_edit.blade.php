@foreach($rows as $key=>$row)
    @php ++$counter @endphp
<div class="col-md-3 exists-group-{{ $row['id'] }}">
    <input type="hidden" value="{{ $row['id'] }}" name="groups[]">
    <fieldset>
        <legend>{{ $row['name'] }}:</legend>
        <a class="text-danger deleteGroup delete-group-btn text-danger" id="{{ $row['id'] }}"><i class="fa fa-times" aria-hidden="true"></i> </a>
        <button type="button" class="btn btn-primary text-danger pull-right text-danger swapping-right" nextgid="" prevgid="" currentgid="{{ $row['id'] }}"><i class="fa fa-chevron-right" aria-hidden="true"></i> </button>
        <button type="button" class="btn btn-primary text-danger pull-right text-danger swapping-left" nextgid="" prevgid="" currentgid="{{ $row['id'] }}"><i class="fa fa-chevron-left" aria-hidden="true"></i> </button>

        <table class="table table-bordered">
            <thead>
            <tr style="background-color: darkcyan">
                <th style="background-color: darkslategray;color: white">#</th>
                <th style="color: white">Maker</th>
                <th style="color: white">Checker</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td id="colorOfTableHeadLightSeaGreenBackground">Touch</td>
                <td></td>
                <td>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="mrd_1_{{ $row['id'] }}" name="touch_checker_{{$row['id']}}" value="1"
                        class="custom-control-input">
                        <label class="custom-control-label green" for="mrd_1_{{ $row['id'] }}">&nbsp;&nbsp;Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="mrd_2_{{ $row['id'] }}" name="touch_checker_{{$row['id']}}" value="2"
                        class="custom-control-input red">
                        <label class="custom-control-label red" for="mrd_2_{{ $row['id'] }}">&nbsp;&nbsp;No</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td id="colorOfTableHeadLightSeaGreenBackground">Hold</td>
                <td>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="mrd_3_{{ $row['id'] }}" name="hold_maker_{{$row['id']}}" value="1"
                        class="custom-control-input">
                        <label class="custom-control-label green" for="mrd_3_{{ $row['id'] }}">&nbsp;&nbsp;Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="mrd_4_{{ $row['id'] }}" name="hold_maker_{{$row['id']}}" value="2"
                        class="custom-control-input">
                        <label class="custom-control-label red" for="mrd_4_{{ $row['id'] }}">&nbsp;&nbsp;No</label>
                    </div>
                </td>
                <td>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="mrd_5_{{ $row['id'] }}" name="hold_checker_{{$row['id']}}" value="1"
                               class="custom-control-input">
                        <label class="custom-control-label green" for="mrd_5_{{ $row['id'] }}">&nbsp;&nbsp;Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="mrd_6_{{ $row['id'] }}" name="hold_checker_{{$row['id']}}" value="2"
                        class="custom-control-input">
                        <label class="custom-control-label red" for="mrd_6_{{ $row['id'] }}">&nbsp;&nbsp;No</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td id="colorOfTableHeadLightSeaGreenBackground">SLA</td>
                <td><input type="number" placeholder="" min="0" class="workflow-input" name="sla_maker_{{$row['id']}}" onkeypress="validate(event)"></td>
                <td>
                    <input type="number" placeholder="" min="0" class="workflow-input" name="sla_checker_{{$row['id']}}" onkeypress="validate(event)">
                </td>
            </tr>
            <tr>
                <td id="colorOfTableHeadLightSeaGreenBackground">Attach</td>
                <td>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="mrd_7_{{ $row['id'] }}" name="attach_maker_{{ $row['id'] }}" value="1"
                               class="custom-control-input">
                        <label class="custom-control-label green" for="mrd_7_{{ $row['id'] }}">&nbsp;&nbsp;Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="mrd_8_{{ $row['id'] }}" name="attach_maker_{{ $row['id'] }}" value="2"
                               class="custom-control-input">
                        <label class="custom-control-label red" for="mrd_8_{{ $row['id'] }}">&nbsp;&nbsp;No</label>
                    </div>
                    <input type="number" placeholder="" class="workflow-input" min="0" name="attach_maker_item_{{ $row['id'] }}" onkeypress="validate(event)">
                </td>
                <td>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="mrd_9_{{ $row['id'] }}" name="attach_checker_{{ $row['id'] }}"
                               value="1" class="custom-control-input">
                        <label class="custom-control-label green" for="mrd_9_{{ $row['id'] }}">&nbsp;&nbsp;Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="mrd_10_{{ $row['id'] }}" name="attach_checker_{{ $row['id'] }}"
                               value="2" class="custom-control-input">
                        <label class="custom-control-label red" for="mrd_10_{{ $row['id'] }}">&nbsp;&nbsp;No</label>
                    </div>
                    <input type="number" placeholder="" class="workflow-input" min="0" name="attach_checker_item_{{ $row['id'] }}" onkeypress="validate(event)">
                </td>
            </tr>
            </tbody>
        </table>
    </fieldset>
</div>
@endforeach
