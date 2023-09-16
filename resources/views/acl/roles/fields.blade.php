<!-- Display name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('display_name', 'Display Name:') !!}
    {!! Form::text('display_name', null, ['class' => 'form-control','onkeyup'=>'onChangeDisplayName()','id'=>'display-name']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>
<!-- Name/Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name/Code:') !!}
    {!! Form::email('name', null, ['class' => 'form-control','readonly' => true,'id'=>'name']) !!}
</div>

<div class="form-group col-sm-12">
    <!-- Permissions -->
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
@push('page_scripts')
    <script>
        function onChangeDisplayName() {
            let value = document.getElementById('display-name').value;
            let name = toKebabCase(value);
            console.log(name)
            document.getElementById('name').value = name;

        }

        function toKebabCase(str) {
            return str &&
                str
                    .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
                    .map(x => x.toLowerCase())
                    .join('-')
                    .trim();
        }
    </script>
@endpush
