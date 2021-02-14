<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','level'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
