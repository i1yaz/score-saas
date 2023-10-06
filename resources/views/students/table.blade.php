<div class="card-body">
    <div class="table-responsive dataTables_wrapper dt-bootstrap4">
        <table class="table" id="students-table">
            <thead>
            <tr>
                <th>Family Code</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {

            $('#students-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('students.index') }}",
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
                    { data: 'status', name: 'status', orderable: true },
                    { data: 'action', name: 'action', orderable: false },

                ],
                order: [[0, 'desc']]
            });

        });
    </script>
@endpush
