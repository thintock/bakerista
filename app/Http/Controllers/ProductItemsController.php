<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductItem;
use App\Models\Company;

class ProductItemsController extends Controller
{
    // 商品一覧表示
    public function index()
    {
        $productItems = ProductItem::all(); // 全商品を取得
        return view('productItems.index', compact('productItems'));
    }

    public function create()
    {
        return view('productItems.create');
    }

    // 商品登録処理
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code' => 'required|string|max:255|unique:product_items,item_code',
            'name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        $productItem = new ProductItem($validated);
    
        $productItem->item_status = '未承認';
    
        $productItem->save();
    
        return redirect()->route('productItems.edit', $productItem->id)->with('success', '商品が正常に登録されました。');
    }

    public function edit(ProductItem $productItem)
    {
        $companies = Company::all(); // Companyの情報を取得
        return view('productItems.edit', compact('productItem', 'companies'));
    }

    public function update(Request $request, ProductItem $productItem)
    {
        $validated = $request->validate([
            'item_code' => 'required|string|max:255|unique:product_items,item_code,' . $productItem->id,
            'jan_code' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'item_status' => 'required|string|max:255',
            'label_name' => 'nullable|string|max:255',
            'label_kana' => 'nullable|string|max:255',
            'label_sub_name' => 'nullable|string|max:255',
            'label_standard' => 'nullable|string|max:255',
            'label_description' => 'nullable|string',
            'food_content_name' => 'nullable|string|max:255',
            'food_content_ingredients' => 'nullable|string|max:255',
            'food_content_volume' => 'nullable|string|max:255',
            'shelf_life' => 'nullable|numeric',
            'storage_method' => 'nullable|string|max:255',
            'allergen_display' => 'nullable|string|max:255',
            'nutritional_energy' => 'nullable|numeric',
            'nutritional_protein' => 'nullable|numeric',
            'nutritional_fat' => 'nullable|numeric',
            'nutritional_carbohydrate' => 'nullable|numeric',
            'nutritional_salt_equivalent' => 'nullable|numeric',
            'nutritional_ash' => 'nullable|numeric',
            'manufacturer_id' => 'nullable|exists:companies,id',
        ]);
    
        $productItem->update($validated);
    
        return redirect()->route('productItems.edit', $productItem->id)->with('success', '商品情報が更新されました。');
    }

    // 商品削除処理
    public function destroy(ProductItem $productItem)
    {
        // 関連するPrintHistoryレコードを削除
        $productItem->printHistories()->delete();
    
        // ProductItemレコードを削除
        $productItem->delete();
    
        return redirect()->route('productItems.index')->with('success', '商品と関連する印刷履歴が削除されました。');
    }

}