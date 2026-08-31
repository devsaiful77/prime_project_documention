@extends('layouts.admin')

@section('content')

<form class="form-horizontal form-label-left"  method="POST" action="{{ url('/Holidays/edit/'.$dataForView['id']) }}" enctype="multipart/form-data">
  {!! Form::token(); !!}
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="dates">Date <span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <input type="text" name="dates" class="form-control srvDatePicker" placeholder="Date" value='{{$dataForView["dates"]}}' autocomplete="off">
    </div>
    @IF($errors->has('dates')) <div class="error-message">{{ $errors->first('dates') }}</div> @ENDIF
  </div><!-- Date -->
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type">Holiday Type
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {{ Form::select('type', [''=>'--Holiday Type--','public'=>'Public','optional'=>'Optional'],(!empty($dataForView["type"])) ? $dataForView["type"] : "" , ['class'=>'form-control']) }}
    </div>
    @IF($errors->has('type')) <div class="error-message">{{ $errors->first('type') }}</div> @ENDIF
  </div><!-- Type -->
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {!!
        Form::textarea('remarks',(!empty($dataForView["remarks"])) ? $dataForView["remarks"] : "",[
          'rows'=>2,
          'class' => 'form-control',
          'autocomplete'=>'off',
          'placeholder'=>'Remarks'
        ]);
      !!}
    </div>
    @IF($errors->has('remarks')) <div class="error-message">{{ $errors->first('remarks') }}</div> @ENDIF
  </div><!-- Type -->

  <div class="ln_solid">&nbsp;</div>
  <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <?php $additionalParams = (!empty($searchDataForView)) ? '?'.http_build_query($searchDataForView) : ""; ?>
        {{ Form::hidden('additionalParams',$additionalParams) }}

        {!!
          Form::submit((!empty($id)) ? 'Update':'Submit',array(
            'class'=>'btn btn-primary gradient',
            'title'=>'Add',
            'escape'=>false
          ));
        !!}
        <button type="button" class="btn btn-info gradient" onclick="cancel('/Holidays{{ $additionalParams}}')">Back</button>
      </div>
  </div>
  {{ Form::hidden('tmpId',(!empty($tmpId)) ? $tmpId : '' ) }}
</form>
@endsection
