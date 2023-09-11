<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="parents-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Display Name</th>
                <th>Name/Code</th>
                <th># Permissions</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>
                        {{$role->getKey()}}
                    </td>
                    <td>
                        {{$role->display_name}}
                    </td>
                    <td >
                        {{$role->name}}
                    </td>
                    <td>
                        {{$role->permissions_count}}
                    </td>
                    <td  style="width: 120px">
                        <div class="btn-group">
                            @if (\Laratrust\Helper::roleIsEditable($role))
                                <a href="{{route('laratrust.roles.edit', $role->getKey())}}" class="btn btn-default btn-sm"><i class="far fa-edit"></i></a>
                            @else
                                <a href="{{route('laratrust.roles.show', $role->getKey())}}" class="btn btn-default btn-sm">Details</a>
                            @endif
                            <form
                                action="{{route('laratrust.roles.destroy', $role->getKey())}}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete the record?');"
                            >
                                @method('DELETE')
                                @csrf
                                <button
                                    type="submit"
                                    class="{{\Laratrust\Helper::roleIsDeletable($role) ? 'btn btn-danger btn-sm' : 'btn btn-default btn-sm'}}"
                                    @if(!\Laratrust\Helper::roleIsDeletable($role)) disabled @endif
                                ><i class="far fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $roles])
        </div>
    </div>
</div>
