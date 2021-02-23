<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Item;
use App\Models\PurchaseItem;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DeliveryController extends Controller
{
    public function index()
    {
        if(request()->ajax()){
            $delivery = Delivery::orderBy('date_delivered','desc')->get();
            return DataTables::of($delivery)

                ->rawColumns([''])
                ->toJson();
        }
        return view('admin.delivery');
    }

    public function search(Request $request)
    {
        $po = PurchaseOrder::where('po_no',$request->po_no)->first();

        if(!$po)
            return abort(404);

        return redirect('/delivery/'.$po->id);

    }

    public function show($id)
    {
        $po = PurchaseOrder::find($id);
        if(!$po)
            return abort(404);
        $supplier = optional(Supplier::find($po->supplier_id));
        $items = PurchaseItem::where('po_id',$po->id)
            ->select('purchase_items.*','items.name','items.unit_cost')
            ->leftJoin('items','items.id','=','purchase_items.item_id')
            ->orderBy('item_no','asc')
            ->get();

        return view('admin\deliver_form',compact('po','supplier','items'));
    }

    static function unDeliveredItems($po_id,$item_id,$qty)
    {
        $sum = Delivery::where('po_id',$po_id)
                ->where('item_id',$item_id)
                ->sum('qty');
        if($sum){
            $sum = $qty - $sum;

            return $sum;
        }
        return $qty;
    }

    public function showItemDescription($id)
    {
        $purchaseItem = PurchaseItem::find($id);
        $po = PurchaseOrder::find($purchaseItem->po_id);
        $item = Item::find($purchaseItem->item_id);
        $inspectors = UserAccess::leftJoin('tdh_user.users','users.id','=','user_accesses.user_id')
                        ->where('level','inspector')
                        ->get();

        return view('load.itemDesc',compact('po','item','purchaseItem','inspectors'));
    }
}
