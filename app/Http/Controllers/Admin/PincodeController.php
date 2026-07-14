<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pincode;

class PincodeController extends Controller
{
    /**
     * Show pincode list + upload form + manual add form
     */
    public function index()
    {
        $pincodes = Pincode::latest()->paginate(20);
        return view('backend.pincodes.index', compact('pincodes'));
    }

    /**
     * Import pincodes from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240',
        ]);

        $file = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($file); // header skip

        $count = 0;

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            if (!empty($data[0])) {
                Pincode::updateOrCreate(
                    ['pincode' => trim($data[0])],
                    [
                        'district' => isset($data[1]) ? strtoupper($data[1]) : null,
                        'state' => isset($data[2]) ? strtoupper($data[2]) : null,
                        'is_active' => 1,
                    ]
                );
                $count++;
            }
        }

        fclose($file);

        return back()->with('success', "$count pincodes imported successfully!");
    }

    /**
     * Store (manual add) new pincode
     */
    public function store(Request $request)
    {
        $request->validate([
            'pincode' => 'required|numeric|digits:6',
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
        ]);

        Pincode::create([
            'pincode' => $request->pincode,
            'district' => strtoupper($request->district),
            'state' => strtoupper($request->state),
            'is_active' => 1,
        ]);

        return redirect()->back()->with('success', 'Pincode added successfully!');
    }

    /**
     * Delete a pincode
     */
    public function destroy($id)
    {
        $pincode = Pincode::findOrFail($id);
        $pincode->delete();

        return back()->with('success', 'Pincode deleted successfully!');
    }
    public function toggle($id)
    {
        $pincode = Pincode::findOrFail($id);
        $pincode->is_active = !$pincode->is_active;
        $pincode->save();

        return back()->with('success', 'Pincode status updated!');
    }
    public function toggleStatus($id)
    {
        $pincode = Pincode::findOrFail($id);
        $pincode->is_active = !$pincode->is_active;
        $pincode->save();

        return redirect()->back()->with('success', 'Pincode status updated successfully!');
    }
}
