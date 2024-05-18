{!! Form::model(['route' => ['landlord.settings.email-templates.update',$template->id], 'method' => 'post' ]) !!}

<div class="card-body">
    <div class="row">
        <div class="form-group col-sm-12">
            {!! Form::label('subject', 'Subject:') !!}
            {!! Form::text('subject', $template->subject, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-12">
            {!! Form::label('enabled', 'Enabled:') !!}
            {!! Form::select('enabled', ['enabled' => 'Enabled', 'disabled' => 'Disable'], $template->status, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-12">
            {!! Form::label('body', 'Body:') !!}
            {!! Form::textarea('body', $template->body, ['class' => 'form-control','id'=>'template-body']) !!}
        </div>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="form-group  col-sm-12 col-md-6">
                            <h4>{{ cleanLang(__('lang.template_variables')) }}</h4>

                            @foreach($variables['template'] as $variable)
                                @if(!empty($variable))
                                    <span  class="list-group-item list-group-item-action">{{$variable}}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <h4>{{ cleanLang(__('lang.general_variables')) }}</h4>
                            @foreach($variables['general'] as $variable)
                                @if(!empty($variable))
                                    <span class="list-group-item list-group-item-action">{{$variable}}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-footer">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}
