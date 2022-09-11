<div>
    @forelse ($cart?->cartDetails as $detail)
        <div wire:ignore class="card p-2 shadow">
            <p class="font-weight-bold">{{ $detail->product->name }}</p>
            <div class="d-flex justify-content-between">
                <div class="w-75">
                    <input type="number" value="{{ $detail->quantity }}" min="0">
                </div>
                <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
            </div>
        </div>
    @empty
        <h1>Cart empty</h1>
    @endforelse
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
