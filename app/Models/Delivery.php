<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_no',
        'po_id',
        'item_id',
        'unit',
        'qty',
        'date_delivered',
        'remarks',
    ];

    public function delivered()
    {
        return $this->belongsTo(PurchaseItem::class)
                    ->sum('qty');
    }
}
