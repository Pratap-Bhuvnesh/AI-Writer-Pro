<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',       'total_amount',
        'status',        'order_number',       
         'shipping_address', 
               'payment_status',
        'payment_method',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }
    
    public function payment(){
        return $this->hasOne(Payment::class);
    }
    public function shipment()
{
    return $this->hasOne(Shipment::class);
}
public function couponUsage()
{
    return $this->hasOne(CouponUsage::class);
}
}
