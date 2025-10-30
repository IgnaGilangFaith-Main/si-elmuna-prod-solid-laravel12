<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BahasaInggris extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'daftar_bahasa_inggris';

    protected $fillable = [
        'nik',
        'nisn',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'alamat',
        'kecamatan',
        'kabupaten',
        'kode_pos',
        'agama',
        'status',
        'nama_ibu',
        'nama_ayah',
        'telepon',
        'email',
        'paket',
        'tgl_mulai',
        'tgl_selesai',

    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];
}
