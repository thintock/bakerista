@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-4">
        <!-- 戻るボタン -->
        <a href="{{ route('supplyOrders.index') }}" class="btn btn-secondary">
            ← 戻る
        </a>
        <h1 class="text-2xl font-bold">発注情報編集</h1>
        <!-- 新規作成ボタン -->
        <a href="{{ route('supplyOrders.create') }}" class="btn btn-primary">
            新規作成
        </a>
    </div>
    <div class="w-full mx-auto">
        <form action="{{ route('supplyOrders.update', $supplyOrder->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="lg:flex lg:gap-10 text-left">
                <!-- 左側のセクション -->
                <div class="lg:w-1/2">
                    
                    <div class="form-group mb-4">
                        <label for="status" class="form-label">ステータス</label>
                        <select id="status" name="status" class="select select-bordered w-full">
                            <option value="" {{ is_null($supplyOrder->status) ? 'selected' : '' }}>選択してください</option>
                            <option value="発注依頼中" {{ $supplyOrder->status === '発注依頼中' ? 'selected' : '' }}>発注依頼中</option>
                            <option value="発注待ち" {{ $supplyOrder->status === '発注待ち' ? 'selected' : '' }}>発注待ち</option>
                            <option value="入荷待ち" {{ $supplyOrder->status === '入荷待ち' ? 'selected' : '' }}>入荷待ち</option>
                            <option value="取消" {{ $supplyOrder->status === '取消' ? 'selected' : '' }}>取消</option>
                            <option value="保留" {{ $supplyOrder->status === '保留' ? 'selected' : '' }}>保留</option>
                            <option value="完了" {{ $supplyOrder->status === '完了' ? 'selected' : '' }}>完了</option>
                        </select>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="request_date" class="form-label">発注依頼日</label>
                        <input type="date" id="request_date" name="request_date" class="input input-bordered w-full" value="{{ $supplyOrder->request_date }}">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="request_user" class="form-label">依頼担当者</label>
                        @if($supplyOrder->requestUser) {{-- requestUser メソッドを使用して関連付けられたユーザーの存在を確認 --}}
                            <input id="request_user" class="input input-bordered w-full" value="{{ $supplyOrder->requestUser->name }} {{ $supplyOrder->requestUser->first_name }}" readonly> {{-- 存在する場合はユーザー名を表示 --}}
                        @else
                            <input id="request_user" class="input input-bordered w-full" value="-" readonly> {{-- 存在しない場合は「未指定」と表示 --}}
                        @endif
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="order_date" class="form-label">発注日</label>
                        <input type="date" id="order_date" name="order_date" class="input input-bordered w-full" value="{{ $supplyOrder->order_date }}">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="delivery_date" class="form-label">納品予定日</label>
                        <input type="date" id="delivery_date" name="delivery_date" class="input input-bordered w-full" value="{{ $supplyOrder->delivery_date }}">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="arrival_date" class="form-label">入荷日</label>
                        <input type="date" id="arrival_date" name="arrival_date" class="input input-bordered w-full" value="{{ $supplyOrder->arrival_date }}">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="description" class="form-label">備考</label>
                        <textarea id="description" name="description" class="textarea textarea-bordered w-full">{{ $supplyOrder->description }}</textarea>
                    </div>
                </div>
                
                <!-- 右側のセクション -->
                <div class="lg:w-1/2">
                    
                    <div class="form-group mb-4">
                        <label for="order_quantity" class="form-label">発注数量</label>
                        <input type="number" id="order_quantity" name="order_quantity" class="input input-bordered w-full" value="{{ $supplyOrder->order_quantity }}">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="arrival_quantity" class="form-label">入荷数量</label>
                        <input type="number" id="arrival_quantity" name="arrival_quantity" class="input input-bordered w-full" value="{{ $supplyOrder->arrival_quantity }}">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="item_name" class="form-label">資材備品名</label>
                        <input type="text" id="item_name" name="item_name" value="{{ $supplyOrder->supplyItem->item_name }}" class="input input-bordered w-full" readonly>
                    </div>

                    <div class="form-group mb-4">
                        <label for="company_id" class="form-label">発注先</label>
                        <input type="text" id="company_id" name="company_id" value="{{ $supplyOrder->company ? $supplyOrder->company->name : '未登録' }}" class="input input-bordered w-full" readonly>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="user_id" class="form-label">発注担当者</label>
                        @if($supplyOrder->user) {{-- requestUser メソッドを使用して関連付けられたユーザーの存在を確認 --}}
                            <input id="user_id" class="input input-bordered w-full" value="{{ $supplyOrder->user->name }} {{ $supplyOrder->user->first_name }}" readonly> {{-- 存在する場合はユーザー名を表示 --}}
                        @else
                            <input id="request_user" class="input input-bordered w-full" value="未登録" readonly> {{-- 存在しない場合は「未指定」と表示 --}}
                        @endif
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="location_id" class="form-label">納品場所</label>
                        <input type="text" id="location_id" name="location_id" value="{{ $supplyOrder->location ? $supplyOrder->location->location_code : '未登録' }} {{ $supplyOrder->location ? $supplyOrder->location->location_name : '' }}" class="input input-bordered w-full" readonly>
                    </div>
                    
                    
                </div>
            </div>
            <div class="flex mt-6">
                <!-- 更新ボタン -->
                <div class="form-control w-1/2 mr-3">
                    <button type="submit" class="btn btn-primary">更新</button>
                </div>
            </form>
            <form action="{{ route('supplyOrders.destroy', $supplyOrder->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="w-1/2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-warning w-full">資材備品情報を削除</button>
            </form>
        </div>
    </div>
</div>
@endsection
