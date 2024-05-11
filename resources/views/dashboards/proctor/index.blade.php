@extends('layouts.app')
@push('page_css')
    <style>
        .feedback-emojis {
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
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Proctor Dashboard</h3>
                    </div>
                    <div id="mock-test-calendar">
                    </div>
                </div>
            </div>
        </div>
        @permission('mock_test-create')
            @include('dashboards.proctor.mock-test-store')
        @endpermission
        @include('dashboards.proctor.mock-test-show')
    </div>
@endsection
@push('page_scripts')

    <script>
        const calendarEl = document.getElementById('mock-test-calendar');
        let calendar;
        $(document).ready(function () {
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                selectable: true,
                lazyFetching: true,
                events: "{{route('mock-tests.index')}}",
                eventClick: function (calEvent) {
                    var mockTestId = calEvent.event.id;
                    $.ajax({
                        url: `/mock-tests/${mockTestId}`,
                        type: 'GET',
                        success: function (response) {
                            $('#location-text').empty()
                            $('#date-text').empty()
                            $('#proctor-text').empty()
                            $('#created-by-text').empty()
                            document.getElementById("location-text").innerHTML = response.location;
                            document.getElementById("date-text").innerHTML = response.scheduled_date;
                            document.getElementById("proctor-text").innerHTML = response.proctor;
                            document.getElementById("created-by-text").innerHTML = response.created_by;
                            $('#mock-test-show').modal('show')
                        },
                        error: function (xhr, status, error) {
                            toastr.error("something went wrong");
                        }
                    });

                },
                dateClick: function (start, end, allDays) {
                    $('#mock-test-store').modal('show');
                    $('#date').datepicker('setDate', new Date(start.dateStr));

                }
            });
            calendar.render();
        });
        @permission('mock_test-create')
        $('#mock-test-form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{route('mock-tests.store')}}',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    $('#mock-test-store').modal('hide');
                    toastr.success(response.message);
                    calendar.refetchEvents()
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function (key, item) {
                            toastr.error(item[0]);
                        });
                    } else if (xhr.status === 404) {
                        let response = xhr.responseJSON
                        toastr.error(response.message);
                    } else {
                        toastr.error("something went wrong");
                    }
                }
            });
        });
        @endpermission
    </script>
{{--    @include('sessions.includes.tutors_select2js',['strict'=>true])--}}
@endpush

