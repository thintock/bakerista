<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\MillPurchaseMaterial;
use Illuminate\Support\Facades\Auth;

class MaterialsController extends Controller
{
    /**
     * Display a listing of the materials.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materials = Material::orderBy('materials_code')->paginate(10);
        return view('materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new material.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('materials.create');
    }

    /**
     * Store a newly created material in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'materials_code' => 'required|max:6|unique:materials',
            'materials_name' => 'nullable',
            'materials_purchaser' => 'nullable',
            'materials_producer_name' => 'nullable',
            // 他の必要なバリデーションルール
        ]);

        $material = new Material($validatedData);
        $material->user_id = Auth::id();
        $material->save();

        return redirect()->route('materials.index')->with('success', '原料情報を登録しました。');
    }

    /**
     * Show the form for editing the specified material.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('materials.edit', compact('material'));
    }

    /**
     * Update the specified material in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'materials_code' => 'required|max:6|unique:materials,materials_code,' . $id,
            'materials_name' => 'nullable',
            'materials_purchaser' => 'nullable',
            'materials_producer_name' => 'nullable',
            // 他の必要なバリデーションルール
        ]);

        $material = Material::findOrFail($id);
        $material->update($validatedData);

        return redirect()->route('materials.index')->with('success', '原料情報を更新しました。');
    }

    /**
     * Remove the specified material from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 対象のMaterialを取得
        $material = Material::findOrFail($id);

        // 関連するMillPurchaseMaterialsがあるかどうかをチェック
        $relatedMillPurchaseMaterials = MillPurchaseMaterial::where('materials_id', $id)->limit(3)->get();

        // 関連するMillPurchaseMaterialsがある場合は削除を拒否
        if ($relatedMillPurchaseMaterials->isNotEmpty()) {
            // 関連するMillPurchaseMaterialsのlot_numberを取得し、カンマ区切りの文字列に変換
            $lotNumbers = $relatedMillPurchaseMaterials->pluck('lot_number')->join(', ');
            return back()->with('error', "関連する原料入荷情報が存在するため、この原料は削除できません。関連するロットナンバー: {$lotNumbers}");
        }

        // 関連するMillPurchaseMaterialsがなければMaterialを削除
        $material->delete();

        return redirect()->route('materials.index')->with('success', '原料情報を削除しました。');
    }
}
