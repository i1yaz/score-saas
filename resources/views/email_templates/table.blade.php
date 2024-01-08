<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="templates-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Subject</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($templates as $template)
                <tr>
                    <td>{{ $template->name }}</td>
                    <td>{{ $template->description }}</td>
                    <td  style="width: 120px">
                        <div class='btn-group'>
                            @permission('email_templates-show')
                            <a href="{{ route('email-templates.show', [$template->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('email_templates-edit')
                            <a href="{{ route('email-templates.edit', [$template->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                        </div>
                        {!! Form::close() !!}
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
</div>
