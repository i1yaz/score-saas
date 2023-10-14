<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="monthly-invoice-packages-table">
            <thead>
            <tr>
                <th>Student Id</th>
                <th>Notes</th>
                <th>Internal Notes</th>
                <th>Start Date</th>
                <th>Hourly Rate</th>
                <th>Tutor Hourly Rate</th>
                <th>Tutoring Location Id</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($monthlyInvoicePackages as $monthlyInvoicePackage)
                <tr>
                    <td>{{ $monthlyInvoicePackage->student_id }}</td>
                    <td>{{ $monthlyInvoicePackage->notes }}</td>
                    <td>{{ $monthlyInvoicePackage->internal_notes }}</td>
                    <td>{{ $monthlyInvoicePackage->start_date }}</td>
                    <td>{{ $monthlyInvoicePackage->hourly_rate }}</td>
                    <td>{{ $monthlyInvoicePackage->tutor_hourly_rate }}</td>
                    <td>{{ $monthlyInvoicePackage->tutoring_location_id }}</td>
                    <td  style="width: 120px">
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
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $monthlyInvoicePackages])
        </div>
    </div>
</div>
