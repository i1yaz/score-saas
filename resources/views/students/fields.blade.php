<!-- Parents -->
@if(!empty($parent))
    <input type="hidden" name="parent_id" value="{{$parent}}">
@else
    <div class="form-group col-sm-6">
        {!! Form::label('parent_id', 'Parent:') !!}
        {!! Form::select('parent_id', $selectedParent??[], null, ['class' => 'form-control select2 ','id'=>'parent-id']) !!}
    </div>
@endif
<!-- School -->
<div class="form-group col-sm-6">
    {!! Form::label('school_id', 'School:',['class' => 'required']) !!}
    <div class="input-group">
        {!! Form::select('school_id', $selectedSchool??[], null, ['class' => 'form-control select2 ','id'=>'school-id']) !!}
        @permission('school-create')
        <div class="input-group-append">
            <a class="input-group-text" href="#" data-toggle="modal" data-target="#add-school">Add School</a>
        </div>
        @endpermission
    </div>
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:',['class' => 'required']) !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- First Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_name', 'First Name:', [ 'class' => 'required']) !!}
    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Last Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_name', 'Last Name:', [ 'class' => 'required']) !!}
    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Known Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_known', 'Email Known:') !!}
    {!! Form::select('email_known', ['yes' =>'YES','no'=>'NO'], booleanSelect($student->email_known??''), ['class' => 'form-control custom-select']) !!}
</div>

<!-- Testing Accommodation Nature Field -->
<div class="form-group col-sm-6">
    {!! Form::label('testing_accommodation_nature', 'Nature of Testing Accommodation:') !!}
    {!! Form::text('testing_accommodation_nature', null, ['class' => 'form-control']) !!}
</div>

<!-- Official Baseline Act Score Field -->
<div class="form-group col-sm-6">
    {!! Form::label('official_baseline_act_score', 'Official Baseline Act Score:') !!}
    {!! Form::text('official_baseline_act_score', null, ['class' => 'form-control']) !!}
</div>

<!-- Official Baseline Sat Score Field -->
<div class="form-group col-sm-6">
    {!! Form::label('official_baseline_sat_score', 'Official Baseline Sat Score:') !!}
    {!! Form::text('official_baseline_sat_score', null, ['class' => 'form-control']) !!}
</div>

<!-- 'bootstrap / Toggle Switch Test Anxiety Challenge Field' -->
<div class="form-group col-sm-6">
    {!! Form::label('test_anxiety_challenge', 'Does parent or student believe test anxiety is a potential challenge?:') !!}
    {!! Form::select('test_anxiety_challenge', ['yes' =>'YES','no'=>'NO'], booleanSelect($student->test_anxiety_challenge??''),  ['class' => 'form-control custom-select']) !!}

</div>
<!-- 'bootstrap / Toggle Switch Testing Accommodation Field' -->
<div class="form-group col-sm-6">
    {!! Form::label('testing_accommodation', 'Testing Accommodation:') !!}
    {!! Form::select('testing_accommodation', ['yes' =>'YES','no'=>'NO'], booleanSelect($student->testing_accommodation??''),  ['class' => 'form-control custom-select']) !!}

</div>
<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', getActiveInactiveArray(),booleanSelect($student->status??null), ['class' => 'form-control custom-select'])  !!}
</div>

<div class="modal fade" id="add-school" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add School</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Name Field -->
                <div class="form-group col-sm-12">
                    {!! Form::label('name', 'Name:') !!}
                    {!! Form::text('name', null, ['class' => 'form-control','id'=>'school-name']) !!}
                </div>

                <!-- Address Field -->
                <div class="form-group col-sm-12 col-lg-12">
                    {!! Form::label('address', 'Address:') !!}
                    {!! Form::textarea('address', null, ['class' => 'form-control','id'=>'school-address']) !!}
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" id="dismiss-school-modal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="store-school">Save changes</button>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
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
            $("#students-form").submit(function(e) {

                e.preventDefault(); // avoid to execute the actual submit of the form.

                var form = $(this);
                var actionUrl = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: form.serialize(), // serializes the form's elements.
                    success: function(response)
                    {
                        toastr.success(response.message);
                        window.location = response.redirectTo
                    },
                    error: function (xhr, status, error) {
                        $("input[type='submit']").attr("disabled", false);
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
                    }
                });

            });
            // Initialize Select2
            $("#parent-id").select2({
                theme: 'bootstrap4',
                minimumInputLength: 1,
                ajax: {
                    url: "{{route('student-parent-ajax')}}",
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
                placeholder: "Please type full email...",
                escapeMarkup: function (markup) {
                    return markup;
                }
            });

            $("#school-id").select2({
                theme: 'bootstrap4',
                minimumInputLength: 1,
                ajax: {
                    url: "{{route('student-school-ajax')}}",
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
                placeholder: "Please type school name",
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
        });
    </script>
@endpush
