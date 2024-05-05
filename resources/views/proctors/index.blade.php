@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1></h1>
                </div>
                @permission('proctor-create')
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('proctors.create') }}">
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
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Proctors</h3>
            </div>

            @include('proctors.table')
        </div>
    </div>

@endsection
