<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="line-items-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lineItems as $lineItem)
                <tr>
                    <td>{{ $lineItem->name }}</td>
                    <td>{{ $lineItem->price }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['line-items.destroy', $lineItem->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('line_item-show')
                            <a href="{{ route('line-items.show', [$lineItem->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('line_item-edit')
                            <a href="{{ route('line-items.edit', [$lineItem->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('line_item-destroy')
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
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
            @include('adminlte-templates::common.paginate', ['records' => $lineItems])
        </div>
    </div>
</div>
