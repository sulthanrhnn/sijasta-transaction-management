<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class AdminUserController extends Controller
{
    public function index(): View
    {
        return view('admin.layout.wrapper', [
            'user' => User::orderBy('name')->paginate(20),
            'content' => 'admin.user.index',
        ]);
    }

    public function create(): View
    {
        return view('admin.layout.wrapper', [
            'content' => 'admin.user.create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'asisten', 'mitra'])],
        ]);

        User::create($data);
        Alert::success('Berhasil', 'Pengguna berhasil ditambahkan.');

        return redirect()->route('user.index');
    }

    public function edit(User $user): View
    {
        return view('admin.layout.wrapper', [
            'user' => $user,
            'content' => 'admin.user.create',
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'asisten', 'mitra'])],
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);
        Alert::success('Berhasil', 'Pengguna berhasil diperbarui.');

        return redirect()->route('user.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            Alert::error('Gagal', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
            return back();
        }

        $user->delete();
        Alert::success('Berhasil', 'Pengguna berhasil dihapus.');

        return redirect()->route('user.index');
    }
}
