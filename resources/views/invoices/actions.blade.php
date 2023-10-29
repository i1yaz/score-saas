{!! Form::open(['route' => ['invoices.destroy', $invoice->invoice_id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @permission('invoice-pay')
    <a href="{{ route('invoices.pay', [$invoice->invoice_id,$type]) }}"
       class='btn btn-default'>
        <i class="fas fa-money-bill-wave" ></i>
    </a>
    @endpermission
    @permission('invoice-show')
    <a href="{{ route('invoices.show', [$invoice->invoice_id,$type]) }}"
       class='btn btn-default '>
        <i class="far fa-eye"></i>
    </a>
    @endpermission
    @permission('invoice-edit')
{{--    <a href="{{ route('invoices.edit', [$invoice->invoice_id]) }}"--}}
{{--       class='btn btn-default'>--}}
{{--        <i class="far fa-edit"></i>--}}
{{--    </a>--}}
    @endpermission
    @permission('invoice-destroy')
    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-', 'onclick' => "return confirm('Are you sure?')"]) !!}
    @endpermission
</div>
{!! Form::close() !!}
