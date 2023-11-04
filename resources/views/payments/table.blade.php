<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="payments-table">
            <thead>
            <tr>
                <th>Invoice Id</th>
                <th>Invoice Type</th>
                <th>Package Id</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Payment Gateway</th>
{{--                <th colspan="3">Action</th>--}}
            </tr>
            </thead>
            <tbody>
            @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->invoice_id }}</td>
                    <td>{{ getInvoiceTypeFromClass($payment->invoiceable_type) }}</td>
                    <td>{{ $payment->invoiceable_id }}</td>
                    <td>{{ formatAmountWithCurrency($payment->amount) }}</td>
                    <td>{{ $payment->created_at }}</td>
                    <td>{{ getPaymentGatewayNameFromId($payment->payment_gateway_id) }}</td>
{{--                    <td  style="width: 120px">--}}
{{--                        @include('payments.actions')--}}
{{--                    </td>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $payments])
        </div>
    </div>
</div>
