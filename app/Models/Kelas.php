<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'judul',
        'pengajar',
        'harga',
        'kuota',
    ];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'kelas_id');
    }
}