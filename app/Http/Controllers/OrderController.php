
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
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
        // Validate shipping address
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_zip' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:255',
        ]);

        // Check if it's a direct purchase
        if ($request->has('book_id')) {
            $book = Book::findOrFail($request->book_id);
            $quantity = $request->get('quantity', 1);

            if ($book->stock_quantity < $quantity) {
                return redirect()->back()->with('error', 'Not enough stock available!');
            }

            $total = $book->price * $quantity;

            // Create order with shipping info
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zip' => $request->shipping_zip,
                'shipping_country' => $request->shipping_country,
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

            // Save as default shipping address if requested
            if ($request->has('save_as_default')) {
                Auth::user()->update([
                    'default_shipping_address' => $request->shipping_address,
                    'default_shipping_city' => $request->shipping_city,
                    'default_shipping_state' => $request->shipping_state,
                    'default_shipping_zip' => $request->shipping_zip,
                    'default_shipping_country' => $request->shipping_country,
                ]);
            }

            // Save address to addresses table
            $this->saveUserAddress($request, Auth::user());

            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
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

        // Create order with shipping info
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $total,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_state' => $request->shipping_state,
            'shipping_zip' => $request->shipping_zip,
            'shipping_country' => $request->shipping_country,
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

        // Save as default shipping address if requested
        if ($request->has('save_as_default')) {
            Auth::user()->update([
                'default_shipping_address' => $request->shipping_address,
                'default_shipping_city' => $request->shipping_city,
                'default_shipping_state' => $request->shipping_state,
                'default_shipping_zip' => $request->shipping_zip,
                'default_shipping_country' => $request->shipping_country,
            ]);
        }

        // Save address to addresses table
        $this->saveUserAddress($request, Auth::user());

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    /**
     * Save user address to addresses table
     */
    private function saveUserAddress(Request $request, $user)
    {
        // Check if this address already exists for the user
        $existingAddress = Address::where('user_id', $user->id)
            ->where('address', $request->shipping_address)
            ->where('city', $request->shipping_city)
            ->where('state', $request->shipping_state)
            ->where('zip', $request->shipping_zip)
            ->where('country', $request->shipping_country)
            ->first();

        if (!$existingAddress) {
            // Create new address
            $address = Address::create([
                'user_id' => $user->id,
                'name' => $request->address_name ?? null,
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'state' => $request->shipping_state,
                'zip' => $request->shipping_zip,
                'country' => $request->shipping_country,
                'is_default' => $request->has('save_as_default'),
            ]);

            // If this is set as default, update other addresses
            if ($request->has('save_as_default')) {
                Address::where('user_id', $user->id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }
        } elseif ($request->has('save_as_default') && !$existingAddress->is_default) {
            // Update existing address to be default
            $existingAddress->update(['is_default' => true]);
            Address::where('user_id', $user->id)
                ->where('id', '!=', $existingAddress->id)
                ->update(['is_default' => false]);
        }
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
     * Update the shipping address for an order.
     */
    public function updateShipping(Order $order, Request $request)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow updating shipping for pending orders
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Shipping address can only be updated for pending orders.');
        }

        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_zip' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:255',
        ]);

        $order->update($request->only(['shipping_address', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country']));

        return redirect()->back()->with('success', 'Shipping address updated successfully.');
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

    /**
     * Update order status by admin.
     */
    public function updateStatus(Order $order, Request $request)
    {
        // Only allow admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        // Cannot update if already cancelled
        if ($order->status === 'cancelled') {
            return redirect()->back()->with('error', 'Cannot update status of a cancelled order.');
        }

        $request->validate([
            'status' => 'required|in:processing,completed,cancelled',
        ]);

        // Update order status
        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Order status updated to ' . ucfirst($request->status) . '.');
    }
}


