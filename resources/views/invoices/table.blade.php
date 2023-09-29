@push('page_css')
    <link  rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link  rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link  rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
<div class="card-body">
    <div class="table-responsive">
        <table class="table" id="invoices-table">
            <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Invoice Status</th>
                <th>Invoice Type</th>
                <th>Student</th>
                <th>Parent</th>
                <th>Created At</th>
                <th>Due Date</th>
                <th>Invoice Total</th>
                <th>Amount Paid</th>
                <th>Amount Remaining</th>
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

    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
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
                    { data: 'invoice_status', name: 'invoice_status', orderable: true },
                    { data: 'invoice_type', name: 'invoice_type', orderable: true },
                    { data: 'student', name: 'student', orderable: true },
                    { data: 'parent', name: 'parent', orderable: true },
                    { data: 'created_at', name: 'created_at', orderable: true },
                    { data: 'due_date', name: 'due_date', orderable: true },
                    { data: 'invoice_total', name: 'invoice_total', orderable: true },
                    { data: 'amount_paid', name: 'amount_paid', orderable: true },
                    { data: 'amount_remaining', name: 'amount_remaining', orderable: true },
                    { data: 'fully_paid_at', name: 'fully_paid_at', orderable: true },
                    { data: 'action', name: 'action', orderable: false },
                ],
                order: [[0, 'desc']]
            });
        });
    </script>
@endpush
