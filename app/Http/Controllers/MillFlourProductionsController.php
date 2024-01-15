<?php

namespace App\Http\Controllers;

use App\Models\MillFlourProduction;
use App\Models\MillFlourProductionDetail;
use App\Models\MillPolishedMaterial;
use App\Models\MillMachine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Add this line
use DateTime;

class MillFlourProductionsController extends Controller
{
    // index method: 一覧表示
    public function index()
    {
        // 'production_date' で降順に並び替えて、ページネーションで1ページあたり25個表示
    $productions = MillFlourProduction::orderBy('production_date', 'desc')->paginate(25);
    return view('millFlourProductions.index', compact('productions'));
    }

    // create method: 新規作成画面表示
    public function create()
    {
        // 全ての製粉機を取得
        $millMachines = MillMachine::all();
        
        // 全ての精麦済原料を取得
        $millPolishedMaterials = MillPolishedMaterial::all();
        
        return view('millFlourProductions.create', compact('millMachines', 'millPolishedMaterials'));
    }



    // store method: 新規レコード保存
    public function store(Request $request)
    {
        // トランザクション開始
        DB::beginTransaction();
        
        try {
            // バリデーションとデータ保存
            $validatedData = $request->validate([
                'production_date' => 'required|date',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
                'flour_weight' => 'nullable|numeric|min:0',
                'bran_weight' => 'nullable|numeric|min:0',
                'remarks' => 'nullable|string|max:1000',
                'mill_machine_id' => 'required|exists:mill_machines,id',
                'input_weights' => 'required|array',
                'input_weights.*' => ['required', 'numeric', 'min:0', 'regex:/^\d{0,5}(\.\d{1,2})?$/'],
            ]);
            
            // 対応する製粉機のmachine_numberを取得
            $millMachine = MillMachine::find($validatedData['mill_machine_id']);
            if (!$millMachine) {
                return back()->withErrors(['msg' => '選択された製粉機が存在しません。']);
            }
            
            // ロット番号作成
            $firstPolishedMaterial = MillPolishedMaterial::first();
            if ($firstPolishedMaterial) {
                $polishedLotNumber = $firstPolishedMaterial->polished_lot_number;
                // production_dateから月と日を取得してフォーマット
                $date = new DateTime($validatedData['production_date']);
                $monthDay = $date->format('md'); // 月と日の4文字
                // 製粉機番号（machine_number）を2桁にフォーマットする
                $machineNumberFormatted = sprintf("%02d", $millMachine->machine_number); 
                // ロット番号を組み立てる
                $lotNumber = $polishedLotNumber . $monthDay . $machineNumberFormatted;
                // ロット番号を検証済みデータに追加
                $validatedData['production_lot_number'] = $lotNumber;
            } else {
                // 例外処理やエラーハンドリング
                return back()->withErrors(['msg' => '精麦済み原料が見つかりません。']);
            }

            
            $millPolishedMaterialIds = $request->input('mill_polished_material_ids', []);
            // 原料投入量と原価と製粉歩留率を計算
            $totals = MillFlourProduction::calculateTotals(
                $request->input('input_weights', []),
                $millPolishedMaterialIds,
                $validatedData['flour_weight'],
                $validatedData['bran_weight']
                );
            $validatedData = array_merge($validatedData, $totals);
            
            
            // user_idを現在認証されているユーザーのものとして追加
            $validatedData['user_id'] = Auth::id();
            
            // 新規製粉生産レコード作成
            $production = MillFlourProduction::create($validatedData);
            
            
            // mill_flour_production_detailsレコード作成
            $millPolishedMaterialIds = $request->input('mill_polished_material_ids', []);
            $inputWeights = $request->input('input_weights', []);
            $inputCosts = $validatedData['input_costs'];
            
            foreach ($millPolishedMaterialIds as $index => $millPolishedMaterialId) {
                // 各詳細レコードに必要なデータを配列で設定
                $production->millPolishedMaterials()->attach($millPolishedMaterialId, [
                    'input_weight' => $inputWeights[$index] ?? null,
                    'input_cost' => $inputCosts[$index] ?? null
                ]);
            }
            
            // 全ての処理が成功したらコミット
            DB::commit();
            
            return redirect()->route('millFlourProductions.index')->with('success', '製粉生産が登録されました。');
        } catch (\Exception $e) {
        // エラーが発生した場合はロールバック
        DB::rollback();

        // エラーメッセージをユーザに返す
        return back()->withErrors('エラーが発生しました: ' . $e->getMessage());
        }
    }

    // edit method: 編集画面表示
    public function edit($id)
    {
        // 製粉機情報と精麦済み原料情報を取得
        $millFlourProduction = MillFlourProduction::findOrFail($id);
        $millMachines = MillMachine::all();
        $millPolishedMaterials = MillPolishedMaterial::all();
        
        // 編集対象の製粉生産レコードを渡す
        return view('millFlourProductions.edit', compact('millFlourProduction', 'millMachines', 'millPolishedMaterials'));
    }

    // update method: レコード更新
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
    
        try {
            
            $production = MillFlourProduction::with('millPolishedMaterials')->findOrFail($id);
            // 同storeアクションで行ったようにバリデーション、値の計算を行う
            $validatedData = $request->validate([
                'production_date' => 'required|date',
                'start_time' => 'nullable',
                'end_time' => 'nullable|after_or_equal:start_time',
                'flour_weight' => 'nullable|numeric|min:0',
                'bran_weight' => 'nullable|numeric|min:0',
                'remarks' => 'nullable|string|max:1000',
                'input_weights' => 'required|array',
                'input_weights.*' => ['required', 'numeric', 'min:0', 'regex:/^\d{0,5}(\.\d{1,2})?$/'],
            ]);
            
            $millPolishedMaterialIds = $production->millPolishedMaterials->pluck('id')->toArray();
            // 原料投入量と原価と製粉歩留率を計算
            $totals = MillFlourProduction::calculateTotals(
                $request->input('input_weights', []),
                $millPolishedMaterialIds,
                $validatedData['flour_weight'],
                $validatedData['bran_weight']
                );
            $validatedData = array_merge($validatedData, $totals);
            
            // レコードの更新
            $production->update($validatedData);
            
            
            // 詳細の更新
            $millPolishedMaterialIds = $request->input('mill_polished_material_ids', []);
            $inputWeights = $request->input('input_weights', []);
            $inputCosts = $validatedData['input_costs'];
            
            // 既存の詳細を一旦削除し、新しく追加する
            $production->millPolishedMaterials()->detach();
            // foreach ($millPolishedMaterialIds as $index => $millPolishedMaterialId) {
            //     $production->millPolishedMaterials()->attach($millPolishedMaterialId, [
            //         'input_weight' => $inputWeights[$index] ?? null,
            //         'input_cost' => $inputCosts[$index] ?? null
            //     ]);
            // }
            foreach ($millPolishedMaterialIds as $index => $millPolishedMaterialId) {
                // 各詳細レコードに必要なデータを配列で設定
                $production->millPolishedMaterials()->attach($millPolishedMaterialId, [
                    'input_weight' => $inputWeights[$index] ?? null,
                    'input_cost' => $inputCosts[$index] ?? null
                ]);
            }
            DB::commit();
            return redirect()->route('millFlourProductions.index')->with('success', '製粉生産が更新されました。');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('エラーが発生しました: ' . $e->getMessage());
        }
    }
    
    
    // destroy method: レコード削除
    public function destroy($id)
    {
        $production = MillFlourProduction::with('millPolishedMaterials')->findOrFail($id);
        // 関連する中間テーブルのデータを削除
        $production->millPolishedMaterials()->detach();
        // 製粉生産レコードを削除
        $production->delete();
        
        return redirect()->route('millFlourProductions.index')->with('success', '製粉生産が削除されました。');
    }
}
