<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $casts = [
        'track_expiry' => 'boolean',
        'is_secondary' => 'boolean',
        'is_active' => 'boolean',
        'tax_rate' => 'decimal:2',
        'pieces_per_box' => 'integer',
        'reorder_level' => 'integer',
        'reorder_qty' => 'integer',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'sub_category',
        'unit',
        'pieces_per_box',
        'hsn_code',
        'tax_rate',
        'track_expiry',
        'reorder_level',
        'reorder_qty',
        'barcode',
        'is_secondary',
        'is_active',
        'created_by',
        'updated_by',
    ];
}