<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="invoice-package-types-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoicePackageTypes as $invoicePackageType)
                <tr>
                    <td>{{ $invoicePackageType->name }}</td>
                    <td>@include('partials.status_badge',['status' => $invoicePackageType->status,'text_success' => 'Active','text_danger' => 'Inactive'])</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['invoice-package-types.destroy', $invoicePackageType->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('invoice_package_type-show')
                            <a href="{{ route('invoice-package-types.show', [$invoicePackageType->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('invoice_package_type-edit')
                            <a href="{{ route('invoice-package-types.edit', [$invoicePackageType->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('invoice_package_type-destroy')
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            @endpermission
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $invoicePackageTypes])
        </div>
    </div>
</div>
