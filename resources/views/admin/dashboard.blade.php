@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-2 text-gray-600">Manage your bookstore content and orders</p>
        </div>

        <!-- Management Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Books Management Card -->
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">Books</h2>
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 12s4.5 5.747 10 5.747m0-13c5.5 0 10 4.745 10 5.747s-4.5 5.747-10 5.747m0-13v13m0 0c5.5 0 10 4.745 10 5.747s-4.5 5.747-10 5.747m0 0c-5.493 0-10-4.745-10-5.747"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-6">Manage your book catalog. Create, edit, and delete books.</p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.books.index') }}" class="block w-full text-center bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition">
                            View All Books
                        </a>
                        <a href="{{ route('admin.books.create') }}" class="block w-full text-center bg-indigo-100 text-indigo-600 py-2 rounded hover:bg-indigo-200 transition">
                            Add New Book
                        </a>
                    </div>
                </div>
            </div>

            <!-- Categories Management Card -->
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">Categories</h2>
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-6">Manage book categories. Organize your library with different genres.</p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.categories.index') }}" class="block w-full text-center bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">
                            View All Categories
                        </a>
                        <a href="{{ route('admin.categories.create') }}" class="block w-full text-center bg-green-100 text-green-600 py-2 rounded hover:bg-green-200 transition">
                            Add New Category
                        </a>
                    </div>
                </div>
            </div>

            <!-- Orders Management Card -->
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">Orders</h2>
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-6">View and manage customer orders. Track order status and history.</p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.orders.index') }}" class="block w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                            View All Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats (Optional) -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-600 text-sm font-semibold uppercase">Total Books</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\Book::count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-600 text-sm font-semibold uppercase">Total Categories</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\Category::count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-600 text-sm font-semibold uppercase">Total Orders</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\Order::count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
