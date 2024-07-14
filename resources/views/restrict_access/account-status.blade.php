@extends('restrict_access.app')

@section('content')
    <section class="content-header">

    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        {{-- <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"></h3>
            </div>
            <div class="card-body p-0">
                You account is {{ $status }}. Please contact the administrator for more information.
            </div>

        </div> --}}
        <div class="error-page">
            <h2 class="headline text-warning"> 403</h2>
            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! {{ $title }}</h3>
                <p>
                    {{ $message }}
                </p>

            </div>

        </div>
    </div>
@endsection
