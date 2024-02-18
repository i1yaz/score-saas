@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>

                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    Create Installments
                </h3>
            </div>
{{--            {!! Form::open(['route' => ['invoices.store-installments','invoice'=>$invoiceId]]) !!}--}}

            {!! Form::model( ['route' => ['invoices.store-installments', $invoiceId], 'method' => 'post']) !!}
            <input type="hidden" name="due_date" value="{{$due_date}}" >
            <input type="hidden" name="installments" value="{{$installmentsCount}}" >
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Sr#</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($installments as $installment)
                        <tr>
                            <td>{{$installment->month}}</td>
                            <td>{{$installment->dueDate}}</td>
                            <td>{{$installment->amountToBePaid}}</td>
                            <td>{{$installment->closingBalance}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary','id'=>'store-installments']) !!}
                <a href="{{ route('invoices.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
