<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use App\QuickResponse;

class VendorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        $vendor = new Vendor();
        $vendor->name = \request('name');
        $vendor->email = \request('email');
        $vendor->phone_number = \request('phone-number');
        $vendor->address = \request('address');
        $vendor->description = \request('description');
        $vendor->save();
        return redirect('/vendors');
    }

    public function delete(Request $request)
    {
        $id = $request['id'];

        if(!Vendor::where('id', $id)->exists()) {
            return QuickResponse::warning('Could not find vendor.');
        }
        $vendor = Vendor::where('id', $id)->first();
        $vendor->delete();
        return QuickResponse::success('Vendor deleted!');
    }

    public function getInfo() {
        $id = \request('id');
        
        if(!Vendor::where('id', $id)->exists()) {
            return QuickResponse::warning("Could not find vendor.");
        }

        $vendor = Vendor::where('id', $id)->first();
        return response()->json(['vendor' => $vendor->toArray()]);
    }

    public function list() {
        return view('vendors');
    }
}
