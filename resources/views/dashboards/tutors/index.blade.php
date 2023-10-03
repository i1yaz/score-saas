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
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Dashboard</h3>
                </div>
                <div id="session-calendar">
                </div>
            </div>
        </div>
    </div>
    @include('dashboards.tutors.store')
    @include('dashboards.tutors.show')
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
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function (key, item) {
                            toastr.error(item[0]);
                        });
                    } else {
                        toastr.error("something went wrong");
                    }
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
    @include('sessions.includes.tutors_select2js')
@endpush

