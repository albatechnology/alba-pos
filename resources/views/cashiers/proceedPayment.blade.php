<div class="modal-header">
    <h5 class="modal-title" id="modalPaymentLabel">Payment Confirmation</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table">
        @foreach ($order?->order_details as $detail)
            <tr>
                <td>{{ $detail->product->name . ' @' . $detail->quantity }}</td>
                <td align="right">{{ number_format($detail->total_price) }}</td>
            </tr>
        @endforeach
    </table>
    <table class="table table-borderless">
        <tr>
            <td>Sub Total</td>
            <td align="right">{{ number_format($order->original_price) }}</td>
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
            <td>Amount Paid</td>
            <td align="right">{{ number_format($order->amount_paid) }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td align="right">{{ number_format($kembali) }}</td>
        </tr>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <form id="orderForm">
        @csrf
        <input type="hidden" name="is_order" value="1">
        <input type="hidden" name="additional_discount" value="{{ $order->additional_discount }}">
        <input type="hidden" name="amount_paid" value="{{ $order->amount_paid }}">
        <input type="hidden" name="payment_type_id" value="{{ $order->payment_type_id }}">
        <button wire:click="processOrder" type="submit" class="btn btn-success"><i
                class="fa fa-paper-plane"></i>Order</button>
    </form>
</div>
<script>
    $(document).ready(function() {
        $(document).on('submit', '#orderForm', function(e) {
            e.preventDefault();
            var dataOrder = $(this).serializeArray();
            $.post("{{ route('cashier.proceedPayment') }}", dataOrder, function(res) {
                window.open("{{ url('cashier/invoice') }}/" + res.order_id);
                window.location.replace("{{ url('cashier') }}");
            })
        })
    })
</script>
