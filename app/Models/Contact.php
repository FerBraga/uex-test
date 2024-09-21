<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cpf', 'phone', 'address_id'];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}