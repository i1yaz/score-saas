@extends('landlord.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Email Templates
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title"></h3>
                <div class="form-group col-sm-2 float-right">
                    {!! Form::select('templates', $templates,null, ['class' => 'form-control','id' =>'templates']) !!}
                </div>
            </div>
           <div id="template-form">

           </div>
        </div>
    </div>
@endsection
@push('after_third_party_scripts')
    <script>
        $(document).ready(function () {
            $('#templates').on('change', function () {
                var id = $(this).val();
                var url = `/app-admin/settings/email-templates/${id}`;
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        $('#template-form').html(data);
                    }
                });
            });
        });
    </script>
@endpush
