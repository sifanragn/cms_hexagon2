<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $fillable = [
        'nama',
        'title',
        'deskripsi',
        'deskripsi2',
        'price',
        'diskon',
        'total',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    /**
     * Accessor untuk mendapatkan total yang sudah dihitung
     * Jika total belum tersimpan di database, hitung otomatis
     */
    public function getTotalAttribute($value)
    {
        // Jika total sudah ada di database, gunakan itu
        if ($value !== null) {
            return $value;
        }

        // Jika tidak, hitung otomatis
        $price = (float) $this->price;
        $diskon = (float) $this->diskon;

        return $price - ($price * $diskon / 100);
    }

    /**
     * Mutator untuk mengatur total otomatis ketika price atau diskon berubah
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value;
        $this->calculateTotal();
    }

    public function setDiskonAttribute($value)
    {
        $this->attributes['diskon'] = $value;
        $this->calculateTotal();
    }

    /**
     * Method untuk menghitung total
     */
    private function calculateTotal()
    {
        if (isset($this->attributes['price'])) {
            $price = (float) $this->attributes['price'];
            $diskon = (float) ($this->attributes['diskon'] ?? 0);
            $this->attributes['total'] = $price - ($price * $diskon / 100);
        }
    }

    /**
     * Scope untuk filter berdasarkan status aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Format price untuk display
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Format total untuk display
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}
