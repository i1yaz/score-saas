@extends('errors.head')

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
            <h2 class="headline text-warning"> 403</h2>
            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Forbidden Page.</h3>
                <p>
                    You don't have access rights.
                </p>
            </div>

        </div>

    </section>
@endsection
