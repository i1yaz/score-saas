{!! Form::open(['route' => ['invoices.destroy', $invoice->invoice_id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @permission('invoice-show')
    <a href="{{ route('invoices.show', [$invoice->invoice_id]) }}"
       class='btn btn-default btn-xs'>
        <i class="far fa-eye"></i>
    </a>
    @endpermission
    @permission('invoice-edit')
    <a href="{{ route('invoices.edit', [$invoice->invoice_id]) }}"
       class='btn btn-default btn-xs'>
        <i class="far fa-edit"></i>
    </a>
    @endpermission
    @permission('invoice-destroy')
    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
    @endpermission
</div>
{!! Form::close() !!}
