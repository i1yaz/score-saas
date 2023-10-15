<h5 class="mb-4 required">Subjects</h5>
@permission('subject-create')
<a href="#" class="btn btn-primary mb-4" data-toggle="modal" data-target="#store-subject">+ Subject</a>
@endpermission
<div class="row">
    @foreach ($subjects as $subject)
        <div class="form-group col-sm-2">
            <div class="custom-control custom-checkbox">
                <input
                    type="checkbox"
                    class="custom-control-input"
                    name="subject_ids[]"
                    value="{{$subject->id}}"
                    {!! in_array($subject->id, $selectedSubjects??[]) ? 'checked' : '' !!}
                    id="subject-{{$subject->id}}"
                >
                <label for="subject-{{$subject->id}}" class="custom-control-label" style="flex: 1 0 20%;">{{$subject->name}}</label>
            </div>
        </div>

    @endforeach
</div>


