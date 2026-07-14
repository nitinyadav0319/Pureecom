<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Redirect;




class ReviewController extends Controller
{
    public function reviewStore(Request $request){

        
        $reviewData = new Review();
        $reviewData->name = $request->author;
        $reviewData->product_id = $request->product_id;
        $reviewData->email = $request->email;
        $reviewData->message = $request->body;
        $reviewData->save();

        return Redirect::back()->with('message','Review save Successful !');




    }
}
