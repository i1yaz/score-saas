{{--{!! Form::open(['route' => ['payments.destroy', $payment->id], 'method' => 'delete']) !!}--}}
<div class='btn-group'>
    @permission('payment-show')
    <a href="{{ route('payments.show', [$payment->id]) }}"
       class='btn btn-default '>
        <i class="far fa-eye"></i>
    </a>
    @endpermission
{{--    @permission('payment-edit')--}}
{{--    <a href="{{ route('payments.edit', [$payment->id]) }}"--}}
{{--       class='btn btn-default '>--}}
{{--        <i class="far fa-edit"></i>--}}
{{--    </a>--}}
{{--    @endpermission--}}
{{--    @permission('payment-destroy')--}}
{{--    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger ', 'onclick' => "return confirm('Are you sure?')"]) !!}--}}
{{--    @endpermission--}}
</div>
{{--{!! Form::close() !!}--}}
