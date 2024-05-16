<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="subscriptions-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Created</th>
                <th>Amount</th>
                <th>Renewed</th>
                <th>Cycle</th>
                <th>Status</th>
                <th>Gateway</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription->id }}</td>
                    <td>{{ $subscription->first_name }} {{ $subscription->last_name }}</td>
                    <td>{{ $subscription->created_at }}</td>
                    <td>{{ $subscription->amount }}</td>
                    <td>{{ $subscription->date_renewed }}</td>
                    <td>{{ $subscription->gateway_billing_cycle }}</td>
                    <td><span class="badge {{runtimeSubscriptionStatusColors($subscription->status)}}">{{runtimeSubscriptionStatusLang($subscription->status)}}</span></td>
                    <td>{{ $subscription->gateway_name }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['taxes.destroy', $subscription->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('taxes.show', [$subscription->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('taxes.edit', [$subscription->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $subscriptions])
        </div>
    </div>
</div>
