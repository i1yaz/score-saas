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
                {!! Form::text('subject', null, ['class' => 'form-control','id'=>'subject']) !!}
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
                    <div class="list-group">
                        @foreach($variables as $variable)
                            <button type="button" onclick="addPlaceholder('{{$variable}}')" class="list-group-item list-group-item-action">{{ucfirst(str_replace('_',' ',$variable))}}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('plugins/summernote/summernote.js')}}"></script>
    <script>
        var previousFocus = '';
        function addPlaceholder(placeholder) {
            placeholder = `@{{${placeholder}}}`
            let focusedInput = $(`#${previousFocus}`)
            if (focusedInput.attr('id') === 'html-template' || focusedInput.attr('id') === undefined) {
                $target = focusedInput.summernote ? focusedInput.summernote() : focusedInput;
                let currentValue = $target.summernote ? $target.summernote('code') : $target.html();
                appendedValue = currentValue ? currentValue + ' ' + placeholder : placeholder;
                $target.summernote ? $target.summernote('code', appendedValue) : $target.html(appendedValue);

            } else if (focusedInput.attr('id') === 'subject') {
                $(`#${previousFocus}`).val($(`#${previousFocus}`).val()+' '+ placeholder);
            }
        }

        $(document).ready(function() {
            $('#html-template').summernote({
                height: 500,
                callbacks: {
                    onFocus: function() {
                        previousFocus = this.id;
                    }
                }
            });
        });

        $('#subject').on('focus',function (e){
            e.preventDefault()
            previousFocus = this.id;
        })
    </script>
@endpush
