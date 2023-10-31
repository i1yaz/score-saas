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
            let paymentMode = $('#payment-mode').find(":selected").val();

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

            $(document).on("submit", "#paymentForm", function (e) {
                e.preventDefault();

                if ($("#partial-amount").val() == 0) {
                    displayErrorMessage("Partial should not be equal to zero");
                    return false;
                }

                if ($("#payment_note").val().trim().length == 0) {
                    displayErrorMessage("Note field is Required");
                    return false;
                }

                let btnSubmitEle = $(this).find("#btnPay");
                ToggleBtnLoader(btnSubmitEle);
                let payloadData = {
                    partialAmount: parseFloat($("#partial-amount").val()),
                };

                if (paymentMode == 1) {
                    $.ajax({
                        url: route("clients.payments.store"),
                        type: "POST",
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function (result) {
                            toastr.error(response.message);
                            if (result.success) {
                                window.location.href = result.data.redirectUrl;
                            }
                        },
                        error: function (result) {
                            toastr.error("something went wrong");
                        },
                        complete: function () {
                            ToggleBtnLoader(btnSubmitEle);
                        },
                    });
                } else if (paymentMode == 2) {
                    $.post(invoiceStripePaymentUrl, payloadData)
                        .done((result) => {
                            let sessionId = result.data.sessionId;
                            stripe
                                .redirectToCheckout({
                                    sessionId: sessionId,
                                })
                                .then(function (result) {
                                    $(this).html("Make Payment").removeClass("disabled");
                                    manageAjaxErrors(result);
                                });
                        })
                        .catch((error) => {
                            $(this).html("Make Payment").removeClass("disabled");
                            manageAjaxErrors(error);
                        });
                } else if (paymentMode == 3) {
                    $.ajax({
                        type: "GET",
                        url: route("paypal.init"),
                        data: {
                            amount: payloadData.amount,
                            invoiceId: payloadData.invoiceId,
                            transactionNotes: payloadData.transactionNotes,
                        },
                        success: function (result) {
                            if (result.status == "CREATED") {
                                let redirectTo = "";

                                $.each(result.links, function (key, val) {
                                    if (val.rel == "approve") {
                                        redirectTo = val.href;
                                    }
                                });
                                location.href = redirectTo;
                            } else {
                                location.href = result.url;
                            }
                        },
                        error: function (result) {
                            displayErrorMessage(result.responseJSON.message);
                        },
                        complete: function () {
                            ToggleBtnLoader(btnSubmitEle);
                        },
                    });
                } else if (paymentMode == 5) {
                    $.ajax({
                        type: "GET",
                        url: route("razorpay.init"),
                        data: $(this).serialize(),
                        success: function (result) {
                            if (result.success) {
                                $("#clientPaymentModal").modal("hide");
                                let {
                                    id,
                                    amount,
                                    name,
                                    email,
                                    invoiceId,
                                    invoice_id,
                                    description,
                                } = result.data;
                                options.description = description;
                                options.order_id = id;
                                options.amount = amount;
                                options.prefill.name = name;
                                options.prefill.email = email;
                                options.prefill.invoiceId = invoiceId;
                                let razorPay = new Razorpay(options);
                                razorPay.open();
                                razorPay.on("payment.failed");
                            }
                        },
                        error: function (result) {
                            displayErrorMessage(result.responseJSON.message);
                        },
                        complete: function () {
                            ToggleBtnLoader(btnSubmitEle);
                        },
                    });
                }
            });
        </script>
    @endpush
@endsection

