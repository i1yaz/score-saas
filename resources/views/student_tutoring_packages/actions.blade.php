{!! Form::open(['route' => ['student-tutoring-packages.destroy', $studentTutoringPackage->id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @permission('student_tutoring_package-show')
    <a href="{{ route('student-tutoring-packages.show', [$studentTutoringPackage->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-eye"></i>
    </a>
    @endpermission
    @permission('student_tutoring_package-edit')
    <a href="{{ route('student-tutoring-packages.edit', [$studentTutoringPackage->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-edit"></i>
    </a>
    @endpermission
    @permission('student_tutoring_package-destroy')
    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
    @endpermission
</div>
{!! Form::close() !!}
