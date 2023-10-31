@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>

                    </h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('invoices.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Invoice Pay
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Id Field -->
                    <div class="form-group col-sm-12 col-md-6">
                        {!! Form::label('final_amount', 'Payable Amount:') !!}
                        {!! Form::input('text','final_amount',$invoice->final_amount,['readonly'=>'readonly','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-12 col-md-6 payment-type">
                        {!! Form::label('payment_type', 'Payment Type:') !!}
                        {!! Form::select('payment_type',['full' => 'Full Payment','partial' => 'Partial'],null,['class' => 'form-control','id'=>'payment-type']) !!}
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        {!! Form::label('payment_mode', 'Payment Mode:') !!}
                        {!! Form::select('payment_mode',$paymentModes??[],null,['class' => 'form-control','id'=>'payment-mode']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('page_scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            @if(!empty($stripeKey))
                let stripe = Stripe('{{  $stripeKey ?? config('services.stripe.key') }}');
            @endif
            let invoiceStripePaymentUrl = '{{ route('client.stripe-payment') }}';

            $('#payment-type').select2({
                dropdownAutoWidth: true, width: 'auto',
                theme: 'bootstrap4',
                placeholder: "Please select payment type",
            });
            $('#payment-type').on('select2:select', function (e) {
                let data = e.params.data;
                console.log(data.id)
                if (data.id === 'partial'){
                    let html = `<div class='form-group col-sm-12 col-md-6 partial-amount'> {!! Form::label('amount', 'Amount:') !!} {!! Form::input('text','amount',0,['class' => 'form-control']) !!}</div>`;
                    $('.payment-type').after(html)
                }else if(data.id === 'full'){
                    $('.partial-amount').remove()
                }

            });
        </script>
    @endpush
@endsection

