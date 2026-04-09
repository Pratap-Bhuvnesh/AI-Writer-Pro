<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;
     public $timestamps = false; // only created_at

    protected $fillable = [
        'admin_id',
        'action',
        'entity_type',
        'entity_id',
        'created_at',
    ];

    /**
     * Admin (User)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
