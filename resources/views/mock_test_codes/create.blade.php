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
                    Create Mock Test Code
                </h3>
            </div>
            {!! Form::open(['route' => 'mock-test-codes.store']) !!}

            <div class="card-body">

                <div class="row">
                    @include('mock_test_codes.fields')
                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('mock-test-codes.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
@push('after_third_party_scripts')
    <script>
        ajaxSubmit = false;
    </script>
@endpush
