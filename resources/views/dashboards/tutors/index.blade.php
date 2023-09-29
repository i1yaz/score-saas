@extends('layouts.app')
@push('page_css')
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
    <style>
        .feedback-emojis{
            width: 30px;
            height: 30px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('css/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.css')}}"/>
    <link rel="stylesheet" href="{{asset('plugins/jquery-ui/jquery-ui.min.css')}}">


@endpush
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1></h1>
            </div>
            <div class="col-sm-6">

            </div>
        </div>
    </div>
</section>

<div class="content px-3">

    @include('flash::message')

    <div class="clearfix"></div>

    <div class="row">
{{--        <div class="col-md-3">--}}
{{--            <div class="sticky-top mb-3">--}}

{{--            </div>--}}
{{--        </div>--}}

        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body p-0">
                    <div id="session-calendar">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="session-store" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Tutoring Session</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="session-form">
                    @csrf
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
                    <div class="form-group col-sm-12">
                        {!! Form::label('student_tutoring_package_id', 'Tutoring Package:') !!}
                        {!! Form::select('student_tutoring_package_id', [], null, ['class' => 'form-control select2 ','id'=>'student-tutoring-package-id']) !!}
                    </div>
                    <!-- Session Fields -->
                    <div class="form-group col-sm-12">
                        <div class="row" style="padding-left: 8px">
                            <div class="form-group">
                                {!! Form::label('scheduled_date', 'Session Date:') !!}
                                {!! Form::text('scheduled_date', null, ['class' => 'form-control date-input col-sm-11']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('start_time', 'Start Time:') !!}
                                {!! Form::time('start_time', null, ['class' => 'form-control  col-sm-11','type'=>'time']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('end_time', 'End Time:') !!}
                                {!! Form::time('end_time', null, ['class' => 'form-control col-sm-11','type'=>'time']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="form-group col-sm-12">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                {!! Form::label('tutoring_location_id', 'Location:') !!}
                                {!! Form::select('tutoring_location_id', [], null, ['class' => 'form-control select2 ','id'=>'location-id']) !!}
                            </div>
                            <!-- Completion Code -->
                            <div class="form-group col-sm-6">
                                {!! Form::label('session_completion_code', 'Session Completion Code:') !!}
                                {!! Form::select('session_completion_code', $completionCodes, null, ['class' => 'form-control ','id'=>'session-completion-code']) !!}
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
                            <div class="form-group col-sm-12 w-100">
                                {!! Form::label('student_parent_session_notes', 'Student parent session notes:') !!}
                                <small style="display: block">Viewable by parent & student.</small>
                                {!! Form::textarea('student_parent_session_notes', null, ['class' => 'form-control date-input col-sm-11']) !!}
                            </div>
                            <div class="form-group col-sm-12 w-100">
                                {!! Form::label('homework', 'Homework:') !!}
                                {!! Form::textarea('homework', null, ['class' => 'form-control date-input col-sm-11']) !!}
                            </div>
                            <div class="form-group col-sm-12 w-100">
                                {!! Form::label('internal_notes', 'Tutor internal_notes:') !!}
                                {!! Form::textarea('internal_notes', null, ['class' => 'form-control date-input col-sm-11']) !!}
                            </div>
                            <div class="form-group col-sm-12 w-100">
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
                            <div class="form-group col-sm-6">

                                {!! Form::label('practice_test_for_homework', 'Did you give a practice test for homework  ?') !!}
                                <div class="radio">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="practice_test_for_homework" id="practice-test-for-homework-yes" value="yes" checked>
                                        <label class="form-check-label" for="practice_test_for_homework"><strong>  Yes </strong> </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="practice_test_for_homework" id="practice-test-for-homework-no" value="no">
                                        <label class="form-check-label" for="practice-test-for-homework-no"><strong>  No</strong></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div  class="modal fade" id="session-show" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tutoring Session Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
{{--            Attended Session Timer Manual Entry--}}
{{--            Total Session Time Charged--}}
{{--            Session Completion Code--}}
{{--            Pre Session Note--}}
{{--            Student/Parent Session Note--}}
{{--            Reviewed Math section.--}}
{{--            Tutor Internal Note--}}
{{--            80% homework completed?--}}
{{--            Homework--}}

            <!-- Modal body -->
            <div class="modal-body">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td> <strong> Scheduled Date & Time</strong></td>
                        <td id="scheduled-date-text"></td>
                    </tr>
                    <tr>
                        <td> <strong> Session Location</strong></td>
                        <td id="session-location-text"></td>
                    </tr>
                    <tr>
                        <td> <strong> Student</strong></td>
                        <td id="student-text"></td>
                    </tr>
                    <tr>
                        <td> <strong> Attended Session Timer Manual Entry</strong></td>
                        <td id="attended-sessions-manual-text"></td>
                    </tr>
                    <tr>
                        <td> <strong> Total Session Time Charged</strong></td>
                        <td id="total-session-time-charged-text"></td>
                    </tr>
                    <tr>
                        <td> <strong> Session Completion Code</strong></td>
                        <td id="session-completion-code-text"></td>
                    </tr>
{{--                    <tr>--}}
{{--                        <td> <strong> Pre Session Note</strong></td>--}}
{{--                        <td id="pre-session-note-text"></td>--}}
{{--                    </tr>--}}
                    <tr>
                        <td> <strong> Student/Parent Session Note</strong></td>
                        <td id="student-session-notes-text"></td>
                    </tr>

                    <tr>
                        <td> <strong> Tutor Internal Note</strong></td>
                        <td id="tutor-internal-note-text"></td>
                    </tr>
                    <tr>
                        <td> <strong> 80% homework completed?</strong></td>
                        <td id="homework-completed-text"></td>
                    </tr>
                    <tr>
                        <td> <strong> Homework</strong></td>
                        <td id="homework-text"></td>
                    </tr>
                    </tbody>
                </table>

            </div>


        </div>
    </div>

</div>

@endsection
@push('page_scripts')
    <script src="{{asset("plugins/toastr/toastr.min.js")}}"></script>
    <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>

    <script type="text/javascript">
        $('.date-input').datepicker()
    </script>
    <script>
        const calendarEl = document.getElementById('session-calendar');
        let calendar;
        $(document).ready(function () {
             calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                selectable:true,
                lazyFetching:true,
                events: "{{route('sessions.index')}}",
                eventClick: function (calEvent) {
                    var sessionId = calEvent.event.id;
                    $.ajax({
                        url: `/sessions/${sessionId}`,
                        type: 'GET',
                        success: function (response) {
                            console.log(response)
                            console.log(response.session_completion_code)
                            $('#scheduled-date-text').empty()
                            $('#session-location-text').empty()
                            $('#student-text').empty()
                            $('#attended-sessions-manual-text').empty()
                            $('#total-session-time-charged-text').empty()
                            $('#session-completion-code-text').empty()
                            $('#pre-session-note-text').empty()
                            $('#student-session-notes-text').empty()
                            $('#tutor-internal-note-text').empty()
                            $('#homework-completed-text').empty()
                            $('#homework-text').empty()
                            document.getElementById("scheduled-date-text").innerHTML = response.scheduled_date + ' ' + response.start_time + ' - ' + response.end_time;
                            document.getElementById("session-location-text").innerHTML = response.tutoring_location_name;
                            document.getElementById("student-text").innerHTML = response.student_name;
                            document.getElementById('attended-sessions-manual-text').innerHTML = '';
                            document.getElementById('total-session-time-charged-text').innerHTML = '';
                            document.getElementById('session-completion-code-text').innerHTML = response.session_completion_code;
                            // document.getElementById('pre-session-note-text').innerHTML = response.pre_session_note;
                            document.getElementById('student-session-notes-text').innerHTML = response.student_parent_session_notes;
                            document.getElementById('tutor-internal-note-text').innerHTML = response.internal_notes;
                            document.getElementById('homework-completed-text').innerHTML = response.percent_homework_completed_80;
                            document.getElementById('homework-text').innerHTML = response.homework;
                            $('#session-show').modal('show')
                        },
                        error: function (xhr, status, error) {
                            toastr.error("something went wrong");
                        }
                    });

                },
                dateClick: function (start,end,allDays) {
                    $('#session-store').modal('show');
                    $('#scheduled_date').datepicker('setDate', new Date(start.dateStr));

                }
            });
            calendar.render();
        });


        $('#session-form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{route('sessions.store')}}',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    console.log(response)
                    $('#session-store').modal('hide');
                    toastr.success(response.message);
                    calendar.refetchEvents()
                },
                error: function (xhr, status, error) {
                    toastr.error("something went wrong");
                }
            });
        });

        $(document).ready(function () {

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

            $("#student-tutoring-package-id").select2({
                dropdownAutoWidth: true, width: 'auto',
                dropdownParent: $('#session-store'),
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
    </script>
@endpush

