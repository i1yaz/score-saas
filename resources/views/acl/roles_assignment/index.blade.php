@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Roles and Permissions</h1>
                </div>
                @if (config('laratrust.panel.create_permissions'))
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{route('laratrust.permissions.create')}}">
                        + New Permission
                    </a>
                </div>
                @endif
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            @include('roles_permissions.roles_assignment.table')
        </div>
    </div>

@endsection
