<h5 class="mb-4">Invoice Detail</h5>

<div class="row">
    <!-- Due Date Field -->
    <div class="form-group col-sm-6">
        @if(isset($isMonthly) && $isMonthly===true)
            {!! Form::label('due_date', 'Billing Date:') !!}
        @else
            {!! Form::label('due_date', 'Due Date:') !!}
        @endif

        {!! Form::text('due_date', isset($invoice)? $invoice->due_date?->format('m/d/Y'):null, ['class' => 'form-control date-input']) !!}
    </div>

    <!-- General Description Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('general_description', 'General Description:') !!}
        {!! Form::text('general_description', isset($invoice)?$invoice->general_description:null, ['class' => 'form-control']) !!}
    </div>

    <!-- General Description Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('email_to_parent', 'Should the parent receive the invoice via email:') !!}

        <div class="radio">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="email_to_parent" id="send-email-to-parent-yes" value="1" @if(!empty($invoice) && $invoice->email_to_parent===true) checked @endif >
                <label class="form-check-label" for="send-email-to-parent-yes"><strong>  YES</strong></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="email_to_parent" id="send-email-to-parent-no" value="0" @if(!empty($invoice) && $invoice->email_to_parent===true)  @else checked @endif>
                <label class="form-check-label" for="send-email-to-parent-no"><strong>  NO</strong></label>
            </div>

        </div>
    </div>
    <!-- Detailed Description Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('detailed_description', 'Detailed Description:') !!}
        {!! Form::textarea('detailed_description', isset($invoice)?$invoice->detailed_description:null, ['class' => 'form-control']) !!}
    </div>
</div>


