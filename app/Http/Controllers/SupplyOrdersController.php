<?php

namespace App\Http\Controllers;

use App\Models\SupplyOrder;
use App\Models\SupplyItem;
use App\Models\Company;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            'orderDescription' => 'nullable|string',
        ], $messages);
        
        $orderDescription = $request->input('orderDescription');
        $faxOrEmailOrderIncluded = false;
        $ordersToUpdate = [];
    
        // 発注情報更新トランザクション開始
        DB::beginTransaction();
        try {
            foreach ($request->selected_orders as $orderId) {
                $order = SupplyOrder::findOrFail($orderId);
                // 発注データの更新
                if (isset($validated['orders'][$orderId])) {
                    $oldQuantity = $order->order_quantity;
                    $order->order_quantity = $validated['orders'][$orderId];
                    if (!empty($validated['orderDescription'])) {
                        $oldDescription = $order->description ?? '';
                        $orderDescription = $validated['orderDescription'] ?? ''; 
                        $order->description = $oldDescription . ' ' . $validated['descriptions'][$orderId] . ' 【発注書備考：' . $orderDescription . '】';
                    }
                    $order->delivery_date = $validated['delivery_dates'][$orderId];
                    $order->order_date = Carbon::today()->toDateString();
                    $order->user_id = auth()->user()->id;
                    if ($oldQuantity != $validated['orders'][$orderId]) {
                        $order->description .= " 発注数修正: {$oldQuantity}→{$validated['orders'][$orderId]}.";
                    }
                    if ($order->company->how_to_order === 'FAX' || $order->company->how_to_order === 'メール') {
                        $faxOrEmailOrderIncluded = true;
                    }
                    // ステータスの更新
                    $order->status = '入荷待ち';
                    $ordersToUpdate[] = $order;
                    $order->save();
                }
            }
            
            DB::commit();
            
            if ($faxOrEmailOrderIncluded) {
                $user = auth()->user();
                return view('supplyOrders.orderForm', ['orders' => $ordersToUpdate, 'user' => $user, 'message' => '発注が成功しました。', 'orderDescription' => $orderDescription ]);
            } else {
                return redirect()->route('supplyOrders.orderExecute')->with('success', '発注が成功しました。');
            }
        } catch (\Exception $e) {
            // 例外発生時はロールバック
            DB::rollBack();
            return redirect()->back()->with('error', '発注処理中にエラーが発生しました。: ' . $e->getMessage());
        }
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

    public function orderArrival(Request $request)
    {
        // 基本のクエリ
        $query = SupplyOrder::with(['supplyItem', 'company', 'location', 'user'])
                    ->where('status', '入荷待ち')
                    ->orderBy('delivery_date', 'asc');
        
        // 検索条件がリクエストに含まれている場合、それをクエリに適用
        if ($request->has('location')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('location_name', 'like', '%' . $request->location . '%');
            });
        }
        if ($request->has('company')) {
            $query->whereHas('company', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->company . '%');
            });
        }
        if ($request->has('delivery_date')) {
            $query->where('delivery_date', $request->delivery_date);
        }
        if ($request->has('item_name')) {
            $query->whereHas('supplyItem', function ($q) use ($request) {
                $q->where('item_name', 'like', '%' . $request->item_name . '%');
            });
        }
        
        // 結果をページネート
        $supplyOrders = $query->paginate(30);
        
        // 関連データを取得
        $supplyItems = SupplyItem::all();
        $locations = Location::all();
        $companies = Company::all();
        $users = User::all();
        
        // ビューにデータを渡す
        return view('supplyOrders.orderArrival', compact('supplyOrders', 'supplyItems', 'locations', 'companies', 'users'));
    }
    
    public function storeArrival(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|integer|exists:supply_orders,id',
            'arrival_quantity' => 'required|integer|min:0',
            'order_fix' => 'nullable|boolean',
            'point_review' => 'required|integer|between:1,5',
        ]);
    
        DB::beginTransaction();
        try {
            $order = SupplyOrder::findOrFail($validatedData['order_id']);
            $oldOrderQuantity = $order->order_quantity; // 元の発注数
            $oldArrivalQuantity = $order->arrival_quantity; // 既に入荷した数
            $outOfStock = $oldOrderQuantity - $oldArrivalQuantity; // 未入荷数
            $arrivalQuantity = $validatedData['arrival_quantity'];
            
            if ($arrivalQuantity >= $outOfStock || $request->has('order_fix')) {
                // 入荷数が発注数以上、または発注修正がある場合
                $order->arrival_quantity = $oldArrivalQuantity + $arrivalQuantity;
                $order->status = '完了';
                if ($arrivalQuantity != $outOfStock) {
                    // 誤差調整がある場合の備考
                    $newOrderQuantity = $oldArrivalQuantity + $arrivalQuantity;
                    $order->order_quantity = $newOrderQuantity;
                    $order->description .= " 【入荷時修正:発注数{$oldOrderQuantity}→{$newOrderQuantity}】";
                }
            } else {
                // 発注数より入荷数が少なく、発注修正がない場合
                $order->arrival_quantity = $oldArrivalQuantity + $arrivalQuantity;
                // statusは更新しない
            }
            
            $currentDate = now();
            $order->arrival_date = $currentDate->toDateString();
            $order->arrival_user = auth()->user()->id;
            $order->save();
    
            // 実在庫の更新
            $supplyItem = $order->supplyItem;
            $supplyItem->actual_stock += $validatedData['arrival_quantity'];
    
            // 納期の更新
            if ($order->order_date) {
                $deliveryPeriod = $currentDate->diffInDays($order->order_date);
                $supplyItem->delivery_period = $deliveryPeriod;
            }
    
            // 次回発注予定日の更新
            // 前回の発注日と今回の発注日の間隔を取得し、その日数を今日に加算
            $lastOrder = SupplyOrder::where('item_id', $supplyItem->id)
                            ->where('id', '<', $order->id)
                            ->latest('order_date')
                            ->first();
    
            if ($lastOrder) {
                $daysSinceLastOrder = $currentDate->diffInDays($lastOrder->order_date);
                $supplyItem->order_schedule = $currentDate->addDays($daysSinceLastOrder);
            }
            
            // 発注点評価による発注点の調整
            $pointReview = $validatedData['point_review'] ?? null;
            $adjustmentFactor = match ($pointReview) {
                "1" => 1.25,
                "2" => 1.10,
                "4" => 0.90,
                "5" => 0.75,
                default => 1,
            };
    
            // 1, 2の場合は切り上げ、4, 5の場合は切り捨て
            if (in_array($pointReview, [1, 2])) {
                $supplyItem->order_point = ceil($supplyItem->order_point * $adjustmentFactor);
            } elseif (in_array($pointReview, [4, 5])) {
                $supplyItem->order_point = floor($supplyItem->order_point * $adjustmentFactor);
            } else {
                $supplyItem->order_point = $supplyItem->order_point * $adjustmentFactor;
            }
            $supplyItem->save();
    
            DB::commit();
    
            return redirect()->route('supplyOrders.orderArrival')->with('success', '入荷登録が完了しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '入荷処理中にエラーが発生しました。: ' . $e->getMessage());
        }
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
        $validatedData = $request->validate([
            'item_id' => 'required|integer|exists:supply_items,id',
            'order_quantity' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);
        $supplyItem = SupplyItem::findOrFail($request->input('item_id'));
        if ($supplyItem && $supplyItem->item_status !== '承認済み') {
            return back()->with('error', 'この資材備品は承認されていません。');
        }
            DB::beginTransaction();
            try {
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
            $validatedData = $request->validate([
            'status' => 'nullable|string',
            'request_date' => 'nullable|date',
            'order_date' => 'nullable|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'arrival_date' => 'nullable|date|after_or_equal:order_date',
            'order_quantity' => 'required|integer|min:0',
            'arrival_quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:255',
            ]);
            
            $supplyOrder->update($validatedData);
            
            return redirect()->route('supplyOrders.edit', $supplyOrder->id)->with('success', '発注情報の更新が成功しました。');
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
