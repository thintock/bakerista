<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\SupplyOrder;
use App\Models\SupplyItem;
use App\Models\Location;
use App\Models\Company;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SupplyItemsController extends Controller
{
    public function generateQr($id)
    {
        $supplyItem = SupplyItem::findOrFail($id);
        // supplyItemに基づくorderRequestのURLを構築
        $url = route('supplyOrders.orderRequest', ['item_id' => $id]);
        // QRコードを生成
        $qrCode = QrCode::size(100)->generate($url);
    
        // QRコードとアイテム情報をビューに渡す
        return view('supplyItems.generateQr', [
            'qrCode' => $qrCode,
            'item_code' => $supplyItem->item_code,
            'item_name' => $supplyItem->item_name,
            'location' => $supplyItem->location->location_name
        ]);
    }

    public function imageUpdate(Request $request, SupplyItem $supplyItem)
    {
        $request->validate([
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $itemId = $request->input('item_id'); 
        $supplyItem = SupplyItem::find($itemId);
        // サムネイル画像のアップロード処理
        if ($request->hasFile('thumbnail')) {
            // 新しいサムネイルの保存
            $path = $request->thumbnail->store('thumbnails');
            $supplyItem->thumbnail =  $path;
        }
        $supplyItem->save();
        
        return redirect()->route('supplyOrders.orderRequest', ['item_id' => $supplyItem->id])->with('success', '資材備品画像を登録しました。');
    }
    
    public function index(Request $request)
    {
        // 発注先のリストを取得
        $companies = Company::orderBy('name', 'asc')->get(); 
        
        // ロケーションのリストを取得
        $locations = Location::orderBy('location_code', 'asc')->get();
        
        $query = SupplyItem::query();

        if ($request->filled('item_code')) {
            $query->where('item_code', 'like', '%' . $request->item_code . '%');
        }
    
        if ($request->filled('item_status')) {
            $query->where('item_status', $request->item_status);
        }
    
        if ($request->filled('item_name')) {
            $query->where('item_name', 'like', '%' . $request->item_name . '%');
        }
    
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
    
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('location_code')) {
            $query->where('location_code', $request->location_code);
        }
    
        $supplyItems = $query->paginate(15)->withQueryString();
        
        return view('supplyItems.index', compact('supplyItems', 'companies', 'locations'));
    }

    public function create()
    {
        $locations = Location::all();
        $companies = Company::all();
        $users = Auth::id();
        
        return view('supplyItems.create', compact('locations', 'companies', 'users',));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // バリデーションルールを定義
            $validatedData = $request->validate([
                'item_code' => 'nullable|string|unique:supply_items,item_code',
                'item_name' => 'nullable|string|max:255',
                'standard' => 'nullable|string|max:255',
                'brand_name' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:255',
                'price' => 'nullable|numeric',
                'order_lot' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'files' => 'nullable|array|max:10',
                'files.*' => 'file|max:102400',
                'print_images' => 'nullable|array|max:2',
                'print_images.*' => 'file|mimetypes:application/pdf,application/postscript|max:102400',
                'order_point' => 'nullable|integer|min:0',
                'constant_stock' => 'nullable|integer|min:0|max:100000',
                'actual_stock' => 'nullable|integer|min:0|max:100000',
                'order_url' => 'nullable|url',
                'order_schedule' => 'nullable|date',
                'delivery_period' => 'nullable|integer|min:1|max:100',
                'location_code' => 'nullable|exists:locations,id',
                'company_id' => 'nullable|exists:companies,id',
            ]);
        
            // ファイルアップロードの処理（attachmentsとprint_images）はここに追加する
        
            // 受付ユーザー登録処理
            $validatedData['user_id'] = Auth::id();
            
            // item_statusデフォルト設定
            $validatedData['item_status'] = '未承認';
            
            // サムネイル画像のアップロード
            if ($request->hasFile('thumbnail')) {
                $path = $request->thumbnail->store('thumbnails');
                $validatedData['thumbnail'] = $path;
            }
            
            // 資材資料のアップロード
            if ($request->hasFile('files')) {
                    $filePaths = [];
                    foreach ($request->file('files') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $path = $file->store('supply_item_files');
                        $filePaths[] = ['path' => $path, 'originalName' => $originalName];
                    }
                $validatedData['files'] = json_encode($filePaths);
            }
        
            // 印刷用データのアップロード
            if ($request->hasFile('print_images')) {
                    $printImagePaths = [];
                    foreach ($request->file('print_images') as $image) {
                    $originalName = $image->getClientOriginalName();
                    $path = $image->store('supply_print_images');
                    $printImagePaths[] = ['path' => $path, 'originalName' => $originalName];
                }
                $validatedData['print_images'] = json_encode($printImagePaths);
            }
            
            // SupplyItemインスタンスの作成と保存
            $supplyItem = new SupplyItem($validatedData);
            $supplyItem->save();
            
        
            DB::commit();
            // ユーザーを登録成功ページにリダイレクト
            return redirect()->route('supplyItems.edit', $supplyItem->id )->with('success', '新規資材備品を登録しました。');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('エラーが発生しました：' . $e->getMessage());
        } 
    }


    // 詳細表示
    public function show(SupplyItem $supplyItem)
    {
        return view('supplyItems.show', compact('supplyItem'));
    }

    // 編集フォーム表示
    public function edit(SupplyItem $supplyItem)
    {
        // 全てのロケーションを取得
        $locations = Location::all();
        
        // 全ての取引先情報を取得
        $companies = Company::orderBy('name','asc')->get();
        
        return view('supplyItems.edit', compact('supplyItem', 'locations', 'companies'));
    }

    // 更新処理
    public function update(Request $request, SupplyItem $supplyItem)
    {
        DB::beginTransaction();
        
        try {
            
            $validatedData = $request->validate([
                'item_code' => 'nullable|string|unique:supply_items,item_code,'.$supplyItem->id,
                'item_name' => 'nullable|string|max:255',
                'item_status' => 'required|string',
                'standard' => 'nullable|string|max:255',
                'brand_name' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:255',
                'price' => 'nullable|numeric',
                'order_lot' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'files' => 'nullable|array|max:10',
                'files.*' => 'file|max:102400',
                'print_images' => 'nullable|array|max:2',
                'print_images.*' => 'file|mimetypes:application/pdf,application/postscript|max:102400',
                'order_point' => 'nullable|integer|min:0',
                'constant_stock' => 'nullable|integer|min:0|max:100000',
                'actual_stock' => 'nullable|integer|min:0|max:100000',
                'order_url' => 'nullable|url',
                'order_schedule' => 'nullable|date',
                'delivery_period' => 'nullable|integer|min:0|max:200',
                'location_code' => 'nullable|exists:locations,id',
                'company_id' => 'nullable|exists:companies,id',
            ]);
    
            // 受付ユーザー登録処理
            $validatedData['user_id'] = Auth::id();
            
            // サムネイル画像のアップロード処理
            if ($request->hasFile('thumbnail')) {
                // 新しいサムネイルの保存
                $path = $request->thumbnail->store('thumbnails');
                $validatedData['thumbnail'] = $path;
            }
        
            // サムネイルの削除
            if ($request->has('delete_thumbnail') && $supplyItem->thumbnail) {
                Storage::delete($supplyItem->thumbnail);
                $validatedData['thumbnail'] = null; // サムネイルのパスをnullに設定
            }
            
            // 資材資料のアップロード処理
            if ($request->hasFile('new_files')) {
                $newFiles = [];
                foreach ($request->file('new_files') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->store('supply_item_files');
                    $newFiles[] = ['path' => $path, 'originalName' => $originalName];
                }
                // 新しいファイルのパスを既存のJSONデータにマージする
                $existingFiles = json_decode($supplyItem->files, true) ?: [];
                $filesToSave = array_merge($existingFiles, $newFiles);
                $supplyItem->files = json_encode($filesToSave);
            }
        
            // 資材資料の削除処理
            if ($request->has('delete_files')) {
                $existingFiles = json_decode($supplyItem->files, true) ?: [];
                foreach ($request->input('delete_files') as $index) {
                    if (array_key_exists($index, $existingFiles)) {
                        Storage::delete($existingFiles[$index]);
                        unset($existingFiles[$index]);
                    }
                }
                $supplyItem->files = json_encode(array_values($existingFiles)); // インデックスをリセット
            }
        
            // 印刷用データのアップロード処理
            if ($request->hasFile('new_print_images')) {
                $newPrintImages = [];
                foreach ($request->file('new_print_images') as $image) {
                    $originalName = $image->getClientOriginalName();
                    $path = $image->store('supply_print_images');
                    $newPrintImages[] = ['path' => $path, 'originalName' => $originalName];
                }
                // 新しいファイルのパスを既存のJSONデータにマージする
                $existingPrintImages = json_decode($supplyItem->print_images, true) ?: [];
                foreach ($newPrintImages as $newImage) {
                    if (!in_array($newImage, $existingPrintImages)) {
                        $existingPrintImages[] = $newImage;
                    }
                }
                $supplyItem->print_images = json_encode(array_values($existingPrintImages)); // 重複を避けてインデックスをリセット
            }
        
            // 印刷用データの削除処理
            if ($request->has('delete_print_images')) {
                $existingPrintImages = json_decode($supplyItem->print_images, true) ?: [];
                foreach ($request->input('delete_print_images') as $index) {
                    Storage::delete($existingPrintImages[$index]);
                    unset($existingPrintImages[$index]);
                }
                $supplyItem->print_images = json_encode(array_values($existingPrintImages)); // インデックスをリセット
            }
            
            $supplyItem->update($validatedData);
            
            DB::commit();
            
            return redirect()->route('supplyItems.edit', $supplyItem->id)->with('success', '資材備品情報を更新しました。');
            
        } catch (\Exception $e) {
        DB::rollback();
        return back()->withErrors('エラーが発生しました：' . $e->getMessage());
    }
    }
    
    // 削除処理
    public function destroy(SupplyItem $supplyItem)
    {
        
        // supply_orders テーブルに現在の supply item が参照されているか確認
        $isReferenced = SupplyOrder::where('item_id', $supplyItem->id)->exists();
    
        if ($isReferenced) {
            // 参照されている場合はエラーメッセージを返す
            return redirect()->back()->withErrors(['error' => 'この資材備品は発注データが存在するため、削除できません。該当する発注データを削除するか、ステータスを使用終了にしてください。']);
        }
    
        $supplyItem->delete();
        
        // サムネイル画像の削除
        if ($supplyItem->thumbnail) {
            Storage::delete($supplyItem->thumbnail);
        }
        
        // 資材資料(files)の削除
        if ($supplyItem->files) {
            $files = json_decode($supplyItem->files, true);
            foreach ($files as $file) {
                Storage::delete($file['path']);
            }
        }
    
        // 印刷用データ(print_images)の削除
        if ($supplyItem->print_images) {
            $printImages = json_decode($supplyItem->print_images, true);
            foreach ($printImages as $image) {
                Storage::delete($image['path']);
            }
        }

        // リダイレクト処理。成功メッセージをフラッシュデータとしてセッションに保存
        return redirect()->route('supplyItems.index')->with('success', '資材備品情報を削除しました。');
    }

}