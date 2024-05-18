{!! Form::model(['route' => ['landlord.settings.email-templates.update',$template->id], 'method' => 'post' ]) !!}

<div class="card-body">
    <div class="row">
        <div class="form-group col-sm-12">
            {!! Form::label('subject', 'Subject:') !!}
            {!! Form::text('subject', $template->subject, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-12">
            {!! Form::label('body', 'Body:') !!}
            {!! Form::textarea('body', $template->subject, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="card-footer">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}
