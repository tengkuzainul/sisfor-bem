<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriProker extends Model
{
    use HasFactory;

    protected $table = 'kategori_proker';

    protected $fillable = [
        'nama',
        'warna',
        'deskripsi',
    ];

    /* ========================================
       Relationships
       ======================================== */

    public function programKerja(): HasMany
    {
        return $this->hasMany(ProgramKerja::class, 'kategori_proker_id');
    }
}
