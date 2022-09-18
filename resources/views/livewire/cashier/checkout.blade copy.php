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
                        <button wire:click="removeCartDetail({{ $detail->id . ', ' . $detail->product_id }})"
                            class="btn btn-danger"><i class="fa fa-trash"></i></button>
                        {{-- <button wire:click="$emit('setSelectedProductIds', {{ $detail->product_id }})" class="btn btn-danger"><i class="fa fa-trash"></i></button> --}}
                    </div>
                </div>
            @empty
                <div class="alert alert-warning">Cart is empty</div>
            @endforelse
        @else
            <div class="alert alert-warning">Cart is empty</div>
        @endif
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
            <input wire:model="amount_paid" wire:ignore type="text" class="form-control" type="number"
                min="0">
        </div>
        <button wire:click="openModal" class="btn btn-primary btn-block" data-toggle="modal"
            data-target="#staticBackdrop">Proceed Payment</button>
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Payment Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($order)
                            <table class="table">
                                @forelse ($order?->order_details as $detail)
                                    <tr>
                                        <td>{{ $detail->product->name . ' x' . $detail->quantity }}</td>
                                        <td align="right">{{ $detail->total_price }}</td>
                                    </tr>
                                @empty
                                    <div class="alert alert-warning">Cart is empty</div>
                                @endforelse
                            </table>
                            <table class="table table-borderless">
                                <tr>
                                    <td>Sub Total</td>
                                    <td align="right">{{ $sub_total_price }}</td>
                                </tr>
                                <tr>
                                    <td>Tax</td>
                                    <td align="right">{{ $order->total_tax }}</td>
                                </tr>
                                <tr>
                                    <td>Additional Discount</td>
                                    <td align="right">{{ $order->additional_discount }}</td>
                                </tr>
                                <tr>
                                    <td>Total Price</td>
                                    <td align="right">{{ $order->total_price }}</td>
                                </tr>
                                <tr>
                                    <td>Total Pay</td>
                                    <td align="right">{{ $order->amount_paid }}</td>
                                </tr>
                                <tr>
                                    <td>Kembali</td>
                                    <td align="right">{{ $order->amount_paid - $order->total_price }}</td>
                                </tr>
                            </table>
                        @else
                            <div class="alert alert-warning">Cart is empty</div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button wire:click="processOrder" type="button" class="btn btn-success"><i
                                class="fa fa-paper-plane"></i> Order</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- <button wire:click="processOrder" class="btn btn-primary btn-block">Proceed Payment</button> --}}
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
