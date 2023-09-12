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
                <h3 class="card-title">Role Assignment</h3>
            </div>

            {!! Form::model($user, ['route' => ['acl.assignments.update',['roles_assignment' => $user->getKey(), 'model' => $modelKey]], 'method' => 'patch']) !!}

            <div class="card-body">
                <div class="row">
                    @include('acl.roles_assignment.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('acl.assignments.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
