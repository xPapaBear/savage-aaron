<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_order_id',
        'user_id',
        'customer_id',
        'total_line_items_price',
        'raw',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
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
