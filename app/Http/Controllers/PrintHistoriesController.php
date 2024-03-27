<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrintHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductItem;

class PrintHistoriesController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $productItems = ProductItem::all(); // 全ての商品データを取得
    
        // 最新の50件の印刷履歴データを取得
        $printHistories = PrintHistory::with('productItem') // 関連するProductItemと共に取得
                            ->latest() // 最新のデータから取得
                            ->paginate(50); // 50件取得
    
        // 商品データと印刷履歴データをビューに渡す
        return view('printHistories.create', compact('productItems', 'printHistories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product_items,id',
            'count' => 'required|integer|min:1'
        ]);
    
        $printHistory = new PrintHistory([
            'user_id' => Auth::id(), 
            'product_id' => $validated['product_id'],
            'count' => $validated['count'],
        ]);
    
        $printHistory->save();
    
        return redirect()->route('printHistories.show', $printHistory->id)->with('success', 'ラベル印刷情報が記録されました。');
    }

    public function show(PrintHistory $printHistory)
    {
        return view('printHistories.show', compact('printHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
