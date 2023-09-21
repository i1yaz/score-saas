@push('page_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('css/select2-bootstrap4.min.css')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha512-lzilC+JFd6YV8+vQRNRtU7DOqv5Sa9Ek53lXt/k91HZTJpytHS1L6l1mMKR9K6VVoDt4LiEXaa6XBrYk1YhGTQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.css')}}"/>
    <link rel="stylesheet" href="{{asset('plugins/jquery-ui/jquery-ui.min.css')}}">
@endpush
<!-- Student Field -->
<div class="form-group col-sm-6">
    {!! Form::label('student_id', 'Student:') !!}
    {!! Form::select('student_id', [],null, ['class' => 'form-control','id'=>'student-id']) !!}
</div>

<!-- Package Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('package_type_id', 'Package Type:') !!}
    {!! Form::select('package_type_id', [],null, ['class' => 'form-control','id'=>'package-type-id']) !!}
</div>

<!-- Tutor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutor-id', 'Tutor:') !!}
    {!! Form::select('tutor_id[]', [],null, ['class' => 'form-control','id'=>'tutor-id']) !!}
</div>
<!-- Tutoring Location Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutoring_location_id', 'Tutoring Location:') !!}
    {!! Form::select('tutoring_location_id', [],null, ['class' => 'form-control','id'=>'tutoring-location-id']) !!}
</div>
<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('notes', 'Notes:') !!}
    {!! Form::text('notes', null, ['class' => 'form-control']) !!}
</div>

<!-- Internal Noted Field -->
<div class="form-group col-sm-6">
    {!! Form::label('internal_noted', 'Internal Noted:') !!}
    {!! Form::text('internal_noted', null, ['class' => 'form-control']) !!}
</div>

<!-- Hours Field -->
<div class="form-group col-sm-6">
    {!! Form::label('hours', 'Hours:') !!}
    {!! Form::number('hours', null, ['class' => 'form-control','id'=>'hours']) !!}
</div>

<!-- Hourly Rate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('hourly_rate', 'Hourly Rate:') !!}
    {!! Form::number('hourly_rate', null, ['class' => 'form-control','id'=>'hourly-rate']) !!}
</div>

<!-- Discount Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('discount_type', 'Discount:') !!}
    <div class="input-group">
        {!!  Form::text('discount', null, ['class' => 'form-control'])  !!}
        <div class="input-group-append">
            <select class="form-control input-group-text" name="discount_type" id = 'discount-type'>
                <option>Flat</option>
                <option>%</option>
            </select>
        </div>
    </div>
</div>

<!-- Tutor Hourly Rate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutor_hourly_rate', 'Tutor Hourly Rate:') !!}
    {!! Form::number('tutor_hourly_rate', null, ['class' => 'form-control']) !!}
</div>
<!-- Start Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_date', 'Start Date:') !!}
    {!! Form::text('start_date', null, ['class' => 'form-control']) !!}
</div>


@push('page_scripts')
    <script src="{{asset("plugins/toastr/toastr.min.js")}}"></script>
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script type="text/javascript">
        $('#start_date').datepicker()
    </script>
    <script>
        $("#store-school").click(function(){
            $.post("{{route('schools.store')}}",
                {
                    _token: "{{csrf_token()}}",
                    name: $("#school-name").val(),
                    address: $("#school-address").val()
                },
                function(data, status){
                    toastr.success(data.success)
                    $('#dismiss-school-modal').trigger('click');
                })
                .fail(function() {
                    toastr.error("something went wrong!")
                });
        });
        $(document).ready(function () {
            // Initialize Select2
            $("#package-type-id").select2({
                theme: 'bootstrap4',
                minimumInputLength: 2,
                ajax: {
                    url: "{{route('package-type-ajax')}}",
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
                                    hours: item.hours,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },
                placeholder: "Please type package name...",
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            $('#package-type-id').on('select2:select', function (e) {
                let data = e.params.data;
                $('#hours').empty()
                $('#hours').val(data.hours)

            });
            $("#student-id").select2({
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

            $("#tutor-id").select2({
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

            $("#tutoring-location-id").select2({
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
        });
    </script>
@endpush
