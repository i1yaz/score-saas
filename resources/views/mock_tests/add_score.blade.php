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
                <h3 class="card-title">
                    Add Score
                </h3>
            </div>
            {!! Form::model($mockTestStudent, ['route' => ['mock-test-add-score.store', $mockTestStudent->mock_test_id,$mockTestStudent->student_id], 'method' => 'post']) !!}

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        @include('mock_tests.add_score_fields')
                    </div>
                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('mock-tests.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
