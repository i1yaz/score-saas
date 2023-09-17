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
