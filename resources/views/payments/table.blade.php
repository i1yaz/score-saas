
<div class="card-body">
    <div class="table-responsive  dataTables_wrapper dt-bootstrap4">
        <table class="table" id="payments-table">
            <thead>
            <tr>
                <th>Invoice Code</th>
                <th>Invoice Type</th>
                <th>Package Code</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Payment Gateway</th>
            </tr>
        </table>
    </div>
</div>

@push('page_scripts')

    <script>
        $(document).ready(function() {

            $('#payments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('payments.index') }}",
                    dataType: "json",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                    }
                },
                columns: [
                    { data: 'invoice_code', name: 'invoice_code', orderable: false },
                    { data: 'invoice_type', name: 'invoice_type', orderable: false },
                    { data: 'package_code', name: 'package_code', orderable: false },
                    { data: 'amount', name: 'amount', orderable: false },
                    { data: 'date', name: 'date', orderable: false },
                    { data: 'payment_gateway', name: 'payment_gateway', orderable: false },
                ],
                order: [[0, 'desc']]
            });

        });
    </script>
@endpush
