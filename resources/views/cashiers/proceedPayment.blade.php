<form id="orderForm">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalPaymentLabel">Payment Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="overflow-auto" style="height:85vh">
        <div class="modal-body">
            <table class="table">
                @foreach ($order?->order_details as $detail)
                    <tr>
                        <td>{{ $detail->product->name . ' @' . $detail->quantity }}</td>
                        <td align="right">{{ rupiah($detail->total_price) }}</td>
                    </tr>
                @endforeach
            </table>
            <table class="table table-borderless">
                <tr>
                    <td>Sub Total</td>
                    <td align="right">{{ rupiah($order->original_price) }}</td>
                </tr>
                {{-- <tr>
                <td>Tax</td>
                <td align="right">{{ rupiah($order->total_tax) }}</td>
            </tr> --}}
                <tr>
                    <td>Discount</td>
                    <td align="right">- {{ rupiah($order->total_discount) }}</td>
                </tr>
                <tr>
                    <td>Additional Discount</td>
                    <td align="right">- {{ rupiah($order->additional_discount) }}</td>
                </tr>
                <tr>
                    <td>Total Discount</td>
                    <td align="right">- {{ rupiah($order->total_discount + $order->additional_discount) }}</td>
                </tr>
                <tr>
                    <td>Total Price</td>
                    <td align="right">{{ rupiah($order->total_price) }}</td>
                </tr>
                <tr>
                    <td>Amount Paid</td>
                    <td align="right">{{ rupiah($order->amount_paid) }}</td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td align="right">{{ rupiah($kembali) }}</td>
                </tr>
            </table>
            <input type="number" name="customer_phone" placeholder="Customer Phone"
                class="form-control mb-2 @error('phone') is-invalid @enderror">
            <input type="text" name="customer_name" placeholder="Customer Name"
                class="form-control mb-2 @error('name') is-invalid @enderror">
            <input type="email" name="customer_email" placeholder="Customer Email"
                class="form-control mb-2 @error('email') is-invalid @enderror">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <input type="hidden" name="is_order" value="1">
            <input type="hidden" name="discount_id" value="{{ $order->discount_id }}">
            <input type="hidden" name="additional_discount" value="{{ $order->additional_discount }}">
            <input type="hidden" name="amount_paid" value="{{ $order->amount_paid }}">
            <input type="hidden" name="payment_type_id" value="{{ $order->payment_type_id }}">
            <button wire:click="processOrder" type="submit" class="btn btn-success"><i
                    class="fa fa-paper-plane"></i>Order</button>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $(document).on('submit', '#orderForm', function(e) {
            e.preventDefault();
            var dataOrder = $(this).serializeArray();
            $.post("{{ route('cashier.proceedPayment') }}", dataOrder, function(res) {
                // window.open("{{ url('cashier/invoice') }}/" + res.order_id);
                window.open("{{ url('orders/invoice') }}/" + res.order_id);
                window.location.replace("{{ url('cashier') }}");
            })
        })
    })
</script>
