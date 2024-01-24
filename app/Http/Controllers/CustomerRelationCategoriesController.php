<?php

namespace App\Http\Controllers;

use App\Models\CustomerRelationCategory;
use Illuminate\Http\Request;

class CustomerRelationCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CustomerRelationCategory $customerRelationCategory)
    {
        $customerRelationCategories = CustomerRelationCategory::orderBY('name', 'asc')->get();
        return View('customerRelationCategories.index', compact('customerRelationCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255|unique:customer_relation_categories,name'
            ]);
            
            CustomerRelationCategory::create($validateData);
            return redirect()->route('customerRelationCategories.index')->with('success', 'カテゴリが追加されました。');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerRelationCategory $customerRelationCategory)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:customer_relation_categories,name,' . $customerRelationCategory->id
        ]);
        
        $customerRelationCategory->update($validatedData);
        return redirect()->route('customerRelationCategories.index')->with('success', 'カテゴリが更新されました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerRelationCategory $customerRelationCategory)
    {
        $customerRelationCategory->delete();
        return redirect()->route('customerRelationCategories.index')->with('success', 'カテゴリが削除されました。');
    }
}
