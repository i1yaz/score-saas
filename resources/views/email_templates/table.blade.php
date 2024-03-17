<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="templates-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Subject</th>
                <th>Active</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($templates as $template)
                <tr>
                    <td>{{ $template->name }}</td>
                    <td>{{ $template->description }}</td>
                    <td>@include('partials.status_badge',['status' => $template->status,'text_success'=>'Yes','text_danger' => 'No'])</td>
                    <td  style="width: 120px">
                        <div class='btn-group'>
                            @permission('email_templates-show')
                            <a href="{{ route('email-templates.show', [$template->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('email_templates-send')
                            <a href="#" data-toggle="modal" data-target="#modal-email-{{$template->id}}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-envelope"></i>
                            </a>
                            <div class="modal fade" id="modal-email-{{$template->id}}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Test Email</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <form class="test-email" id="test-email-{{$template->id}}" action="{{route('email-templates.send',[$template->id])}}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="col-sm-12">
                                                    {!! Form::label('email', 'Email:') !!}
                                                    {!! Form::text('email', null, ['class' => 'form-control','id'=>'email']) !!}
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Send Test Email</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endpermission
                            @permission('email_templates-edit')
                            <a href="{{ route('email-templates.edit', [$template->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                        </div>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $templates])
        </div>
    </div>
    @push('page_scripts')
        <script>
            $(function () {
                $('.test-email').on('submit', function (e) {
                    e.preventDefault();
                    var elementId = this.id;
                    console.log(elementId)
                    $.ajax({
                        type: 'post',
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        success: function () {
                            toastr.success('Email Sent Successfully');
                        },
                        error: function () {
                            toastr.error('Email Failed to Send');
                        }
                    });
                    $('#test-email').modal('hide');
                });
            });
        </script>

    @endpush

</div>
