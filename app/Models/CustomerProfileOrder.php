<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProfileOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_id', 'total_items_price', 'multiplier', 'total_entry_points', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    protected $appends = [
        'created_format'
    ];

    /**
     * Append Methods
     */
    public function getCreatedFormatAttribute() {
        return date( 'Y-m-d h:i:s', strtotime( $this->created_at ) );
    }
}
