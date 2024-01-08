@push('page_css')
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
@endpush
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-sm-10">
            <div class="col-sm-12">
                {!! Form::label('subject', 'Subject:') !!}
                {!! Form::text('subject', null, ['class' => 'form-control']) !!}
            </div>
            <div class="col-sm-12">
                {!! Form::label('html_template', 'Email Body:') !!}
                {!! Form::textarea('html_template', null, ['class' => 'form-control','id' => 'html-template']) !!}
            </div>
        </div>

        <div class="col-sm-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Placeholders</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            @foreach($variables as $variable)
                                <a href="#" class="nav-link" data-variable="{{$variable}}">
                                    {{str_replace('_',' ',$variable)}}
                                </a>
                            @endforeach
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('plugins/summernote/summernote.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#html-template').summernote({
                height: 500
            });
        });
    </script>
@endpush
