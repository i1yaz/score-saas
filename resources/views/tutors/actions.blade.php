{!! Form::open(['route' => ['tutors.destroy', $tutor->id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @permission('tutor-show')
    <a href="{{ route('tutors.show', [$tutor->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-eye"></i>
    </a>
    @endpermission
    @permission('tutor-edit')
    <a href="{{ route('tutors.edit', [$tutor->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-edit"></i>
    </a>
    @endpermission
    @permission('tutor-destroy')
    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
    @endpermission
</div>
{!! Form::close() !!}
