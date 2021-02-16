<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $unit = Unit::orderBy('code','asc')->get();
            return DataTables::of($unit)
                ->addColumn('id', function($row){
                    $id = str_pad($row->id,4,0,STR_PAD_LEFT);
                    return "<span class='edit' onclick='editFunc($row->id)'><i class='fa fa-barcode'></i> $id</span>";
                })
                ->addColumn('created_at', function ($row){
                    return date('M d, Y h:i A',strtotime($row->created_at));
                })
                ->rawColumns(['id'])
                ->toJson();
        }
        return view('misc.unit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $check = Unit::where('code',$request->code)->first();
        if($check)
            return 'duplicate';

        $tbl = new Unit();
        $tbl->code = $request->code;
        $tbl->description = $request->description;
        $tbl->save();

        return "$request->description successfully added to database";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Unit::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $check = Unit::where('code',$request->code)
            ->where('id','<>',$id)
            ->first();
        if($check)
            return 'duplicate';

        $unit = Unit::find($id);
        $unit->code = $request->code;
        $unit->description = $request->description;
        $unit->save();

        return "Unit $request->description successfully updated in database!";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Unit::find($id)->delete();

        return "Unit of measure successfully removed from database!";
    }
}
