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
        <!-- Status Field -->
        <div class="form-group col-sm-12 col-md-2">
            <select class="form-control custom-select" name="model" id="model">
            <option value="initial" disabled selected>Select a user model</option>
            @foreach ($models as $model)
                <option value="{{route('acl.assignments.index',['model'=>$model])}}" @if($model==$modelKey) selected @endif >{{ucwords($model)}}</option>
            @endforeach
            </select>
        </div>
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
@push('page_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha512-lzilC+JFd6YV8+vQRNRtU7DOqv5Sa9Ek53lXt/k91HZTJpytHS1L6l1mMKR9K6VVoDt4LiEXaa6XBrYk1YhGTQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(function(){
            // bind change event to select
            $('#model').on('change', function () {
                var url = $(this).val(); // get selected value
                if (url) { // require a URL
                    window.location = url; // redirect
                }
                return false;
            });
        });
    </script>
@endpush
