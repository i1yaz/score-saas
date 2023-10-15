{!! Form::open(['route' => ['monthly-invoice-packages.destroy', $monthlyInvoicePackage->id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @permission('monthly_invoice_package-show')
    <a href="{{ route('monthly-invoice-packages.show', [$monthlyInvoicePackage->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-eye"></i>
    </a>
    @endpermission
    @permission('monthly_invoice_package-edit')
    <a href="{{ route('monthly-invoice-packages.edit', [$monthlyInvoicePackage->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-edit"></i>
    </a>
    @endpermission
    @permission('monthly_invoice_package-destroy')
    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
    @endpermission
</div>
{!! Form::close() !!}
