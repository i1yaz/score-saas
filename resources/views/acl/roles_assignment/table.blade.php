<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="parents-table">
            <thead>
            <tr>
                <th class="th">Id</th>
                <th class="th">email</th>
                <th class="th"># Roles</th>
                @if(config('laratrust.panel.assign_permissions_to_user'))<th class="th"># Permissions</th>@endif
                <th class="th">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>
                        {{$user->getKey()}}
                    </td>
                    <td>
                        {{$user->email ?? 'The model doesn\'t have a `email` attribute'}}
                    </td>
                    <td>
                        {{$user->roles_count}}
                    </td>
                    @if(config('laratrust.panel.assign_permissions_to_user'))
                        <td>
                            {{$user->permissions_count}}
                        </td>
                    @endif
                    <td  style="width: 120px">
                        <a href="{{route('acl.assignments.edit', ['roles_assignment' => $user->getKey(), 'model' => $modelKey])}}"  class='btn btn-default btn-sm'><i class="far fa-edit"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $users])
        </div>
    </div>
</div>
