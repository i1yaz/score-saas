<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="parents-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name/Code</th>
                <th>Display Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td>
                        {{$permission->getKey()}}
                    </td>
                    <td >
                        {{$permission->name}}
                    </td>
                    <td>
                        {{$permission->display_name}}
                    </td>
                    <td>
                        {{$permission->description}}
                    </td>
                    <td  style="width: 120px">
                        <a href="{{route('acl.permissions.edit', $permission->getKey())}}"  class='btn btn-default btn-sm'><i class="far fa-edit"></i></a>
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
