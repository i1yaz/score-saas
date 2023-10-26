<tr class="tax-tr">
    <td class="text-center item-number align-center">1</td>
    <td class="w-25">
        <select name="item_id[]" class='form-control items' data-control='select2' id='item-id' multiple="multiple">
            @foreach ($items as $item)
                <option value="{{ $item->id }}" data-tax="{{ $item->price }}">{{ $item->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td style="width: 10% !important;">
        {{ Form::number('quantity[]', null, ['class' => 'form-control qty ', 'required', 'type' => 'number', 'min' => '0', 'step' => '.01', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))"]) }}
    </td>
    <td style="width: 10% !important;">
        {{ Form::number('price[]', null, ['class' => 'form-control price-input price ', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))", 'min' => '0', 'value' => '0', 'step' => '.01', 'pattern' => "^\d*(\.\d{0,2})?$", 'required', 'onKeyPress' => 'if(this.value.length==8) return false;']) }}
    </td>
    <td class="w-25">
        <select name="tax_id[]" class='form-control taxes' data-control='select2' id='tax-id' multiple="multiple">
            @foreach ($taxes as $tax)
                <option value="{{ $tax->id }}" data-tax="{{ $tax->value }}">{{ $tax->name }}
                </option>
            @endforeach
        </select>
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
