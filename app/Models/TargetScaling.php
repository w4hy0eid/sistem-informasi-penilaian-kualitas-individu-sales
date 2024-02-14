<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetScaling extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'value_januari',
        'value_febuari',
        'value_maret',
        'value_april',
        'value_mei',
        'value_juni',
        'value_juli',
        'value_agustus',
        'value_september',
        'value_oktober',
        'value_november',
        'value_desember',
        'updated_at',
        'value_year',
    ];

    protected $table = 't_scaling';

    public function users()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
