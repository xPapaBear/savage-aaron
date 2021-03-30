<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'customer_id',
        'multiplier_id',
        'points'
    ];

    public function shop()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function multiplier()
    {
        return $this->hasOne(Multiplier::class);
    }

    public function scopeFilter($query, $request)
    {
        $startDate = date('Y-m-d', strtotime($request->get('start')));
        $endDate = date('Y-m-d', strtotime($request->get('end')));
        if ( ! empty( $startDate ) && ! empty( $endDate ) ) {
            $query->where(function($q) use (&$startDate, $endDate){
                $q->whereBetween('created_at', [
                    $startDate . " 00:00:00",
                    $endDate . " 23:59:59"
                ]);
           });
        }

        return $query;
    }
}