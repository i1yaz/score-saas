{!! Form::open(['route' => ['landlord.customer.destroy', $customer->id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('students.create', ['customer' => $customer->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="fas fa-plus"></i>
    </a>

    <a href="{{ route('landlord.customer.show', [$customer->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-eye"></i>
    </a>

    <a href="{{ route('landlord.customer.edit', [$customer->id]) }}"
       class='btn btn-default btn-sm'>
        <i class="far fa-edit"></i>
    </a>
    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
</div>
{!! Form::close() !!}
