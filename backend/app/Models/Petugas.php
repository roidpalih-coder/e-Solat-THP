<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Petugas extends Model
{
    use HasApiTokens, Notifiable;

    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';

    protected $fillable = [
        'nama_petugas',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'id_petugas', 'id_petugas');
    }
}
