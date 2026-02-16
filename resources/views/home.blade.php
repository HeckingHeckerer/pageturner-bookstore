@extends('layouts.app')

@section('title', 'PageTurner - Online Bookstore')

@section('content')
<div class="bg-gray-900 text-white min-h-screen p-6">

    {{-- Hero Section --}}
    <div class="bg-gray-800 text-white rounded-lg p-8 mb-8">
        <h1 class="text-4xl font-bold mb-4">Welcome to PageTurner</h1>
        <p class="text-xl text-gray-300 mb-6">
            Discover your next favorite book from our extensive collection.
        </p>

        <a href="{{ route('books.index') }}" 
           class="bg-white text-gray-900 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition">
            Browse Books
        </a>
    </div>

    {{-- Categories Section --}}
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Browse by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" 
                   class="bg-gray-800 p-4 rounded-lg shadow hover:shadow-md hover:bg-gray-700 transition text-center">
                    <h3 class="font-semibold text-white">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-400">
                        {{ $category->books_count }} books
                    </p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured Books Section --}}
    <section>
        <h2 class="text-2xl font-bold mb-6">Featured Books</h2>

        @forelse($featuredBooks as $book)
            @if($loop->first)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @endif

            <x-book-card :book="$book" />

            @if($loop->last)
                </div>
            @endif
        @empty
            <x-alert type="info">
                No books available at the moment. Check back soon!
            </x-alert>
        @endforelse
    </section>

</div>
@endsection
