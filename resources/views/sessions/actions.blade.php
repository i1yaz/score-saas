{!! Form::open(['route' => ['sessions.destroy', $session->id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @permission('session-show')
    <a href="{{ route('sessions.show', [$session->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-eye"></i>
    </a>
    @endpermission
    @permission('session-edit')
    <a href="{{ route('sessions.edit', [$session->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-edit"></i>
    </a>
    @endpermission
    @permission('session-destroy')
    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
    @endpermission
</div>
{!! Form::close() !!}
