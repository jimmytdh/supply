<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Item;
use App\Models\PurchaseInspector;
use App\Models\PurchaseItem;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\UserAccess;
use Carbon\Carbon;
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
                ->addColumn('po_no',function($delivery){
                    $po = PurchaseOrder::find($delivery->po_id);
                    $url = url('/delivery/'.$po->id);
                    return "<a class='font-weight-bold text-success' href='$url'>$po->po_no</a>";
                })
                ->addColumn('item',function($delivery){
                    return "<span class='font-weight-bold'>".Item::find($delivery->item_id)->name."</span>";
                })
                ->addColumn('date_delivered',function($delivery){
                    return date('F d, Y',strtotime($delivery->date_delivered));
                })
                ->rawColumns(['po_no','item'])
                ->toJson();
        }
        return view('admin\delivery');
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

        $deliveries = Delivery::select(
                            'deliveries.*',
                            'items.name'
                        )
                        ->leftJoin('items','items.id','=','deliveries.item_id')
                        ->orderBy('date_delivered','desc')
                        ->orderBy('deliveries.id','desc')
                        ->where('po_id',$id)
                        ->get();

        return view('admin\deliver_form',compact('po','supplier','items','deliveries'));
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

    public function store(Request $request)
    {
        $item = PurchaseItem::where('item_id',$request->item_id)->first();

        $d = new Delivery();
        $d->po_id = $request->po_id;
        $d->item_id = $request->item_id;
        $d->unit = $item->unit;
        $d->qty = $request->qty;
        $d->date_delivered = Carbon::now();
        $d->remarks = $request->remarks;
        $d->save();

        if($request->inspectors){
            foreach($request->inspectors as $ins){
                $i = new PurchaseInspector();
                $i->delivery_id = $d->id;
                $i->user_id = $ins;
                $i->save();
            }
        }

        return redirect()->back()->with('success',true);
    }
}
