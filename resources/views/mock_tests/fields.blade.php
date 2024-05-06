<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', 'Date:',['class' => 'required']) !!}
    {!! Form::text('date', null, ['class' => 'form-control','id'=>'date']) !!}
</div>

<!-- Location Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('location_id', 'Location:',['class' => 'required']) !!}
    {!! Form::select('location_id', $location??[],null, ['class' => 'form-control','id'=>'location-id']) !!}
</div>

<!-- Start Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_time', 'Start Time:') !!}
    {!! Form::time('start_time', null, ['class' => 'form-control','id'=>'start_time']) !!}
</div>

<!-- End Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('end_time', 'End Time:') !!}
    {!! Form::time('end_time', null, ['class' => 'form-control','id'=>'start_time']) !!}
</div>

<!-- Proctor Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('proctor_id', 'Proctor:',['class' => 'required']) !!}
    {!! Form::select('proctor_id', $proctor??[],null, ['class' => 'form-control', 'id' =>'proctor-id']) !!}
</div>

@push('after_third_party_scripts')
    <script type="text/javascript">
        ajaxSubmit = false;
        $('#date').datepicker()
        $(document).ready(function() {
            $("#location-id").select2({
                dropdownAutoWidth: true, width: 'auto',
                theme: 'bootstrap4',
                minimumInputLength: 1,
                ajax: {
                    url: "{{route('mock-test-location-ajax')}}",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            name: params.term
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
                placeholder: "Please type Tutoring location name...",
                escapeMarkup: function (markup) {
                    return markup;
                }
            });

            $("#proctor-id").select2({
                dropdownAutoWidth: true, width: 'auto',
                theme: 'bootstrap4',
                minimumInputLength: 1,
                ajax: {
                    url: "{{route('proctors-ajax')}}",
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
                placeholder: "Please type Proctor email...",
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
        });
    </script>
@endpush
