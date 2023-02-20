<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'status',
        'added_by',
        'type',
    ];
    

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function histories()
{
    return $this->hasMany(ProductHistory::class);
}



}
