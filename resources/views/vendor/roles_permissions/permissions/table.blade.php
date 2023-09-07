<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="parents-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name/Code</th>
                <th>Display Name</th>
                <th>Description</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td class="td text-sm leading-5 text-gray-900">
                        {{$permission->getKey()}}
                    </td>
                    <td class="td text-sm leading-5 text-gray-900">
                        {{$permission->name}}
                    </td>
                    <td class="td text-sm leading-5 text-gray-900">
                        {{$permission->display_name}}
                    </td>
                    <td class="td text-sm leading-5 text-gray-900">
                        {{$permission->description}}
                    </td>
                    <td  style="width: 120px">
                        <a href="{{route('laratrust.permissions.edit', $permission->getKey())}}"  class='btn btn-default btn-xs'><i class="far fa-edit"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $permissions])
        </div>
    </div>
</div>
