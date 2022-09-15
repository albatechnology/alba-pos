<?php

namespace App\Http\Livewire\Cashier;

use App\Models\Order;
use App\Services\CartService;
use Livewire\Component;

class Checkout extends Component
{
    public $cart;
    public array $items = [];
    public int $additional_discount = 0;
    public int $total_price = 0;
    public int $total_user_pay = 0;

    protected $listeners = ['refreshCart'];

    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $this->cart = CartService::getMyCart()?->load('cartDetails');
        $this->total_price = $this->cart?->total_price ?? 0;

        $this->fillItemsAttribute();
        $this->dispatchBrowserEvent('contentChanged');
        $this->previewOrder();
        // dd('refreshCart');
    }

    protected function fillItemsAttribute()
    {
        if ($this->cart?->cartDetails->count() > 0) {
            $cartDetails = $this->cart->cartDetails;
            // dump($cartDetails);
            // $this->items = [];
            foreach ($cartDetails as $detail) {
                $this->items[$detail->id] = [
                    'id' => (int) $detail->id,
                    'quantity' => (int) $detail->quantity,
                ];
            }
        }
        // dd($this->items);
    }

    public function updatedItems($value)
    {
        // dd('updatedItems');
        $this->dispatchBrowserEvent('contentChanged');
        $this->previewOrder();
        // dump($value);
        // dd($this->items);
    }

    public function previewOrder()
    {
        $validatedData = $this->validateData();
        // dd($validatedData);

        $order = \App\Services\OrderService::previewOrder(Order::make(['raw_source' => $validatedData]));
        $this->total_price = $order?->total_price ?? 0;
    }

    public function processOrder()
    {
        $validatedData = $this->validateData();
        dump($validatedData);
        dd('processOrder');
        $order = \App\Services\OrderService::processOrder(Order::make(['raw_source' => $validatedData]));
        $this->total_price = $order?->total_price ?? 0;
    }

    private function validateData()
    {
        return $this->validate(
            [
                'items' => 'required|array',
                'items.*.id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|min:0',

                'total_price' => 'required|numeric|min:0',
                'additional_discount' => 'required|numeric|min:0',
                'total_user_pay' => 'required|numeric|min:0',
            ],
            // [
            //     'email.required' => 'The :attribute cannot be empty.',
            //     'email.email' => 'The :attribute format is not valid.',
            // ],
            // [
            //     'email' => 'Email Address'
            // ]
        );
    }

    public function deleteCartDetail($cart_detail_id, $product_id)
    {
        unset($this->items[$product_id]);
        CartService::deleteDetail($cart_detail_id);
        $this->emit('toggleSelectedProductIds', $product_id);
    }

    public function render()
    {
        return view('livewire.cashier.checkout');
    }
}
