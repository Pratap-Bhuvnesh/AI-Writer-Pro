<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;
    protected $casts = [
    'meta' => 'array',
];
    public function user()
{
    return $this->belongsTo(User::class);
}
}
