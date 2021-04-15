<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Entry;
use App\Models\Order;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'phone',
        'raw',
        'store_customer_id',
        'user_id',
        'status',
    ];

    public $appends = ['total_points', 'total_spent', 'full_name'];

    public function shop()
    {
        return $this->belongsTo(User::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class)->orderBy( 'id', 'DESC' );
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getTotalPointsAttribute($request)
    {
        return $this->entries->filter($request)->sum('points');
    }

    public function getTotalSpentAttribute($request)
    {
        return $this->orders->filter($request)->sum('total_line_items_price');
    }

    /**
    * Get the user's full name.
    *
    * @return string
    */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
