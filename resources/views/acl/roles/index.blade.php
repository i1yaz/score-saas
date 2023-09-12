@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1></h1>
                </div>
                @if (config('laratrust.panel.create_permissions'))
                    <div class="col-sm-6">
                        <a class="btn btn-primary float-right"
                           href="{{route('acl.roles.create')}}">
                            + New Role
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Roles and Permissions</h3>
            </div>
            @include('acl.roles.table')
        </div>
    </div>

@endsection
