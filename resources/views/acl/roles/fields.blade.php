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

<div class="row">
    <!-- Permissions -->
{{--    @php--}}
{{--        $groupName = "";--}}
{{--        $currentGroupName = "";--}}
{{--    @endphp--}}
    @foreach ($permissions as $permission)
{{--        @php--}}
{{--            $groupName =  explode(' ',$permission->display_name)[0];--}}
{{--        @endphp--}}
        <div class="form-group col-sm-3">
            {!! Form::checkbox('permissions[]', $permission->getKey()) !!}
            {!! Form::label('permissions', $permission->display_name ?? $permission->name) !!}
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
