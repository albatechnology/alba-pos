<?php

namespace App\Http\Livewire\Cashier;

use App\Models\Cart;
use App\Models\Product as ModelsProduct;
use App\Services\CartService;
use Illuminate\Support\Collection;
use Livewire\Component;

class Product extends Component
{
    public Cart $cart;
    public Collection $productCategories;
    public Collection $products;

    public int $quantity = 0;

    public $search = '';
    public $selectedProductCategoryId = null;
    public array $selectedProductIds = [];

    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1'
    ];

    public function mount($productCategories)
    {
        $this->initCart();
        $this->productCategories = $productCategories;
        $this->getProduct();
    }

    public function initCart(){
        $productIds = $this->cart?->cartDetails?->pluck('product_id')->toArray() ?? [];
        $this->selectedProductIds = array_combine($productIds, $productIds);
    }

    public function changeProductCategory($productCategoryId = null)
    {
        $this->selectedProductCategoryId = $productCategoryId;
        $this->getProduct();
    }

    public function getProduct()
    {
        $products = ModelsProduct::where('name', 'like', '%' . $this->search . '%');
        if (!is_null($this->selectedProductCategoryId)) {
            $products = $products->whereHas('productCategories', fn ($q) => $q->where('product_category_id', $this->selectedProductCategoryId));
        }

        $this->products = $products->get();
    }

    public function updatedSearch($value)
    {
        $this->getProduct();
    }

    public function syncCart($product_id, bool $is_remove)
    {
        if ($is_remove) {
            $cart = CartService::deleteDetailByProductId($product_id);
        } else {
            $cart = CartService::store([
                'product_id' => $product_id,
            ]);
        }

        $this->emit('refreshCart');
    }

    public function setSelectedProductIds($product_id)
    {
        if (in_array($product_id, $this->selectedProductIds)) {
            unset($this->selectedProductIds[$product_id]);
            $this->syncCart($product_id, true);
        } else {
            $this->selectedProductIds[$product_id] = $product_id;
            $this->syncCart($product_id, false);
        }
    }

    public function render()
    {
        return view('livewire.cashier.product');
    }
}
