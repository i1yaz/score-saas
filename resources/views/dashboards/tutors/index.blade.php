@extends('layouts.app')
@push('page_css')
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
    <style>
        .feedback-emojis{
            width: 30px;
            height: 30px;
        }
    </style>
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
<!-- Example modal markup -->
<div class="modal fade" id="session-store" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Tutoring Session Log Click on Calendar</h5>
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
                        <div class="row">
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
                                {!! Form::select('session_completion_code', [], null, ['class' => 'form-control ','id'=>'session-completion-code']) !!}
                            </div>
                            <div class="form-group col-sm-12">
                                {!! Form::label('completion_code', 'How was your session ? (The student will not see this):') !!}
                                <div class="radio">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="how_was_session" id="how-was-session-1" value="1">
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
                                        <input class="form-check-input" type="radio" name="practice-test-for-homework-yes" id="practice-test-for-homework-yes" value="yes" checked>
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
@endsection
@push('page_scripts')
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('plugins/jquery-ui/jquery-ui.min.css')}}">
    <script type="text/javascript">
        $('.date-input').datepicker()
    </script>
    <script>
        $(document).ready(function () {
            var calendarEl = document.getElementById('session-calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                selectable:true,
                selectHelper:true,
                lazyFetching:true,
                events: "{{route('sessions.index')}}",
                eventClick: function (start,end,allDays) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    $('#session-store').modal('show');
                },
                dateClick: function (info) {
                    $('#session-store').modal('show');
                    $('#session-form').find('input[name="start"]').val(info.dateStr);
                    $('#session-form').find('input[name="end"]').val(info.dateStr);
                }
            });
            calendar.render();
        });

        $('#session-form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '/add-event', // Replace with your Laravel route
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    // Handle the response from the server (e.g., update the FullCalendar)
                    $('#eventModal').modal('hide'); // Close the modal
                },
                error: function (xhr, status, error) {
                    // Handle any errors that occur during the AJAX request
                }
            });
        });
    </script>
@endpush

