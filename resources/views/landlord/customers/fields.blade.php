<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:',['class' => 'required']) !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:',['class' => 'required']) !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>
<!-- Plan Field -->
<div class="form-group col-sm-6">
    {!! Form::label('package', 'Package:',['class' => 'required']) !!}
    {!! Form::select('package', $packages, null, ['placeholder' => 'Pick a package...','class' => 'form-control select2']) !!}
</div>

<div class="form-group col-sm-6">
    <label for="validationServerUsername">Account Name</label>
    <div class="input-group">
        <input type="text" class="form-control" id="account-name" placeholder="Account name" required>
        <div class="input-group-append">
            <span class="input-group-text">.{{config('saas.settings_base_domain')}}</span>
        </div>
    </div>
</div>

