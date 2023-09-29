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
                <th>Scheduled Date</th>
                <th>Location</th>
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
                    { data: 'scheduled_date', name: 'package_id', orderable: false },
                    { data: 'location', name: 'student', orderable: true },
                    { data: 'student', name: 'tutoring_package_type', orderable: true },
                    { data: 'completion_code', name: 'notes', orderable: false },
                    { data: 'homework_completed_80', name: 'hours', orderable: false },
                    { data: 'action', name: 'action', orderable: false },

                ],
                order: [[0, 'desc']]
            });

        });
    </script>
@endpush
