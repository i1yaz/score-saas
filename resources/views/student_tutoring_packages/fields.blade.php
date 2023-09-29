@push('page_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('css/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.css')}}"/>
    <link rel="stylesheet" href="{{asset('plugins/jquery-ui/jquery-ui.min.css')}}">
@endpush
<!-- Student Field -->
<div class="form-group col-sm-6">
    {!! Form::label('student_id', 'Student:') !!}
    {!! Form::select('student_id', [],null, ['class' => 'form-control','id'=>'student-id']) !!}
</div>

<!-- Tutoring Package Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutoring_package_type_id', 'Tutoring Package Type:') !!}
    {!! Form::select('tutoring_package_type_id', [],null, ['class' => 'form-control','id'=>'tutoring-package-type-id']) !!}
</div>

<!-- Tutor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutor-id', 'Tutor:') !!}
    {!! Form::select('tutor_ids[]', [],null, ['class' => 'form-control','id'=>'tutor-id']) !!}
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
    {!! Form::label('internal_notes', 'Internal Noted:') !!}
    {!! Form::text('internal_notes', null, ['class' => 'form-control']) !!}
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
        {!!  Form::number('discount', null, ['class' => 'form-control'])  !!}
        <div class="input-group-append">
            <select class="form-control input-group-text" name="discount_type" id = 'discount-type'>
                <option value="1" @if(isset($studentTutoringPackage) && $studentTutoringPackage->discount_type == \App\Models\StudentTutoringPackage::FLAT_DISCOUNT) selected @endif>Flat</option>
                <option value="2" @if (isset($studentTutoringPackage) && $studentTutoringPackage->discount_type == \App\Models\StudentTutoringPackage::PERCENTAGE_DISCOUNT) selected @endif>%</option>
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
    {!! Form::text('start_date', null, ['class' => 'form-control date-input']) !!}
</div>
<div class="form-group col-sm-12" id="all-subjects">
    @include('student_tutoring_packages.subjects')
</div>
<div class="form-group col-sm-12">
    @include('student_tutoring_packages.invoice_details')
</div>
<div class="modal fade" id="store-subject" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Subject</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Name Field -->
                <div class="form-group col-sm-12">
                    {!! Form::label('name', 'Name:') !!}
                    {!! Form::text('name', null, ['class' => 'form-control','id'=>'subject-name']) !!}
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" id="dismiss-subject-modal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="store-subject-button">Save changes</button>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script src="{{asset("plugins/toastr/toastr.min.js")}}"></script>
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $('.date-input').datepicker()
    </script>
    <script>
        $("#store-subject-button").click(function(){
            let subject = $("#subject-name").val();
            if(subject.trim()) {
                $.post("{{route('subjects.store')}}",
                    {
                        _token: "{{csrf_token()}}",
                        name: subject,
                        student_tutoring_package_id:{{$studentTutoringPackage->id??0}}
                    },
                    function(data, status){
                        toastr.success(data.success)
                        $('#dismiss-subject-modal').trigger('click');
                        $('#all-subjects').empty()
                        $('#all-subjects').append(data.html)
                    })
                    .fail(function() {
                        toastr.error("something went wrong!")
                    });
            }else {
                toastr.error('Name can not be empty')
            }

        });
        $(document).ready(function () {
            // Initialize Select2
            $("#tutoring-package-type-id").select2({
                theme: 'bootstrap4',
                minimumInputLength: 2,
                ajax: {
                    url: "{{route('tutoring-package-type-ajax')}}",
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
            $('#tutoring-package-type-id').on('select2:select', function (e) {
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
