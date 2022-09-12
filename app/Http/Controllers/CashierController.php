<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductCategory;
use App\Services\CartService;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index()
    {
        $productCategories = ProductCategory::tenanted()->get();
        $cart = CartService::getMyCart() ?? new Cart();
        return view('cashiers.index', ['productCategories' => $productCategories, 'cart' => $cart]);
    }
}
