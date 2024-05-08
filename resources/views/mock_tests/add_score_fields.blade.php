<div class="col-sm-12">
    {!! Form::label('student_name', 'Student Name:') !!}
    <p>{{ $mockTestStudent->first_name }} {{$mockTestStudent->last_name}}</p>
</div>
<div class="col-sm-12">
    {!! Form::label('test_name', 'Official Test Code Taken:') !!}
    <p>{{ $mockTestStudent->mock_test_code }} ({{$mockTestStudent->test_type}})</p>
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
    <input type="radio" id="url" name="score_report_type" value="url" {{ strtolower($mockTestStudent->score_report_type ?? '') === 'url' ? 'checked' : '' }} onchange="updateInputField()">
    <label for="url">URL</label><br>
</div>

<div class="col-sm-12">
    <input type="text" class="form-control" name="url" id="url_input" value="{{ $mockTestStudent->score_report_path ?? '' }}">
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
