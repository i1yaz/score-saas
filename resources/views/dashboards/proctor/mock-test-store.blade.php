<style>
    .flex-child {
        flex: 1 0 0;
    }
</style>
<div class="modal fade" id="mock-test-store" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Mock Test</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="mock-test-form">
                <div class="modal-body">

                    @csrf
                    <div class="row">
                        @include('mock_tests.fields')
                    </div>

                </div>
                <div class="card-footer">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                    <a href="{{ route('mock-tests.index') }}" class="btn btn-default"> Cancel </a>
                </div>
            </form>
        </div>
    </div>
</div>
@push('page_scripts')
    <script>

    </script>
@endpush
