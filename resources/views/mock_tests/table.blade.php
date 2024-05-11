<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="mock-tests-table">
            <thead>
            <tr>
                <th>Date</th>
{{--                <th>Location Id</th>--}}
{{--                <th>Proctor Id</th>--}}
                <th>Start Time</th>
                <th>End Time</th>
                <th>Add Students</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($mockTests as $mockTest)
                <tr>
                    <td>{{ formatDate($mockTest->date) }}</td>
{{--                    <td>{{ $mockTest->location_id }}</td>--}}
{{--                    <td>{{ $mockTest->proctor_id }}</td>--}}
                    <td>{{ $mockTest->start_time }}</td>
                    <td>{{ $mockTest->end_time }}</td>
                    <td>
                        @permission('mock_test-create')
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add-student-to-mocktest-{{$mockTest->id}}">
                            <i class="fas fa-plus"></i>
                        </button>
                        <div class="modal" id="add-student-to-mocktest-{{$mockTest->id}}" role="dialog" aria-labelledby="modal-defaultTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-defaultTitle">Add Student to Mock Test</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Student Field -->
                                        <div class="form-group col-sm-12">
                                            {!! Form::label('student_id', 'Student:',['class' => 'required']) !!}
                                            {!! Form::select('student_id',$selectedStudent??[],null, ['class' => 'form-control students-select2','id'=>'student-id-'.$mockTest->id]) !!}
                                        </div>
                                        <!-- Test Code Field -->
                                        <div class="form-group col-sm-12">
                                            {!! Form::label('mock_test_codes', 'Official Test Code:',['class' => 'required']) !!}
                                            {!! Form::select('mock_test_codes',$testCodeTaken??[],null, ['class' => 'form-control mock-test-codes-select2','id'=>'mock-test-code-id-'.$mockTest->id]) !!}
                                        </div>
                                        <div class="form-group col-sm-12">
                                            {!! Form::label('notes_to_proctor', 'Proctor\'s Notes:') !!}
                                            {!! Form::textarea('notes_to_proctor',null, ['class' => 'form-control','id'=>'notes-to-proctor-'.$mockTest->id]) !!}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="addStudent({{$mockTest->id}})">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--                        <a href="{{ route('mock-tests-add-students', [$mockTest->id]) }}"--}}
                        {{--                           class='btn btn-default '>--}}
                        {{--                            <i class="fas fa-plus"></i>--}}
                        {{--                        </a>--}}
                        @endpermission
                    </td>
{{--                    <td>--}}
{{--                        @permission('mock_test-score')--}}
{{--                        <a href="{{ route('mock-tests-add-score', [$mockTest->id]) }}"--}}
{{--                           class='btn btn-default '>--}}
{{--                            <i class="fas fa-edit"></i>--}}
{{--                        </a>--}}
{{--                        @endpermission--}}
{{--                    </td>--}}
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['mock-tests.destroy', $mockTest->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @permission('mock_test-show')
                            <a href="{{ route('mock-tests.show', [$mockTest->id]) }}"
                               class='btn btn-default'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endpermission
                            @permission('mock_test-edit')
                            <a href="{{ route('mock-tests.edit', [$mockTest->id]) }}"
                               class='btn btn-default'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endpermission
                            @permission('mock_test-destroy')
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
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
            @include('adminlte-templates::common.paginate', ['records' => $mockTests])
        </div>
    </div>
</div>

@push('after_third_party_scripts')
    <script>
        $(document).ready(function () {
            $(".students-select2").select2({
                dropdownAutoWidth: true, width: 'auto',
                theme: 'bootstrap4',
                minimumInputLength: 1,
                ajax: {
                    url: "{{route('mock-test-student-email-ajax')}}",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            email: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.text,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },
                placeholder: "Please type student email",
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            $(".mock-test-codes-select2").select2({
                dropdownAutoWidth: true, width: 'auto',
                theme: 'bootstrap4',
                minimumInputLength: 1,
                ajax: {
                    url: "{{route('mock-test-code-ajax')}}",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            code: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.text,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },
                placeholder: "Please type student email",
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
        });
        function addStudent(mockTestId){
            let studentId = $('#student-id-'+mockTestId).val();
            let mockTestCodeId = $('#mock-test-code-id-'+mockTestId).val();
            let notesToProctor = $('#notes-to-proctor-'+mockTestId).val();
            $.ajax({
                url: "{{route('mock-test-add-students')}}",
                type: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    student_id: studentId,
                    mock_test_code_id: mockTestCodeId,
                    notes_to_proctor: notesToProctor,
                    mock_test_id: mockTestId
                },
                success: function (data) {
                    console.log(data.success)
                    if(data.success){
                        toastr.success(data.success);
                        $('#add-student-to-mocktest-'+mockTestId).modal('hide');
                        $('#student-id-'+mockTestId).val('').trigger('change');
                        $('#mock-test-code-id-'+mockTestId).val('').trigger('change');
                        $('#notes-to-proctor-'+mockTestId).val('');
                    }else{
                        toastr.error(data.error);
                    }
                }
            });
        }
    </script>
@endpush
