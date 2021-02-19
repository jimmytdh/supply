<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
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

        $supplier = optional(Supplier::find($po->supplier_id));
        return view('admin\deliver_form',compact('po','supplier'));
    }
}
