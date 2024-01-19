@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"></h1>
                </div>

            </div>
        </div>
    </div>
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-danger">500</h2>
            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Something went wrong.</h3>
                <p>
                    We will work on fixing that right away.
                </p>
            </div>
        </div>

    </section>
@endsection
