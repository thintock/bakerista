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
        
        // カテゴリの絞り込み
        $selectedCategoryId = $request->input('category_id', '');

        // クエリビルダの初期化
        $query = CustomerRelation::with('customerRelationCategories', 'user');
    
        // 受付日時での絞り込み
        if ($request->filled('received_at_start') && $request->filled('received_at_end')) {
            $query->whereBetween('received_at', [$request->input('received_at_start'), $request->input('received_at_end')]);
        } elseif ($request->filled('received_at_start')) {
            $query->whereDate('received_at', '>=', $request->input('received_at_start'));
        } elseif ($request->filled('received_at_end')) {
            $query->whereDate('received_at', '<=', $request->input('received_at_end'));
        }
    
        // 受付担当者での絞り込み
        if ($request->filled('user_id')) {
            $query->where('received_by_user_id', $request->input('user_id'));
        }
    
        // お客様名での検索
        if ($request->filled('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->input('customer_name') . '%');
        }
    
        // 電話番号での検索
        if ($request->filled('contact_number')) {
            $query->where('contact_number', 'like', '%' . $request->input('contact_number') . '%');
        }
    
        // 受付場所での検索
        if ($request->filled('reception_channel')) {
            $query->where('reception_channel', 'like', '%' . $request->input('reception_channel') . '%');
        }
    
        // 初期受付内容での検索
        if ($request->filled('initial_content')) {
            $query->where('initial_content', 'like', '%' . $request->input('initial_content') . '%');
        }
        
        // 完了フラグでの検索
        if ($request->has('is_finished')) {
            if ($request->input('is_finished') == '') {
                // 「全て」が選択された場合はフィルタを適用しない
            } else {
                $query->where('is_finished', $request->input('is_finished'));
            }
        } else {
            // ユーザーが完了フラグでの検索条件を指定していない場合、デフォルトで対応中のものを表示
            $query->where('is_finished', false);
        }
        
        // カテゴリでの絞り込み
        if (!empty($selectedCategoryId)) {
            $query->whereHas('customerRelationCategories', function ($query) use ($selectedCategoryId) {
                $query->where('customer_relation_categories.id', $selectedCategoryId);
            });
        }
        
        // 結果の取得
        $customerRelations = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return View('customerRelations.index', compact('customerRelations', 'users', 'customerRelationCategories', 'selectedCategoryId'));
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
                'images.*' => 'image|max:10240' // 10MB
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
            
            // customerRelationHistoriesの登録処理
            if ($request->has('customerRelationHistories')) {
                foreach ($request->input('customerRelationHistories') as $historyData) {
                    $customerRelation->customerRelationHistories()->create([
                        'response_category' => $historyData['response_category'],
                        'response_content' => $historyData['response_content'],
                        'respondent_user_id' => Auth::id(),
                    ]);
                }
            }
            
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
            return redirect()->route('customerRelations.index')->with('success', '顧客対応情報が登録されました。');
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
        $customerRelationHistories = $customerRelation->customerRelationHistories;
        
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
                'new_images.*' => 'image|max:10240' // 10MB
            ]);
            
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
            
            // CustomerRelationSelection配列のバリデート
            $validatedData = $request->validate([
                'category_id' => 'nullable|array',
                'category_id.*' => 'exists:customer_relation_categories,id'
            ]);
            
            // カテゴリの関連付けを更新
            if (array_key_exists('category_id', $validatedData)) {
                $customerRelation->customerRelationCategories()->sync($validatedData['category_id']);
            }
    
            // 既存履歴の削除処理
            if ($request->has('delete_histories')) {
                $deleteHistories = $request->input('delete_histories');
                foreach ($deleteHistories as $deleteHistoryId) {
                    CustomerRelationHistory::destroy($deleteHistoryId);
                }
            }
            
            // 新しい履歴の追加処理
            if ($request->has('newHistories')) {
                foreach ($request->input('newHistories') as $newHistoryData) {
                    $customerRelation->customerRelationHistories()->create([
                        'response_category' => $newHistoryData['response_category'],
                        'response_content' => $newHistoryData['response_content'],
                        'respondent_user_id' => Auth::id() // 現在のユーザーID
                    ]);
                }
            }
            DB::commit();
            
            return redirect()->route('customerRelations.index')->with('success', '顧客対応が更新されました。');
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