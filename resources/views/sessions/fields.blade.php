@push('page_css')
    <style>
        .feedback-emojis{
            width: 30px;
            height: 30px;
        }
    </style>
@endpush
<div class="form-group col-sm-12">
    <p>
        While creating the session keep in mind the following:
    </p>
    <p>
        1. If you are creating a session for a date in the future (considering date and time), you must edit it and fill out the remaining details once the session has been completed so that it can be marked as billable.
    </p>
    <p>
        2. If you are creating a session for a date in the past (considering date and time), you will be asked to complete all fields related to how the session went and in this case, the session will be marked as billable right away.
    </p>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('student_tutoring_package_id', 'Tutoring Package:', ['class' => 'required']) !!}
    {!! Form::select('student_tutoring_package_id',  $selectedTutoringPackage??[], null, ['class' => 'form-control select2','id'=>'tutoring-package-id']) !!}
</div>

<!-- Session Fields -->
<div class="form-group col-sm-6">
    <div class="row" style="padding-left: 8px">
        <div class="form-group">
            {!! Form::label('scheduled_date', 'Session Date:', ['class' => 'required']) !!}
            {!! Form::text('scheduled_date', null, ['class' => 'form-control date-input col-sm-11']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('start_time', 'Start Time:', ['class' => 'required']) !!}
            {!! Form::time('start_time', null, ['class' => 'form-control  col-sm-11','type'=>'time']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('end_time', 'End Time:', ['class' => 'required']) !!}
            {!! Form::time('end_time', null, ['class' => 'form-control col-sm-11','type'=>'time']) !!}
        </div>
    </div>

</div>

<div class="form-group col-sm-12">
    <div class="row">
        @role(['super-admin','admin'])
        <!-- Tutor Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('tutor-id', 'Tutor:',[ 'class' => 'required']) !!}
            {!! Form::select('tutor_id', [],null, ['class' => 'form-control select2','id'=>'tutor-id']) !!}
        </div>
        @endrole
        <div class="form-group col-sm-6">
            {!! Form::label('tutoring_location_id', 'Location:') !!}
            {!! Form::select('tutoring_location_id', $selectedLocation??[], null, ['class' => 'form-control select2 ','id'=>'location-id']) !!}
        </div>
        <!-- Completion Code -->
        <div class="form-group col-sm-6">
            {!! Form::label('session_completion_code', 'Session Completion Code:') !!}
            {!! Form::select('session_completion_code', $completionCodes, null, ['class' => 'form-control ','id'=>'session-completion-code','onchange'=>'sessionCompletionCode()']) !!}
        </div>

        <div class="form-group col-sm-12">
            <div class="row" style="padding-left: 8px">

                <div class="form-group flex-child @if(!(isset($session) && $session->session_completion_code===\App\Models\Session::PARTIAL_COMPLETION_CODE)) d-none @endif  attended-session-time">
                    {!! Form::label('attended_start_time', 'Attended Session start time') !!}
                    {!! Form::time('attended_start_time', null, ['class' => 'form-control col-sm-11','type'=>'time']) !!}
                </div>
                <div class="form-group flex-child @if(!(isset($session) && $session->session_completion_code===\App\Models\Session::PARTIAL_COMPLETION_CODE)) d-none @endif attended-session-time">
                    {!! Form::label('attended_end_time', 'Attended Session end time ') !!}
                    {!! Form::time('attended_end_time', null, ['class' => 'form-control col-sm-11','type'=>'time']) !!}
                </div>
                <div class="form-group flex-child @if(!(isset($session) && $session->session_completion_code===\App\Models\Session::PARTIAL_COMPLETION_CODE)) d-none @endif attended-session-time">
                    {!! Form::label('charge_for_missed_time','Charge for missed time') !!}
                    {!! Form::select('charge_for_missed_time',[1=>'No',2=>'Yes'],null, ['class' => 'form-control col-sm-11','id'=>'charge-missed-time', 'onChange'=>'chargeMissedTime()']) !!}
                </div>

            </div>
        </div>
        <div class="form-group col-sm-12">
            {!! Form::label('completion_code', 'How was your session ? (The student will not see this):') !!}
            <div class="radio">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="how_was_session" id="how-was-session-1" value="1" checked>
                    <label class="form-check-label" for="how-was-session-1"><strong>  1 </strong> <img class="feedback-emojis" src="{{asset('img/1.png')}}" alt="1"></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="how_was_session" id="how-was-session-2" value="2">
                    <label class="form-check-label" for="how-was-session-2"><strong>  2</strong> <img class="feedback-emojis" src="{{asset('img/2.png')}}" alt="1"></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="how_was_session" id="how-was-session-3" value="3">
                    <label class="form-check-label" for="how-was-session-3"><strong>  3</strong> <img class="feedback-emojis" src="{{asset('img/3.png')}}" alt="1"></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="how_was_session" id="how-was-session-4" value="4">
                    <label class="form-check-label" for="how-was-session-4"><strong>  4</strong> <img class="feedback-emojis" src="{{asset('img/4.png')}}" alt="1"></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="how_was_session" id="how-was-session-5" value="5">
                    <label class="form-check-label" for="how-was-session-5"><strong>  5</strong> <img class="feedback-emojis" src="{{asset('img/5.png')}}" alt="1"></label>
                </div>
            </div>
        </div>
        <div class="form-group col-sm-6 w-100">
            {!! Form::label('student_parent_session_notes', 'Student parent session notes:') !!}
            <small style="display: block">Viewable by parent & student.</small>
            {!! Form::textarea('student_parent_session_notes', null, ['class' => 'form-control date-input col-sm-11']) !!}
        </div>
        <div class="form-group col-sm-6 w-100">
            {!! Form::label('homework', 'Homework:') !!}
            <small style="display: block">.</small>
            {!! Form::textarea('homework', null, ['class' => 'form-control date-input col-sm-11']) !!}
        </div>
        <div class="form-group col-sm-12 w-100">
            {!! Form::label('internal_notes', 'Tutor internal_notes:') !!}
            {!! Form::textarea('internal_notes', null, ['class' => 'form-control date-input col-sm-11']) !!}
        </div>
        <div class="form-group col-sm-6 w-100">
            {!! Form::label('flag_session', 'Flag this Session ? ') !!}
            <small style="display: block">Flagging will notify the Admins to take a look at this session.</small>
            {!! Form::checkbox('flag_session', 'yes') !!} Flag session
        </div>
        <div class="form-group col-sm-6">
            {!! Form::label('home_work_completed', '80% homework completed ?') !!}
            <div class="radio">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="home_work_completed" id="homework-completed-yes" value="yes" checked>
                    <label class="form-check-label" for="homework-completed-yes"><strong>  Yes </strong> </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="home_work_completed" id="homework-completed_no" value="no">
                    <label class="form-check-label" for="homework-completed_no"><strong>  No</strong></label>
                </div>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script>

        $(document).ready(function () {
            $("#scheduled_date").datepicker("setDate", new Date());
            $("#location-id").select2({
                theme: 'bootstrap4',
                dropdownAutoWidth: true, width: 'auto',
                minimumInputLength: 3,
                ajax: {
                    url: "{{route('location-ajax')}}",
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
                placeholder: "Please type location name...",
                escapeMarkup: function (markup) {
                    return markup;
                }
            });

            $("#tutoring-package-id").select2({
                dropdownAutoWidth: true, width: 'auto',
                theme: 'bootstrap4',
                minimumInputLength: 3,
                ajax: {
                    url: "{{route('tutoring-package-ajax')}}",
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
                placeholder: "Please type package name...",
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
        });


        function sessionCompletionCode(){
            console.log('ok')
            let sessionCompletionCode = parseInt($('#session-completion-code').val())
            console.log(sessionCompletionCode)
            if(sessionCompletionCode ===2){
                $('.attended-session-time').removeClass('d-none')
            }else{
                $('.attended-session-time').addClass('d-none')
            }
        }

    </script>
    @include('sessions.includes.tutors_select2js',['strict'=>true])

@endpush
