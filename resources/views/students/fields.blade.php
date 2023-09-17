@push('page_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('css/select2-bootstrap4.min.css')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha512-lzilC+JFd6YV8+vQRNRtU7DOqv5Sa9Ek53lXt/k91HZTJpytHS1L6l1mMKR9K6VVoDt4LiEXaa6XBrYk1YhGTQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.css')}}"/>
@endpush
<!-- Parents -->
@if(!empty($parent))
    <input type="hidden" name="parent_id" value="{{$parent}}">
@else
    <div class="form-group col-sm-6">
        {!! Form::label('parent_id', 'Parent:') !!}
        {!! Form::select('parent_id', [], null, ['class' => 'form-control select2 ','id'=>'parent-id']) !!}

    </div>
@endif
<!-- School -->
<div class="form-group col-sm-6">
    {!! Form::label('school_id', 'School:') !!}
    <div class="input-group">
        {!! Form::select('school_id', [], null, ['class' => 'form-control select2 ','id'=>'school-id']) !!}
        <div class="input-group-append">
            <a class="input-group-text" href="#" data-toggle="modal" data-target="#add-school">Add School</a>
        </div>
    </div>
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- First Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_name', 'First Name:') !!}
    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Last Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_name', 'Last Name:') !!}
    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
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
    {!! Form::select('status', ['yes' =>'YES','no'=>'NO'],booleanSelect($student->status??null), ['class' => 'form-control custom-select'])  !!}
</div>

<div class="modal fade" id="add-school" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
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
    <script src="{{asset("plugins/toastr/toastr.min.js")}}"></script>
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
                    toastr.success("something went wrong!")
                });
        });
        $(document).ready(function () {
            // Initialize Select2
            $("#parent-id").select2({
                theme: 'bootstrap4',
                minimumInputLength: 3, // Minimum input length before triggering the AJAX call
                ajax: {
                    url: "{{route('student-parent-ajax')}}", // Replace with your API endpoint
                    dataType: "json",
                    delay: 250, // Delay in milliseconds before making the AJAX request
                    data: function (params) {
                        return {
                            email: params.term // Pass the user's input as the 'query' parameter
                        };
                    },
                    processResults: function (data) {
                        console.log(data)
                        return {
                            results: data // Assuming your API returns an array of objects with 'id' and 'text' properties
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
                minimumInputLength: 3, // Minimum input length before triggering the AJAX call
                ajax: {
                    url: "{{route('student-school-ajax')}}", // Replace with your API endpoint
                    dataType: "json",
                    delay: 250, // Delay in milliseconds before making the AJAX request
                    data: function (params) {
                        return {
                            name: params.term // Pass the user's input as the 'query' parameter
                        };
                    },
                    processResults: function (data) {
                        console.log(data)
                        return {
                            results: data // Assuming your API returns an array of objects with 'id' and 'text' properties
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
