@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>

                    </h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('invoices.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Invoice Pay
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Id Field -->
                    <div class="col-sm-12 col-md-6">
                        {!! Form::label('final_amount', 'Payable Amount:') !!}
                        {!! Form::input('text','final_amount',$invoice->final_amount,['readonly'=>'readonly','class' => 'form-control']) !!}
                    </div>
                    <div class="col-sm-12 col-md-6">
                        {!! Form::label('final_amount', 'Payable Amount:') !!}
                        {!! Form::input('text','final_amount',$invoice->final_amount,['readonly'=>'readonly','class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
