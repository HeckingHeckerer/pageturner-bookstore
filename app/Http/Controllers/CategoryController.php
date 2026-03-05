<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function show(Category $category)
    {
        $books = $category->books()->paginate(12);

        return view('categories.show', compact('category', 'books'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $cascade = request('cascade', false);

        // Check if category has books and cascade is not requested
        if ($category->books()->count() > 0 && !$cascade) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category that contains books. Please remove all books from this category first.'
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'Cannot delete category that contains books. Please remove all books from this category first.');
        }

        // If cascade delete is requested, delete all books first
        if ($cascade) {
            $booksCount = $category->books()->count();
            $category->books()->delete(); // This will delete all books in the category

            // Log the cascade deletion for audit purposes
            \Log::info("Cascade delete: Deleted category '{$category->name}' and {$booksCount} associated books");
        }

        $category->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $cascade
                    ? 'Category and all associated books deleted successfully!'
                    : 'Category deleted successfully!'
            ]);
        }

        return redirect()->route('categories.index')
            ->with('success', $cascade
                ? 'Category and all associated books deleted successfully!'
                : 'Category deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = Category::withCount('books');

        if ($request->filled('category')) {
            $query->where('id', $request->category);
        }

        $categories = $query->get();

        return response()->json([
            'categories' => $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'books_count' => $category->books_count,
                ];
            })
        ]);
    }
}
