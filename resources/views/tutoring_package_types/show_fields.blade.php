<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $tutoringPackageType->id }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $tutoringPackageType->name }}</p>
</div>

<!-- Hours Field -->
<div class="col-sm-12">
    {!! Form::label('hours', 'Hours:') !!}
    <p>{{ $tutoringPackageType->hours }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $tutoringPackageType->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $tutoringPackageType->updated_at }}</p>
</div>

