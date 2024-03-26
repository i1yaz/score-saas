<div class="card-body">
    <div class="table-responsive dataTables_wrapper dt-bootstrap4">
        <table class="table" id="landlord-customers-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created</th>
                <th>Account</th>
                <th>Plan</th>
                <th>Type</th>
                <th>Status</th>
                <th colspan="3">Action</th>
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
            $('#landlord-customers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('landlord.customer.index') }}",
                    dataType: "json",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id',searchable: true, orderable: true},
                    { data: 'name', name: 'name', searchable: true, orderable: true},
                    { data: 'created_at', name: 'created_at', searchable: true, orderable: true},
                    { data: 'account_url', name: 'account_url', searchable: true, orderable: true},
                    { data: 'plan', name: 'plan' , searchable: true, orderable: true},
                    { data: 'type', name: 'type' , searchable: true, orderable: true},
                    { data: 'status', name: 'status' , searchable: true, orderable: true},
                    { data: 'action', name: 'action', searchable: false, orderable: false}
                ],
                order: [[0, 'desc']]
            });
        });
    </script>
@endpush
