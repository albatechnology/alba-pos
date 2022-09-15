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
    public int $sub_total_price = 0;
    public int $total_price = 0;
    public int $total_tax = 0;
    public int $total_user_pay = 0;

    protected $listeners = ['refreshCart'];

    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $this->cart = CartService::getMyCart()?->load('cartDetails');
        // $this->total_price = $this->cart?->total_price ?? 0;

        $this->fillItemsAttribute();
        $this->previewOrder();
        $this->dispatchBrowserEvent('contentChanged');
    }

    protected function fillItemsAttribute()
    {
        if ($this->cart?->cartDetails->count() > 0) {
            $cartDetails = $this->cart->cartDetails;

            foreach ($cartDetails as $detail) {
                $this->items[$detail->product_id] = [
                    'product_id' => (int) $detail->product_id,
                    'quantity' => (int) $detail->quantity,
                ];
            }
        }
    }

    public function updatedItems($value)
    {
        $this->dispatchBrowserEvent('contentChanged');
        $this->previewOrder();
        CartService::syncCart($this->items);
    }

    public function previewOrder()
    {
        $validatedData = $this->validateData();

        $order = \App\Services\OrderService::previewOrder(Order::make(['raw_source' => $validatedData]));
        $this->total_tax = $order?->total_tax ?? 0;
        $this->sub_total_price = $order?->total_price ?? 0;
        $this->total_price = $this->sub_total_price + $this->total_tax ?? 0;
    }

    public function processOrder()
    {
        $validatedData = $this->validateData();
        dump($validatedData);
        dd('processOrder');
        $order = \App\Services\OrderService::processOrder(Order::make(['raw_source' => $validatedData]));
        $this->total_tax = $order?->total_tax ?? 0;
        $this->sub_total_price = $order?->total_price ?? 0;
        $this->total_price = $this->sub_total_price + $this->total_tax ?? 0;
    }

    private function validateData()
    {
        return $this->validate(
            [
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
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

    public function render()
    {
        return view('livewire.cashier.checkout');
    }
}
