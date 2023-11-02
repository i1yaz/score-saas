<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="clients-table">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Address</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $client->first_name }}</td>
                    <td>{{ $client->last_name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->address }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['clients.destroy', $client->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('client-show')
                            <a href="{{ route('clients.show', [$client->id]) }}"
                               class='btn btn-default'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('client-edit')
                            <a href="{{ route('clients.edit', [$client->id]) }}"
                               class='btn btn-default'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('client-destroy')
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
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
            @include('adminlte-templates::common.paginate', ['records' => $clients])
        </div>
    </div>
</div>
