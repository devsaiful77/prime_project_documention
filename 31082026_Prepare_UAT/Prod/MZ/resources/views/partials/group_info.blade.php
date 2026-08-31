<div class="row py-3" id="group">
    @foreach($rows as $key=>$row)
    <div class="col-md-3">
        <input type="hidden" value="{{ $row['id'] }}" name="groups[]">
        <fieldset>
            <legend>{{ $row['name'] }}:</legend>
            <a class="text-danger deleteGroup delete-group-btn text-danger" id="{{ $row['id'] }}"><i class="fa fa-times" aria-hidden="true"></i> </a>
            {{-- <button type="button" class="btn btn-primary text-danger pull-right text-danger swapping-right" nextgid="" prevgid="" currentgid="{{ $row['id'] }}"><i class="fa fa-chevron-right" aria-hidden="true"></i> </button>
            <button type="button" class="btn btn-primary text-danger pull-right text-danger swapping-left" nextgid="" prevgid="" currentgid="{{ $row['id'] }}"><i class="fa fa-chevron-left" aria-hidden="true"></i> </button> --}}

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
                            <input type="radio" id="nrd_1_{{ $key }}" name="touch_checker_{{$key}}" value="1" class="custom-control-input">
                            <label class="custom-control-label green" for="nrd_1_{{ $key }}">&nbsp;&nbsp;Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nrd_2_{{ $key }}" name="touch_checker_{{$key}}" value="2" class="custom-control-input">
                            <label class="custom-control-label red" for="nrd_2_{{ $key }}">&nbsp;&nbsp;No</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td id="colorOfTableHeadLightSeaGreenBackground">Hold</td>
                    <td>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nrd_3_{{ $key }}" name="hold_maker_{{$key}}"  value="1"
                                   class="custom-control-input">
                            <label class="custom-control-label green" for="nrd_3_{{ $key }}">&nbsp;&nbsp;Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nrd_4_{{ $key }}" name="hold_maker_{{$key}}"  value="2"
                                   class="custom-control-input">
                            <label class="custom-control-label red" for="nrd_4_{{ $key }}">&nbsp;&nbsp;No</label>
                        </div>
                    </td>
                    <td>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nrd_5_{{ $key }}" name="hold_checker_{{$key}}"  value="1"
                                   class="custom-control-input">
                            <label class="custom-control-label green" for="nrd_5_{{ $key }}">&nbsp;&nbsp;Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nrd_6_{{ $key }}" name="hold_checker_{{$key}}" value="2"
                                   class="custom-control-input">
                            <label class="custom-control-label red" for="nrd_6_{{ $key }}">&nbsp;&nbsp;No</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td id="colorOfTableHeadLightSeaGreenBackground">SLA</td>
                    <td><input type="number" placeholder="" min="0" class="workflow-input" name="sla_maker_{{$key}}" onkeypress="validate(event)"></td>
                    <td>
                        <input type="number" placeholder="" min="0" class="workflow-input" name="sla_checker_{{$key}}" onkeypress="validate(event)">
                    </td>
                </tr>
                <tr>
                    <td id="colorOfTableHeadLightSeaGreenBackground">Attach</td>
                    <td>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nrd_7_{{ $key }}" name="attach_maker_{{ $key }}" value="1"
                                   class="custom-control-input">
                            <label class="custom-control-label green" for="nrd_7_{{ $key }}">&nbsp;&nbsp;Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nrd_8_{{ $key }}" name="attach_maker_{{ $key }}" value="2"
                                   class="custom-control-input">
                            <label class="custom-control-label red" for="nrd_8_{{ $key }}">&nbsp;&nbsp;No</label>
                        </div>
                        <input type="number" placeholder="" class="workflow-input" min="0" name="attach_maker_item_{{ $key }}" onkeypress="validate(event)">
                    </td>
                    <td>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nrd_9_{{ $key }}" name="attach_checker_{{ $key }}" value="1"
                                   class="custom-control-input">
                            <label class="custom-control-label green" for="nrd_9_{{ $key }}">&nbsp;&nbsp;Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nrd_10_{{ $key }}" name="attach_checker_{{ $key }}" value="2"
                                   class="custom-control-input">
                            <label class="custom-control-label red" for="nrd_10_{{ $key }}">&nbsp;&nbsp;No</label>
                        </div>
                        <input type="number" placeholder="" class="workflow-input" min="0" name="attach_checker_item_{{ $key }}" onkeypress="validate(event)">
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>
    </div>
    @endforeach
</div>
