@push('page_css')
    <link  rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link  rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link  rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
<div class="card-body">
    <div class="table-responsive dataTables_wrapper dt-bootstrap4">
        <table class="table" id="student-tutoring-packages-table">
            <thead>
            <tr>
                <th>Package ID</th>
                <th>Student</th>
                <th>Tutoring Package Type</th>
                <th>Notes</th>
                <th>Hours</th>
                <th>Tutoring Location</th>
                <th>Start Date</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>

</div>

@push('page_scripts')
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function() {

            $('#student-tutoring-packages-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('student-tutoring-packages.index') }}",
                    dataType: "json",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                    }
                },
                columns: [
                    { data: 'package_id', name: 'package_id', orderable: true },
                    { data: 'student', name: 'student', orderable: true },
                    { data: 'tutoring_package_type', name: 'tutoring_package_type', orderable: false },
                    { data: 'notes', name: 'notes', orderable: false },
                    { data: 'hours', name: 'hours', orderable: true },
                    { data: 'location', name: 'location', orderable: false },
                    { data: 'start_date', name: 'start_date', orderable: true },
                    { data: 'action', name: 'action', orderable: false },

                ],
                order: [[0, 'desc']]
            });

        });
    </script>
@endpush
