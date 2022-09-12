<?php

namespace App\Http\Livewire\Cashier;

use App\Services\CartService;
use Livewire\Component;

class Checkout extends Component
{
    public $cart;

    protected $listeners = ['refreshCart'];

    public function mount()
    {
    }

    public function refreshCart()
    {
        $this->cart = CartService::getMyCart();
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function render()
    {
        return view('livewire.cashier.checkout');
    }
}
