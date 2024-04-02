<div class="card-body">
    <div class="table-responsive  dataTables_wrapper dt-bootstrap4">
        <table class="table" id="customer-table">
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
        </table>
    </div>
</div>

@push('page_scripts')

    <script>
        $(document).ready(function() {

            $('#customer').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('landlord.customers.index') }}",
                    dataType: "json",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id', orderable: true },
                    { data: 'name', name: 'name', orderable: false },
                    { data: 'created_at', name: 'created_at', orderable: false },
                    { data: 'account', name: 'account', orderable: false },
                    { data: 'plan', name: 'plan', orderable: false },
                    { data: 'type', name: 'type', orderable: false },
                    { data: 'status', name: 'status', orderable: false },
                    { data: 'action', name: 'action', orderable: false },
                ],
                order: [[0, 'desc']]
            });

        });
    </script>
@endpush
