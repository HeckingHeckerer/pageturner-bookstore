<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function cart()
    {
        $cartItems = Auth::user()->cart()->with('book')->get();
        $total = $cartItems->sum(fn($item) => $item->book->price * $item->quantity);

        return view('orders.cart', compact('cartItems', 'total'));
    }

    /**
     * Add book to cart.
     */
    public function addToCart(Book $book, Request $request)
    {
        $quantity = $request->get('quantity', 1);
        $user = Auth::user();

        $cartItem = Cart::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'quantity' => $quantity
            ]);
        }

        return redirect()->back()->with('success', 'Book added to cart!');
    }

    /**
     * Update cart item quantity.
     */
    public function updateCart(Book $book, Request $request)
    {
        $user = Auth::user();
        $quantity = $request->input('quantity', 1);

        if ($quantity > 0) {
            Cart::updateOrCreate(
                ['user_id' => $user->id, 'book_id' => $book->id],
                ['quantity' => $quantity]
            );
        } else {
            Cart::where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->delete();
        }

        // Return JSON for AJAX requests, redirect for form submissions
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Cart updated!']);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    /**
     * Remove book from cart.
     */
    public function removeFromCart(Book $book)
    {
        Cart::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->delete();

        return redirect()->back()->with('success', 'Book removed from cart!');
    }

    /**
     * Display a listing of the user's orders (or all orders for admin).
     */
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            // Admin view: show all orders grouped by user
            $ordersByUser = Order::with(['orderItems.book', 'user'])
                ->latest()
                ->get()
                ->groupBy('user_id');

            return view('admin.orders.index', compact('ordersByUser'));
        } else {
            // Customer view: show only user's orders
            $orders = Auth::user()->orders()->with('orderItems.book')->latest()->paginate(10);
            return view('orders.index', compact('orders'));
        }
    }

    /**
     * Store a newly created order from cart.
     */
    public function store(Request $request)
    {
        // Check if it's a direct purchase
        if ($request->has('book_id')) {
            $book = Book::findOrFail($request->book_id);
            $quantity = $request->get('quantity', 1);

            if ($book->stock_quantity < $quantity) {
                return redirect()->back()->with('error', 'Not enough stock available!');
            }

            $total = $book->price * $quantity;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending'
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $book->id,
                'quantity' => $quantity,
                'unit_price' => $book->price
            ]);

            // Update stock
            $book->decrement('stock_quantity', $quantity);

            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
        }

        // Handle cart checkout
        $cartItems = Auth::user()->cart()->with('book')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        $total = 0;
        $orderItems = [];

        foreach ($cartItems as $cartItem) {
            $book = $cartItem->book;
            if ($book->stock_quantity >= $cartItem->quantity) {
                $total += $book->price * $cartItem->quantity;
                $orderItems[] = [
                    'book_id' => $book->id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $book->price
                ];
            } else {
                return redirect()->route('cart')->with('error', 'Some items are out of stock!');
            }
        }

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            $item['order_id'] = $order->id;
            OrderItem::create($item);

            // Update stock
            $book = Book::find($item['book_id']);
            $book->decrement('stock_quantity', $item['quantity']);
        }

        // Clear cart from database
        Auth::user()->cart()->delete();

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
    }

    /**
     * Cancel an order (restore stock and update status).
     */
    public function cancel(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow cancelling pending orders
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be cancelled.');
        }

        // Restore stock for all items in the order
        foreach ($order->orderItems as $item) {
            $book = Book::find($item->book_id);
            if ($book) {
                $book->increment('stock_quantity', $item->quantity);
            }
        }

        // Update order status
        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.index')->with('success', 'Order cancelled successfully. Stock has been restored.');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Check if user owns this order or is admin
        if ($order->user_id !== Auth::id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load('orderItems.book', 'user');

        if (auth()->user()->isAdmin()) {
            return view('admin.orders.show', compact('order'));
        } else {
            return view('orders.show', compact('order'));
        }
    }
}
