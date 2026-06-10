<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumentasiProker extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi_proker';

    protected $fillable = [
        'program_kerja_id',
        'judul',
        'deskripsi',
        'file_path',
        'tipe',
    ];

    /* ========================================
       Relationships
       ======================================== */

    public function programKerja(): BelongsTo
    {
        return $this->belongsTo(ProgramKerja::class);
    }
}
