@extends('landlord.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        General Settings
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model(['route' => ['landlord.settings.smtp.update'], 'method' => 'post']) !!}

            <div class="card-body">
                <div class="row">
                    @include('landlord.settings.smtp_settings_form')
                </div>
            </div>

            <div class="card-footer">
                {!! Form::button('Send A Test Email', ['class' => 'btn btn-info','data-toggle'=>"modal",'data-target'=>"#send-test-email-modal"]) !!}
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}

        </div>
        <div class="modal" id="send-test-email-modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Send A Test Email</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group col-sm-12">
                            {!! Form::label('email_to_address', 'Send Email to:') !!}
                            {!! Form::text('email_to_address',strtolower('admin@' . config('system.email_domain')), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="send-test-email">Send</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('after_third_party_scripts')
    <script>
        $(document).ready(function () {
            $('#send-test-email').on('click', function () {
                var url = `/app-admin/settings/email/test-email`;
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        email_to_address: $('#email_to_address').val()
                    },
                    success: function (data) {
                        console.log(data);
                        toastr.success(data.message);
                    },
                    error: function (data) {
                        toastr.error(data.responseJSON.error);
                    }
                });
            });
        });
    </script>
@endpush
