<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_no',
        'po_id',
        'item_id',
        'qty',
        'unit',
    ];

    public function status($po_id, $item_id)
    {
        return $this->hasMany(Delivery::class)->where('po_id',$po_id)
                    ->where('item_id',$item_id)
                    ->count();
    }
}
