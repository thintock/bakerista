<?php

namespace App\Http\Controllers;

use App\Models\MillPurchaseMaterial;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MillPurchaseMaterialsController extends Controller
{
    public function index(Request $request)
    {
        // 表示期間設定
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // クエリを作成
        $query = MillPurchaseMaterial::query();
        
        if ($startDate && $endDate) {
            $query->whereBetween('arrival_date', [$startDate, $endDate]); // 表示期間設定
        }
        
        if ($request->input('show_all') == 'true') {
            $query->where('is_finished', true); // 在庫表示設定
        } else {
            $query->where('is_finished', false);
        }
        
        $millPurchaseMaterials = $query->orderBy('lot_number')->paginate(15);
        
        // 各種計算
        $allTotalAmount = MillPurchaseMaterial::sum('total_amount'); // 累計入荷量
        $totalRemainingAmount = MillPurchaseMaterial::where('is_finished', false) // 合計在庫量
            ->sum('remaining_amount');
        $totalStockValue = MillPurchaseMaterial::where('is_finished',false) // 合計在庫金額
            ->sum(\DB::raw('(cost / total_amount) * remaining_amount'));
        
        return view('millPurchaseMaterials.index', compact('millPurchaseMaterials', 'totalRemainingAmount', 'totalStockValue', 'allTotalAmount'));
    }

    public function create()
    {
        $millPurchaseMaterials = Material::all(); //すべての原材料データを取得する
        return view('millPurchaseMaterials.create', compact('millPurchaseMaterials'));
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
        'materials_id' => 'required|exists:materials,id', // materialsテーブルのidが存在すること
        'arrival_date' => 'nullable|date', //日付であること、またはnull許可
        'year_of_production' => 'required|string|size:2', // 文字列で長さが2
        'flecon_number' => 'required|string|size:3', // 文字列で長さが3
        'total_amount' => 'required|integer', // 整数であること、またはnull許可
        'cost' => 'nullable|numeric', // 数値であること、またはnull許可
        'inspection_completed' => 'boolean',
        ]);
        
        $material = Material::find($validateData['materials_id']);
        $materialsCode = $material ? $material->materials_code : '';

       // ロットナンバーを生成
        $lotNumber = $materialsCode . $validateData['year_of_production'] . $validateData['flecon_number'];

        // ロットナンバーの一意性をチェック
        $existingLotNumber = MillPurchaseMaterial::where('lot_number', $lotNumber)->exists();
        if ($existingLotNumber) {
            // 既に存在する場合はエラーを返すか、別のロットナンバーを生成
            return back()->withErrors(['lot_number' => 'すでにロットナンバーが存在します。'])->withInput();
        } 
        
        // 入荷量から在庫量を作成
        $remaining_amount = $validateData['total_amount'] ?? 0; // nullの場合は0を設定
        
        $millPurchaseMaterial = new MillPurchaseMaterial($validateData);
        $millPurchaseMaterial->lot_number = $lotNumber; // ロットナンバーをセット
        $millPurchaseMaterial->remaining_amount = $remaining_amount; // 在庫量をセット
        $millPurchaseMaterial->user_id = Auth::id(); // ユーザーIDをセット
        $millPurchaseMaterial->save();

        return redirect()->route('millPurchaseMaterials.edit', $millPurchaseMaterial->id)->with('success', '原料入荷情報を登録しました。');
    }

    public function edit($id)
    {
        $millPurchaseMaterial = MillPurchaseMaterial::findOrFail($id);
        return view('millPurchaseMaterials.edit', compact('millPurchaseMaterial'));
    }

    public function update(Request $request, $id)
    {
        // レコードを取得
        $millPurchaseMaterial = MillPurchaseMaterial::findOrFail($id);

        // バリデーションを実行
        $validatedData = $request->validate([
            'arrival_date' => 'nullable|date',
            'year_of_production' => 'required|max:2',
            'flecon_number' => 'required|max:3',
            'total_amount' => 'nullable|integer',
            'cost' => 'nullable|numeric',
            'inspection_completed' => 'nullable|boolean',
        ]);
        
        // ロットナンバーの生成（materials_code, year_of_production, flecon_numberを組み合わせ）
        // ただし、editではlot_numberは変更しないのでここでは再生成しません。

        // 更新可能な情報のみを更新
        $millPurchaseMaterial->arrival_date = $validatedData['arrival_date'];
        $millPurchaseMaterial->year_of_production = $validatedData['year_of_production'];
        $millPurchaseMaterial->flecon_number = $validatedData['flecon_number'];
        $millPurchaseMaterial->total_amount = $validatedData['total_amount'];
        $millPurchaseMaterial->cost = $validatedData['cost'];
        $inspectionCompleted = $request->has('inspection_completed') ? $validatedData['inspection_completed'] : 0;
        $millPurchaseMaterial->inspection_completed = $inspectionCompleted;
        
        // 在庫残量の再計算
        $usedAmount = $millPurchaseMaterial->millPolishedMaterials()->sum('mill_purchase_material_polished.input_weight');
        $remaining_amount = ($validatedData['total_amount'] ?? 0) - $usedAmount;

        // 在庫量を更新し、is_finishedの状態を設定
        $millPurchaseMaterial->remaining_amount = $remaining_amount;
        $millPurchaseMaterial->is_finished = $remaining_amount <= 0;

        // レコードを保存
        $millPurchaseMaterial->save();

        // リダイレクト
        return redirect()->route('millPurchaseMaterials.edit',$millPurchaseMaterial->id)->with('success', '原料入荷情報を更新しました。');
    }

    public function destroy($id)
    {
        $millPurchaseMaterial = MillPurchaseMaterial::findOrFail($id);
        $millPurchaseMaterial->delete();
        return redirect()->route('millPurchaseMaterials.index');
    }
}
