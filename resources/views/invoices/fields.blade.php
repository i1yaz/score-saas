<div class="form-group col-sm-6">
    {!! Form::label('client_id', 'Client:',['class'=>'required']) !!}


    <div class="input-group">
        {!! Form::select('client_id', $clients??[] ,null, ['class' => 'form-control','id'=>'client-id']) !!}
{{--        @permission('client-create')--}}
        <div class="input-group-append">
            <a class="input-group-text" href="#" data-toggle="modal" data-target="#add-client">Add Client</a>
        </div>
{{--        @endpermission--}}
    </div>

</div>


<!-- Due Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('due_date', 'Due Date:',['class'=>'required']) !!}
    {!! Form::text('due_date', null, ['class' => 'form-control date-input']) !!}
</div>


<!-- General Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('general_description', 'General Description:') !!}
    {!! Form::textarea('general_description', null, ['class' => 'form-control']) !!}
</div>

<!-- Detailed Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('detailed_description', 'Detailed Description:') !!}
    {!! Form::textarea('detailed_description', null, ['class' => 'form-control']) !!}
</div>


<div class="modal fade" id="add-client" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Client</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- First Name Field -->
                <div class="form-group col-sm-12">
                    {!! Form::label('first_name', 'First Name:',['class'=>'required']) !!}
                    {!! Form::text('first_name', null, ['class' => 'form-control','id'=>'first-name']) !!}
                </div>
                <!-- Last Name Field -->
                <div class="form-group col-sm-12">
                    {!! Form::label('last_name', 'Last Name:') !!}
                    {!! Form::text('last_name', null, ['class' => 'form-control','id'=>'last-name']) !!}
                </div>
                <div class="form-group col-sm-12">
                    {!! Form::label('email', 'Email:',['class'=>'required']) !!}
                    {!! Form::text('email', null, ['class' => 'form-control','id'=>'email']) !!}
                </div>
                <!-- Address Field -->
                <div class="form-group col-sm-12 col-lg-12">
                    {!! Form::label('address', 'Address:') !!}
                    {!! Form::textarea('address', null, ['class' => 'form-control','id'=>'client-address']) !!}
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" id="dismiss-client-modal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="store-client">Save changes</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $("#store-client").click(function(){
            $.post("{{route('clients.store')}}",
                {
                    _token: "{{csrf_token()}}",
                    first_name: $("#first-name").val(),
                    last_name: $("#last-name").val(),
                    email: $("#email").val(),
                    address: $("#client-address").val()
                },
                function(data, status){
                    toastr.success(data.success)
                    $('#dismiss-client-modal').trigger('click');
                })
                .fail(function() {
                    toastr.error("something went wrong!")
                });
        });

        $('#non-package-invoice-package-form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function (response) {
                    console.log(response)
                    toastr.success(response.message);
                    $("input[type='submit']").attr("disabled", false);
                    window.location = response.redirectTo
                },
                error: function (xhr, status, error) {
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


        $("#client-id").select2({
            dropdownAutoWidth: true, width: 'auto',
            theme: 'bootstrap4',
            minimumInputLength: 3,
            multiple: true,
            ajax: {
                url: "{{route('tutor-email-ajax')}}",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        email: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
            placeholder: "Please type tutor email",
            escapeMarkup: function (markup) {
                return markup;
            }
        });
    </script>
@endpush
