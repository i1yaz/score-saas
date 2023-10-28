<tr class="item-tr" id="item-{{$id}}">
    <td class="text-center item-number align-center">1</td>
    <td class="w-25">
        <select name="item_id[{{$id}}]" class='form-control items' data-control='select2' id="item-id-{{$id}}">
            <option >Select Line Item</option>
            @foreach ($items as $item)
                <option value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td style="width: 10% !important;">
        {{ Form::number("quantity[$id]", null, ['class' => 'form-control qty ', 'required','id'=>"item-quantity-$id", 'type' => 'number', 'min' => '0', 'step' => '.01', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))"]) }}
    </td>
    <td style="width: 10% !important;">
        {{ Form::number("price[$id]", null, ['class' => 'form-control price-input price ','id'=>"item-price-$id", 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))", 'min' => '0', 'value' => '0', 'step' => '.01', 'pattern' => "^\d*(\.\d{0,2})?$", 'required', 'onKeyPress' => 'if(this.value.length==8) return false;']) }}
    </td>
    <td class="w-25">
        <select name="tax_id[][{{$id}}]" class='form-control taxes' data-control='select2' id="tax-id-{{$id}}" multiple="multiple">
            @foreach ($taxes as $tax)
                <option value="{{ $tax->id }}" data-tax="{{ $tax->value }}">{{ $tax->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td style="width: 10% !important;text-align:right" class="text-end item-total pt-8 text-nowrap" >
        <span class="invoice-selected-currency" >{{ getCurrencySymbol() }}</span>0.00
    </td>
    <td class="item-tax-price" style="text-align:right" >
        <span >{{ getCurrencySymbol() }}</span>0.00
    </td>
    <td style="width: 10% !important;text-align:right" class="item-price-after-tax">
        <span class="invoice-item-currency" >{{ getCurrencySymbol() }}</span>0.00
    </td>
    <td class="text-end">
        <button type="button" class="btn btn-icon fs-3 text-danger btn-active-color-danger delete-item" id="delete-item-{{$id}}">
            <i class="far fa-trash-alt"></i>
        </button>
    </td>
</tr>
