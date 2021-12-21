<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteSpgr extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    // Protected $fillable = ['Nilai_Claim', 'No_SPGR','Nama_Kapal'];
    protected $table = "note_spgrs";
    public function user(){
        return $this->belongsTo(User::class);
    }
}
