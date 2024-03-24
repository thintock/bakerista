<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompaniesController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('name', 'asc')->paginate(15);
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'postal_code' => 'nullable|string|max:8',
        'address' => 'nullable|string|max:255',
        'phone_number' => 'nullable|regex:/^\d{10,11}$/',
        'fax_number' => 'nullable|regex:/^\d{10,11}$/',
        'email' => 'nullable|email|max:255',
        'order_url' => 'nullable|url|max:255',
        'how_to_order' => 'nullable|string|max:255',
        'order_condition' => 'nullable|string|max:2550',
        'staff_name' => 'nullable|string|max:255',
        'staff_phone' => 'nullable|regex:/^\d{10,11}$/',
    ]);

    $company = new Company($validatedData);
    $company->save();

    return redirect()->route('companies.index')->with('success', '企業情報を登録しました。');
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'postal_code' => 'nullable|max:8',
            'address' => 'nullable|max:255',
            'phone_number' => 'nullable|regex:/^\d{10,11}$/',
            'fax_number' => 'nullable|regex:/^\d{10,11}$/',
            'email' => 'nullable|email|max:255',
            'order_url' => 'nullable|url|max:255',
            'how_to_order' => 'nullable|string|max:255',
            'order_condition' => 'nullable|string|max:2550',
            'staff_name' => 'nullable|max:255',
            'staff_phone' => 'nullable|regex:/^\d{10,11}$/',
        ]);
        
        $company->update($validatedData);
    
        return redirect()->route('companies.edit', $company->id)->with('success', '企業情報が更新されました。');
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        // `supply_items` テーブルに関連付けられたレコードがあるかをチェック
        if ($company->supplyItems()->exists()) {
            // 関連付けられたレコードが存在する場合は、削除を中止しエラーメッセージを返す
            return redirect()->route('companies.edit', $id)->with('error', '会社情報を使用している資材備品が存在するため、削除できません。');
        }
    
        // 関連付けられたレコードがなければ、企業情報を削除
        $company->delete();
    
        // 削除に成功したら、成功メッセージをセッションにフラッシュしてリダイレクト
        return redirect()->route('companies.index')->with('success', '企業情報が削除されました。');
    }

}
