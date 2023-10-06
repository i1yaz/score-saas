<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="tutoring-locations-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tutoringLocations as $tutoringLocation)
                <tr>
                    <td>{{ $tutoringLocation->name }}</td>
                    <td>@include('partials.status_badge',['status' => $tutoringLocation->status,'text_success' => 'Active','text_danger' => 'Inactive'])</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['tutoring-locations.destroy', $tutoringLocation->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('tutoring_location-show')
                            <a href="{{ route('tutoring-locations.show', [$tutoringLocation->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('tutoring_location-edit')
                            <a href="{{ route('tutoring-locations.edit', [$tutoringLocation->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('tutoring_location-destroy')
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
            @include('adminlte-templates::common.paginate', ['records' => $tutoringLocations])
        </div>
    </div>
</div>
