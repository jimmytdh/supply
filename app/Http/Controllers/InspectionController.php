<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public $data = array();
    public $po_no = null;
    public $po = array();

    public function index()
    {
        $data = $this->data;
        $po_no = $this->po_no;
        $po = $this->po;
        return view('admin.inspection',compact('data','po_no','po'));
    }

    public function search(Request $req)
    {
        $po = PurchaseOrder::where('po_no',$req->po_no)->first();
        if($po){
            $this->po_no = $req->po_no;
            $this->po = $po;
            $this->data = Delivery::select(
                'deliveries.*',
                'items.name'
            )
                ->leftJoin('items', 'items.id', '=', 'deliveries.item_id')
                ->orderBy('date_delivered', 'desc')
                ->orderBy('deliveries.id', 'desc')
                ->where('po_id', $po->id)
                ->get();

            return self::index();
        }
        return self::index();
    }
}
