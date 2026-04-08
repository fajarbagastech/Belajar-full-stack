<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'deskripsi' => $this->description,
            'nominal' => $this->amount,
            'format_rupiah' => 'Rp ' . number_format($this->amount, 0, ',', '.'),
            'tipe' => $this->type == 'income' ? 'Pemasukan' : 'Pengeluaran',
            'kategori' => $this->category->name ?? 'Tanpa Kategori',
            'tanggal' => $this->created_at->format('d M Y'),
        ];
    }
}
