<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /*
    Fungsi -> Untuk menangani mass assignment, mengizinkan semua field
    yang ada di table untuk dimanipulasi seperti tambah, edit dan hapus
    */
    protected $guarded = [];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}