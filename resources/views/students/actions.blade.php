{!! Form::open(['route' => ['students.destroy', $student->id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @permission('student-show')
    <a href="{{ route('students.show', [$student->id]) }}"
       class='btn btn-default btn-xs'>
        <i class="far fa-eye"></i>
    </a>
    @endpermission
    @permission('student-edit')
    <a href="{{ route('students.edit', [$student->id]) }}"
       class='btn btn-default btn-xs'>
        <i class="far fa-edit"></i>
    </a>
    @endpermission
    @permission('student-destroy')
    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
    @endpermission
</div>
{!! Form::close() !!}
