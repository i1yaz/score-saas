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
            <div class="modal-body">
                <form id="session-form">
                    @csrf
                    <div class="col-sm-12">
                        {!! Form::label('student_name', 'Student Name:') !!}
                    </div>
                    <div class="col-sm-12">
                        {!! Form::label('test_name', 'Official Test Code Taken:') !!}
                    </div>
                    <div class="col-sm-12">
                        {!! Form::label('score', 'Score') !!}
                        {!! Form::number('score', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="col-sm-12">
                        <h4>Format of the Score Report</h4>
                    </div>
                    {{--@dd($mockTestStudent)--}}
                    <div class="col-sm-12">
                        <input type="radio" id="file" name="score_report_type" value="file" checked onchange="updateInputField()">
                        <label for="file">File</label><br>
                        <input type="radio" id="url" name="score_report_type" value="url"  onchange="updateInputField()">
                        <label for="url">URL</label><br>
                    </div>

                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="url" id="url_input" value="">
                        <input type="file" class="form-control" name="file" id="file_input">
                    </div>

                    <div class="col-sm-12 mt-2">
                        <h4>Subsection Scores</h4>
                    </div>

                    @if($mockTestStudent->test_type==='ACT')
                        <div class="col-sm-12">
                            {!! Form::label('english_score', 'English') !!}
                            {!! Form::number('english_score', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-12">
                            {!! Form::label('math_score', 'Math') !!}
                            {!! Form::number('math_score', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-12">
                            {!! Form::label('reading_score', 'Reading') !!}
                            {!! Form::number('reading_score', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-12">
                            {!! Form::label('science_score', 'Science') !!}
                            {!! Form::number('science_score', null, ['class' => 'form-control']) !!}
                        </div>

                    @elseif($mockTestStudent->test_type==='SAT')
                        <div class="col-sm-12">
                            {!! Form::label('english_score', 'English') !!}
                            {!! Form::number('english_score', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-12">
                            {!! Form::label('math_score', 'Math') !!}
                            {!! Form::number('math_score', null, ['class' => 'form-control']) !!}
                        </div>
                    @endif

                    <script>
                        function updateInputField() {
                            if (document.getElementById('url').checked) {
                                document.getElementById('url_input').style.display = 'block';
                                document.getElementById('file_input').style.display = 'none';
                            } else {
                                document.getElementById('url_input').style.display = 'none';
                                document.getElementById('file_input').style.display = 'block';
                            }
                        }

                        updateInputField();
                    </script>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script>

    </script>
@endpush
