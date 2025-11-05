<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected function ensureAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }

    public function index(Request $request)
    {
        $this->ensureAdmin();

        $filter = $request->get('filter', 'pending');
        if ($filter === 'all') {
            $users = User::orderBy('created_at', 'desc')->paginate(20);
        } else {
            $users = User::where('approved', false)->orderBy('created_at', 'desc')->paginate(20);
        }

        return view('admin.users.index', compact('users', 'filter'));
    }

    public function create()
    {
        $this->ensureAdmin();
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,staff',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['approved'] = $request->has('approved') ? true : false;

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $this->ensureAdmin();
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,staff',
            'approved' => 'sometimes|boolean',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['approved'] = $request->has('approved') ? true : false;

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->ensureAdmin();
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User dihapus.');
    }

    public function approve(User $user)
    {
        $this->ensureAdmin();
        $user->approved = true;
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'User telah disetujui.');
    }
}
