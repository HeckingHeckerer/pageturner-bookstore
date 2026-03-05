@extends('layouts.app') <!-- if you have a layout -->

@section('content')
<h1 class="text-3xl font-bold text-white">All Categories</h1>

<ul class=" text-gray-300 mt-4">
@foreach($categories as $category)
    <li>{{ $category->name }} ({{ $category->books_count }} books)</li>
@endforeach
</ul>

{{ $categories->links() }} <!-- pagination links -->
@endsection