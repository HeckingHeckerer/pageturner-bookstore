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
                        <a href="{{ route('admin.books.index') }}" class="block w-full text-center bg-red-600 text-white py-2 rounded hover:bg-red-700 transition" onclick="openDeleteBooksModal(); return false;">
                            Delete Books
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
                        <a href="{{ route('admin.categories.index') }}" class="block w-full text-center bg-red-600 text-white py-2 rounded hover:bg-red-700 transition" onclick="openDeleteCategoriesModal(); return false;">
                            Delete Categories
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

<!-- Delete Books Modal -->
<div id="deleteBooksModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b">
            <h3 class="text-xl font-bold text-gray-900">Delete Books</h3>
            <button onclick="closeDeleteBooksModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <!-- Search and Filter -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <form id="bookSearchForm" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input
                            type="text"
                            id="bookSearch"
                            placeholder="Search by title or author..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <div class="w-48">
                        <select
                            id="bookCategory"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">All Categories</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button
                        type="button"
                        onclick="searchBooks()"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition"
                    >
                        Search
                    </button>
                </form>
            </div>

            <!-- Books Grid -->
            <div id="booksContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-h-96 overflow-y-auto">
                <!-- Books will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Categories Modal -->
<div id="deleteCategoriesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b">
            <h3 class="text-xl font-bold text-gray-900">Delete Categories</h3>
            <button onclick="closeDeleteCategoriesModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <!-- Category Filter -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <select
                    id="categoryFilter"
                    onchange="filterCategories()"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                >
                    <option value="">All Categories</option>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Categories Grid -->
            <div id="categoriesContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-96 overflow-y-auto">
                <!-- Categories will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-4">
        <div class="flex items-center mb-4">
            <svg class="h-8 w-8 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 5v.01M7.08 6.36A9 9 0 1021 12a9 9 0 00-13.92-5.64z"></path>
            </svg>
            <h3 id="confirmTitle" class="text-xl font-bold text-gray-900">Confirm Deletion</h3>
        </div>

        <p id="confirmMessage" class="text-gray-600 mb-6">Are you sure you want to delete this item? This action cannot be undone.</p>

        <div class="flex justify-end space-x-4">
            <button onclick="closeConfirmModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition">
                Cancel
            </button>
            <button id="confirmDeleteBtn" onclick="confirmDelete()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
let deleteItemType = '';
let deleteItemId = '';

function openDeleteBooksModal() {
    document.getElementById('deleteBooksModal').classList.remove('hidden');
    loadBooks();
}

function closeDeleteBooksModal() {
    document.getElementById('deleteBooksModal').classList.add('hidden');
}

function openDeleteCategoriesModal() {
    document.getElementById('deleteCategoriesModal').classList.remove('hidden');
    loadCategories();
}

function closeDeleteCategoriesModal() {
    document.getElementById('deleteCategoriesModal').classList.add('hidden');
}

function closeConfirmModal() {
    document.getElementById('deleteConfirmModal').classList.add('hidden');
    // Reset any dynamic content
    document.getElementById('confirmMessage').innerHTML = '';
}

function searchBooks() {
    loadBooks();
}

function filterCategories() {
    loadCategories();
}

function loadBooks() {
    const search = document.getElementById('bookSearch').value;
    const category = document.getElementById('bookCategory').value;

    fetch(`/admin/books/search?search=${encodeURIComponent(search)}&category=${category}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('booksContainer');
        container.innerHTML = '';

        if (data.books.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">No books found.</div>';
            return;
        }

        data.books.forEach(book => {
            const bookCard = `
                <div class="bg-white border rounded-lg shadow-sm p-4">
                    <div class="flex items-start space-x-4">
                        <img src="${book.cover_image ? '/storage/' + book.cover_image : 'https://via.placeholder.com/80'}"
                             alt="${book.title}"
                             class="w-20 h-28 object-cover rounded">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg">${book.title}</h3>
                            <p class="text-gray-600">by ${book.author}</p>
                            <p class="text-sm text-gray-500">ISBN: ${book.isbn}</p>
                            <p class="text-sm text-gray-500">Category: ${book.category.name}</p>
                            <p class="text-sm text-gray-500">Stock: ${book.stock_quantity}</p>
                            <p class="font-bold text-indigo-600">$${book.price}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button onclick="confirmDeleteBook(${book.id}, '${book.title}')"
                                class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                            Delete Book
                        </button>
                    </div>
                </div>
            `;
            container.innerHTML += bookCard;
        });
    })
    .catch(error => {
        console.error('Error loading books:', error);
        document.getElementById('booksContainer').innerHTML = '<div class="col-span-full text-center py-8 text-red-500">Error loading books.</div>';
    });
}

function loadCategories() {
    const categoryId = document.getElementById('categoryFilter').value;

    fetch(`/admin/categories/search?category=${categoryId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('categoriesContainer');
        container.innerHTML = '';

        if (data.categories.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">No categories found.</div>';
            return;
        }

        data.categories.forEach(category => {
            const hasBooks = category.books_count > 0;
            const categoryCard = `
                <div class="bg-white border rounded-lg shadow-sm p-4 ${hasBooks ? 'border-orange-200 bg-orange-50' : ''}">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-semibold text-lg">${category.name}</h3>
                        ${hasBooks ? '<span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded">Has Books</span>' : '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Empty</span>'}
                    </div>
                    <p class="text-gray-600 mb-2">${category.books_count} ${category.books_count === 1 ? 'book' : 'books'}</p>
                    <p class="text-sm text-gray-500 mb-4">${category.description || 'No description available.'}</p>
                    <button onclick="confirmDeleteCategory(${category.id}, '${category.name}', ${category.books_count})"
                            class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                        ${hasBooks ? 'Delete Category & Books' : 'Delete Category'}
                    </button>
                </div>
            `;
            container.innerHTML += categoryCard;
        });
    })
    .catch(error => {
        console.error('Error loading categories:', error);
        document.getElementById('categoriesContainer').innerHTML = '<div class="col-span-full text-center py-8 text-red-500">Error loading categories.</div>';
    });
}

function confirmDeleteBook(bookId, bookTitle) {
    deleteItemType = 'book';
    deleteItemId = bookId;
    document.getElementById('confirmTitle').textContent = 'Delete Book';
    document.getElementById('confirmMessage').textContent = `Are you sure you want to delete "${bookTitle}"? This action cannot be undone.`;
    document.getElementById('deleteConfirmModal').classList.remove('hidden');
}

function confirmDeleteCategory(categoryId, categoryName, booksCount) {
    deleteItemType = 'category';
    deleteItemId = categoryId;

    if (booksCount > 0) {
        // Category has books - show cascade delete options
        document.getElementById('confirmTitle').textContent = 'Delete Category and Books';
        document.getElementById('confirmMessage').innerHTML = `
            <div class="mb-4">
                <p class="mb-3">The category "${categoryName}" contains ${booksCount} book${booksCount > 1 ? 's' : ''}. What would you like to do?</p>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="deleteOption" value="cascade" class="mr-2" checked>
                        <span class="text-sm">Delete category AND all ${booksCount} book${booksCount > 1 ? 's' : ''} in this category</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="deleteOption" value="cancel" class="mr-2">
                        <span class="text-sm">Cancel - keep the category and its books</span>
                    </label>
                </div>
            </div>
            <p class="text-red-600 font-semibold">Warning: Cascade deletion cannot be undone!</p>
        `;
        document.getElementById('confirmDeleteBtn').textContent = 'Delete Category & Books';
    } else {
        // Category is empty - normal delete
        document.getElementById('confirmTitle').textContent = 'Delete Category';
        document.getElementById('confirmMessage').textContent = `Are you sure you want to delete "${categoryName}"? This action cannot be undone.`;
        document.getElementById('confirmDeleteBtn').textContent = 'Delete Category';
    }

    document.getElementById('deleteConfirmModal').classList.remove('hidden');
}

function confirmDelete() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let url = '';
    let data = {};

    if (deleteItemType === 'book') {
        url = `/admin/books/${deleteItemId}`;
    } else if (deleteItemType === 'category') {
        url = `/admin/categories/${deleteItemId}`;

        // Check if cascade delete is selected
        const cascadeOption = document.querySelector('input[name="deleteOption"]:checked');
        if (cascadeOption && cascadeOption.value === 'cascade') {
            data.cascade = true;
        } else if (cascadeOption && cascadeOption.value === 'cancel') {
            closeConfirmModal();
            return; // Don't proceed with deletion
        }
    }

    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: Object.keys(data).length > 0 ? JSON.stringify(data) : null
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeConfirmModal();
            if (deleteItemType === 'book') {
                loadBooks(); // Reload books
            } else {
                loadCategories(); // Reload categories
            }
            // You could add a success message here
        } else {
            alert('Error deleting item: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error deleting item:', error);
        alert('Error deleting item. Please try again.');
    });
}

// Load initial data when modals open
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token meta tag if not present
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const csrfMeta = document.createElement('meta');
        csrfMeta.name = 'csrf-token';
        csrfMeta.content = '{{ csrf_token() }}';
        document.head.appendChild(csrfMeta);
    }
});
</script>
@endsection
