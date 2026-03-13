<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $usersQuery = User::query();

        if ($search) {
            $usersQuery->where('name', 'like', '%' . $search . '%')
                       ->orWhere('email', 'like', '%' . $search . '%');
        }

        $users = $usersQuery->latest()->paginate(15);
        $totalUsers = User::count();

        return view('admin.users.index', compact('users', 'totalUsers', 'search'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['user', 'admin'])],
            'default_shipping_address' => 'nullable|string|max:255',
            'default_shipping_city' => 'nullable|string|max:100',
            'default_shipping_state' => 'nullable|string|max:100',
            'default_shipping_zip' => 'nullable|string|max:20',
            'default_shipping_country' => 'nullable|string|max:100',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'default_shipping_address' => $validated['default_shipping_address'] ?? null,
            'default_shipping_city' => $validated['default_shipping_city'] ?? null,
            'default_shipping_state' => $validated['default_shipping_state'] ?? null,
            'default_shipping_zip' => $validated['default_shipping_zip'] ?? null,
            'default_shipping_country' => $validated['default_shipping_country'] ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['user', 'admin'])],
            'default_shipping_address' => 'nullable|string|max:255',
            'default_shipping_city' => 'nullable|string|max:100',
            'default_shipping_state' => 'nullable|string|max:100',
            'default_shipping_zip' => 'nullable|string|max:20',
            'default_shipping_country' => 'nullable|string|max:100',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'default_shipping_address' => $validated['default_shipping_address'] ?? $user->default_shipping_address,
            'default_shipping_city' => $validated['default_shipping_city'] ?? $user->default_shipping_city,
            'default_shipping_state' => $validated['default_shipping_state'] ?? $user->default_shipping_state,
            'default_shipping_zip' => $validated['default_shipping_zip'] ?? $user->default_shipping_zip,
            'default_shipping_country' => $validated['default_shipping_country'] ?? $user->default_shipping_country,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting self
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}

