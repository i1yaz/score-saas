<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:' , ['class' => 'required']) !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Test Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('test_type', 'Test Type:' , ['class' => 'required']) !!}
    {!! Form::select('test_type',$testTypes??[] ,null, ['class' => 'form-control','id'=>'test-type']) !!}
</div>
