<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Redirect;

class DileveryChargesController extends Controller
{
    public function edit(Request $request){

        $dilevery_chargeData = DB::table('dilevery_chargers')->find(1);
        return view('backend.pages.dilevery_charges.edit', compact('dilevery_chargeData'));

    }

    public function updatedileveryCharges(Request $request){

        $update = \DB::table('dilevery_chargers')
        ->where('id', $request['id'])->update([ 'amount' => $request['amount']]);
        
        return Redirect::back()->with('message','Review save Successful !');


    }
}
