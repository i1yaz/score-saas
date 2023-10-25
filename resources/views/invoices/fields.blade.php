<div class="form-group col-sm-6">
    {!! Form::label('client_id', 'Client:',['class'=>'required']) !!}


    <div class="input-group">
        {!! Form::select('client_id', $clients??[] ,null, ['class' => 'form-control','id'=>'client-id']) !!}
{{--        @permission('client-create')--}}
        <div class="input-group-append">
            <a class="input-group-text" href="#" data-toggle="modal" data-target="#add-client">Add Client</a>
        </div>
{{--        @endpermission--}}
    </div>

</div>


<!-- Due Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('due_date', 'Due Date:',['class'=>'required']) !!}
    {!! Form::text('due_date', null, ['class' => 'form-control date-input']) !!}
</div>


<!-- General Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('general_description', 'General Description:') !!}
    {!! Form::text('general_description', null, ['class' => 'form-control']) !!}
</div>

<!-- Detailed Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('detailed_description', 'Detailed Description:') !!}
    {!! Form::text('detailed_description', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12">
    <div class="mb-0">
        <div class="col-12 text-end mb-lg-10 mb-6 ">
            <button type="button" class="btn btn-primary text-start float-right" id="addItem">
                {{ __('messages.invoice.add') }}</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped box-shadow-none mt-4" id="billTbl">
                <thead>
                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                    <th scope="col">#</th>
                    <th scope="col" class="required">{{ __('messages.product.product') }}</th>
                    <th scope="col" class="required">{{ __('messages.invoice.qty') }}</th>
                    <th scope="col" class="required">{{ __('messages.product.unit_price') }}</th>
                    <th scope="col">{{ __('messages.invoice.tax') }}</th>
                    <th scope="col" class="required">{{ __('messages.invoice.amount') }}</th>
                    <th scope="col" class="text-end">{{ __('messages.common.action') }}</th>
                </tr>
                </thead>
                <tbody class="invoice-item-container">
                <tr class="tax-tr">
                    <td class="text-center item-number align-center">1</td>
                    <td class="w-25">
                        {!! Form::select('product_id', $clients??[] ,null, ['class' => 'form-control','id'=>'product-id']) !!}
                    </td>
                    <td style="width: 10% !important;">
                        {{ Form::number('quantity[]', null, ['class' => 'form-control qty ', 'required', 'type' => 'number', 'min' => '0', 'step' => '.01', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))"]) }}
                    </td>
                    <td style="width: 10% !important;">
                        {{ Form::number('price[]', null, ['class' => 'form-control price-input price ', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))", 'min' => '0', 'value' => '0', 'step' => '.01', 'pattern' => "^\d*(\.\d{0,2})?$", 'required', 'onKeyPress' => 'if(this.value.length==8) return false;']) }}
                    </td>
                    <td class="w-25">
                        {!! Form::select('tax_id[]', $taxes??[] ,null, ['class' => 'form-control','id'=>'tax-id']) !!}

                    </td>
                    <td class="text-end item-total pt-8 text-nowrap">
                        <span class="invoice-selected-currency">{{ getCurrencySymbol() }}</span>0.00
                    </td>
                    <td class="text-end">
                        <button type="button" title="Delete"
                                class="btn btn-icon fs-3 text-danger btn-active-color-danger delete-invoice-item">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-sm-6 mt-2 mt-lg-0 align-right-for-full-screen">
                <div class="row">
                    <div class="form-group col-sm-12 float-right">
                        {!! Form::label('discount_type', 'Discount:') !!}
                        <div class="input-group">
                            {!!  Form::number('discount', null, ['class' => 'form-control'])  !!}
                            <div class="input-group-append">
                                <select class="form-control input-group-text" name="discount_type" id = 'discount-type'>
                                    <option value="1" @if(isset($studentTutoringPackage) && $studentTutoringPackage->discount_type == \App\Models\StudentTutoringPackage::FLAT_DISCOUNT) selected @endif>Flat</option>
                                    <option value="2" @if(isset($studentTutoringPackage) && $studentTutoringPackage->discount_type == \App\Models\StudentTutoringPackage::PERCENTAGE_DISCOUNT) selected @endif>%</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12 float-right">
                        {{ Form::label('tax2', __('messages.invoice.tax') . ':', ['class' => 'form-label mb-1']) }}
                        {!! Form::select('tax2_id[]', $taxes??[] ,null, ['class' => 'form-control','id'=>'tax2-id']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6 ms-md-auto mt-4 mb-lg-10 mb-6">
                <div class="border-top">
                    <table class="table table-borderless box-shadow-none mb-0 mt-5">
                        <tbody>
                        <tr>
                            <td class="ps-0">{{ __('messages.invoice.sub_total') . ':' }}</td>
                            <td class="text-gray-900 text-end pe-0">
                                    <span class="invoice-selected-currency">{{ getCurrencySymbol() }}</span><span id="total" class="price">
                                        0
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0">{{ __('messages.invoice.discount') . ':' }}</td>
                            <td class="text-gray-900 text-end pe-0">
                                    <span class="invoice-selected-currency">{{ getCurrencySymbol() }}</span> <span id="discountAmount">
                                        0
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0">{{ __('messages.invoice.total_tax') . ':' }}</td>
                            <td class="text-gray-900 text-end pe-0">
                                    <span class="invoice-selected-currency">{{ getCurrencySymbol() }}</span> <span id="totalTax">
                                        0
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0">{{ __('messages.invoice.total') . ':' }}</td>
                            <td class="text-gray-900 text-end pe-0">
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
    </div>
</div>

<div class="modal fade" id="add-client" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Client</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- First Name Field -->
                <div class="form-group col-sm-12">
                    {!! Form::label('first_name', 'First Name:',['class'=>'required']) !!}
                    {!! Form::text('first_name', null, ['class' => 'form-control','id'=>'first-name']) !!}
                </div>
                <!-- Last Name Field -->
                <div class="form-group col-sm-12">
                    {!! Form::label('last_name', 'Last Name:') !!}
                    {!! Form::text('last_name', null, ['class' => 'form-control','id'=>'last-name']) !!}
                </div>
                <div class="form-group col-sm-12">
                    {!! Form::label('email', 'Email:',['class'=>'required']) !!}
                    {!! Form::text('email', null, ['class' => 'form-control','id'=>'email']) !!}
                </div>
                <!-- Address Field -->
                <div class="form-group col-sm-12 col-lg-12">
                    {!! Form::label('address', 'Address:') !!}
                    {!! Form::textarea('address', null, ['class' => 'form-control','id'=>'client-address']) !!}
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" id="dismiss-client-modal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="store-client">Save changes</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $("#store-client").click(function(){
            $.post("{{route('clients.store')}}",
                {
                    _token: "{{csrf_token()}}",
                    first_name: $("#first-name").val(),
                    last_name: $("#last-name").val(),
                    email: $("#email").val(),
                    address: $("#client-address").val()
                },
                function(data, status){
                    toastr.success(data.success)
                    $('#dismiss-client-modal').trigger('click');
                })
                .fail(function() {
                    toastr.error("something went wrong!")
                });
        });

        $('#non-package-invoice-package-form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function (response) {
                    console.log(response)
                    toastr.success(response.message);
                    $("input[type='submit']").attr("disabled", false);
                    window.location = response.redirectTo
                },
                error: function (xhr, status, error) {
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
                    $("input[type='submit']").attr("disabled", false);
                }
            });
        });


        $("#client-id").select2({
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
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
            placeholder: "Please type client email",
            escapeMarkup: function (markup) {
                return markup;
            }
        });
        $("#tax-id").select2({
            dropdownAutoWidth: true, width: 'auto',
            theme: 'bootstrap4',
            minimumInputLength: 3,
            multiple: true,
            placeholder: "Please select tax type",
            escapeMarkup: function (markup) {
                return markup;
            }
        });
        $("#tax2-id").select2({
            dropdownAutoWidth: true, width: 'auto',
            theme: 'bootstrap4',
            minimumInputLength: 3,
            multiple: true,
            placeholder: "Please select tax type",
            escapeMarkup: function (markup) {
                return markup;
            }
        });
        $("#product-id").select2({
            dropdownAutoWidth: true, width: 'auto',
            theme: 'bootstrap4',
            minimumInputLength: 3,
            placeholder: "Please type item",
            escapeMarkup: function (markup) {
                return markup;
            }
        });
    </script>
@endpush
