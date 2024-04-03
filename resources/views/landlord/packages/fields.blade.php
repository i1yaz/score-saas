<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:',['class' => 'required']) !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<!-- Options -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_option', 'Payment Option:',['class' => 'required']) !!}
    {!! Form::select('payment_option', ['paid' => 'Paid','free'=>'Free'], null, ['class' => 'form-control select2']) !!}
</div>
<!-- Price Monthly -->
<div class="form-group col-sm-6">
    {!! Form::label('price_monthly', 'Price Monthly:',['class' => 'required']) !!}
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{config('saas.currency')}}</span>
        </div>
{{--        {{ Form::number('price_monthly', null, ['class' => 'form-control price-input price ','id'=>'price_monthly' , 'oninput' => "validity.valid||(value=value.replace(/[e\+]/gi,''))", 'min' => '-1', 'value' => '0', 'step' => '.01', 'pattern' => "^-?\d*(\.\d{0,2})?$", 'required', 'onKeyPress' => 'if(this.value.length==8) return false;'])}}--}}

                <input type="number" name="price_monthly" class="form-control" placeholder="Currency" aria-label="Currency" aria-describedby="basic-addon1">
    </div>
</div>
<!-- Price Yearly-->
<div class="form-group col-sm-6">
    {!! Form::label('price_yearly', 'Price Yearly:',['class' => 'required']) !!}
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" >{{config('saas.currency')}}</span>
        </div>
{{--        {{ Form::text('price_yearly', null, ['class' => 'form-control price-input price','id'=>'' , 'oninput' => "this.value = formatPrice(this.value);", 'required', 'maxlength' => '10'])}}--}}
        <input type="number" name="price_yearly" class="form-control" placeholder="Currency" aria-label="Currency" aria-describedby="basic-addon1">

    </div>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('is_featured', 'Featured:',['class' => 'required']) !!}
    {!! Form::checkbox('is_featured','yes') !!}

</div>
<div class="form-group col-sm-12">
    <h5 class="mb-4">Plan Limits</h5>
    <div class="row">
        <div class="form-group col-sm-6">
            {!! Form::label('max_students', 'Maximum Students:',['class' => '']) !!}
            {!! Form::number('max_students', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-6">
            {!! Form::label('max_teacher', 'Maximum Teachers:',['class' => '']) !!}
            {!! Form::number('max_teacher', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-6">
            {!! Form::label('max_student_packages', 'Maximum Student Packages:',['class' => '']) !!}
            {!! Form::number('max_student_packages', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-6">
            {!! Form::label('max_monthly_packages', 'Maximum Monthly Packages:',['class' => '']) !!}
            {!! Form::number('max_monthly_packages', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="callout callout-info" role="alert">
        For unlimited, use the value -1 or leave it empty
    </div>
</div>



