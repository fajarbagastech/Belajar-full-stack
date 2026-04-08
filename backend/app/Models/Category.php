<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'user_id'
    ];

    public  function user()
    {
        return $this->hasMany(User::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
