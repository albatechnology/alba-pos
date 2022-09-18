<?php

namespace App\Http\Livewire\Cashier;

use Livewire\Component;

class Payment extends Component
{
    public $order;

    protected $listeners = ['setOrderData'];

    public function setOrderData($order)
    {
        dd('setOrderData');
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.cashier.payment');
    }
}
