@push('page_css')
    <link  rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link  rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link  rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
<div class="card-body">
    <div class="table-responsive dataTables_wrapper dt-bootstrap4">
        <table class="table" id="parents-table">
            <thead>
            <tr>
                <th>Family Code</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
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
    {{--    <script src="../../plugins/jszip/jszip.min.js"></script>--}}
    {{--    <script src="../../plugins/pdfmake/pdfmake.min.js"></script>--}}
    {{--    <script src="../../plugins/pdfmake/vfs_fonts.js"></script>--}}
    {{--    <script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>--}}
    {{--    <script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>--}}
    {{--    <script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>--}}
    <script>
        $(document).ready(function() {

            $('#parents-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('parents.index') }}",
                    dataType: "json",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                    }
                },
                columns: [
                    { data: 'family_code', name: 'family_code', orderable: true },
                    { data: 'email', name: 'email', orderable: false },
                    { data: 'first_name', name: 'first_name', orderable: false },
                    { data: 'last_name', name: 'last_name', orderable: false },
                    { data: 'phone', name: 'phone', orderable: false },
                    { data: 'status', name: 'status', orderable: true },
                    { data: 'action', name: 'action', orderable: false },

                ],
                order: [[0, 'desc']]
            });

        });
    </script>
@endpush
