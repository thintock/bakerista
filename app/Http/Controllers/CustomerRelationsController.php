<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\CustomerRelation;
use App\Models\CustomerRelationCategory;
use App\Models\CustomerRelationHistory;
use App\Models\CustomerRelationSelection;

class CustomerRelationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // ユーザーリストの取得
        $users = User::all();
        
        // カテゴリリストの取得
        $customerRelationCategories = CustomerRelationCategory::all();
        
        // 部署リストの取得
        $departments = CustomerRelationCategory::select('department')->distinct()->pluck('department');
        
        // カテゴリの絞り込み
        $selectedCategoryId = $request->input('category_id', '');
        
        // クエリ構築
        $customerRelations = CustomerRelation::with('customerRelationCategories', 'user')
        ->receivedAtBetween($request->input('received_at_start'), $request->input('received_at_end'))
        ->receivedByUserId($request->input('user_id'))
        ->customerName($request->input('customer_name'))
        ->contactNumber($request->input('contact_number'))
        ->receptionChannel($request->input('reception_channel'))
        ->initialContent($request->input('initial_content'))
        ->isFinished($request->input('is_finished'))
        ->category($selectedCategoryId)
        ->department($request->input('department'))
        ->orderBy('received_at', 'desc')
        ->paginate(15);
        
        return View('customerRelations.index', compact('customerRelations', 'users', 'customerRelationCategories', 'selectedCategoryId','departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CustomerRelationCategory $customerRelationCategory)
    {
        $customerRelationCategories = CustomerRelationCategory::orderBY('name', 'asc')->get();
        return view('customerRelations.create', compact('customerRelationCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            
            $validatedData = $request->validate([
                'received_at' => 'date',
                'reception_channel' => 'nullable|string',
                'initial_content' => 'nullable|string',
                'product_name' => 'nullable|string',
                'customer_name' => 'string',
                'contact_number' => 'nullable|string',
                'link' => 'nullable|string',
                'needs_health_department_contact' => 'boolean',
                'health_department_contact_details' => 'nullable|string',
                'is_finished' => 'boolean',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|max:10240', // 10MB
                'newHistories.*.response_category' => 'nullable|string|max:255',
                'newHistories.*.response_content' => 'nullable|string|max:10000',
            ]);
            
            // 画像登録
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                $path = $image->store('customer_relations_images');
                $imagePaths[] = $path;
            }
                $validatedData['images'] = json_encode($imagePaths);
            }
            
            // 受付ユーザー登録処理
            $validatedData['received_by_user_id'] = Auth::id();
            
            // CustomerRelation レコードの作成
            $customerRelation = CustomerRelation::create($validatedData);
            
            // Historyの更新メソッド
            $newHistories = $validatedData['newHistories'] ?? [];
            CustomerRelationHistory::newHistories($customerRelation, $newHistories); // モデルメソッド実行
            
            // CustomerRelationSelection配列のバリデート
            $validatedData = $request->validate([
                'category_id' => 'nullable|array',
                'category_id.*' => 'exists:customer_relation_categories,id'
            ]);
            
            // customerRelationSelectionsの登録処理
            if (!empty($validatedData['category_id'])) {
                $customerRelation->customerRelationCategories()->sync($validatedData['category_id']);
            }

            
            DB::commit();
            return redirect()->route('customerRelations.edit', $customerRelation->id )->with('success', '顧客対応情報が登録されました。');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('エラーが発生しました：' . $e->getMessage());
        }
    }

    public function edit(CustomerRelation $customerRelation)
    {
        // 全てのカテゴリを取得
        $allCategories = CustomerRelationCategory::orderBy('name', 'asc')->get();
        
        // 選択されているカテゴリのIDを取得
        $selectedCategoryIds = $customerRelation->customerRelationCategories->pluck('id')->toArray();
        
        // CustomerRelationHistoryの関連レコードを取得
        $customerRelationHistories = $customerRelation->customerRelationHistories()
        ->orderBy('created_at', 'asc')
        ->get();
        
        return view('customerRelations.edit', compact('customerRelation', 'allCategories', 'selectedCategoryIds', 'customerRelationHistories'));
    }

   public function update(Request $request, CustomerRelation $customerRelation)
    {
        DB::beginTransaction();
        
        try {
                
            $validatedData = $request->validate([
                'received_at' => 'nullable|date',
                'reception_channel' => 'nullable|string',
                'initial_content' => 'nullable|string',
                'product_name' => 'nullable|string',
                'customer_name' => 'nullable|string',
                'contact_number' => 'nullable|string',
                'link' => 'nullable|string',
                'needs_health_department_contact' => 'boolean',
                'health_department_contact_details' => 'nullable|string',
                'is_finished' => 'boolean',
                'new_images' => 'nullable|array|max:5',
                'new_images.*' => 'image|max:10240', // 10MB
                'updateHistories.*.response_content' => 'nullable|string|max:10000',
                'newHistories.*.response_category' => 'nullable|string|max:255',
                'newHistories.*.response_content' => 'nullable|string|max:10000',
                'deleteHistories' => 'nullable|array',
                'deleteHistories.*' => 'exists:customer_relation_histories,id',
                'category_id' => 'nullable|array',
                'category_id.*' => 'exists:customer_relation_categories,id'
            ]);
            
            // カテゴリの関連付けを更新
            if (array_key_exists('category_id', $validatedData)) {
                $customerRelation->customerRelationCategories()->sync($validatedData['category_id']);
            }
            
            // 既存の画像削除処理
            if ($request->has('delete_images')) {
                $currentImages = json_decode($customerRelation->images, true);
                foreach ($request->delete_images as $deleteIndex) {
                    Storage::delete($currentImages[$deleteIndex]);
                    unset($currentImages[$deleteIndex]);
                }
                $customerRelation->images = json_encode(array_values($currentImages));
                $customerRelation->save();
            }
    
            // 新しい画像の追加処理
            if ($request->hasFile('new_images')) {
                $newImages = array_values(json_decode($customerRelation->images, true) ?? []);
                foreach ($request->file('new_images') as $newImage) {
                    $path = $newImage->store('customer_relations_images');
                    $newImages[] = $path;
                }
                $customerRelation->images = json_encode($newImages);
                $customerRelation->save();
            }
            
            // チェックボックスの値をbooleanに変換
            $validatedData['needs_health_department_contact'] = $request->has('needs_health_department_contact');
            $validatedData['is_finished'] = $request->has('is_finished');
    
            // 顧客対応情報を更新
            $customerRelation->update($validatedData);
            
            // Historyの操作メソッド
            $updateHistories = $validatedData['updateHistories'] ?? [];
            CustomerRelationHistory::updateHistories($updateHistories);
            
            $deleteHistories = $validatedData['deleteHistories'] ?? [];
            CustomerRelationHistory::deleteHistories($deleteHistories);
 
            $newHistories = $validatedData['newHistories'] ?? [];
            CustomerRelationHistory::newHistories($customerRelation, $newHistories);
            
            DB::commit();
            
            return redirect()->route('customerRelations.edit', $customerRelation->id)->with('success', '顧客対応が更新されました。');
            
        } catch (\Exception $e) {
        DB::rollback();
        return back()->withErrors('エラーが発生しました：' . $e->getMessage());
    }
    }


    public function destroy(CustomerRelation $customerRelation)
    {
        DB::beginTransaction();
    
        try {
            
            // 関連する画像ファイルを削除
            if ($customerRelation->images) {
                $images = json_decode($customerRelation->images, true);
                foreach ($images as $image) {
                    Storage::delete($image);
                }
            }
            
            // 関連する対応履歴を削除
            foreach ($customerRelation->customerRelationHistories as $history) {
                $history->delete();
            }
            
            // 関連するカテゴリ選択を削除
            $customerRelation->customerRelationCategories()->detach();
            
            // 顧客対応情報を削除
            $customerRelation->delete();
            
            DB::commit();
            return redirect()->route('customerRelations.index')->with('success', '顧客対応情報が削除されました。');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('エラーが発生しました: ' . $e->getMessage());
        }
    }
}