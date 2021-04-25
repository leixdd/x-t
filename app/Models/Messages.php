<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $fillable = ['message'];


    public function Sender(){
        return $this->hasOne(User::class, 'user_id', 'id');

    }

    public function Receiver(){
        return $this->hasOne(User::class, 'user_id', 'id');

    }

}
