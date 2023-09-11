<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="parents-table">
            <thead>
            <tr>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Status</th>
                <th>Phone</th>
                <th>Added By</th>
                <th>Added On</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($parents as $parent)
                <tr>
                    <td>{{ $parent->email }}</td>
                    <td>{{ $parent->first_name }}</td>
                    <td>{{ $parent->last_name }}</td>
                    <td>@include('partials.status_badge',['status' => $parent->status,'text_success' => 'Active','text_danger' => 'Inactive'])</td>
                    <td>{{ $parent->phone }}</td>
                    <td>{{ $parent->created_by_email }}</td>
                    <td>{{ $parent->added_on }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['parents.destroy', $parent->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('student-create')
                            <a href="{{ route('students.create', ['parent' => $parent->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="fas fa-plus"></i>
                            </a>
                            @endpermission
                            @permission('parent-show')
                            <a href="{{ route('parents.show', [$parent->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('parent-edit')
                            <a href="{{ route('parents.edit', [$parent->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('parent-destroy')
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            @endpermission
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
