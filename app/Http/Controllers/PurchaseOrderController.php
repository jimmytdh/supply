<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PurchaseItem;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        if(request()->ajax()){
            $po = PurchaseOrder::orderBy('po_date','desc')->get();
            return DataTables::of($po)
                ->addColumn('po_no', function($row){
                    $url = url('/po/'.$row->id);
                    $date = date('M d, Y',strtotime($row->po_date));
                    $str = "<i class='fa fa-barcode'></i> <a href='$url' class='text-success font-weight-bold'>$row->po_no</a>";
                    $str .="<br>";
                    $str .="<small class='text-muted'>$date</small>";
                    return $str;
                })
                ->addColumn('supplier', function($row){
                    $supplier = Supplier::find($row->supplier_id);
                    if(!$supplier)
                        return "<font class='text-danger'>No Supplier Selected!</font>";
                    $str = "<strong>$supplier->company</strong>";
                    $str .="<br><small class='text-muted'>$supplier->contact</small>";
                    return $str;
                })
                ->addColumn('no_items', function($row){
                    $items = PurchaseItem::where('po_id',$row->id)->count();
                    return $items;
                })
                ->addColumn('total_amount', function($row){
                    $amount = number_format($row->total_amount,2);
                    return $amount;
                })
                ->rawColumns(['supplier','po_no'])
                ->toJson();
        }
        return view('admin.po');
    }

    public function create()
    {
        $po_id = self::createDefaultValue();
        return redirect('/po/'.$po_id);
    }

    public function edit($id)
    {
        $po = PurchaseOrder::find($id);
        $items = PurchaseItem::where('po_id',$id)->get();
        $suppliers = Supplier::orderBy('company','asc')->get();
        $supplier = SupplierController::show($po->supplier_id);
        $units = Unit::orderBy('code','asc')->get();
        return view('admin.po_form',compact('po','items','suppliers','supplier','units'));
    }

    public function createDefaultValue()
    {
        $t = new PurchaseOrder();
        $t->po_no = date('Y-m-')."000";
        $t->po_date = Carbon::now();
        $t->procurement_mode = 'Public Bidding';
        $t->bac_no = "----";
        $t->supplier_id = 0;
        $t->delivery_date = null;
        $t->delivery_term = "within 30 working days from receipt of PO";
        $t->payment_term = null;
        $t->total_amount = 0;
        $t->fund_source = '----';
        $t->save();

        $i = new Item();
        $i->name = 'Item Name';
        $i->description = 'Item Description';
        $i->unit_cost = '0';
        $i->save();

        $p = new PurchaseItem();
        $p->item_no = 1;
        $p->po_id = $t->id;
        $p->item_id = $i->id;
        $p->qty = 1;
        $p->unit = 'unit';
        $p->save();

        return $t->id;
    }

    public function update(Request $request)
    {
        PurchaseOrder::find($request->pk)
            ->update([
                $request->name => $request->value
            ]);

        if($request->name=='supplier_id'){
            return Supplier::find($request->value);
        }
        return 0;
    }

    public function delete(Request $request)
    {
        $p = PurchaseOrder::find($request->id);
        $pi = PurchaseItem::where('po_id',$request->id)->get();
        foreach($pi as $i)
        {
            Item::find($i->item_id)->delete();
            PurchaseItem::find($i->id)->delete();
        }
        $p->delete();
        return 0;
    }

    public function items($id)
    {
        if(request()->ajax()){
            $items = PurchaseItem::select(
                            'purchase_items.*',
                            'items.id as item_id',
                            'items.name',
                            'items.description',
                            'items.unit_cost'
                        )
                        ->leftJoin('purchase_orders','purchase_orders.id','=','purchase_items.po_id')
                        ->leftJoin('items','items.id','=','purchase_items.item_id')
                        ->where('po_id',$id)
                        ->orderBy('item_no','asc')
                        ->get();
            return DataTables::of($items)
                ->addColumn('item_no', function($row){
                    return "<span class='editPurchaseItem' id='item_no' data-title='Item No.' data-pk='$row->id'>$row->item_no</span>";
                })
                ->addColumn('unit', function($row){
                    return "<span class='selectPurchaseItem' data-value='$row->unit' id='unit' data-type='select' data-title='Unit' data-pk='$row->id'>$row->unit</span>";
                })
                ->addColumn('qty', function($row){
                    return "<span class='editPurchaseItem' id='qty' data-title='Qty' data-pk='$row->id'>$row->qty</span>";
                })
                ->addColumn('description',function($row){
                        $desc = "<span class='editItem' id='name' data-title='Item Name' data-pk='$row->item_id'>$row->name</span>";
                        $desc .= "<br><br>";
                        $desc .= "<span class='editItem' id='description' data-title='Item Description' data-type='textarea' data-pk='$row->item_id'>$row->description</span>";
                        return $desc;
                })

                ->addColumn('amount', function($row){
                    $amount = number_format($row->qty * $row->unit_cost);
                    return "<span class='perAmount'>$amount</span>";
                })
                ->addColumn('unit_cost', function($row){
                    $cost = number_format($row->unit_cost);
                    return "<span class='editItem' id='unit_cost' data-title='Unit Cost' data-pk='$row->item_id'>$cost</span>";
                })
                ->addColumn('action', function($row){
                    return "<button class='btn btn-danger btn-sm deleteItem' data-id='$row->id'><i class='fa fa-trash'></i> Remove</button>";
                })
                ->rawColumns(['item_no','unit','qty','unit_cost','amount','description','action'])
                ->toJson();
        }
        return 0;
    }

    public function updatePurchaseItem(Request $request)
    {
        PurchaseItem::find($request->pk)
            ->update([
                $request->name => $request->value
            ]);
        return $request;
    }

    public function calculateAmount($po_id)
    {
        $items = PurchaseItem::leftJoin('items','items.id','=','purchase_items.item_id')
                ->leftJoin('purchase_orders','purchase_orders.id','=','purchase_items.po_id')
                ->where('po_id',$po_id)
                ->get();
        $sum = 0;
        foreach($items as $i)
        {
            $sum += $i->qty * $i->unit_cost;
        }
        PurchaseOrder::find($po_id)->update([
            'total_amount' => $sum
        ]);
        return number_format($sum);
    }
}
