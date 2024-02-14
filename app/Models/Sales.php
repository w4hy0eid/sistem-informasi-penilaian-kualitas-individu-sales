<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul_project',
        'nama_pelanggan',
        'mitra',
        'deal_dibulan',
        'nilai_project',
        'lama_kontrak',
        'pembayaran_bulanan',
        'type',
        'updated_at'
    ];

    protected $table = 'sales';

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
