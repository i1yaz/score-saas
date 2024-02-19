
<div class='btn-group float-right'>
    @if($invoice->invoiceable_type===\App\Models\StudentTutoringPackage::class && $invoice->has_installments==false)
        @permission('invoice-installments')
        <a href="#" data-toggle="modal" data-target="#create-installments-{{$invoice->id}}" class='btn btn-default'>
            <i class="fa fa-bars" aria-hidden="true"></i>
            Installments
        </a>

        <div class="modal fade" id="create-installments-{{$invoice->id}}" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create Installments</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('invoices.create-installments', [$invoice->invoice_id]) }}" method="get" id="create-installments-{{$invoice->invoice_id}}">
                        @csrf
                        <div class="modal-body">
                            <!-- Amount Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::label('amount', 'Invoice Amount:') !!}
                                {!! Form::text('amount', $remainingAmount??0, ['class' => 'form-control','readonly'=>'readonly']) !!}
                            </div>
                            <div class="form-group col-sm-12">
                                {!! Form::label('installments', 'Total installments:') !!}
                                {!! Form::number('installments', 4, ['class' => 'form-control',]) !!}
                            </div>
                            <div class="form-group col-sm-12">
                                {!! Form::label('due_date', 'Due Date:') !!}
                                <small  class="text-danger">Of each month.</small>
                                {!! Form::select('due_date', array_combine(range(1,30),range(1,30)),10, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary create-installments">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endpermission
    @endif
    @permission('invoice-pay')
    <a href="{{ route('invoices.pay', [$invoice->invoice_id,$type]) }}"
       class='btn btn-default'>
        <i class="fas fa-money-bill-wave" ></i>
    </a>
    @endpermission
    @permission('invoice-show')
    <a href="{{ route('invoices.show', [$invoice->invoice_id,$type]) }}"
       class='btn btn-default '>
        <i class="far fa-eye"></i>
    </a>
    @endpermission
    @permission('invoice-edit')
{{--    <a href="{{ route('invoices.edit', [$invoice->invoice_id]) }}"--}}
{{--       class='btn btn-default'>--}}
{{--        <i class="far fa-edit"></i>--}}
{{--    </a>--}}
    @endpermission
    @permission('invoice-destroy')
        {!! Form::open(['route' => ['invoices.destroy', $invoice->invoice_id], 'method' => 'delete']) !!}
        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-', 'onclick' => "return confirm('Are you sure?')"]) !!}
        {!! Form::close() !!}
    @endpermission
</div>

