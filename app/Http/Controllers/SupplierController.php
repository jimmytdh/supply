<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Array_;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        if(request()->ajax()){
            $suppliers = Supplier::orderBy('company','asc')->get();
            return DataTables::of($suppliers)
                ->addColumn('id', function($row){
                    $id = str_pad($row->id,4,0,STR_PAD_LEFT);
                    return "<span class='edit' onclick='editSupplier($row->id)'><i class='fa fa-barcode'></i> $id</span>";
                })
                ->rawColumns(['id'])
                ->toJson();
        }
        return view('misc.supplier');
    }

    public function store(Request $req)
    {
        $check = Supplier::where('company',$req->company)->first();
        if($check)
            return 'duplicate';

        $tbl = new Supplier();
        $tbl->company = $req->company;
        $tbl->name = $req->name;
        $tbl->contact = $req->contact;
        $tbl->address = $req->address;
        $tbl->tin = $req->tin;
        $tbl->email = ($req->email) ? $req->email: 'None';
        $tbl->save();

        return "Supplier $req->company successfully saved in database!";
    }

    public static function show($id)
    {
        $check = Supplier::find($id);
        if(!$check){
            $check = new Array_();
            $check->id = 0;
            $check->company = 'N/A';
            $check->name = 'N/A';
            $check->contact = 'N/A';
            $check->address = 'N/A';
            $check->tin = 'N/A';
            $check->email = 'N/A';
        }
        return $check;
    }

    public function edit($id)
    {
        return Supplier::find($id);
    }

    public function update(Request $req, $id)
    {
        $check = Supplier::where('company',$req->company)
                ->where('id','<>',$id)
                ->first();
        if($check)
            return 'duplicate';

        Supplier::find($id)
            ->update([
                'company' => $req->company,
                'name' => $req->name,
                'contact' => $req->contact,
                'email' => $req->email,
                'tin' => $req->tin,
                'address' => $req->address,
            ]);
        return "Supplier $req->company successfully updated in database!";
    }

    public function delete(Request $req, $id)
    {
        Supplier::find($id)->delete();

        return "Supplier successfully removed from database!";
    }
}
