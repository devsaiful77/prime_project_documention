<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 6/6/2020.
 */
?>
@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">

            <h3 class="text-center">Issue Configuration</h3>
        </div>
        <h4>Issue:{{ $row->name }}</h4>

    </div>
    <div class="row">
        <form action="{{ url('issues-config/store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (Session::has('success'))
                <div class="alert alert-success text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            @if(count($rows)!=0)
                <table class="table" id="dynamicTable">
                    <tr>
                        <th>Label Name</th>
                        <th>Field Type</th>
                        <th>Field Name</th>
                        <th>API Key:Field Name</th>
                        <th>Option</th>
                        <th>Placeholder</th>
                        <th>Maximum Length</th>
                        <th>Required</th>
                        <th>Action</th>
                    </tr>
                    <input type="hidden" value="{{ $row->id }}" name="issue_id">
                    @foreach($rows as $r)


                        <tr>

                            <td><input type="text" name="addmore[0][label_name]" placeholder="Enter Label name" class="form-control" value="{{ $r->label_name }}" /></td>
                            <td> <select name="addmore[0][field_type]" class="question-type form-control">
                                    <option value="text" @if($r->field_type=='text') selected @endif>Text</option>
                                    <option value="address" @if($r->field_type=='address') selected @endif>Address</option>
                                    <option value="checkbox" @if($r->field_type=='checkbox') selected @endif>Checkbox</option>
                                    <option value="date" @if($r->field_type=='date') selected @endif>Date</option>
                                    <option value="dropdown" @if($r->field_type=='dropdown') selected @endif>Dropdown</option>
                                    <option value="radio" @if($r->field_type=='radio') selected @endif>Radio Button</option>
                                    <option value="textarea" @if($r->field_type=='textarea') selected @endif>Text Area</option>
                                    <option value="number" @if($r->field_type=='number') selected @endif>Number</option>
                                    <option value="decimal" @if($r->field_type=='decimal') selected @endif>Decimal</option>
                                </select></td>
                            <td><input type="text" name="addmore[0][field_name]" placeholder="Enter field name" class="form-control" value="{{ $r->field_name }}" /></td>
                            <td><input type="text" name="addmore[0][api_key]" placeholder="API key:field name" class="form-control" value="{{ $r->api_key }}" /></td>
                            <td class="options">
                                <textarea  name="addmore[0][options]" placeholder="Enter option" class="form-control">{{ $r->options }}</textarea>

                            </td>
                            <td><input type="text" name="addmore[0][placeholder]" class="form-control" placeholder="Enter Placeholder" value="{{ $r->placeholder }}"></td>
                            <td><input type="text" name="addmore[0][maximumlength]" class="form-control" placeholder="Enter Maximum Length" value="{{ $r->maximumlength }}"></td>
                            <input type="hidden" name="addmore[0][is_required]" value="0" >
                            <td><label><input type="checkbox" name="addmore[0][is_required]" value="1" @if($r->is_required==1) checked  @endif>Required</label></td>
                            <td><button type="button" class="btn btn-danger remove-tr">Remove</button></td>
                        </tr>


                    @endforeach

                </table><button type="button" name="add" id="add" class="btn btn-success"><i class="fa fa-plus"></i> Add More</button>
                <button type="submit" class="btn btn-info">Update</button>
            @else
                <table class="table" id="dynamicTable">
                    <tr>
                        <th>Label Name</th>
                        <th>Field Type</th>
                        <th>Field Name</th>
                        <th>API Key:Field Name</th>
                        <th>Option</th>
                        <th>Placeholder</th>
                        <th>Maximum Length</th>
                        <th>Required</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <input type="hidden" value="{{ $row->id }}" name="issue_id">
                        <td><input type="text" name="addmore[0][label_name]" placeholder="Enter Label name" class="form-control" /></td>
                        <td> <select name="addmore[0][field_type]" class="question-type form-control">
                                <option value="text">Text</option>
                                <option value="address">Address</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="date">Date</option>
                                <option value="dropdown">Dropdown</option>
                                <option value="radio">Radio Button</option>
                                <option value="textarea">Text Area</option>
                                <option value="number">Number</option>
                                <option value="decimal">Decimal</option>
                            </select></td>
                        <td><input type="text" name="addmore[0][field_name]" placeholder="Enter field name" class="form-control" /></td>
                        <td><input type="text" name="addmore[0][api_key]" placeholder="API key:field name" class="form-control" /></td>
                        <td class="options">
                            <textarea  name="addmore[0][options]" placeholder="Enter option" class="form-control"></textarea>

                        </td>
                        <td><input type="text" name="addmore[0][placeholder]" class="form-control" placeholder="Enter Placeholder"></td>
                        <td><input type="text" name="addmore[0][maximumlength]" class="form-control" placeholder="Enter Maximum Length"></td>
                        <input type="hidden" name="addmore[0][is_required]" value="0">
                        <td><label><input type="checkbox" name="addmore[0][is_required]" value="1">Required</label></td>
                        <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
                    </tr>
                </table>

                <button type="submit" class="btn btn-success">Save</button>
            @endif
        </form>
        {{--<fieldset class="part">

            <div class="form-group ">
                <div class="cols-sm-10">
                        <div class="input-group">

                            <select name="" class="question-type form-control">
                                <option value="text">Text</option>
                                <option value="address">Address</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="date">Date</option>
                                <option value="dropdown">Dropdown</option>
                                <option value="radio">Radio Button</option>
                                <option value="textarea">Text Area</option>
                            </select>
                        </div>

                        <div class="input-group options">
                            {!! Form::label('title', 'Options(Separated by comma)' ) !!}
                            <textarea name=""></textarea>
                        </div>
                        <div class="input-group address_field_wrapper">
                            {!! Form::label('title', 'Fields to show as address' ) !!}
                            <div class="row"> <input type="checkbox" checked name="" value="name" id="address_name"> <label for="address_name">Name</label></div>
                            <div class="row"> <input type="checkbox" checked name="" value="address"  id="address_address"> <label for="address_address">Address Line as Text</label></div>
                            <div class="row"> <input type="checkbox" checked name="" value="post_office" id="address_post_office"> <label for="address_post_office">Post Office</label></div>
                            <div class="row"> <input type="checkbox" checked name="" value="post_code" id="address_post_code"> <label for="address_post_code">Post Code </label></div>
                            <div class="row"> <input type="checkbox" checked name="" value="city" id="address_city"> <label for="address_city">City</label></div>
                            <div class="row"> <input type="checkbox" checked name="" value="district" id="address_district"> <label for="address_district">District</label></div>
                        </div>

                        <div class="input-group">

                        </div>
                </div>
            </div>


        </fieldset>--}}

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $(document).on('change', '.question-type', function() {
                console.log('dd');
                var field_set_class = ($(this).parents("fieldset").attr("class"));
                var selected_value = $(this).val();
                console.log(field_set_class);
                if( selected_value == 'checkbox' || selected_value == 'radio' || selected_value == 'dropdown' ) {
                    /* $('.options').css('display','block')
                     $('.address_field_wrapper').css('display','none')*/
                }
                else if(selected_value == 'address') {
                    //$('.address_field_wrapper').css('display','block')
                    //$('.options').css('display','none')
                }
                else {
                    // $('.options').css('display','none')
                    // $('.address_field_wrapper').css('display','none')
                }
            });

            var current = 1,current_step,next_step,steps;
            steps = $("fieldset").length;
            $(".next").click(function(){
                current_step = $(this).parent();
                next_step = $(this).parent().next();

                var parent_class = $(this).parent().attr('class')
                var field_value = $('.' + parent_class + ' input').val();
                if(field_value) {
                    $('.' + parent_class + ' input').css('border-color','#ddd')
                    next_step.show();
                    current_step.hide();
                    setProgressBar(++current);
                }
                else {
                    $('.' + parent_class + ' input').css('border-color','#f00')
                    return false
                }
            });
            $(".previous").click(function(){
                current_step = $(this).parent();
                next_step = $(this).parent().prev();
                next_step.show();
                current_step.hide();
                setProgressBar(--current);
            });
            setProgressBar(current);
            // Change progress bar action
            function setProgressBar(curStep){
                var percent = parseFloat(100 / steps) * curStep;
                percent = percent.toFixed();
                $(".progress-bar")
                    .css("width",percent+"%")
                    .html(percent+"%");
            }

        });
    </script>
    <script type="text/javascript">

        var i = 0;

        $("#add").click(function(){

            ++i;

            $("#dynamicTable").append(
                '<tr><td><input type="text" name="addmore['+i+'][label_name]" placeholder="Enter Label name" class="form-control" /></td>' +
                '<td><select name="addmore['+i+'][field_type]"  class="form-control"><option value="text">Text</option>\n' +
                '                            <option value="address">Address</option>\n' +
                '                            <option value="checkbox">Checkbox</option>\n' +
                '                            <option value="date">Date</option>\n' +
                '                            <option value="dropdown">Dropdown</option>\n' +
                '                            <option value="radio">Radio Button</option>\n' +
                '                            <option value="textarea">Text Area</option>\n' +
                '                            <option value="number">Number</option></select></td>' +
                '                            <option value="decimal">Decimal</option></select></td>' +
                '<td><input type="text" name="addmore['+i+'][field_name]" placeholder="Enter field name" class="form-control" /></td>' +
                '<td><input type="text" name="addmore['+i+'][api_key]" placeholder="API key:field name" class="form-control" /></td>' +
                '<td><textarea name="addmore['+i+'][options]" placeholder="Enter option" class="form-control" ></textarea></td>' +
                '<td><input type="text" name="addmore['+i+'][placeholder]" placeholder="Enter Placeholder" class="form-control" /></td>' +
                '<td><input type="text" name="addmore['+i+'][maximumlength]" placeholder="Enter Maximum Length" class="form-control" /></td>' +
                '<input type="hidden" name="addmore['+i+'][is_required]" value="0">' +
                '<td><label><input type="checkbox" name="addmore['+i+'][is_required]" value="1">Required</label></td>' +
                '<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
        });

        $(document).on('click', '.remove-tr', function(){
            $(this).parents('tr').remove();
        });

    </script>
@endsection
