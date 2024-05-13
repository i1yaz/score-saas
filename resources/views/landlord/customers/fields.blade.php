<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('full_name', 'Name:',['class' => 'required']) !!}
    {!! Form::text('full_name', null, ['class' => 'form-control']) !!}
</div>
<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_address', 'Email:',['class' => 'required']) !!}
    {!! Form::text('email_address', null, ['class' => 'form-control']) !!}
</div>
<!-- Plan Field -->
<div class="form-group col-sm-6">
    {!! Form::label('plan', 'Plan:',['class' => 'required']) !!}
    {!! Form::select('plan', $packages, null, ['placeholder' => 'Pick a package...','class' => 'form-control select2']) !!}
</div>

<div class="form-group col-sm-6">
    <label for="validationServerUsername" class="required">Account URL</label>
    <div class="input-group">
        <input type="text" class="form-control" id="account-name" name="account_name" placeholder="Account Url" required>
        <div class="input-group-append">
            <span class="input-group-text">.{{config('saas.settings_base_domain')}}</span>
        </div>
    </div>
</div>
@push('after_third_party_scripts')
    <script>
        ajaxSubmit = false;
    </script>
@endpush
