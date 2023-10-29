<div class="card-body">
    <div class="table-responsive">
        <table class="table" id="invoices-table">
            <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Package</th>
                <th>Invoice Status</th>
                <th>Invoice Type</th>
{{--                <th>Student</th>--}}
{{--                <th>Parent</th>--}}
                <th>Created At</th>
                <th>Due Date</th>
                <th>Amount Paid</th>
                <th>Fully Paid At</th>
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
            $('#invoices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('invoices.index') }}",
                    dataType: "json",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                    }
                },
                columns: [
                    { data: 'invoice_id', name: 'invoice_id', orderable: true },
                    { data: 'package', name: 'package', orderable: false },
                    { data: 'invoice_status', name: 'invoice_status', orderable: true },
                    { data: 'invoice_type', name: 'invoice_type', orderable: true },
                    // { data: 'student', name: 'student', orderable: true },
                    // { data: 'parent', name: 'parent', orderable: true },
                    { data: 'created_at', name: 'created_at', orderable: true },
                    { data: 'due_date', name: 'due_date', orderable: true },
                    { data: 'amount_paid', name: 'amount_paid', orderable: true },
                    { data: 'fully_paid_at', name: 'fully_paid_at', orderable: true },
                    { data: 'action', name: 'action', orderable: false },
                ],
                order: [[0, 'desc']]
            });
        });
    </script>
@endpush
