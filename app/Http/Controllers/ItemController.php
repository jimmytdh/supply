<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function update(Request $req)
    {
        $value = $req->value;
        if($req->name == 'unit_cost')
        {
            $value = str_replace(',', '', $req->value);
        }
        Item::find($req->pk)
            ->update([
                $req->name => $value
            ]);
        return $req;
    }

    public function createDefaultValue($po_id)
    {
        $check = PurchaseItem::orderBy('id','desc')
                    ->where('po_id',$po_id)
                    ->first();
        $last_item = 1;
        if($check)
            $last_item = $check->item_no + 1;

        $i = new Item();
        $i->name = 'Item Name';
        $i->description = 'Item Description';
        $i->unit_cost = '0';
        $i->type = 'supplies';
        $i->save();

        $p = new PurchaseItem();
        $p->item_no = $last_item;
        $p->po_id = $po_id;
        $p->item_id = $i->id;
        $p->qty = 1;
        $p->unit = 'unit';
        $p->save();
    }

    public function delete(Request $request)
    {
        $p = PurchaseItem::find($request->id);
        Item::find($p->item_id)->delete();
        $p->delete();
        return 0;
    }
}
