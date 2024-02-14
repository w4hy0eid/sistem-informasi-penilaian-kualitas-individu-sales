<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiNgtma extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sales_id',
        'updated_at',
        'value',
        'month',
    ];

    protected $table = 'r_ngtma';

    public function users()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class, "sales_id");
    }
}
