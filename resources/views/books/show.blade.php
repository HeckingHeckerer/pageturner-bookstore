@extends('layouts.app')
@section('title', $book->title . ' - PageTurner')

@section('content')
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="md:flex">
        {{-- Book Cover --}}
        <div class="md:w-1/3 bg-gray-200 p-8 flex items-center justify-center">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="max-h-96 object-contain">
            @else
                <svg class="h-48 w-48 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            @endif
        </div>

        {{-- Book Details --}}
        <div class="md:w-2/3 p-8">
            <span class="text-indigo-600 text-sm font-medium ">{{ $book->category->name }}</span>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $book->title }}</h1>
            <p class="text-xl text-gray-600 mt-1">by {{ $book->author }}</p>

            {{-- Rating --}}
            <div class="flex items-center mt-4">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="h-6 w-6 {{ $i <= round($book->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
                <span class="ml-2 text-gray-600">{{ number_format($book->average_rating, 1) }} ({{ $book->reviews->count() }} reviews)</span>
            </div>

            <p class="text-3xl font-bold text-indigo-600 mt-4">{{ number_format($book->price, 2) }}</p>

            <div class="mt-4">
                <span class="text-sm {{ $book->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                    @if($book->stock_quantity > 0)
                        In Stock ({{ $book->stock_quantity }} available)
                    @else
                        Out of Stock
                    @endif
                </span>
            </div>

            @if($book->stock_quantity > 0)
                <div class="mt-6 flex space-x-4">
                    <form action="{{ route('cart.add', $book) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            Add to Cart
                        </button>
                    </form>

                    @auth
                        <button type="button" onclick="openBuyNowModal({{ $book->id }}, '{{ $book->title }}', '{{ $book->author }}', {{ $book->price }}, '{{ $book->cover_image ? asset('storage/'.$book->cover_image) : 'https://via.placeholder.com/80' }}')" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                            Buy Now
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition inline-block">
                            Buy Now
                        </a>
                    @endauth
                </div>
            @endif

            <div class="mt-4">
                <p class="text-gray-600 text-sm"><strong>ISBN:</strong> {{ $book->isbn }}</p>
            </div>

            <div class="mt-6">
                <h3 class="font-semibold text-gray-800 ">Description</h3>
                <p class="text-gray-600 mt-2 ">{{ $book->description }}</p>
            </div>

            {{-- Admin Actions --}}
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('admin.books.edit', $book) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                            Edit Book
                        </a>
                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                                Delete Book
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>

{{-- Reviews Section --}}
<div class="mt-8">
    <h2 class="text-2xl font-bold mb-6 text-white">Customer Reviews</h2>

    {{-- Review Form --}}
    @auth
        @php
            $hasPurchased = \App\Models\Order::where('user_id', auth()->id())
                ->whereHas('orderItems', function ($query) use ($book) {
                    $query->where('book_id', $book->id);
                })
                ->exists();
        @endphp

        @if($hasPurchased)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4">Write a Review</h3>
                <form action="{{ route('reviews.store', $book) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Rating</label>
                        <div class="flex space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-8 w-8 cursor-pointer star-rating text-gray-300 hover:text-yellow-400" data-rating="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-input" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Comment</label>
                        <textarea name="comment" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Share your thoughts about this book..."></textarea>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                        Submit Review
                    </button>
                </form>
            </div>
        @else
            <x-alert type="info" class="mb-6">
                You must <a href="{{ route('books.show', $book) }}" class="text-indigo-600 hover:underline">purchase this book</a> to write a review.
            </x-alert>
        @endauth
    @else
        <x-alert type="info" class="mb-6">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login</a> to write a review.
        </x-alert>
    @endauth

    {{-- Display Reviews --}}
    @forelse($book->reviews as $review)
        <div class="bg-white rounded-lg shadow p-6 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-semibold">{{ $review->user->name }}</p>
                    <div class="flex items-center mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-gray-500 text-sm">{{ $review->created_at->diffForHumans() }}</span>
                    @auth
                        @if(auth()->id() === $review->user_id || auth()->user()->isAdmin())
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
            @isset($review->comment)
                <p class="text-gray-600 mt-3">{{ $review->comment }}</p>
            @endisset
        </div>
    @empty
        <x-alert type="info">
            No reviews yet. Be the first to review this book!
        </x-alert>
    @endforelse
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating');
    const ratingInput = document.getElementById('rating-input');
    let selectedRating = 0;

    stars.forEach(star => {
        star.addEventListener('click', function() {
            selectedRating = parseInt(this.dataset.rating);
            ratingInput.value = selectedRating;

            // Update star colors
            stars.forEach((s, index) => {
                if (index < selectedRating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });

        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.dataset.rating);
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });

        star.addEventListener('mouseout', function() {
            stars.forEach((s, index) => {
                if (index < selectedRating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });
});
</script>

<!-- Buy Now Modal -->
<div id="buyNowModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl mx-4 w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center mb-4">
            <svg class="h-8 w-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900">Confirm Your Purchase</h3>
        </div>
        
        <div class="mb-6">
            <h4 class="font-semibold mb-4">Product Details</h4>
            <div class="flex items-center border rounded-lg p-4 bg-gray-50">
                <img id="modal-book-image" src="" alt="Book Cover" class="w-16 h-20 object-cover rounded mr-4">
                <div class="flex-1">
                    <h5 id="modal-book-title" class="font-semibold text-lg"></h5>
                    <p id="modal-book-author" class="text-gray-600"></p>
                    <p class="text-lg font-bold text-green-600 mt-1" id="modal-book-price"></p>
                </div>
            </div>
        </div>

        <form id="buyNowForm" action="{{ route('orders.store') }}" method="POST" onsubmit="closeBuyNowModal()">
            @csrf
            <input type="hidden" id="modal-book-id" name="book_id" value="">
            <input type="hidden" name="quantity" value="1">
            
            <div class="mb-6">
                <h4 class="font-semibold mb-4">Shipping Address</h4>
                <div id="existing-address-section">
                    <div class="border rounded-lg p-4 bg-blue-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="font-medium text-blue-900">Your Default Shipping Address</h5>
                                <div id="existing-address-display" class="mt-2 text-gray-700">
                                    <!-- Address will be populated by JavaScript -->
                                </div>
                            </div>
                            <button type="button" onclick="editAddress()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Change
                            </button>
                        </div>
                    </div>
                </div>

                <div id="address-form-section" class="hidden">
                    <div class="space-y-4">
                        <div>
                            <label for="saved_addresses" class="block text-sm font-medium text-gray-700">Select Saved Address (Optional)</label>
                            <select id="saved_addresses" onchange="populateAddress(this.value)" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                <option value="">-- Choose from saved addresses --</option>
                                @auth
                                    @foreach(Auth::user()->addresses as $address)
                                        <option value="{{ $address->id }}" 
                                                data-address="{{ $address->address }}"
                                                data-city="{{ $address->city }}"
                                                data-state="{{ $address->state }}"
                                                data-zip="{{ $address->zip }}"
                                                data-country="{{ $address->country }}">
                                            {{ $address->name ? $address->name . ': ' : '' }}{{ $address->address }}, {{ $address->city }}
                                        </option>
                                    @endforeach
                                @endauth
                            </select>
                        </div>

                        <div>
                            <label for="address_name" class="block text-sm font-medium text-gray-700">Address Name (Optional)</label>
                            <input type="text" id="address_name" name="address_name" placeholder="e.g., Home, Work, etc." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" id="shipping_address" name="shipping_address" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="shipping_city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" id="shipping_city" name="shipping_city" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label for="shipping_state" class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" id="shipping_state" name="shipping_state" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="shipping_zip" class="block text-sm font-medium text-gray-700">ZIP Code</label>
                                <input type="text" id="shipping_zip" name="shipping_zip" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country</label>
                                <input type="text" id="shipping_country" name="shipping_country" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="save_as_default" name="save_as_default" checked class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="save_as_default" class="ml-2 block text-sm text-gray-900">
                                Save as my default shipping address
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeBuyNowModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition font-medium">
                    Confirm Purchase
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openBuyNowModal(bookId, title, author, price, image) {
    // Populate product details
    document.getElementById('modal-book-id').value = bookId;
    document.getElementById('modal-book-title').textContent = title;
    document.getElementById('modal-book-author').textContent = 'by ' + author;
    document.getElementById('modal-book-price').textContent = '$' + price.toFixed(2);
    document.getElementById('modal-book-image').src = image;

    // Check if user has default address
    @auth
        @if(Auth::user()->default_shipping_address)
            // Show existing address
            document.getElementById('existing-address-section').classList.remove('hidden');
            document.getElementById('address-form-section').classList.add('hidden');
            document.getElementById('existing-address-display').innerHTML = `
                <p>{{ Auth::user()->default_shipping_address }}</p>
                <p>{{ Auth::user()->default_shipping_city }}, {{ Auth::user()->default_shipping_state }} {{ Auth::user()->default_shipping_zip }}</p>
                <p>{{ Auth::user()->default_shipping_country }}</p>
            `;
            // Populate form fields with default values even though form is hidden
            document.getElementById('shipping_address').value = '{{ Auth::user()->default_shipping_address }}';
            document.getElementById('shipping_city').value = '{{ Auth::user()->default_shipping_city }}';
            document.getElementById('shipping_state').value = '{{ Auth::user()->default_shipping_state }}';
            document.getElementById('shipping_zip').value = '{{ Auth::user()->default_shipping_zip }}';
            document.getElementById('shipping_country').value = '{{ Auth::user()->default_shipping_country }}';
        @else
            // Show address form
            document.getElementById('existing-address-section').classList.add('hidden');
            document.getElementById('address-form-section').classList.remove('hidden');
        @endif
    @endauth

    document.getElementById('buyNowModal').classList.remove('hidden');
}

function closeBuyNowModal() {
    document.getElementById('buyNowModal').classList.add('hidden');
}

function editAddress() {
    // Populate form with existing address if available
    @auth
        @if(Auth::user()->default_shipping_address)
            document.getElementById('shipping_address').value = '{{ Auth::user()->default_shipping_address }}';
            document.getElementById('shipping_city').value = '{{ Auth::user()->default_shipping_city }}';
            document.getElementById('shipping_state').value = '{{ Auth::user()->default_shipping_state }}';
            document.getElementById('shipping_zip').value = '{{ Auth::user()->default_shipping_zip }}';
            document.getElementById('shipping_country').value = '{{ Auth::user()->default_shipping_country }}';
        @endif
    @endauth

    document.getElementById('existing-address-section').classList.add('hidden');
    document.getElementById('address-form-section').classList.remove('hidden');
}

function populateAddress(addressId) {
    if (!addressId) {
        // Clear all fields if "Choose from saved addresses" is selected
        document.getElementById('address_name').value = '';
        document.getElementById('shipping_address').value = '';
        document.getElementById('shipping_city').value = '';
        document.getElementById('shipping_state').value = '';
        document.getElementById('shipping_zip').value = '';
        document.getElementById('shipping_country').value = '';
        return;
    }

    const select = document.getElementById('saved_addresses');
    const option = select.querySelector(`option[value="${addressId}"]`);
    
    if (option) {
        document.getElementById('shipping_address').value = option.getAttribute('data-address');
        document.getElementById('shipping_city').value = option.getAttribute('data-city');
        document.getElementById('shipping_state').value = option.getAttribute('data-state');
        document.getElementById('shipping_zip').value = option.getAttribute('data-zip');
        document.getElementById('shipping_country').value = option.getAttribute('data-country');
    }
}

// Close modal when clicking outside
document.getElementById('buyNowModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeBuyNowModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBuyNowModal();
    }
});
</script>
@endsection
