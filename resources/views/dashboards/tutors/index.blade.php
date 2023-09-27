@extends('layouts.app')
@push('page_css')
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Session Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Event input fields go here -->
                <form id="session-form">
                    @csrf
                    <!-- Event input fields go here -->
                    <button type="submit" class="btn btn-primary">Save Event</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('page_scripts')
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            var calendarEl = document.getElementById('session-calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                selectable:true,
                selectHelper:true,
                lazyFetching:true,
                events: "{{route('fullcalender.fetch')}}",
                eventClick: function (start,end,allDays) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    if (info.event.url) {
                        window.open(info.event.url);
                    }
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

