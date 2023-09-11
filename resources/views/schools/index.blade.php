@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Schools</h1>
                </div>
                @permission('school-create')
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('schools.create') }}">
                        Add New
                    </a>
                </div>
                @endpermission
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            @include('schools.table')
        </div>
    </div>

@endsection
