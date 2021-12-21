<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class headerformclaim extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    // Protected $fillable = ["nama_file"];
}
