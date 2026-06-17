<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class AdminMitraController extends Controller
{
    public function index(): View
    {
        return view('admin.layout.wrapper', [
            'mitra' => Mitra::orderBy('name')->paginate(20),
            'content' => 'admin.mitra.index',
        ]);
    }

    public function create(): View
    {
        return view('admin.layout.wrapper', [
            'content' => 'admin.mitra.create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:mitras,email'],
            'no_hp' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string', 'max:255'],
        ]);

        Mitra::create($data);
        Alert::success('Berhasil', 'Data mitra berhasil disimpan.');

        return redirect()->route('mitra.index');
    }

    public function edit(Mitra $mitra): View
    {
        return view('admin.layout.wrapper', [
            'mitra' => $mitra,
            'content' => 'admin.mitra.create',
        ]);
    }

    public function update(Request $request, Mitra $mitra): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('mitras', 'email')->ignore($mitra->id)],
            'no_hp' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string', 'max:255'],
        ]);

        $mitra->update($data);
        Alert::success('Berhasil', 'Data mitra berhasil diperbarui.');

        return redirect()->route('mitra.index');
    }

    public function destroy(Mitra $mitra): RedirectResponse
    {
        $mitra->delete();
        Alert::success('Berhasil', 'Data mitra berhasil dihapus.');

        return redirect()->route('mitra.index');
    }
}
