<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $items = Cart::where('user_id', $request->user()->id)
            ->with('product.category')
            ->get();

        $total = $items->sum(fn($i) => $i->product->price * $i->quantity);

        return response()->json(['items' => $items, 'total' => $total, 'count' => $items->sum('quantity')]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'integer|min:1|max:99',
        ]);

        $cart = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            $cart->increment('quantity', $request->quantity ?? 1);
        } else {
            $cart = Cart::create([
                'user_id'    => $request->user()->id,
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity ?? 1,
            ]);
        }

        return response()->json(['message' => 'Added to cart', 'cart' => $cart->load('product')]);
    }

    public function update(Request $request, Cart $cart)
    {
        $this->authorize($cart->user_id === $request->user()->id);
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);
        $cart->update(['quantity' => $request->quantity]);
        return response()->json(['message' => 'Cart updated', 'cart' => $cart->load('product')]);
    }

    public function remove(Request $request, Cart $cart)
    {
        abort_if($cart->user_id !== $request->user()->id, 403);
        $cart->delete();
        return response()->json(['message' => 'Removed from cart']);
    }

    public function clear(Request $request)
    {
        Cart::where('user_id', $request->user()->id)->delete();
        return response()->json(['message' => 'Cart cleared']);
    }

    private function authorize(bool $condition)
    {
        abort_if(!$condition, 403);
    }
}
