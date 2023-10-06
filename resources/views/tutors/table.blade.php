<div class="card-body">
    <div class="table-responsive">
        <table class="table" id="tutors-table">
            <thead>
            <tr>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#tutors-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('tutors.index') }}",
                    dataType: "json",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                    }
                },
                columns: [
                    { data: 'email', name: 'email', orderable: false },
                    { data: 'first_name', name: 'first_name', orderable: false },
                    { data: 'last_name', name: 'last_name', orderable: false },
                    { data: 'phone', name: 'last_name', orderable: false },
                    { data: 'status', name: 'status', orderable: true },
                    { data: 'start_date', name: 'start_date', orderable: true },
                    { data: 'action', name: 'action', orderable: false },

                ],
                order: [[0, 'desc']]
            });

        });
    </script>
@endpush
