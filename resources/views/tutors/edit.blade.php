@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Tutor</h3>
            </div>
            {!! Form::model($tutor, ['route' => ['tutors.update', $tutor->id], 'method' => 'patch','enctype' => 'multipart/form-data']) !!}

            <div class="card-body">
                <div class="row">
                    @include('tutors.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('tutors.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
