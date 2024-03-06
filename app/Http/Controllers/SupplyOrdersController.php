<?php

namespace App\Http\Controllers;

use App\Models\SupplyOrder;
use App\Models\SupplyItem;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;

class SupplyOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 発注先のリストを取得
        $companies = Company::orderBy('name', 'asc')->get();
        
        // ロケーションのリストを取得
        $locations = Location::orderBy('location_code', 'asc')->get();
        
        // 資材備品のリストを取得
        $supplyItems = SupplyItem::orderBy('item_name', 'asc')->get();
        
        $supplyOrders = SupplyOrder::with(['supplyItem', 'company', 'location'])->paginate(15);
        
        return view('supplyOrders.index', compact('supplyOrders', 'companies', 'locations', 'supplyItems'));
    }
    
    // 発注依頼画面
    public function orderRequest()
    {
        // すべての供給品目、企業、およびロケーションを取得
        $companies = Company::all();
        $locations = Location::all();
        $supplyItems = SupplyItem::all();
    
        // URLからsupplyItemのitem_idパラメータを取得
        $selectedItemId = request('item_id');
        $selectedItem = null;
    
        // 選択されたアイテムIDが存在する場合、その詳細情報を取得
        if ($selectedItemId) {
            $selectedItem = SupplyItem::find($selectedItemId);
        }
        
        // 入荷待ち数を取得
        $pendingArrivalsQuantity = SupplyOrder::calculatePendingArrivals($selectedItemId);
    
        // 選択されたアイテムの詳細情報をビューに渡す
        return view('supplyOrders.orderRequest', compact('companies', 'locations', 'selectedItem', 'supplyItems', 'pendingArrivalsQuantity'));
    }

    public function storeRequest(Request $request)
    {
        DB::beginTransaction();
        try {
               $validatedData = $request->validate([
                'item_id' => 'required|integer|exists:supply_items,id',
                'actual_stock' => 'required|integer',
                'description' => 'nullable|string',
            ]);
            
            if ($request->has('update_stock')) {
                // 在庫更新処理
                $itemId = $request->input('item_id'); 
                $stock = $request->input('actual_stock', 0); 
                $supplyItem = SupplyItem::find($itemId);
                if ($supplyItem) {
                    $supplyItem->actual_stock = $stock;
                    $supplyItem->save();
                    DB::commit();
                    return redirect()->back()->with('success', '在庫が正常に更新されました。');

                } else {
                    return redirect()->back()->with('error', '在庫の更新に失敗しました。再度お試しください。');

                }
                
            } else {
                // 発注依頼処理
                $supplyItem = SupplyItem::findOrFail($request->item_id);
                $oldActualStock = $supplyItem->actual_stock;
                
                // actual_stockが変更されているか確認
                if ($oldActualStock != $request->actual_stock) {
                    // 実在庫数の更新
                    $supplyItem->actual_stock = $request->actual_stock;
                    // descriptionに実在庫変更記録を追加
                    $request->description .= "【実在庫変更：{$oldActualStock}→{$request->actual_stock}】";
                }
                
                $supplyItem->save();
                
                // SupplyOrderの作成と保存
                $supplyOrder = new SupplyOrder();
                $supplyOrder->item_id = $request->item_id;
                $supplyOrder->description = $request->description;
                $supplyOrder->status = '発注依頼中';
                $supplyOrder->request_date = now();
                $supplyOrder->request_user = auth()->user()->id;
                $supplyOrder->save();
            }
            
            
            DB::commit();
            return redirect()->route('supplyOrders.orderRequest')->with('success', '登録しました。');
    } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '処理に失敗しました。');
        }
    }
    
    public function orderEntry()
    {
        // ステータスが「発注依頼中」の発注データを取得
        $pendingOrders = SupplyOrder::with(['supplyItem'])
                            ->where('status', '発注依頼中')
                            ->get();
    
        // 各発注データに対して、発注数量を計算
        foreach ($pendingOrders as $order) {
            $calculationResult = SupplyOrder::calculateOrderQuantity($order->item_id);
            $order->calculatedOrderQuantity = $calculationResult['orderQuantity'];
            $order->pendingArrivals = $calculationResult['pendingArrivals'];
        }
    
        // 資材備品マスタの条件に合致する資材備品を取得
        $itemsForOrdering = SupplyItem::with(['location'])
                        ->where('item_status', '承認済み')
                        ->where(function($query) {
                            $query->where('order_schedule', '<', now())
                                  ->orWhereColumn('actual_stock', '<=', 'order_point');
                        })
                        ->get();
        
        // 各資材備品に対して、発注数量を計算
        foreach ($itemsForOrdering as $item) {
            $calculationResult = SupplyOrder::calculateOrderQuantity($item->id);
            $item->order_quantity = $calculationResult['orderQuantity'];
            $item->pendingArrivals = $calculationResult['pendingArrivals'];
        }
        
        // 件数を取得
        $itemsCount = $itemsForOrdering->filter(function($item) {
            return $item->order_quantity > 0;
        })->count();
        
        return view('supplyOrders.orderEntry', [
            'pendingOrders' => $pendingOrders,
            'itemsForOrdering' => $itemsForOrdering,
            'itemsCount' => $itemsCount,
        ]);
    }
    
    public function updateEntry(Request $request)
    {
        $messages = [
            'selected_orders.required' => '少なくとも一つの発注依頼を選択してください。',
            'selected_orders.*.exists' => '選択された項目が無効です。',
            'order_quantities.*' => '発注数が入力されていません。',
        ];
        // バリデーションルール
        $validatedData = $request->validate([
            'selected_orders' => 'required|array',
            'selected_orders.*' => 'exists:supply_orders,id',
            'order_quantities' => 'required|array',
            'order_quantities.*' => 'integer|min:1',
            'descriptions' => 'array',
            'descriptions.*' => 'nullable|string'
        ], $messages);
        
        DB::beginTransaction();
        try {
            foreach ($validatedData['selected_orders'] as $orderId) {
                $order = SupplyOrder::with('supplyItem')->find($orderId);
                $orderQuantity = $validatedData['order_quantities'][$orderId] ?? null;
                $newDescription = ($request->descriptions[$orderId] ?? '');
                $order->description = $order->description ? $order->description . " | " . '【依頼】' . $newDescription : '【依頼】' . $newDescription;
    
                if ($orderQuantity !== null && $order) {
                    // 発注数量を更新
                    $order->order_quantity = $orderQuantity;
                    // ステータスを「発注待ち」に更新
                    $order->status = '発注待ち';
                    // supplyItem関連情報のコピー
                    if ($order->supplyItem) {
                        $order->company_id = $order->supplyItem->company_id;
                        $order->location_id = $order->supplyItem->location_code;
                    }
                    // ログイン中のユーザーIDを登録
                    $order->user_id = auth()->id();
                    $order->save();
                }
            }
            DB::commit();
            return redirect()->route('supplyOrders.orderEntry')->with('success', '選択された発注依頼が更新され、「発注待ち」に変更されました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '更新処理に失敗しました。' . $e->getMessage());
        }
    }

    public function storeEntry(Request $request)
    {
        $messages = [
            'selected_stores.required' => '少なくとも一つの発注候補を選択してください。',
            'selected_stores.*.exists' => '選択された項目が無効です。',
        ];
        
        $validatedData = $request->validate([
            'selected_stores' => 'required|array',
            'selected_stores.*' => 'exists:supply_items,id',
            'orders' => 'required|array',
            'orders.*' => 'integer|min:1',
            'descriptions' => 'array',
            'descriptions.*' => 'nullable|string'
        ], $messages);
        
        DB::beginTransaction();
        try {
            foreach ($request->selected_stores as $itemId) {
                $orderQuantity = $request->orders[$itemId];
                $description = '【自動】' . ($request->descriptions[$itemId] ?? '');
                
                $newOrder = new SupplyOrder();
                $newOrder->item_id = $itemId;
                $newOrder->order_quantity = $orderQuantity;
                $newOrder->description = $description;
                
                $newOrder->status = '発注待ち';
                $newOrder->company_id = SupplyItem::find($itemId)->company_id;
                $newOrder->location_id = SupplyItem::find($itemId)->location_code;
                $newOrder->user_id = auth()->user()->id; // ログイン中のユーザーIDを設定
                $newOrder->save();
            }
            DB::commit();
            return redirect()->route('supplyOrders.orderEntry')->with('success', '選択された発注情報が作成され、「発注待ち」に変更されました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '発注入力に失敗しました。' . $e->getMessage());
        }
    }


    public function orderExecute()
    {
        // ステータスが「発注待ち」の発注データを取得
        $ordersWaiting = SupplyOrder::with(['supplyItem', 'company', 'location'])
                            ->where('status', '発注待ち')
                            ->get()
                            ->map(function ($order) {
                                // SupplyItemのdelivery_periodを基に納期を計算
                                $deliveryDate = Carbon::now()->addDays($order->supplyItem->delivery_period)->format('Y-m-d');
                                // 納期をオーダーに追加
                                $order->delivery_date = $deliveryDate;
                                return $order;
                            });
    
        // 発注先(company_id)ごとにグループ化し、ソート。
        $ordersByCompany = $ordersWaiting->sortBy('company_id')->groupBy('company_id');
    
        // 発注先ごとのデータをビューに渡す
        return view('supplyOrders.orderExecute', compact('ordersByCompany'));
    }
    
    public function storeExecute(Request $request)
    {
        $messages = [
            'selected_orders.required' => '少なくとも一つの発注候補を選択してください。',
            'selected_orders.*.exists' => '選択された項目が無効です。',
        ];
        // バリデーションルールを設定
        $validated = $request->validate([
            'selected_orders' => 'required|array',
            'selected_orders.*' => 'exists:supply_orders,id',
            'orders.*' => 'required|numeric|min:0',
            'delivery_dates.*' => 'required|date',
            'descriptions.*' => 'nullable|string',
        ], $messages);
    
        // 発注情報更新トランザクション開始
        DB::beginTransaction();
        try {
            foreach ($request->selected_orders as $orderId) {
                $order = SupplyOrder::findOrFail($orderId);
                // 発注データの更新
                if (isset($validated['orders'][$orderId])) {
                    $oldQuantity = $order->order_quantity;
                    $order->order_quantity = $validated['orders'][$orderId];
                    $oldDescription = $order->description ?? '';
                    $order->description = $oldDescription . ' ' . $validated['descriptions'][$orderId];
                    $order->delivery_date = $validated['delivery_dates'][$orderId];
                    $order->order_date = Carbon::today()->toDateString();
                    // 発注数量の修正があった場合は備考欄に追記
                    if ($oldQuantity != $validated['orders'][$orderId]) {
                        $order->description .= " 発注数修正: {$oldQuantity}→{$validated['orders'][$orderId]}.";
                    }
                    // ステータスの更新
                    $order->status = '入荷待ち';
    
                    $order->save();
                }
            }
    
            DB::commit();
            return redirect()->route('supplyOrders.orderExecute')->with('success', '発注が成功しました。');
        } catch (\Exception $e) {
            // 例外発生時はロールバック
            DB::rollBack();
            return redirect()->back()->with('error', '発注処理中にエラーが発生しました。: ' . $e->getMessage());
        }
    }

    public function createOrderForm(Request $request)
    {
        // how_to_order の値をリクエストから取得
        $howToOrder = $request->input('how_to_order');
    
        // 「発注待ち」かつ how_to_order がフォームから送信された値に一致するレコードを取得
        $orders = SupplyOrder::with('supplyItem', 'company', 'location')
                    ->where('status', '発注待ち')
                    ->whereHas('company', function($query) use ($howToOrder) {
                        $query->where('how_to_order', $howToOrder);
                    })
                    ->get();
    
        // PDFを生成する場合
        $pdf = PDF::loadView('path.to.order_form_view', compact('orders'));
    
        // PDFをブラウザに表示する
        return $pdf->stream('order_form.pdf');
    }


    public function cancel(Request $request, $orderId)
    {
        $order = SupplyOrder::findOrFail($orderId);
        $user = Auth::user();
        $now = Carbon::now()->toDateTimeString();
        
        $additionalNote = "取消日時：{$now}, 取消者：{$user->name} {$user->first_name}";
        
        $order->description = $order->description ? $order->description . "　|　" . $additionalNote : $additionalNote;
        
        $order->status = '取消';
        $order->save();
    
        return redirect()->back()->with('success', '発注依頼が取消されました。');
    }

    
    public function create()
    {
        $supplyItems = SupplyItem::all();
        $companies = Company::all();
        $locations = Location::all();
        return view('supplyOrders.create', compact('supplyItems', 'companies', 'locations'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $validatedData = $request->validate([
                'item_id' => 'required|integer|exists:supply_items,id',
                'order_quantity' => 'nullable|integer|min:1',
                'description' => 'nullable|string|max:255',
            ]);
            
            // order_quantityがnullまたは未設定の場合、0を代入
            $validatedData['order_quantity'] = $validatedData['order_quantity'] ?? 0;
            
            // supply_itemsテーブルから発注先(company_id)とロケーション(location_code)を取得
            $supplyItem = SupplyItem::findOrFail($validatedData['item_id']);
            $supplyOrder = new SupplyOrder($validatedData);
            $supplyOrder->description = '【手動】' . ($validatedData['description'] ?? '');
            $supplyOrder->status = '発注待ち';
            $supplyOrder->company_id = $supplyItem->company_id;
            $supplyOrder->location_id = $supplyItem->location_code;
            $supplyOrder->user_id = Auth::id(); // 現在ログインしているユーザーID
            $supplyOrder->save();

            DB::commit();
            return redirect()->route('supplyOrders.orderExecute')->with('success', '登録しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '発注登録できませんでした。');
        }
    }

    public function show(SupplyOrder $supplyOrder)
    {
        return view('supplyOrders.show', compact('supplyOrder'));
    }

    public function edit(SupplyOrder $supplyOrder)
    {
        $supplyItems = SupplyItem::all();
        $companies = Company::all();
        $locations = Location::all();
        return view('supplyOrders.edit', compact('supplyOrder', 'supplyItems', 'companies', 'locations'));
    }

    public function update(Request $request, SupplyOrder $supplyOrder)
    {
        DB::beginTransaction();
        try {
            
            $validatedData = $request->validate([
            'status' => 'nullable|string',
            'request_date' => 'nullable|date',
            'order_date' => 'nullable|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'arrival_date' => 'nullable|date|after_or_equal:order_date',
            'order_quantity' => 'required|integer|min:0',
            'arrival_quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'location_id' => 'required|exists:locations,id',
            ]);

        
            $supplyOrder->update($validatedData);
            // ここに複雑な更新処理を追加
            DB::commit();
            return redirect()->route('supplyOrders.edit', $supplyOrder->id)->with('success', '発注情報の更新が成功しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '発注情報の更新に失敗しました。');
        }
    }

    public function destroy($id)
    {
        
        DB::beginTransaction();
        try {
            
            // 指定されたIDを持つSupplyOrderを検索
            $supplyOrder = SupplyOrder::findOrFail($id);
    
            // レコードを削除
            $supplyOrder->delete();
            
            DB::commit();
            return redirect()->route('supplyOrders.index')->with('success', '発注情報を削除しました。');
    } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '発注情報の削除に失敗しました。');
        }
    }
}
