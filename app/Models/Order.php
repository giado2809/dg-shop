<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'total_price',
        'voucher_code',
        'discount_amount',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, OrderItem::class);
    }

    public function getHasReviewAttribute()
    {
       // Nếu tất cả sản phẩm trong đơn đều đã được đánh giá
        return $this->items->every(function ($item) {
            return $item->review !== null;
        });
    }

}
