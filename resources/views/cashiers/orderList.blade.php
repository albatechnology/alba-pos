<div class="modal-header">
    <h5 class="modal-title" id="modalOrderListLabel">Daftar Order</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-3">
            <div class="list-group" id="list-tab" role="tablist">
                @for ($i = 0; $i < count($carts); $i++)
                    <a class="list-group-item list-group-item-action {{ $i == 0 ? 'active' : '' }}"
                        id="tab-{{ $carts[$i]->id  }}-list" data-toggle="list" href="#tab-{{ $carts[$i]->id  }}"
                        role="tab" aria-controls="home">#{{ $carts[$i]->code }}</a>
                @endfor
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="nav-tabContent">
                @for ($i = 0; $i < count($carts); $i++)
                    <div class="tab-pane fade {{ $i == 0 ? 'show active' : '' }}" id="tab-{{ $carts[$i]->id  }}" role="tabpanel"
                        aria-labelledby="tab-{{ $carts[$i]->id  }}-list">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($carts[$i]->cartDetails as $detail)
                                    <tr>
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>{{ rupiah($detail->total_price) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            <a href="{{ url('cashier/set-order/'. $carts[$i]->code) }}?type=order" class="btn btn-outline-info">Order</a>
                            <a href="{{ url('cashier/set-order/'. $carts[$i]->code) }}?type=pay" class="btn btn-info">Bayar</a>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>
