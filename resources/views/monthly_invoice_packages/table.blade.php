<div class="card-body">
    <div class="table-responsive">
        <table class="table" id="monthly-invoice-packages-table">
            <thead>
            <tr>
                <th>Package ID</th>
                <th>Student</th>
                <th>Notes</th>
                <th>Internal Notes</th>
                <th>Start Date</th>
                <th>Tutoring Location</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#monthly-invoice-packages-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('monthly-invoice-packages.index') }}",
                    dataType: "json",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                    }
                },
                columns: [
                    { data: 'package_id', name: 'package_id', orderable: true },
                    { data: 'student', name: 'student', orderable: true },
                    { data: 'notes', name: 'notes', orderable: true },
                    { data: 'internal_notes', name: 'internal_notes', orderable: true },
                    { data: 'start_date', name: 'start_date', orderable: true },
                    { data: 'tutoring_location_id', name: 'tutoring_location_id', orderable: true },
                    { data: 'action', name: 'action', orderable: false },
                ],
                order: [[0, 'desc']]
            });
        });
    </script>
@endpush
