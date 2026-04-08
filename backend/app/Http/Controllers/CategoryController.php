<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Menampilkan semua kategori milik user yang sedang login.
     */
    public function index(Request $request)
    {
        // Mengambil kategori lewat relasi User agar otomatis terfilter
        $categories = $request->user()->categories()->get();

        return response()->json([
            'status' => 'success',
            'data'   => $categories
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi input dasar
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        // 2. Normalisasi: Hapus spasi & paksa jadi huruf kecil
        $cleanName = strtolower(trim($request->name));

        // 3. Cek apakah USER INI sudah punya kategori tersebut
        // Kita cek secara case-insensitive
        $exists = Category::where('user_id', Auth::id())
            ->where('name', $cleanName)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori "' . $request->name . '" sudah ada di daftar kamu.'
            ], 422);
        }

        // 4. Jika lolos, baru buat datanya
        $category = Category::create([
            'user_id' => Auth::id(),
            'name'    => $cleanName,
        ]);

        return response()->json([
            'status'  => 'success',
            'data'    => $category,
            'message' => 'Kategori berhasil ditambahkan!'
        ], 201);
    }
    public function show(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        // Proteksi: Cek apakah ini milik user yang login
        if ($category->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $category
        ]);
    }

    /**
     * Memperbarui kategori.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        // Proteksi: Hanya pemilik yang boleh update
        if ($category->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:225',
        ]);

        $category->update($validatedData);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kategori berhasil diperbarui',
            'data'    => $category
        ], 200);
    }

    /**
     * Menghapus kategori.
     */
    public function destroy(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        // Proteksi: Hanya pemilik yang boleh hapus
        if ($category->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Kategori berhasil dihapus',
        ], 200);
    }
}
