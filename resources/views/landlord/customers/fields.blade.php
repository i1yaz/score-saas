<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('full_name', 'Name:',['class' => 'required']) !!}
    {!! Form::text('full_name', $customer->name??null, ['class' => 'form-control']) !!}
</div>
<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_address', 'Email:',['class' => 'required']) !!}
    {!! Form::text('email_address', $customer->email??null, ['class' => 'form-control']) !!}
</div>
@if(\Illuminate\Support\Facades\Request::is('app-admin/customers/create'))
    <!-- Plan Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('plan', 'Plan:',['class' => 'required']) !!}
        {!! Form::select('plan', $packages, null, ['placeholder' => 'Pick a package...','class' => 'form-control select2']) !!}
    </div>
@endif
<div class="form-group col-sm-6">
    <label for="validationServerUsername" class="required">Account URL</label>
    <div class="input-group">
        <input type="text" class="form-control" id="account-name" name="account_name" placeholder="Account Url" value="{{$customer->subdomain??null}}" required>
        <div class="input-group-append">
            <span class="input-group-text">.{{config('saas.base_domain')}}</span>
        </div>
    </div>
</div>
@if(\Illuminate\Support\Facades\Request::is('app-admin/customers/create'))
    <div class="form-group col-sm-6">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="send-welcome-email" name="send_welcome_email" value="yes" checked>
            <label class="custom-control-label" for="send-welcome-email">Send Welcome Email</label>
        </div>
    </div>
    @endif

    </div>
    @push('after_third_party_scripts')
        <script>
            ajaxSubmit = false;
        </script>
    @endpush
