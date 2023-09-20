<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="package-types-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Hours</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($packageTypes as $packageType)
                <tr>
                    <td>{{ $packageType->name }}</td>
                    <td>{{ $packageType->hours }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['package-types.destroy', $packageType->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('package_type-show')
                            <a href="{{ route('package-types.show', [$packageType->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('package_type-edit')
                            <a href="{{ route('package-types.edit', [$packageType->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('package_type-destroy')
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
            @include('adminlte-templates::common.paginate', ['records' => $packageTypes])
        </div>
    </div>
</div>
