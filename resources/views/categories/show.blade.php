@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Category Header --}}
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-white">
            {{ $category->name }}
        </h1>

        @if($category->description)
            <p class="text-gray-600 mt-2 ">
                {{ $category->description }}
            </p>
        @endif
    </div>

    <hr class="my-4">

    {{-- Books Section --}}
   <h2 class="text-xl font-semibold mb-3 text-purple-800">
        Books in this Category
    </h2>

    @if($books->count())
        <div class="grid grid-cols-3 gap-4">
            @foreach($books as $book)
                <div class="border p-3 rounded shadow-sm">
                    <h3 class="font-bold text-white">
                        <a href="{{ route('books.show', $book) }}">
                            {{ $book->title }}
                        </a>
                    </h3>

                    @if($book->author)
                        <p class="text-sm text-gray-500">
                            by {{ $book->author }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $books->links() }}
        </div>

    @else
        <p class="text-gray-500">
            No books found in this category.
        </p>
    @endif

</div>
@endsection
