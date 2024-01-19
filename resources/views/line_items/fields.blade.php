<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:' , ['class' => 'required']) !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Price:' , ['class' => 'required']) !!}
    {{ Form::number('price', null, ['class' => 'form-control','id'=>'price' , 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))", 'min' => '0', 'value' => '0', 'step' => '.01', 'pattern' => "^\d*(\.\d{0,2})?$", 'required', 'onKeyPress' => 'if(this.value.length==8) return false;']) }}
</div>
