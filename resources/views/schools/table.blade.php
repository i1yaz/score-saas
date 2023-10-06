<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="schools-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($schools as $school)
                <tr>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->address }}</td>
                    <td>@include('partials.status_badge',['status' => $school->status,'text_success'=>'Active','text_danger' => 'Inactive'])</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['schools.destroy', $school->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('school-show')
                            <a href="{{ route('schools.show', [$school->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('school-edit')
                            <a href="{{ route('schools.edit', [$school->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('school-destroy')
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
            @include('adminlte-templates::common.paginate', ['records' => $schools])
        </div>
    </div>
</div>
