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
            {!! Form::open(['route' => 'invoices.store','id'=>'non-package-invoice-package-form','id' => 'payment-form']) !!}

            <div class="card-body">

                <div class="row">
                    <!-- Id Field -->
                    <div class="form-group col-sm-12 col-md-6">
                        {!! Form::label('final_amount', 'Payable Amount:') !!}
                        {!! Form::input('text','final_amount',($subscriptionAmount),['readonly'=>'readonly','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        {!! Form::label('payment_mode', 'Payment Mode:') !!}
                        {!! Form::select('payment_mode',$paymentModes??[],null,['class' => 'form-control','id'=>'payment-mode']) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Subscribe', ['class' => 'btn btn-primary','id'=>'btnPay']) !!}
                <a href="{{ route('invoices.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
    @push('page_scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            @if(!empty($stripeKey))
            let stripe = Stripe('{{  $stripeKey ?? config('services.stripe.key') }}');
            @endif
            let invoiceStripePaymentUrl = '{{ route('client.stripe-subscribe') }}';


            $(document).on("submit", "#payment-form", function (e) {
                e.preventDefault();
                let paymentMode = $('#payment-mode').find(":selected").val()

                let btnSubmitEle = $("#btnPay");
                ToggleBtnLoader(btnSubmitEle);
                let payloadData = {
                    _token: "{{ csrf_token() }}",
                    monthlyInvoicePackageId: "{{ $monthlyInvoicePackageId }}",
                };

                if (paymentMode === 'stripe') {
                    $.post(invoiceStripePaymentUrl, payloadData)
                        .done((result) => {
                            let sessionId = result.data.sessionId;
                            stripe
                                .redirectToCheckout({
                                    sessionId: sessionId,
                                })
                                .then(function (result) {
                                    ToggleBtnLoader(btnSubmitEle);
                                });
                        })
                        .catch((error) => {
                            toastr.error(error.message);
                            ToggleBtnLoader(btnSubmitEle);
                        });
                }
                ToggleBtnLoader(btnSubmitEle);
            });
        </script>
    @endpush
@endsection

