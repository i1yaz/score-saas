@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Session Details
                    </h1>
                </div>
                <div class="col-sm-6">
                    @permission('session-index')
                    <a class="btn btn-default float-right"
                       href="{{ route('sessions.index') }}">
                                                    Back
                                            </a>
                    @endpermission
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Session Details</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @include('sessions.show_fields')
                </div>
            </div>
        </div>
    </div>

@endsection
