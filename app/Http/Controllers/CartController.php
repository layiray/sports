<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CheckoutDetail;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    public function index()
    {
        // Retrieve cart items for the authenticated user
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        // Calculate the overall total price
        $overallTotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Pass the cart items and total to the view
        return view('cart.index', compact('cartItems', 'overallTotal'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|string',
            'billing_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_zip' => 'required|string|max:10',
            'billing_country' => 'required|string|max:255',
            'shipping' => 'required|string|in:standard,express',
            'card_name' => 'required|string|max:255',
            'card_number' => 'required|string|max:16',
            'expiration_date' => 'required|string|max:7',
            'cvv' => 'required|string|max:4',
        ]);
    
        try {
            DB::beginTransaction();
    
            // Save checkout details
            $checkoutDetail = CheckoutDetail::create([
                'user_id' => Auth::id(),
                'transaction_reference' => $validated['reference'],
                'billing_name' => $validated['billing_name'],
                'billing_address' => $validated['billing_address'],
                'billing_city' => $validated['billing_city'],
                'billing_zip' => $validated['billing_zip'],
                'billing_country' => $validated['billing_country'],
                'shipping_method' => $validated['shipping'],
                'card_name' => $validated['card_name'],
                'card_number' => $validated['card_number'],
                'expiration_date' => $validated['expiration_date'],
                'cvv' => $validated['cvv'],
            ]);
    
            // Retrieve cart items for the authenticated user
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
    
            if ($cartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Your cart is empty.']);
            }
    
            // Calculate the total price of the order
            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
    
            // Create a new order
            $order = Order::create([
                'user_id' => Auth::id(),
                'reference' => $validated['reference'],
                'total' => $total,
                'status' => 'pending', // Default status
            ]);
    
            // Save each cart item as an order item
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
            }
    
            // Clear the cart for the authenticated user
            Cart::where('user_id', Auth::id())->delete();
    
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Order placed successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing checkout:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'An error occurred while processing your order.']);
        }
    }

    public function showCheckout()
    {
        // Retrieve cart items for the authenticated user
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        // Calculate the overall total price
        $overallTotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Get the authenticated user's email
        $userEmail = Auth::user()->email;

        // Pass the cart items, total, and email to the view
        return view('cart.checkout', compact('cartItems', 'overallTotal', 'userEmail'));
    }
    
    public function clearCart(Request $request)
    {
        Cart::where('user_id', Auth::id())->delete();
    
        return response()->json(['success' => true]);
    }

    public function remove($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Item removed from cart!');
        }

        return redirect()->back()->with('error', 'Item not found in cart.');
    }

    public function incrementQuantity($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();

            return redirect()->back()->with('success', 'Item quantity increased!');
        }

        return redirect()->back()->with('error', 'Item not found in cart.');
    }

    public function decrementQuantity($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if ($cartItem) {
            if ($cartItem->quantity > 1) {
                $cartItem->quantity -= 1;
                $cartItem->save();
            } else {
                $cartItem->delete(); // Remove the item if the quantity becomes 0
            }

            return redirect()->back()->with('success', 'Item quantity decreased!');
        }

        return redirect()->back()->with('error', 'Item not found in cart.');
    }

}
