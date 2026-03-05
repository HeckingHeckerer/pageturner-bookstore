@extends('layouts.app') <!-- if you have a layout -->

@section('content')
<h1 class="text-3xl font-bold text-white">All Categories</h1>

<ul class="text-gray-300 mt-4 space-y-6">
    @foreach($categories as $category)
        <li class="p-4 bg-gray-800 rounded-lg">
            <h2 class="text-xl font-semibold text-white">{{ $category->name }}</h2>

            <p class="mt-1">
                <a href="{{ route('categories.show', $category) }}" class="text-indigo-400 hover:underline">
                    {{ $category->books_count }} {{ Str::plural('book', $category->books_count) }}
                </a>
            </p>

            @if($category->description)
                <p class="mt-2 text-gray-400">{{ $category->description }}</p>
            @else
                <p class="mt-2 text-gray-400 italic">No description available.</p>
            @endif
        </li>
    @endforeach
</ul>

{{ $categories->links() }} <!-- pagination links -->
@endsection