<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MillPolishedMaterial;
use App\Models\MillPurchaseMaterial;
use Illuminate\Support\Facades\Auth;
use Carbon\carbon; // ロット番号生成（日付生成用）
use Illuminate\Support\Facades\DB; // トランザクション使用

class MillPolishedMaterialsController extends Controller
{
    // Display a listing of the polished materials.
    public function index()
    {
        $millPolishedMaterials = MillPolishedMaterial::orderBy('polished_lot_number')->paginate(10);
        return view('millPolishedMaterials.index', compact('millPolishedMaterials'));
    }

    // Show the form for creating a new polished material.
    public function create()
    {
        // is_finishedがfalse（在庫あり）のMillPurchaseMaterialのみを取得する。
        $millPurchaseMaterials = MillPurchaseMaterial::where('is_finished', false)->get();
        
        return view('millPolishedMaterials.create', compact('millPurchaseMaterials'));
    }

    public function store(Request $request)
    {   
    // トランザクション開始
    DB::beginTransaction();

    try {
        $validatedPolishedData = $request->validate([
            'polished_date' => 'required|date',
            'total_output_weight' => 'required|numeric',
            'polished_retention' => 'nullable|numeric|between:0,999.9',
            'mill_whiteness_1' => 'nullable|numeric|between:0,99.9',
            'mill_whiteness_2' => 'nullable|numeric|between:0,99.9',
            'mill_whiteness_3' => 'nullable|numeric|between:0,99.9',
            'mill_whiteness_4' => 'nullable|numeric|between:0,99.9',
            'mill_whiteness_5' => 'nullable|numeric|between:0,99.9',
            'mill_whiteness_6' => 'nullable|numeric|between:0,99.9',
            'mill_whiteness_7' => 'nullable|numeric|between:0,99.9',
            'mill_whiteness_8' => 'nullable|numeric|between:0,99.9',
        ]);
        
        $selectedMaterialIds = $request->input('selectMaterials');
        $inputWeights = $request->input('input_weights');
        $totalInputWeight = array_sum($inputWeights);
        $totalOutputWeight = $request->input('total_output_weight');
        $totalInputCost = 0; 

        $polishedDate = Carbon::createFromFormat('Y-m-d', $request->input('polished_date'));
        $datePart = $polishedDate->format('md');
        
        $firstMaterialId = $selectedMaterialIds[0] ?? null;
        if ($firstMaterialId) {
            $firstMaterial = MillPurchaseMaterial::find($firstMaterialId);
            if ($firstMaterial) {
                $lotPart = $firstMaterial->lot_number;
            } else {
                throw new \Exception('指定された原料が見つかりません。');
            }
        } else {
            throw new \Exception('原料が選択されていません。');
        }
        
        $polishedLotNumber = $lotPart . $datePart;
        $validatedPolishedData['polished_lot_number'] = $polishedLotNumber;

        $polishedMaterial = new MillPolishedMaterial($validatedPolishedData);
        $polishedMaterial->total_input_weight = $totalInputWeight;
        $polishedMaterial->user_id = Auth::id();
        // 精麦歩留りを計算し、値をセット
        if ($totalInputWeight > 0) { // 0での除算を防ぐ
            $polishedMaterial->polished_retention = ($totalOutputWeight / $totalInputWeight) * 100;
        } else {
            $polishedMaterial->polished_retention = null; // 入力重量が0または無効な場合、歩留りは計算できない
        }
        $polishedMaterial->save();
        
        foreach ($selectedMaterialIds as $index => $materialId) {
            $material = MillPurchaseMaterial::find($materialId);
            if ($material && $material->total_amount > 0) {
                // 単位原価の計算
                $unitCost = $material->cost / $material->total_amount;
                $inputWeight = $inputWeights[$index] ?? 0;
                $inputCost = $unitCost * $inputWeight;
                $totalInputCost += $inputCost;

                // 使用原料に関する情報を中間テーブルに保存
                $polishedMaterial->millPurchaseMaterials()->attach(
                    $materialId,
                    [
                        'input_weight' => $inputWeight,
                        'input_cost' => $inputCost, 
                    ]
                );

                // 在庫の更新
                $material->remaining_amount -= $inputWeight;
                if ($material->remaining_amount <= 0) {
                    $material->is_finished = true;
                }
                $material->save();
            }  else {
                throw new \Exception("原料ID {$materialId} のデータに問題があります。");
            }
        }

        $polishedMaterial->total_input_cost = $totalInputCost;
        $polishedMaterial->save();

        // すべての処理が成功したらコミット
        DB::commit();

        return redirect()->route('millPolishedMaterials.index')->with('success', '精麦済み原料が正常に登録されました。');

    } catch (\Illuminate\Database\QueryException $e) {
    if ($e->getCode() == 23000) { // 一意性制約違反のエラーコード
        // 一意性制約違反の場合のエラーハンドリング
        return back()->withErrors('指定された精麦ロット番号は今日すでに既に登録されています。同日に行う同じ原料の精麦は一つのロットとして登録してください。');
    } else {
        // その他のデータベースエラーの場合
        return back()->withErrors('データベースエラーが発生しました: ' . $e->getMessage());
    }
    }
    }

    // Display the specified polished material.
    public function show($id)
    {
        $polishedMaterial = MillPolishedMaterial::findOrFail($id);
        return view('millPolishedMaterials.show', compact('polishedMaterial'));
    }

    // Show the form for editing the specified polished material.
   public function edit($id)
    {
        $polishedMaterial = MillPolishedMaterial::findOrFail($id);
    
        // 在庫がある原料のみを取得
        $millPurchaseMaterials = MillPurchaseMaterial::where('is_finished', false)
            ->where('remaining_amount', '>', 0)
            ->get();
    
        return view('millPolishedMaterials.edit', compact('polishedMaterial', 'millPurchaseMaterials'));
    }


    public function update(Request $request, $id)
    {
        // トランザクション開始
        DB::beginTransaction();
    
        try {
    
            $polishedMaterial = MillPolishedMaterial::with('millPurchaseMaterials')->findOrFail($id);
            // バリデーション
            $validatedData = $request->validate([
                'total_output_weight' => 'required|numeric',
                'polished_retention' => 'nullable|numeric|between:0,999.9',
                'mill_whiteness_1' => 'nullable|numeric|between:0,99.9',
                'mill_whiteness_2' => 'nullable|numeric|between:0,99.9',
                'mill_whiteness_3' => 'nullable|numeric|between:0,99.9',
                'mill_whiteness_4' => 'nullable|numeric|between:0,99.9',
                'mill_whiteness_5' => 'nullable|numeric|between:0,99.9',
                'mill_whiteness_6' => 'nullable|numeric|between:0,99.9',
                'mill_whiteness_7' => 'nullable|numeric|between:0,99.9',
                'mill_whiteness_8' => 'nullable|numeric|between:0,99.9',
            ]);
            
            $polishedMaterial->mill_whiteness_1 = $request->input('mill_whiteness_1');
            $polishedMaterial->mill_whiteness_2 = $request->input('mill_whiteness_2');
            $polishedMaterial->mill_whiteness_3 = $request->input('mill_whiteness_3');
            $polishedMaterial->mill_whiteness_4 = $request->input('mill_whiteness_4');
            $polishedMaterial->mill_whiteness_5 = $request->input('mill_whiteness_5');
            $polishedMaterial->mill_whiteness_6 = $request->input('mill_whiteness_6');
            $polishedMaterial->mill_whiteness_7 = $request->input('mill_whiteness_7');
            $polishedMaterial->mill_whiteness_8 = $request->input('mill_whiteness_8');
            
            // 総重量の更新
            $polishedMaterial->total_output_weight = $validatedData['total_output_weight'];
            // 使用原料の更新（中間テーブル）
            $selectedMaterialIds = $request->input('selectMaterials', []);
            $inputWeights = $request->input('input_weights', []);
    
            // 中間テーブルから削除されるエントリのIDを取得
            $removedMaterialIds = $request->input('removeMaterials', []); // 削除するエントリのID配列
            // 精麦歩留計算のための値を取得
            $totalOutputWeight = $request->input('total_output_weight');
            // 合計投入重量と投入原価を再計算
            $totalInputWeight = 0;
            $totalInputCost = 0;
            
            // 削除処理
            if (!empty($removedMaterialIds)) {
                foreach ($removedMaterialIds as $removedMaterialId) {
                    $relatedPivot = $polishedMaterial->millPurchaseMaterials()->where('mill_purchase_material_id', $removedMaterialId)->first();
                    if ($relatedPivot) {
                        $existingInputWeight = $relatedPivot->pivot->input_weight;
                        $existingInputCost = $relatedPivot->pivot->input_cost;
                        
                        // MillPurchaseMaterialsの在庫を元に戻す
                        $relatedMaterial = MillPurchaseMaterial::find($removedMaterialId);
                        if ($relatedMaterial) {
                            $relatedMaterial->remaining_amount += $existingInputWeight;
                            $relatedMaterial->is_finished = ($relatedMaterial->remaining_amount > 0) ? false : true;
                            $relatedMaterial->save();
                        }
                        
                        // 合計投入重量と原価を調整
                        $polishedMaterial->total_input_weight -= $existingInputWeight;
                        $polishedMaterial->total_input_cost -= $existingInputCost;
                        
                        // 中間テーブルから削除
                        $polishedMaterial->millPurchaseMaterials()->detach($removedMaterialId);
                    }
                }
            }
            
            // 在庫更新処理
            if (!empty($selectedMaterialIds)) {
                // IDの重複チェック
                if(count($selectedMaterialIds) !== count(array_unique($selectedMaterialIds))) {
                // 重複が存在する場合はエラーメッセージとともに処理を中断
                return back()->withErrors('同じ原料ロットは一つの行にまとめて登録してください。');
                }
                foreach ($selectedMaterialIds as $index => $materialId) {
                    $material = MillPurchaseMaterial::find($materialId);
                    if ($material) {
                        $newInputWeight = $inputWeights[$index] ?? 0;
                        $existingPivot = $polishedMaterial->millPurchaseMaterials()->where('mill_purchase_material_id', $materialId)->first();
                        $existingInputWeight = $existingPivot ? $existingPivot->pivot->input_weight : 0;
                
                        // 在庫の更新 差分だけ在庫を増減する
                        $material->remaining_amount += $existingInputWeight - $newInputWeight;
                        if ($material->remaining_amount <= 0) {
                            $material->remaining_amount = max($material->remaining_amount, 0);
                            $material->is_finished = true; // 在庫が0以下になったため、在庫なしとする。
                        } else {
                            $material->is_finished = false; // 在庫が残っているため、在庫ありとする。
                        }
                    
                        // コスト計算
                        $unitCost = $material->cost / $material->total_amount; // kg当たりのコスト
                        $inputCost = $unitCost * $newInputWeight; // 当該原料のコスト
                        
                        // 合計値の更新
                        $totalInputWeight += $newInputWeight;
                        $totalInputCost += $inputCost; // 合計コストに加算
                        
                        $material->save();
                        // 中間テーブルの更新
                        if ($existingPivot) {
                            $polishedMaterial->millPurchaseMaterials()->updateExistingPivot($materialId, [
                            'input_weight' => $newInputWeight,
                            'input_cost' => $inputCost,
                        ]);
                        } else {
                            $polishedMaterial->millPurchaseMaterials()->attach($materialId, [
                                'input_weight' => $newInputWeight,
                                'input_cost' => $inputCost,
                            ]);
                        }
                        
                    } 
                }
            }
            // 総投入重量と総投入原価を更新
            $polishedMaterial->total_input_weight = $totalInputWeight;
            $polishedMaterial->total_input_cost = $totalInputCost;
            // 精麦歩留りを計算し、値をセット
            if ($totalInputWeight > 0) { // 0での除算を防ぐ
                $polishedMaterial->polished_retention = ($totalOutputWeight / $totalInputWeight) * 100;
            } else {
                $polishedMaterial->polished_retention = null; // 入力重量が0または無効な場合、歩留りは計算できない
            }
            // 変更を保存
            $polishedMaterial->save();
    
            // トランザクションをコミット
            DB::commit();
    
            // 成功した場合のリダイレクト
            return redirect()->route('millPolishedMaterials.index')->with('success', '精麦済み原料が更新されました。');
    } catch (\Exception $e) {
        // エラーが発生した場合はロールバック
        DB::rollback();

        // エラーメッセージをユーザに返す
        return back()->withErrors('エラーが発生しました: ' . $e->getMessage());
    }
}



    // Remove the specified polished material from storage.
    public function destroy($id)
    {
    DB::beginTransaction();
    try {
        $polishedMaterial = MillPolishedMaterial::findOrFail($id);

        // 関連する中間テーブルデータを取得し削除
        $relatedPurchaseMaterials = $polishedMaterial->millPurchaseMaterials;
        foreach ($relatedPurchaseMaterials as $purchaseMaterial) {
            // 在庫を再計算して更新
            $usedAmount = $purchaseMaterial->pivot->input_weight; // この精麦で使用した量
            $purchaseMaterial->remaining_amount += $usedAmount; // 削除するので再度足す
            if ($purchaseMaterial->remaining_amount > 0) {
                $purchaseMaterial->is_finished = false;
            }
            $purchaseMaterial->save();

            // 中間テーブルデータの削除
            $polishedMaterial->millPurchaseMaterials()->detach($purchaseMaterial->id);
        }

        // 精麦済み原料データの削除
        $polishedMaterial->delete();

        // すべて成功したらコミット
        DB::commit();
        return redirect()->route('millPolishedMaterials.index')->with('success', '精麦済み原料が正常に削除されました。');

    } catch (\Exception $e) {
        // エラーが発生した場合はロールバック
        DB::rollback();
        return back()->withErrors('エラーが発生しました: ' . $e->getMessage());
    }
}
}
