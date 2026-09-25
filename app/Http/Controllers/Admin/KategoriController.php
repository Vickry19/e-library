<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('buku')->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $kategoriExists = Kategori::where('nama_kategori', $request->nama_kategori)->exists();

        if ($kategoriExists) {
            return redirect()->back()->with('error', 'Nama kategori sudah ada!');
        }

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, string $id)
    {
        $kategoriExists = Kategori::where('nama_kategori', $request->nama_kategori)->exists();

        if ($kategoriExists) {
            return redirect()->back()->with('error', 'Nama kategori sudah ada!');
        }

        Kategori::where('id', $request->id)->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        $post = Kategori::findOrFail($id);
        $post->delete();

        return redirect()->back()->with(['success' => 'Kategori berhasil dihapus!']);
    }
}