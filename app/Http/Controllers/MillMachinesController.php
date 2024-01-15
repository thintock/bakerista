<?php

namespace App\Http\Controllers;

use App\Models\MillMachine;
use Illuminate\Http\Request;

class MillMachinesController extends Controller
{

    public function index()
    {
        $millMachines = MillMachine::orderBy('machine_number', 'asc')->get();
        return view('millMachines.index', compact('millMachines'));
    }

    public function create()
    {
        return view('millMachines.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'machine_number' => 'required|integer|between:1,99',
            'machine_name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $millMachine = MillMachine::create($validatedData);

        return redirect()->route('millMachines.index')->with('success', '製粉機が作成されました');
    }

     public function edit(MillMachine $millMachine)
    {
        return view('millMachines.edit', compact('millMachine'));
    }
    
    public function update(Request $request, MillMachine $millMachine)
    {
        $validatedData = $request->validate([
            'machine_number' => 'required|integer|between:1,99',
            'machine_name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $millMachine->update($validatedData);

        return redirect()->route('millMachines.index')->with('success', '製粉機が更新されました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MillMachine  $millMachine
     * @return \Illuminate\Http\Response
     */
    public function destroy(MillMachine $millMachine)
    {
        $millMachine->delete();

        return redirect()->route('millMachines.index')->with('success', '製粉機が削除されました');
    }
}
