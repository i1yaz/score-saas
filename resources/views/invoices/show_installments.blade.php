<table class="table table-hover text-nowrap">
    <thead>
    <tr>
        <th>Sr#</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Pay</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->installments as $installment)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$installment->due_date}}</td>
            <td>{{$installment->amount}}</td>
            <td>{!!getSuccessErrorBadge($installment->is_paid,'Paid','Pending') !!}</td>
            <td>
                @if($installment->is_paid===false)
                    <a href="{{ route('invoices.pay-installments', [$installment->id]) }}" class='btn btn-default'>
                        <i class="fas fa-money-bill-wave" ></i>
                    </a>
                @endif
            </td>

        </tr>
    @endforeach
    </tbody>
</table>
