<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="parents-table">
            <thead>
            <tr>
                <th>User Id</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Status</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Address2</th>
                <th>Phone Alternate</th>
                <th>Referral Source</th>
                <th>Added By</th>
                <th>Added On</th>
                <th>Referral From Positive Experience With Tutor</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($parents as $parent)
                <tr>
                    <td>{{ $parent->user_id }}</td>
                    <td>{{ $parent->first_name }}</td>
                    <td>{{ $parent->last_name }}</td>
                    <td>{{ $parent->status }}</td>
                    <td>{{ $parent->phone }}</td>
                    <td>{{ $parent->address }}</td>
                    <td>{{ $parent->address2 }}</td>
                    <td>{{ $parent->phone_alternate }}</td>
                    <td>{{ $parent->referral_source }}</td>
                    <td>{{ $parent->added_by }}</td>
                    <td>{{ $parent->added_on }}</td>
                    <td>{{ $parent->referral_from_positive_experience_with_tutor }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['parents.destroy', $parent->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('parents.show', [$parent->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('parents.edit', [$parent->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $parents])
        </div>
    </div>
</div>
