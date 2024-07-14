@extends('landlord.layouts.app')
@push('page_css')
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
@endpush
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
                    {!! Form::select('templates', $templates,null, ['class' => 'form-control','id' =>'all-templates']) !!}
                </div>
            </div>
           <div id="template-form">

           </div>
        </div>
    </div>
@endsection
@push('after_third_party_scripts')
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('plugins/summernote/summernote.js')}}"></script>
    <script>
        var template_id = '';
        ajaxSubmit =false;
        $(document).ready(function () {
            $('#all-templates').on('change', function () {
                template_id = $(this).val();
                var url = `/app-admin/settings/email-templates/${template_id}`;
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        $('#template-form').html(data.html);
                        $('#template-body').summernote({
                            height: 500,
                            callbacks: {
                                onImageUpload: function(files) {
                                    uploadImage(files[0]);
                                }
                            }
                        });
                    }
                });
            });
        });
        function uploadImage(file) {
            let formData = new FormData();
            formData.append("image", file);
            formData.append("_token", "{{ csrf_token() }}")
            formData.append("template_id", template_id)

            $.ajax({
                url: `/email-templates/upload-image/${template_id}`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#template-body').summernote('editor.insertImage', response.url);
                },
                error: function(error) {
                    console.error('Image upload failed: ', error);
                }
            });
        }

        $(document).on("click", "input[type='submit']", function (e) {
            e.preventDefault();
            var form = $(this).closest("form");
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function(response) {
                    toastr.success(response.message);
                    if (typeof response.redirectTo !== 'undefined') {
                        window.location = response.redirectTo

                    } else {
                        $("input[type='submit']").attr("disabled", false);
                    }
                },
                error: function(xhr, status, error) {

                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function (key, item) {
                            toastr.error(item[0]);
                        });
                    } else if(xhr.status === 404){
                        let response = xhr.responseJSON
                        toastr.error(response.message);
                    } else {
                        toastr.error("something went wrong");
                    }
                    $("input[type='submit']").attr("disabled", false);
                }
            });
        });
    </script>
@endpush

