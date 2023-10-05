@push('page_css')
    <link  rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link  rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link  rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
<div class="card-body">
    <div class="table-responsive  dataTables_wrapper dt-bootstrap4">
        <table class="table" id="sessions-table">
            <thead>
            <tr>
                <th>Session ID</th>
                <th>Tutoring Package</th>
                <th>Scheduled Date</th>
                <th>Location</th>
                <th>Tutor</th>
                <th>Student</th>
                <th>Completion Code</th>
                <th>80% homework completed</th>
                <th>Action</th>
            </tr>
        </table>
    </div>
</div>

@push('page_scripts')
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function() {

            $('#sessions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('sessions.index') }}",
                    dataType: "json",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id', orderable: true },
                    { data: 'student_tutoring_package', name: 'student_tutoring_package', orderable: true },
                    { data: 'scheduled_date', name: 'scheduled_date', orderable: false },
                    { data: 'location', name: 'location', orderable: true },
                    { data: 'tutor', name: 'student', orderable: true },
                    { data: 'student', name: 'student', orderable: true },
                    { data: 'completion_code', name: 'completion_code', orderable: false },
                    { data: 'homework_completed_80', name: 'homework_completed_80', orderable: false },
                    { data: 'action', name: 'action', orderable: false },

                ],
                order: [[0, 'desc']]
            });

        });
    </script>
@endpush
