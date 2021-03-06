<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'po_no',
        'po_date',
        'procurement_mode',
        'bac_no',
        'supplier_id',
        'delivery_date',
        'delivery_term',
        'payment_term',
        'total_amount',
        'fund_source',
        'status',
    ];

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class,'po_id');
    }

    public function items()
    {
        return $this->purchaseItems()->leftJoin('items','items.id','=','purchase_items.item_id')->get();
    }
}
