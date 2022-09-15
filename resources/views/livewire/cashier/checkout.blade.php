<div>
    <div>
        @if ($cart)
            @forelse ($cart?->cartDetails as $detail)
                <div class="card p-2 shadow">
                    <p class="font-weight-bold">{{ $detail->product->name }}</p>
                    <div wire:ignore class="d-flex justify-content-between">
                        <div class="w-75">
                            <input wire:model="items.{{ $detail->product_id }}.quantity" type="number"
                                value="{{ $items[$detail->product_id]['quantity'] }}" min="0">
                        </div>
                        <button wire:click="$emit('setSelectedProductIds', {{ $detail->product_id }})"
                            class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning">Cart is empty</div>
            @endforelse
        @else
            <div class="alert alert-warning">Cart is empty</div>
        @endif
    </div>
    <div>
        <table class="table table-borderless">
            <tr>
                <td>Sub Total</td>
                <td align="right">{{ $sub_total_price }}</td>
            </tr>
            <tr>
                <td>Tax</td>
                <td align="right">{{ $total_tax }}</td>
            </tr>
            <tr>
                <td>Total</td>
                <td align="right">{{ $total_price }}</td>
            </tr>
        </table>
    </div>
    <div wire:ignore>
        {{-- <div class="form-group">
            <label>Discount</label>
            <input wire:model="additional_discount" wire:ignore type="text" class="form-control" type="number"
                min="0" disabled>
        </div> --}}
        <div class="form-group">
            <label>Additional Discount</label>
            <input wire:model="additional_discount" wire:ignore type="text" class="form-control" type="number"
                min="0">
        </div>
        <div class="form-group">
            <label>Pay</label>
            <input wire:model="total_user_pay" wire:ignore type="text" class="form-control" type="number"
                min="0">
        </div>
        <button wire:click="processOrder" class="btn btn-primary btn-block">Payment</button>
    </div>
</div>

@push('js')
    <script src="https://shaack.com/projekte/bootstrap-input-spinner/src/bootstrap-input-spinner.js"></script>
    <script>
        $("input[type='number']").inputSpinner();
        window.addEventListener('contentChanged', event => {
            $("input[type='number']").inputSpinner();
        });
    </script>
@endpush
