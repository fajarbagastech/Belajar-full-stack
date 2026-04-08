<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Pastikan ini ada di paling atas file
    public function allData()
    {
        $userId = Auth::id();

        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($transactions);
    }
    public function index(Request $request)
    {
        // 1. Cek apakah user benar-benar ada (untuk debugging)
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $search = $request->query('search');

        return Transaction::with('category:id,name')
            ->where('user_id', Auth::id()) // Gunakan Auth::id()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                        ->orWhereHas(
                            'category',
                            fn($cat) =>
                            $cat->where('name', 'like', "%{$search}%")
                        );
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
    /**
     * Menyimpan transaksi baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'amount'      => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|max:255',
            'type'        => ['required', new Enum(TransactionType::class)]
        ]);

        // Simpan melalui relasi agar user_id otomatis terisi
        $transaction = $request->user()->transactions()->create($validatedData);

        return response()->json([
            'message' => 'Transaksi Berhasil',
            'data'    => $transaction->load('category') // Load kategori agar data yang dikembalikan lengkap
        ], 201);
    }

    /**
     * Menampilkan detail satu transaksi milik user.
     */
    public function show(Request $request, string $id)
    {
        // Pastikan hanya bisa melihat milik sendiri
        $transaction = $request->user()->transactions()->with('category')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $transaction
        ]);
    }

    /**
     * Memperbarui transaksi.
     */
    public function update(Request $request, string $id)
    {
        // 1. Cari transaksi milik user tersebut
        $transaction = $request->user()->transactions()->findOrFail($id);

        // 2. Validasi input
        $validatedData = $request->validate([
            'amount'      => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|max:255',
            'type'        => ['required', new Enum(TransactionType::class)]
        ]);

        // 3. Eksekusi update pada objek
        $transaction->update($validatedData);

        return response()->json([
            'message' => 'Transaksi Berhasil Diperbarui',
            'data'    => $transaction->load('category')
        ], 200);
    }

    /**
     * Menghapus transaksi (Mendukung Soft Deletes jika aktif di Model).
     */
    public function destroy(Request $request, string $id)
    {
        $transaction = $request->user()->transactions()->findOrFail($id);
        $transaction->delete();

        return response()->json([
            'message' => 'Transaksi Berhasil Dihapus',
        ], 200);
    }

    /**
     * Mendapatkan ringkasan keuangan.
     */
    public function getSummary(Request $request)
    {
        $user = $request->user();

        // Gunakan Enum yang benar (biasanya huruf besar semua di PHP)
        $totalIncome = $user->transactions()
            ->where('type', TransactionType::INCOME)
            ->sum('amount');

        $totalExpense = $user->transactions()
            ->where('type', TransactionType::EXPENSE)
            ->sum('amount');

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_income'  => (float) $totalIncome,
                'total_expense' => (float) $totalExpense,
                'balance'       => (float) ($totalIncome - $totalExpense),
            ]
        ]);
    }
}
