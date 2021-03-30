<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiplierHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'value', 'tag_label', 'metadata'
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
