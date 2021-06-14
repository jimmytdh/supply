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
                ->addColumn('delivery_no',function($delivery){
                    $po = PurchaseOrder::find($delivery->po_id);
                    $y = Carbon::parse($po->po_date)->format('Y');
                    $p = str_pad($po->id,2,0,STR_PAD_LEFT);
                    $d = str_pad($delivery->delivery_no,2,0,STR_PAD_LEFT);
                    return "<span class='font-weight-bold text-info'>D$y-$p-$d</span>";
                })
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
                ->rawColumns(['delivery_no','po_no','item'])
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

        $deliveries = Delivery::select(
                            'deliveries.*',
                            'items.name'
                        )
                        ->leftJoin('items','items.id','=','deliveries.item_id')
                        ->orderBy('date_delivered','desc')
                        ->orderBy('deliveries.id','desc')
                        ->where('po_id',$id)
                        ->get();

        return view('admin.deliver_form',compact('po','supplier','items','deliveries'));
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
        $date_delivered = Carbon::now()->format('Y-m-d');
        $qty = $request->qty;
        $delivery_no = 1;
        $previousDelivery = Delivery::where('po_id',$request->po_id)
            ->orderBy('id','desc')
            ->first();
        if($previousDelivery){
            $sameDateDelivery = Delivery::where('po_id',$request->po_id)
                ->where('date_delivered',$date_delivered)
                ->first();
            if($sameDateDelivery){
                $delivery_no = $sameDateDelivery->delivery_no;
                //return $request->item_id;
                if($sameDateDelivery->item_id == $request->item_id){
                    $qty += $sameDateDelivery->qty;
                    Delivery::find($sameDateDelivery->id)
                        ->update([
                           'qty' => $qty
                        ]);
                    return redirect()->back()->with('success',true);
                }
            }else{
                $delivery_no = $previousDelivery->delivery_no + 1;
            }
        }

        $d = new Delivery();
        $d->delivery_no = $delivery_no;
        $d->po_id = $request->po_id;
        $d->item_id = $request->item_id;
        $d->unit = $item->unit;
        $d->qty = $qty;
        $d->date_delivered = $date_delivered;
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

    function pages(Request $req)
    {
        $position = array();
        foreach($req->pages as $page)
        {
            $position->pages($page);
        }
    }

    static function generateDeliveryNo($delivery)
    {
        $po = PurchaseOrder::find($delivery->po_id);
        $y = Carbon::parse($po->po_date)->format('Y');
        $p = str_pad($po->id,2,0,STR_PAD_LEFT);
        $d = str_pad($delivery->delivery_no,2,0,STR_PAD_LEFT);
        return "D$y-$p-$d";
    }

    public function delete($id)
    {
        Delivery::find($id)->delete();
        PurchaseInspector::where('delivery_id',$id)->delete();
        return redirect()->back()->with('status','deleteDelivery');
    }
}
