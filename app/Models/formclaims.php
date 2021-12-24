<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class formclaims extends Model
{
    protected $table = "formclaim";
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    Protected $hidden =['header_id','user_id','created_at','updated_at'];
    // Protected $fillable = ['tgl_insiden', 'tgl_formclaim' , 'name', 'item' ,
    // 'jenis_incident','no_FormClaim',
    // 'barge','TSI_barge','TSI_Tugboat',
    // 'deductible','amount','surveyor',
    // 'tugBoat','incident','description'];
    
}
 