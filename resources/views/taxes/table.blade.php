<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="taxes-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Percentage</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($taxes as $tax)
                <tr>
                    <td>{{ $tax->name }}</td>
                    <td>{{ $tax->value }}%</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['taxes.destroy', $tax->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('tax-show')
                            <a href="{{ route('taxes.show', [$tax->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('tax-edit')
                            <a href="{{ route('taxes.edit', [$tax->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('tax-destroy')
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
            @include('adminlte-templates::common.paginate', ['records' => $taxes])
        </div>
    </div>
</div>
