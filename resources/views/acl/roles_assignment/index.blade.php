@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1></h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Role Assignment</h3>
            </div>
            @include('acl.roles_assignment.table')
        </div>
    </div>

@endsection
