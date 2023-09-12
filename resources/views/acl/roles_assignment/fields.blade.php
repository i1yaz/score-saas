<!-- Display name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control','readonly'=>'true',]) !!}
</div>
<h5 class="mb-4 w-100">Roles</h5>
<div class="row">
    @foreach ($roles as $role)
        <div class="form-group col-sm-3">
            {!! Form::checkbox('permissions[]', $role->getKey()) !!}
            {!! Form::label('permissions', $role->display_name ?? $permission->name) !!}
        </div>
    @endforeach
</div>

<h5 class="mb-4 w-100">Permissions</h5>
<div class="row">
    @foreach ($permissions as $groupName => $permissionsGroup)
        <h5 class="mb-4 w-100">{{$groupName}}</h5>
        <div class="row">
            @foreach($permissionsGroup as $permission)
                <div class="form-group col-sm-3">
                    {!! Form::checkbox('permissions[]', $permission->getKey()) !!}
                    {!! Form::label('permissions', $permission->display_name ?? $permission->name) !!}
                </div>
            @endforeach
        </div>
    @endforeach
</div>

