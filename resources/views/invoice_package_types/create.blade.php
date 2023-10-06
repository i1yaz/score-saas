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
                    Create Invoice Package Types
                </h3>
            </div>
            {!! Form::open(['route' => 'invoice-package-types.store']) !!}

            <div class="card-body">

                <div class="row">
                    @include('invoice_package_types.fields')

                </div>

                <div class="card-footer">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                    <a href="{{ route('invoice-package-types.index') }}" class="btn btn-default"> Cancel </a>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
@endsection
