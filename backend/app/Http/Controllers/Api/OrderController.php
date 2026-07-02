<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items')
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    public function show(Request $request, Order $order)
    {
        abort_if($order->user_id !== $request->user()->id, 403);
        $order->load('items');
        return response()->json($order);
    }

    public function cancel(Request $request, Order $order)
    {
        abort_if($order->user_id !== $request->user()->id, 403);
        abort_if($order->status !== 'pending', 422, 'Only pending orders can be cancelled.');
        $order->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Order cancelled']);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'shipping_address'          => 'required|array',
            'shipping_address.name'     => 'required|string',
            'shipping_address.phone'    => 'required|string',
            'shipping_address.address'  => 'required|string',
            'shipping_address.city'     => 'required|string',
            'payment_method'            => 'required|in:aba,acleda,card,cod',
        ]);

        $cartItems = Cart::where('user_id', $request->user()->id)->with('product')->get();
        abort_if($cartItems->isEmpty(), 422, 'Cart is empty');

        DB::beginTransaction();
        try {
            $subtotal = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
            $shipping = 5.00;
            $tax      = round($subtotal * 0.1, 2);
            $total    = $subtotal + $shipping + $tax;

            $order = Order::create([
                'user_id'          => $request->user()->id,
                'subtotal'         => $subtotal,
                'tax'              => $tax,
                'shipping'         => $shipping,
                'total'            => $total,
                'status'           => 'pending',
                'payment_status'   => 'pending',
                'payment_method'   => $request->payment_method,
                'shipping_address' => $request->shipping_address,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->product->price,
                    'quantity'     => $item->quantity,
                    'total'        => $item->product->price * $item->quantity,
                ]);
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            Cart::where('user_id', $request->user()->id)->delete();
            DB::commit();

            return response()->json(['message' => 'Order placed successfully', 'order' => $order->load('items')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Checkout failed', 'error' => $e->getMessage()], 500);
        }
    }
}
