@extends('layouts.app')

@section('content')
<div class="container">
    <h1>新規お客様対応登録</h1>

    <form action="{{ route('customerRelations.store') }}" method="POST" class="lg:flex lg:gap-10" enctype="multipart/form-data">
        @csrf
         <!-- 左側のセクション -->
        <div class="lg:w-2/5">
            <div class="flex justify-between">
                <!-- 受付日時 -->
                <div class="form-control">
                    <label class="label" for="received_at">受付日時</label>
                    <input type="date" id="received_at" name="received_at" class="input input-bordered" value="{{ date('Y-m-d') }}" required>
                </div>
                
                <!-- 受付場所 -->
                <div class="form-control">
                    <label class="label" for="reception_channel">受付場所</label>
                    <select id="reception_channel" name="reception_channel" class="select select-bordered" required>
                        <option value="">選択してください</option>
                        <option value="LINE">公式LINE</option>
                        <option value="メール">メール</option>
                        <option value="電話">電話</option>
                        <option value="レビュー">レビュー</option>
                        <option value="コメント">コメント</option>
                        <option value="その他">その他</option>
                    </select>
                </div>
                
            </div>
            
            <div class="flex justify-between">
                <!-- 受付担当者 -->
                <div class="form-control">
                    <label class="label">受付担当者：{{ Auth::User()->name }} {{ Auth::User()->first_name }}</label>
                </div>
                <!-- 受付状態 -->
                <div class="form-control">
                    <label class="label cursor-pointer" for="is_finished">
                        <span class="mr-3">対応完了</span>
                        <input type="hidden" name="is_finished" value="0">
                        <input type="checkbox" name="is_finished" class="checkbox checkbox-accent" value="1">
                    </labe>
                </div>
            
            </div>
            
            <!-- 対象の商品名 -->
            <div class="form-control">
                <label class="label" for="product_name">対象の商品名</label>
                <input type="text" id="product_name" name="product_name" class="input input-bordered">
            </div>
    
            <!-- お客様名 -->
            <div class="form-control">
                <label class="label" for="customer_name">お客様名</label>
                <input type="text" id="customer_name" name="customer_name" class="input input-bordered" required>
            </div>
    
            <!-- 連絡先電話番号 -->
            <div class="form-control">
                <label class="label" for="contact_number">連絡先電話番号</label>
                <input type="tel" id="contact_number" name="contact_number" class="input input-bordered">
            </div>
    
            <!-- リンク -->
            <div class="form-control">
                <label class="label" for="link">受注画面</label>
                <input type="url" id="link" name="link" class="input input-bordered">
            </div>
            
            <!-- 画像アップロード -->
            <div class="form-group mt-4">
                <input type="file" name="images[]" multiple accept="image/*" class="file-input file-input-bordered file-input-sm w-full">
                <small>最大5個の画像をアップロードできます。各ファイルは10MBまでです。</small>
            </div>
            
            <!-- 保健所への連絡の要・不要 -->
            <div class="form-control">
                <label class="label cursor-pointer">
                    <span class="label-text">保健所への連絡が必要</span>
                    <input type="hidden" name="needs_health_department_contact" value="0">
                    <input type="checkbox" name="needs_health_department_contact" class="checkbox checkbox-accent" value="1">
                </label>
            </div>
    
            <!-- 保健所への報告内容 -->
            <div class="form-control">
                <label class="label" for="health_department_contact_details">保健所への報告内容</label>
                <textarea id="health_department_contact_details" name="health_department_contact_details" class="textarea textarea-bordered"></textarea>
            </div>
        </div>
        <!-- 右側のセクション -->
        <div class="lg:w-3/5">
            
            <div class="flex justify-between items-center mb-2">
                <!-- カテゴリ -->
                <div class="form-control">
                    <label class="label">カテゴリ</label>
                    <div class="overflow-y-auto max-h-40 w-60 p-2 border rounded">
                        @foreach ($customerRelationCategories as $category)
                            <div class="flex items-center">
                                <input type="checkbox" id="category_{{ $category->id }}" name="category_id[]" value="{{ $category->id }}" class="checkbox checkbox-primary" style="width: 1rem;height: 1rem;">
                                <label for="category_{{ $category->id }}" class="ml-2 text-xs">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- 初期受付内容 -->
                <div class="form-control w-full pl-4">
                    <label class="label" for="initial_content">初期受付内容</label>
                    <textarea id="initial_content" name="initial_content" class="textarea textarea-bordered h-40 border"></textarea>
                </div>
            </div>
            
            <!-- 対応履歴 -->
            <div id="customerRelationHistories">
                <div class="customerRelationHistory">
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
                                <div>対応者：{{ Auth::user()->name }} {{ Auth::user()->first_name }}</div>
                            </div>
                            <div class="form-control">
                                <textarea name="newHistories[${historyCount}][response_content]" class="input input-bordered textarea textarea-bordered h-60" placeholder="対応内容を入力してください。"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-control mt-6">
            <button type="submit" class="btn btn-primary">登録</button>
        </div>
    </form>
</div>
@endsection
