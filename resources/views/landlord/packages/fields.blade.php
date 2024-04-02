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
<!-- Price -->
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Price:',['class' => 'required']) !!}
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">{{config('saas.currency')}}</span>
        </div>
        <input type="text" name="price" class="form-control" placeholder="Currency" aria-label="Currency" aria-describedby="basic-addon1">
    </div>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('is_featured', 'Featured:',['class' => 'required']) !!}
    {!! Form::checkbox('is_featured', true) !!}

</div>
<div class="form-group col-sm-12">
    <h5 class="mb-4">Plan Limits</h5>
    <div class="row">
        <div class="form-group col-sm-6">
            {!! Form::label('max_students', 'Maximum Students:',['class' => 'required']) !!}
            {!! Form::text('max_students', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-6">
            {!! Form::label('max_teacher', 'Maximum Teachers:',['class' => 'required']) !!}
            {!! Form::text('max_teacher', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-6">
            {!! Form::label('max_student_packages', 'Maximum Student Packages:',['class' => 'required']) !!}
            {!! Form::text('max_student_packages', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-6">
            {!! Form::label('max_monthly_packages', 'Maximum Monthly Packages:',['class' => 'required']) !!}
            {!! Form::text('max_monthly_packages', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

