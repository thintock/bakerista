<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::orderBY('location_code', 'asc')->get();
        return view('locations.index', compact('locations'));
    }

    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'location_code' => 'required|unique:locations,location_code|max:3',
            'location_name' =>'required|max:255',
            ]);
            
        Location::create($validatedData);
        return redirect()->route('locations.index')->with('success','ロケーションを登録しました。');
    }

    
    public function update(Request $request, Location $location)
    {
        $validatedData = $request->validate([
            'location_code' => 'required|unique:locations,location_code,' . $location->id . '|max:3',
            'location_name' =>'required|max:255',
        ]);
        
        $location->update($validatedData);
        return redirect()->route('locations.index')->with('success','ロケーションを更新しました。');
    }

    public function destroy(location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('success','ロケーションが削除されました。');
    }
}
