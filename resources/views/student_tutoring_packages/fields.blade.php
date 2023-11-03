<!-- Student Field -->
<div class="form-group col-sm-6">
    {!! Form::label('student_id', 'Student:') !!}
    {!! Form::select('student_id',$selectedStudent??[],null, ['class' => 'form-control','id'=>'student-id']) !!}
</div>

<!-- Tutoring Package Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutoring_package_type_id', 'Tutoring Package Type:') !!}
    {!! Form::select('tutoring_package_type_id', $tutoringPackageType??[],null, ['class' => 'form-control','id'=>'tutoring-package-type-id']) !!}
</div>

<!-- Tutor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutor-id', 'Tutor:') !!}
    <select class="form-control" name="tutor_ids[]" multiple="multiple" id='tutor-id'>
        @foreach ($selectedTutors??[] as $id => $selectedTutorEmail)
            <option selected="selected"  value="{{$id}}" >{{$selectedTutorEmail}}</option>
        @endforeach
    </select>

</div>
<!-- Tutoring Location Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutoring_location_id', 'Tutoring Location:') !!}
    {!! Form::select('tutoring_location_id', $tutoringLocation??[],null, ['class' => 'form-control','id'=>'tutoring-location-id']) !!}
</div>
<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('notes', 'Notes:') !!}
    {!! Form::text('notes', null, ['class' => 'form-control']) !!}
</div>

<!-- Internal Noted Field -->
<div class="form-group col-sm-6">
    {!! Form::label('internal_notes', 'Internal Noted:') !!}
    {!! Form::text('internal_notes', null, ['class' => 'form-control']) !!}
</div>

<!-- Hours Field -->
<div class="form-group col-sm-6">
    {!! Form::label('hours', 'Hours:') !!}
    {!! Form::number('hours', null, ['class' => 'form-control','id'=>'hours']) !!}
</div>

<!-- Hourly Rate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('hourly_rate', 'Hourly Rate:') !!}
    {!! Form::number('hourly_rate', null, ['class' => 'form-control','id'=>'hourly-rate']) !!}
</div>

<!-- Discount Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('discount_type', 'Discount:') !!}
    <div class="input-group">
        {!!  Form::number('discount', null, ['class' => 'form-control','id'=> 'discount'])  !!}
        <div class="input-group-append">
            <select class="form-control input-group-text" name="discount_type" id = 'discount-type'>
                <option value="1" @if(isset($studentTutoringPackage) && $studentTutoringPackage->discount_type == \App\Models\StudentTutoringPackage::FLAT_DISCOUNT) selected @endif>Flat</option>
                <option value="2" @if(isset($studentTutoringPackage) && $studentTutoringPackage->discount_type == \App\Models\StudentTutoringPackage::PERCENTAGE_DISCOUNT) selected @endif>%</option>
            </select>
        </div>
    </div>
</div>

<!-- Tutor Hourly Rate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tutor_hourly_rate', 'Tutor Hourly Rate:') !!}
    {!! Form::number('tutor_hourly_rate', null, ['class' => 'form-control','id'=>'tutor-hourly-rate']) !!}
    <span class="text-danger" id="tutor-hourly-rate-validation"> </span>
</div>

<!-- Start Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_date', 'Start Date:') !!}
    {!! Form::text('start_date', isset($studentTutoringPackage)? $studentTutoringPackage->start_date?->format('m/d/Y'):null, ['class' => 'form-control date-input']) !!}
</div>
<div class="form-group col-sm-12">
    <div class="row" style="border:2px dotted">
        <div class="form-group col-sm-12">
            <table class="table table-borderless box-shadow-none mb-0 mt-5">
                <tbody>
                <tr>
                    <td class="ps-0" style="width:50%;">{{ __('messages.invoice.sub_total') . ':' }}</td>
                    <td class="text-gray-900 text-end pe-0" style="width:50%;">
                        <span class="invoice-selected-currency">{{ getCurrencySymbol() }}</span> <span id="total" class="price">
                                        0
                                    </span>
                    </td>
                </tr>
                <tr>
                    <td class="ps-0" style="width:50%;">{{ __('messages.invoice.discount') . ':' }}</td>
                    <td class="text-gray-900 text-end pe-0" style="width:50%;">
                        <span class="invoice-selected-currency">{{ getCurrencySymbol() }}</span> <span id="discountAmount">
                                        0
                                    </span>
                    </td>
                </tr>
                <tr>
                    <td class="ps-0" style="width:50%;">{{ __('messages.invoice.total') . ':' }}</td>
                    <td class="text-gray-900 text-end pe-0" style="width:50%;">
                        <span class="invoice-selected-currency">{{ getCurrencySymbol() }}</span> <span id="finalAmount">
                                        0
                                    </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="form-group col-sm-12" id="all-subjects">
    @include('student_tutoring_packages.subjects')
</div>
@if(!isset($studentTutoringPackage))
    <div class="form-group col-sm-12">
        @include('student_tutoring_packages.invoice_details')
    </div>
@endif
<div class="modal fade" id="store-subject" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Subject</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Name Field -->
                <div class="form-group col-sm-12">
                    {!! Form::label('name', 'Name:') !!}
                    {!! Form::text('name', null, ['class' => 'form-control','id'=>'subject-name']) !!}
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" id="dismiss-subject-modal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="store-subject-button">Save changes</button>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')

    <script>
        $("#store-subject-button").click(function(){
            let subject = $("#subject-name").val();
            if(subject.trim()) {
                $.post("{{route('subjects.store')}}",
                    {
                        _token: "{{csrf_token()}}",
                        name: subject,
                        student_tutoring_package_id:{{$studentTutoringPackage->id??0}}
                    },
                    function(data, status){
                        toastr.success(data.success)
                        $('#dismiss-subject-modal').trigger('click');
                        $('#all-subjects').empty()
                        $('#all-subjects').append(data.html)
                    })
                    .fail(function() {
                        toastr.error("something went wrong!")
                    });
            }else {
                toastr.error('Name can not be empty')
            }

        });
        $(document).ready(function () {
            // this is the id of the form
            $("#student-tutoring-packages-form").submit(function(e) {

                e.preventDefault(); // avoid to execute the actual submit of the form.

                var form = $(this);
                var actionUrl = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: form.serialize(), // serializes the form's elements.
                    success: function(response)
                    {
                        toastr.success(response.message);
                        window.location = response.redirectTo
                    },
                    error: function (xhr, status, error) {
                        $("input[type='submit']").attr("disabled", false);
                        if (xhr.status === 422) {
                            $.each(xhr.responseJSON.errors, function (key, item) {
                                toastr.error(item[0]);
                            });
                        } else if(xhr.status === 404){
                            let response = xhr.responseJSON
                            toastr.error(response.message);
                        } else {
                            toastr.error("something went wrong");
                        }
                    }
                });

            });
            // Initialize Select2
            $("#tutoring-package-type-id").select2({
                dropdownAutoWidth: true, width: 'auto',
                theme: 'bootstrap4',
                minimumInputLength: 2,
                ajax: {
                    url: "{{route('tutoring-package-type-ajax')}}",
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
                                    hours: item.hours,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },
                placeholder: "Please type package name...",
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            $('#tutoring-package-type-id').on('select2:select', function (e) {
                let data = e.params.data;
                $('#hours').empty()
                $('#hours').val(data.hours)
                $('#hours').trigger('change')
            });
            $("#student-id").select2({
                dropdownAutoWidth: true, width: 'auto',
                theme: 'bootstrap4',
                minimumInputLength: 3,
                ajax: {
                    url: "{{route('student-email-ajax')}}",
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

            $("#tutor-id").select2({
                dropdownAutoWidth: true, width: 'auto',
                theme: 'bootstrap4',
                minimumInputLength: 3,
                multiple: true,
                ajax: {
                    url: "{{route('tutor-email-ajax')}}",
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
                                    id: item.id,
                                    hourly: item.hourly
                                }
                            })
                        };
                    },
                    cache: true
                },
                placeholder: "Please type tutor email",
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateSelection: function (data) {
                    $(data.element).attr('data-hourly', data.hourly);
                    return data.text;
                },

            });

            $("#tutoring-location-id").select2({
                dropdownAutoWidth: true, width: 'auto',
                theme: 'bootstrap4',
                minimumInputLength: 3,
                ajax: {
                    url: "{{route('tutoring-location-ajax')}}",
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
        });
        $(document).on('change keyup','#hours',function (){
            calculateTotal();
        });
        $(document).on('change keyup','#hourly-rate',function (){
            calculateTotal();
        });
        $(document).on('change','#discount-type',function (){
            calculateTotal();
        });
        $(document).on('change keyup','#discount',function (){
            calculateTotal();
        })
        function calculateTotal(originalPrice=null){
            let hours = parseInt($('#hours').val());
            if (isNaN(hours)){
                hours = 0;
            }
            let hourlyRate = parseFloat($('#hourly-rate').val());
            if (isNaN(hourlyRate)){
                hourlyRate = 0;
            }
            let discountType = parseInt($('#discount-type').val());
            let discount = parseInt($('#discount').val());
            if (isNaN(discount)){
                discount = 0;
            }
            let totalPrice = 0;

            if (!(originalPrice ==null || isNaN(originalPrice))){
                totalPrice = originalPrice;
            }else{
                totalPrice = (hourlyRate * hours);
            }

            let afterDiscount = 0;
            if (discountType===1){
                afterDiscount = totalPrice - discount;
            }else if(discountType===2){
                discount = (discount/100) * totalPrice
                afterDiscount = totalPrice-discount;
            }
            $("#total").text(totalPrice.toFixed(2));
            $("#discountAmount").text(discount.toFixed(2));
            $("#finalAmount").text(afterDiscount.toFixed(2));
        }
        $("#tutor-id").on("select2:select select2:unselect", function (e) {
            let hours = parseInt($('#hours').val());
            let teacherHourly = e.params.data.hourly;
            let count = parseInt($("option:selected", "#tutor-id").length)
            let hourly_rate = parseFloat($('#hourly-rate').val())
            let totalPrice = 0;
            if (isNaN(hourly_rate) && count===1){

                $("option:selected", "#tutor-id").each(function (index, val) {
                    teacherHourly = parseFloat(val.getAttribute("data-hourly"));
                });
                totalPrice = teacherHourly * hours;
            }else if(!isNaN(hourly_rate) && count===1){
                totalPrice = hourly_rate * hours;
            }else if(count>1){
                totalPrice = hourly_rate * hours;
            }else if (count < 1){
                totalPrice = hourly_rate * hours;
            }

            if (isNaN(totalPrice) && count > 1){
                $('#tutor-hourly-rate-validation').text('Please enter tutor hourly rate if the selected tutors are more than one!')
            }else{
                $('#tutor-hourly-rate-validation').empty()
            }
            calculateTotal(totalPrice)
        })


    </script>
@endpush
