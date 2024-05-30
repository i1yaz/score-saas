<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table class="table no-border">
                    <tbody>
                    <tr>
                        <td>Plan</td>
                        <td>{{$package->name}}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>{{$subscription->status}}</td>
                    </tr>
                    <tr>
                        <td>Billing Cycle</td>
                        <td>{{$subscription->gateway_billing_cycle}}</td>
                    </tr>
                    <tr>
                        <td>Amount</td>
                        <td>{{formatAmountWithCurrency($subscription->amount)}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@if($subscription->payment_method == 'automatic')
    <div class="card-footer">
        <div class="div row justify-content-end">
            <div class="col-sm-12 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <select class="select2-basic form-control" id="payment_now_method_selector"
                                name="payment_now_method_selector">
                            <option>Select Payment Method</option>
                            <!--stripe-->
                            @if($landlord_settings->stripe_status == 'enabled')
                                <option value="stripe">
                                    {{ $landlord_settings->stripe_display_name }}
                                </option>
                            @endif
                            <!--stripe-->
                            @if($landlord_settings->paypal_status == 'enabled')
                                <option value="paypal">
                                    {{ $landlord_settings->paypal_display_name }}
                                </option>
                            @endif
                            <!--paystack-->
                            @if($landlord_settings->paystack_status == 'enabled')
                                <option value="paystack">
                                    {{ $landlord_settings->paystack_display_name }}
                                </option>
                            @endif
                            <!--razorpay-->
                            @if($landlord_settings->razorpay_status == 'enabled')
                                <option value="razorpay">
                                    {{ $landlord_settings->razorpay_display_name }}
                                </option>
                            @endif
                            <!--offline payments (also show it here)-->
                            @if($landlord_settings->offline_payments_status == 'enabled')
                                <option value="offline">
                                    {{ $landlord_settings->offline_payments_display_name }}
                                </option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="div row justify-content-end">
            <div class="col-sm-12 col-lg-4">
                <div class="div-checkout-buttons-container text-right"
                     id="payment_now_buttons_container">
                    <!--place holder button-->
                    <button type="button" class="btn waves-effect waves-light btn-block btn-default"
                            id="payment_now_placeholder_button" disabled>@lang('lang.pay_now')</button>
                </div>
            </div>
        </div>
    </div>
@endif
@push('after_third_party_scripts')
    <script>
        $(document).ready(function () {
            $('#payment_now_method_selector').on('change', function () {
                var paymentMethod = $(this).val();
                //send ajax request
                if(paymentMethod !='' || paymentMethod != "Select Payment Method"){
                    $.ajax({
                        url: '{{route('settings-billing.pay',$subscription->unique_id)}}',
                        type: 'POST',
                        data: {
                            payment_method: paymentMethod,
                            subscription_id: '{{$subscription->id}}',
                            _token: '{{csrf_token()}}'
                        },
                        success: function (response) {
                            $('#payment_now_buttons_container').html(response);
                        }
                    });
                }
            });
        });

    </script>
@endpush
