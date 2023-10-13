<!-- Student Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('student_id', 'Student:',['class'=>'required']) !!}
    {!! Form::select('student_id', [] ,null, ['class' => 'form-control','id'=>'student-id']) !!}
</div>
<!-- Tutoring Location Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutoring_location_id', 'Tutoring Location:',['class'=> 'required']) !!}
    {!! Form::select('tutoring_location_id', [] ,null, ['class' => 'form-control','id'=>'tutoring-location-id']) !!}
</div>
<!-- Tutor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutor-id', 'Tutor:') !!}
    <select class="form-control" name="tutor_ids[]" multiple="multiple" id='tutor-id'>
        @foreach ($selectedTutors??[] as $id => $selectedTutorEmail)
            <option selected="selected"  value="{{$id}}" >{{$selectedTutorEmail}}</option>
        @endforeach
    </select>
</div>
<!-- Start Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_date', 'Start Date:') !!}
    {!! Form::text('start_date', null, ['class' => 'form-control date-input']) !!}
</div>
<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('notes', 'Notes:') !!}
    {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
</div>

<!-- Internal Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('internal_notes', 'Internal Notes:') !!}
    {!! Form::textarea('internal_notes', null, ['class' => 'form-control']) !!}
</div>



<!-- Hourly Rate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('hourly_rate', 'Hourly Rate:',['class'=> 'required']) !!}
    {!! Form::text('hourly_rate', null, ['class' => 'form-control']) !!}
</div>

<!-- Tutor Hourly Rate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutor_hourly_rate', 'Tutor Hourly Rate:',['class'=> 'required']) !!}
    {!! Form::text('tutor_hourly_rate', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12" id="all-subjects">
    @include('student_tutoring_packages.subjects')
</div>

@push('page_scripts')
    <script>
        $('#monthly-invoice-package-form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function (response) {
                    toastr.success(response.message);
                    $("input[type='submit']").attr("disabled", false);
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

        $("#student-id").select2({
            dropdownAutoWidth: true, width: 'auto',
            theme: 'bootstrap4',
            minimumInputLength: 3,
            ajax: {
                url: "{{route('student-email-ajax')}}",
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
            placeholder: "Please type student email",
            escapeMarkup: function (markup) {
                return markup;
            }
        });
        $("#tutoring-location-id").select2({
            dropdownAutoWidth: true, width: 'auto',
            theme: 'bootstrap4',
            minimumInputLength: 3,
            ajax: {
                url: "{{route('tutoring-location-ajax')}}",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        name: params.term
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
            placeholder: "Please type Tutoring location name...",
            escapeMarkup: function (markup) {
                return markup;
            }
        });
        $("#tutor-id").select2({
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
