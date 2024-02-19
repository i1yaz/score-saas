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
                        {!! Form::input('text','final_amount',($subscriptionAmount),['readonly'=>'readonly','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        {!! Form::label('payment_mode', 'Payment Mode:') !!}
                        {!! Form::select('payment_mode',$paymentModes??[],null,['class' => 'form-control','id'=>'payment-mode']) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @if(empty($subscriptionId))
                    {!! Form::button('Subscribe', ['class' => 'btn btn-primary','id'=>'btnPay']) !!}
                @elseif($isActive === true)
                    {!! Form::button('Cancel Subscription', ['class' => 'btn btn-danger','id'=>'cancel-subscription']) !!}
                @else
                    {!! Form::button('Completed', ['class' => 'btn btn-warning','id'=>'btnCompleted']) !!}
                @endif

                <a href="{{ route('invoices.index') }}" class="btn btn-default"> Cancel </a>
            </div>

        </div>
    </div>
    @push('page_scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            @if(!empty($stripeKey))
            let stripe = Stripe('{{  $stripeKey ?? config('services.stripe.key') }}');
            @endif

            @if(empty($subscriptionId))
            let subscribeMonthlyPackage = '{{ route('client.stripe-monthly-subscription') }}';
            $(document).on("click", "#btnPay", function (e) {
                e.preventDefault();
                let paymentMode = $('#payment-mode').find(":selected").val()

                let btnSubmitEle = $("#btnPay");
                ToggleBtnLoader(btnSubmitEle);
                let payloadData = {
                    _token: "{{ csrf_token() }}",
                    monthlyInvoicePackageId: "{{ $monthlyInvoicePackageId }}",
                };

                if (paymentMode === 'stripe') {
                    $.post(subscribeMonthlyPackage, payloadData)
                        .done((result) => {
                            toastr.success(result.message);
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
                            let response = error.responseJSON
                            toastr.error(response.message);
                            ToggleBtnLoader(btnSubmitEle);
                        });
                }
            });
            @else
            let cancelMonthlySubscription = '{{ route('client.stripe-cancel-monthly-subscription') }}';
            $(document).on("click", "#cancel-subscription", function (e) {
                e.preventDefault();
                Swal.fire({
                    title: "Are you sure you want to cancel subscription?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, cancel subscription!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let paymentMode = $('#payment-mode').find(":selected").val()

                        let btnSubmitEle = $("#cancel-subscription");
                        ToggleBtnLoader(btnSubmitEle);
                        let payloadData = {
                            _token: "{{ csrf_token() }}",
                            monthlyInvoicePackageId: "{{ $monthlyInvoicePackageId }}",
                        };

                        if (paymentMode === 'stripe') {
                            $.post(cancelMonthlySubscription, payloadData)
                                .done((result) => {
                                    toastr.success(result.message);
                                    location.reload();
                                    btnSubmitEle = $("#cancel-subscription");
                                })
                                .catch((error) => {
                                    btnSubmitEle = $("#cancel-subscription");
                                    let response = error.responseJSON
                                    toastr.error(response.message);
                                    ToggleBtnLoader(btnSubmitEle);
                                });
                        }
                    }
                });

            });
            @endif
            $('#btnCompleted').on('click',function (e){
                e.preventDefault();
                Swal.fire({
                    title: "Package Completed",
                    text: "This package is completed. Please contact to admin!",
                    icon: "warning",
                });
            })
        </script>
    @endpush
@endsection

