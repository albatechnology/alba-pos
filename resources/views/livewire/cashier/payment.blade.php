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
                        <td align="right">{{ number_format($sub_total_price) }}</td>
                    </tr>
                    <tr>
                        <td>Tax</td>
                        <td align="right">{{ number_format($order->total_tax) }}</td>
                    </tr>
                    <tr>
                        <td>Additional Discount</td>
                        <td align="right">{{ number_format($order->additional_discount) }}</td>
                    </tr>
                    <tr>
                        <td>Total Price</td>
                        <td align="right">{{ number_format($order->total_price) }}</td>
                    </tr>
                    <tr>
                        <td>Total Pay</td>
                        <td align="right">{{ number_format($order->amount_paid) }}</td>
                    </tr>
                    <tr>
                        <td>Kembali</td>
                        <td align="right">{{ number_format($order->amount_paid - $order->total_price) }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click="processOrder" type="button" class="btn btn-success"><i
                        class="fa fa-paper-plane"></i> Order</button>
            </div>
        </div>
    </div>
</div>
