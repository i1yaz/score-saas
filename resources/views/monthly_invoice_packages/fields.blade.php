<!-- Student Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('student_id', 'Student:',['class'=>'required']) !!}
    {!! Form::select('student_id',$selectedStudent??[] ,null, ['class' => 'form-control','id'=>'student-id']) !!}
</div>
<!-- Tutoring Location Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutoring_location_id', 'Tutoring Location:',['class'=> 'required']) !!}
    {!! Form::select('tutoring_location_id',  $tutoringLocation??[] ,null, ['class' => 'form-control','id'=>'tutoring-location-id']) !!}
</div>
<!-- Tutor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutor-id', 'Tutor:',['class'=> 'required']) !!}
    <select class="form-control" name="tutor_ids[]" multiple="multiple" id='tutor-id'>
        @foreach ($selectedTutors??[] as $id => $selectedTutorEmail)
            <option selected="selected"  value="{{$id}}" >{{$selectedTutorEmail}}</option>
        @endforeach
    </select>
</div>
<!-- Start Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_date', 'Start Date:') !!}
    {!! Form::text('start_date', isset($monthlyInvoicePackage)? $monthlyInvoicePackage->start_date?->format('m/d/Y'):null, ['class' => 'form-control date-input']) !!}
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
    {!! Form::label('hourly_rate', 'Hourly Rate:',['class'=> 'required','type' => 'number', 'min' => '1', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))"]) !!}
    {!! Form::text('hourly_rate', null, ['class' => 'form-control']) !!}
</div>

<!-- Tutor Hourly Rate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutor_hourly_rate', 'Tutor Hourly Rate:',['class'=> 'required','type' => 'number', 'min' => '1', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))"]) !!}
    {!! Form::text('tutor_hourly_rate', null, ['class' => 'form-control']) !!}
</div>
<!-- Discount Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('discount_type', 'Discount:') !!}
    <div class="input-group">
        {!!  Form::number('discount', null, ['class' => 'form-control'])  !!}
        <div class="input-group-append">
            <select class="form-control input-group-text" name="discount_type" id = 'discount-type'>
                <option value="1" @if(isset($monthlyInvoicePackage) && $monthlyInvoicePackage->discount_type == \App\Models\StudentTutoringPackage::FLAT_DISCOUNT) selected @endif>Flat</option>
                <option value="2" @if(isset($monthlyInvoicePackage) && $monthlyInvoicePackage->discount_type == \App\Models\StudentTutoringPackage::PERCENTAGE_DISCOUNT) selected @endif>%</option>
            </select>
        </div>
    </div>
</div>

<div class="form-group col-sm-12" id="all-subjects">
    @include('student_tutoring_packages.subjects')
</div>


<div class="form-group col-sm-6">
    {!! Form::label('is_score_guaranteed', 'Is this a score guarantee invoice?') !!}
    <small class="text-danger">If yes, invoice will automatically be set to paid.</small>
    <div class="radio">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="is_score_guaranteed" id="is-score-guaranteed-yes" value="yes" @if(!empty($monthlyInvoicePackage) && $monthlyInvoicePackage->is_score_guaranteed===true) checked @endif>
            <label class="form-check-label" for="is-score-guaranteed-yes"><strong>  YES</strong></label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="is_score_guaranteed" id="is-score-guaranteed-no" value="no" @if(!empty($monthlyInvoicePackage) && $monthlyInvoicePackage->is_score_guaranteed===true)  @else checked @endif>
            <label class="form-check-label" for="is-score-guaranteed-no"><strong>  NO</strong></label>
        </div>

    </div>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('is_free', 'Is this a score guarantee invoice?') !!}
    <small  class="text-danger">If yes, invoice will automatically be set to paid.</small>
    <div class="radio">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="is_free" id="is-free-yes" value="yes"  @if(!empty($monthlyInvoicePackage) && $monthlyInvoicePackage->is_free===true) checked @endif>
            <label class="form-check-label" for="is-free-yes"><strong>  YES</strong></label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="is_free" id="is-free-no" value="no"  @if(!empty($monthlyInvoicePackage) && $monthlyInvoicePackage->is_free===true)  @else checked @endif>
            <label class="form-check-label" for="is-free-no"><strong>  NO</strong></label>
        </div>

    </div>
</div>

<div class="form-group col-sm-12">
    @include('student_tutoring_packages.invoice_details',['invoice' =>$monthlyInvoicePackage->invoice??null ])
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
    <script>
        $("#store-subject-button").click(function(){
            let subject = $("#subject-name").val();
            if(subject.trim()) {
                $.post("{{route('subjects.store')}}",
                    {
                        _token: "{{csrf_token()}}",
                        name: subject,
                        monthly_tutoring_package_id:{{$monthlyInvoicePackage->id??0}}
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
        $('#monthly-invoice-package-form').submit(function (e) {
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
