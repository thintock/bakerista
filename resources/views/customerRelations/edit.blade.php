@extends('layouts.app')

@section('content')
<div class="container">
    <h1>顧客対応編集</h1>

    <form action="{{ route('customerRelations.update', $customerRelation->id) }}" method="POST" class="lg:flex lg:gap-10" enctype="multipart/form-data">
        @csrf
        @method('PUT')

         <!-- 左側のセクション -->
        <div class="lg:w-2/5">
                
            <div class="flex justify-between">
                    
                <!-- 受付日時 -->
                <div class="form-control">
                    <label class="label" for="received_at">受付日時</label>
                    <input type="date" id="received_at" name="received_at" class="input input-bordered" value="{{ $customerRelation->received_at->format('Y-m-d') }}" readonly>
                </div>
        
                <!-- 受付場所 -->
                <div class="form-control">
                    <label class="label" for="reception_channel">受付場所</label>
                    <input type="text" id="reception_channel" name="reception_channel" class="input input-bordered" value="{{ $customerRelation->reception_channel }}" readonly>
                </div>
            </div>
            
            <div class="flex justify-between">
                <!-- 受付担当者 -->
                <div class="form-control">
                    <label class="label">受付担当者：{{ $customerRelation->user->name }} {{ $customerRelation->user->first_name }}</label>
                </div>
                
                <!-- 完了状態 -->
                <div class="form-control">
                    <label class="label cursor-pointer" for="is_finished">
                        <span class="mr-3 text-xs">対応完了</span>
                        <input type="checkbox" id="is_finished" name="is_finished" class="checkbox checkbox-secondary" value="1" {{ $customerRelation->is_finished ? 'checked' : '' }}>
                    </label>
                </div>
                
                <!-- 保健所連絡の有無 -->
                <div class="form-control">
                    <label class="label cursor-pointer" for="needs_health_department_contact">
                        <span class="label-text mr-3 text-xs">保健所連絡</span>
                        <input type="checkbox" id="needs_health_department_contact" name="needs_health_department_contact" class="checkbox checkbox-secondary" value="1" {{ $customerRelation->needs_health_department_contact ? 'checked' : '' }}>
                    </label>
                </div>
    
                
            </div>
            
            <!-- 対象商品名 -->
            <div class="form-control">
                <label class="label" for="product_name">対象商品名</label>
                <input type="text" id="product_name" name="product_name" class="input input-bordered" value="{{ $customerRelation->product_name }}">
            </div>
    
            <!-- お客様名 -->
            <div class="form-control">
                <label class="label" for="customer_name">お客様名</label>
                <input type="text" id="customer_name" name="customer_name" class="input input-bordered" value="{{ $customerRelation->customer_name }}">
            </div>
    
            <!-- 連絡先電話番号 -->
            <div class="form-control">
                <label class="label" for="contact_number">連絡先電話番号</label>
                <input type="tel" id="contact_number" name="contact_number" class="input input-bordered" value="{{ $customerRelation->contact_number }}">
            </div>
    
            <!-- リンク -->
            <div class="form-control">
                <label class="label" for="link">受注画面</label>
                <input type="url" id="link" name="link" class="input input-bordered" value="{{ $customerRelation->link }}">
            </div>
            
            <!-- 画像アップロード -->
            <div class="form-control">
                <label class="label" for="images">画像データ</label>
                @if($customerRelation->images)
                    @php
                        $images = json_decode($customerRelation->images, true);
                    @endphp
                    <div class="grid grid-cols-5 gap-4">
                        @foreach($images as $index => $image)
                            <div class="relative w-full pb-full">
                                <img src="{{ Storage::url($image) }}" alt="Uploaded Image" class="top-0 left-0 w-full h-full object-cover rounded-lg cursor-pointer" onclick="showModal('{{ Storage::url($image) }}')">
                                <label>
                                    <input type="checkbox" name="delete_images[]" value="{{ $index }}" class="checkbox checkbox-accent" style="width: 1rem;height: 1rem;">
                                    <label for="images" class="text-xs text-accent">削除</label>
                                </label>
                            </div>
                        @endforeach
                        @for ($i = count($images); $i < 5; $i++)
                            <!--新規画像登録-->
                            <div class="relative w-full pb-full">
                                 <div class="relative w-full pb-full flex items-center justify-center">
                                    <input type="file" name="new_images[]" accept="image/*" class="file-input file-input-bordered file-input-sm" />
                                </div>
                            </div>
                        @endfor
                    </div>
                @else
                    @for ($i = 0; $i < 5; $i++)
                                <input type="file" name="new_images[]" accept="image/*" class="file-input file-input-bordered file-input-sm" />
                    @endfor
                @endif
            </div>
            <!-- モーダル -->
            <div id="imageModal" class="modal">
                <div class="modal-box">
                    <img id="fullSizeImage" src="" alt="Full Size Image" class="max-w-full h-auto">
                    <div class="modal-action">
                        <a href="#" class="btn btn-error w-full" onclick="closeModal()">閉じる</a>
                    </div>
                </div>
            </div>
            
            <script>
            function showModal(imageUrl) {
                document.getElementById('fullSizeImage').src = imageUrl;
                document.getElementById('imageModal').classList.add('modal-open');
            }
            
            function closeModal() {
                document.getElementById('imageModal').classList.remove('modal-open');
            }
            </script>

            <!-- 保健所連絡詳細 -->
            <div class="form-control mt-5">
                <label class="label" for="health_department_contact_details">備考欄</label>
                <textarea id="health_department_contact_details" name="health_department_contact_details" class="textarea textarea-bordered">{{ $customerRelation->health_department_contact_details }}</textarea>
            </div>
            
            
        </div>
        
        <!-- 右側のセクション -->
        <div class="lg:w-3/5">
            
            <div class="flex justify-between items-center mb-2">
                
                <!-- カテゴリ -->
                <div class="form-control">
                    <label class="label">カテゴリ</label>
                    <div class="overflow-y-auto max-h-40 w-60 p-2 border rounded">
                        @foreach ($allCategories as $category)
                            <div class="flex items-center">
                                <input type="checkbox" id="category_{{ $category->id }}" name="category_id[]" value="{{ $category->id }}" class="checkbox checkbox-primary"{{ in_array($category->id, $selectedCategoryIds) ? 'checked' : '' }} style="width: 1rem;height: 1rem;">
                                <label for="category_{{ $category->id }}" class="ml-2 text-xs">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                
        
                <!-- 初期受付内容 -->
                <div class="form-control w-full pl-4">
                    <label class="label" for="initial_content">初期受付内容</label>
                    <textarea id="initial_content" name="initial_content" class="textarea textarea-bordered h-40 border">{{ $customerRelation->initial_content }}</textarea>
                </div>
            </div>
            
            <!-- 対応履歴 -->
            <div id="existingHistories">
                <div class="customerRelationHistory">
                    @foreach ($customerRelationHistories as $history)
                        <div class="card border mt-4">
                            <div class="card-body p-3">
                                <div class="form-control">
                                    <div class="existingHistory p-4 border rounded-lg">
                                        <!-- 1行目：対応カテゴリ、対応者、作成日 -->
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-semibold">対応内容: {{ $history->response_category }}</span>
                                            <span class="text-sm">対応者： {{ $history->user->name }} {{ $history->user->first_name }}</span>
                                            <span class="text-sm">作成日：{{ $history->created_at }}</span>
                                            <span class="text-sm">更新日：{{ $history->updated_at }}</span>
                                            <div>
                                                <label class="label-text ml-2 text-accent">削除</label>
                                                <input type="checkbox" name="deleteHistories[]" value="{{ $history->id }}" class="checkbox checkbox-accent">
                                            </div>
                                        </div>
                                        
                                        <!-- 2行目：対応内容 -->
                                        <div class="form-control">
                                            <textarea name="updateHistories[{{ $history->id }}][response_content]" class="textarea textarea-bordered">{{ $history->response_content }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- 新規履歴追加 -->
            <div id="newHistories"></div>
            <button type="button" onclick="addNewHistory()" class="btn btn-primary mt-4">対応を追加</button>
    
            <script>
            function addNewHistory() {
                var container = document.getElementById('newHistories');
                var historyCount = container.getElementsByClassName('newHistory').length;
                var newHistory = document.createElement('div');
                newHistory.className = 'newHistory';
                newHistory.innerHTML = `
                    <div class="card border mt-4">
                        <div class="card-body p-3">
                            <div class="flex justify-between items-center mb-2">
                                <div class="form-control">
                                    <select name="newHistories[${historyCount}][response_category]" class="select select-bordered">
                                        <option value="連絡対応">連絡対応</option>
                                        <option value="代替商品発送＆引き取り">代替商品発送＆引き取り</option>
                                        <option value="代替商品発送のみ">代替商品発送のみ</option>
                                        <option value="引き取り&キャンセル">引き取り&キャンセル</option>
                                        <option value="引き取りなし&キャンセル">引き取りなし&キャンセル</option>
                                        <option value="その他">その他</option>
                                    </select>
                                </div>
                                <div>対応者：楠本 幸貴</div>
                            </div>
                            <div class="form-control">
                                <textarea name="newHistories[${historyCount}][response_content]" class="input input-bordered textarea textarea-bordered h-24" placeholder="対応内容を入力してください。"></textarea>
                            </div>
                        </div>
                    </div>`;
                container.appendChild(newHistory);
            }
            </script>
        </div>
        <div class="form-control mt-6">
            <button type="submit" class="btn btn-primary">更新</button>
        </div>
    </form>
</div>
@endsection
