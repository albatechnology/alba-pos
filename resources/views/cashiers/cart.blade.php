@if ($cart)
    <div  style="overflow-y: scroll; height: 50vh">
        @forelse ($cart?->cartDetails as $detail)
            <div class="card p-2 shadow">
                <p class="font-weight-bold">{{ $detail->product->name }}</p>
                <div class="d-flex justify-content-between">
                    <div class="w-75">
                        <input name="add[{{$detail->product_id}}]" type="number" class="btnPlusMinus" value="{{ $detail->quantity }}" min="1" max="{{ $detail->getStock() }}" onchange="plusMinus({{$detail->product_id}}, this.value)">
                    </div>
                    <button class="btn btn-danger btn-sm removeItem" onclick="removeCartDetail({{ $detail->id . ', ' . $detail->product_id }})"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        @empty
            <div class="alert alert-warning">Cart is empty</div>
        @endforelse
    </div>
@else
    <div class="alert alert-warning">Cart is empty</div>
@endif
<table class="table table-borderless">
    <tr>
        <td class="pb-0">Sub Total</td>
        <td class="pb-0" align="right">{{ number_format($sub_total_price) }}</td>
    </tr>
    {{-- <tr>
        <td>Tax</td>
        <td align="right">{{ number_format($total_tax) }}</td>
    </tr> --}}
    <tr>
        <td class="pb-0">Total</td>
        <td class="pb-0" align="right">{{ number_format($total_price) }}</td>
    </tr>
</table>
<script>
    $(".btnPlusMinus").inputSpinner({
        buttonsOnly: true
    });
</script>
