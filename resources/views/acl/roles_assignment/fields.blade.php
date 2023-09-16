<!-- Display name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control','readonly'=>'true',]) !!}
</div>

<div class="form-group col-sm-12">
    <h5 class="mb-4">Roles</h5>

    <div class="row">
    @foreach ($roles as $role)
        <div class="form-group col-sm-2">
            <div class="custom-control custom-checkbox">
                <input
                    type="checkbox"
                    class="custom-control-input"
                    @if ($role->assigned && !$role->isRemovable)
                        disabled
                    @endif
                    name="roles[]"
                    value="{{$role->getKey()}}"
                    {!! $role->assigned ? 'checked' : '' !!}
                    {!! $role->assigned && !$role->isRemovable ? 'onclick="return false;"' : '' !!}
                    id="{{$role->getKey()}}"
                >

                <label for="{{$role->getKey()}}" class="custom-control-label" style="flex: 1 0 20%;">{{$role->display_name ?? $role->name}}</label>
            </div>
        </div>

    @endforeach
    </div>
</div>

<div class="form-group col-sm-12">
    <h5 class="mb-4 w-100">Permissions</h5>

    @foreach ($permissions as $groupName => $permissionsGroup)
        <h5 class="mb-4 w-100">{{$groupName}}</h5>
        <div class="row">
            @foreach($permissionsGroup as $permission)
                <div class="form-group col-sm-2">
                    <div class="custom-control custom-checkbox">
                        <input
                            type="checkbox"
                            class="custom-control-input"
                            name="permissions[]"
                            value="{{$permission->getKey()}}"
                            {!! $permission->assigned ? 'checked' : '' !!}
                            id="{{$permission->getKey()}}"
                        >
                        <label for="{{$permission->getKey()}}" class="custom-control-label" style="flex: 1 0 20%;">{{$permission->display_name ?? $permission->name}}</label>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>

