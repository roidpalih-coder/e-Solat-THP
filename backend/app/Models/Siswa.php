<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'nis';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nis',
        'nama_siswa',
        'jenis_kelamin',
        'id_kelas',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'nis', 'nis');
    }
}
