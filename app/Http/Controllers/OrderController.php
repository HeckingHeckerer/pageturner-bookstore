<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
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
        $cart = session()->get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $bookId => $quantity) {
            $book = Book::find($bookId);
            if ($book) {
                $cartItems[] = [
                    'book' => $book,
                    'quantity' => $quantity,
                    'subtotal' => $book->price * $quantity
                ];
                $total += $book->price * $quantity;
            }
        }

        return view('orders.cart', compact('cartItems', 'total'));
    }

    /**
     * Add book to cart.
     */
    public function addToCart(Book $book, Request $request)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->get('quantity', 1);

        if (isset($cart[$book->id])) {
            $cart[$book->id] += $quantity;
        } else {
            $cart[$book->id] = $quantity;
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Book added to cart!');
    }

    /**
     * Update cart item quantity.
     */
    public function updateCart(Book $book, Request $request)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->get('quantity', 1);

        if ($quantity > 0) {
            $cart[$book->id] = $quantity;
        } else {
            unset($cart[$book->id]);
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Cart updated!');
    }

    /**
     * Remove book from cart.
     */
    public function removeFromCart(Book $book)
    {
        $cart = session()->get('cart', []);
        unset($cart[$book->id]);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Book removed from cart!');
    }

    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with('orderItems.book')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
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
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        $total = 0;
        $orderItems = [];

        foreach ($cart as $bookId => $quantity) {
            $book = Book::find($bookId);
            if ($book && $book->stock_quantity >= $quantity) {
                $total += $book->price * $quantity;
                $orderItems[] = [
                    'book_id' => $bookId,
                    'quantity' => $quantity,
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

        // Clear cart
        session()->forget('cart');

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
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load('orderItems.book');
        return view('orders.show', compact('order'));
    }
}
