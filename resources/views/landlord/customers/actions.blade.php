{!! Form::open(['route' => ['landlord.customers.destroy', $customer->id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('landlord.customers.show', [$customer->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-eye"></i>
    </a>

    <a href="{{ route('landlord.customers.edit', [$customer->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-edit"></i>
    </a>
    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
</div>
{!! Form::close() !!}
