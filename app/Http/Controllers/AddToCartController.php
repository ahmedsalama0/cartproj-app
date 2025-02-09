<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AddToCartController extends Controller
{
    public $cart = [];

    public function __construct() {
        $this->cart = Session::get('cart', []);
    }


    // a method to store the session data
    public function store(Request $request, string $id) {
        $product = Product::findOrFail($id);

        $this->cart[$product->id] = [
            'id' => $product->id,
            'image' => $product->image,
            'name' => $product->name,
            'price' => $product->price,
            'color' => $request->color,
            'qty' => $request->qty,
        ];

        Session::put('cart', $this->cart);

        return response([
            'status' => 'ok',
            'message' => 'Product added to cart',
            'cart_count' => count($this->cart),
        ]);
    }

    function destroy(string $id) {
        $cartItems = $this->cart;
        unset($cartItems[$id]);
        Session::put('cart', $cartItems);
        notyf()->success('Product REMOVED from the cart.');
        return redirect()->back();
    }

    function updateQty(Request $request) {
        // dd($request->all());
        $cartItems = $this->cart;
        $cartItems[$request->id]['qty'] = $request->qty;
        Session::put('cart', $cartItems);
        notyf()->success('Product quantity updated.');
        return response(['status' => 'ok']);
    }
}
