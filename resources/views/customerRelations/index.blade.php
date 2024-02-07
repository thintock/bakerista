@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-xl font-semibold">お客様対応管理</h1>
    
    <div class="flex mt-4">
    <!-- 絞り込み検索 -->
        <form action="{{ route('customerRelations.index') }}" method="GET" class="flex flex-wrap gap-4 mr-2">
            <!-- 受付日時での絞り込み -->
            <input type="date" name="received_at_start" value="{{ request('received_at_start') }}" placeholder="開始日" class="input input-bordered text-xs">
            <p class="mt-3")>〜</p>
            <input type="date" name="received_at_end" value="{{ request('received_at_end') }}" placeholder="終了日" class="input input-bordered text-xs">
            <!-- 受付担当者での絞り込み -->
            <select name="user_id" class="select select-bordered text-xs">
                <option value="" class="text-xs">担当者を選択</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }} class="text-xs">{{ $user->name }}</option>
                @endforeach
            </select>
        
            <!-- お客様名での検索 -->
            <input type="text" name="customer_name" value="{{ request('customer_name') }}" placeholder="お客様名" class="input input-bordered text-xs">
        
            <!-- 電話番号での検索 -->
            <input type="tel" name="contact_number" value="{{ request('contact_number') }}" placeholder="電話番号" class="input input-bordered text-xs">
        
            <!-- 受付場所での検索 -->
            <select name="reception_channel" class="select select-bordered text-xs">
                <option value="">受付場所</option>
                <option value="LINE" {{ request('reception_channel') == 'LINE' ? 'selected' : '' }} class="text-xs">公式LINE</option>
                <option value="メール" {{ request('reception_channel') == 'メール' ? 'selected' : '' }} class="text-xs">メール</option>
                <option value="電話" {{ request('reception_channel') == '電話' ? 'selected' : '' }} class="text-xs">電話</option>
                <option value="レビュー" {{ request('reception_channel') == 'レビュー' ? 'selected' : '' }} class="text-xs">レビュー</option>
                <option value="コメント" {{ request('reception_channel') == 'コメント' ? 'selected' : '' }} class="text-xs">コメント</option>
                <option value="その他" {{ request('reception_channel') == 'その他' ? 'selected' : '' }} class="text-xs">その他</option>
            </select>
            
            <!-- 初期受付内容での検索 -->
            <input type="text" name="initial_content" value="{{ request('initial_content') }}" placeholder="初期受付内容" class="input input-bordered text-xs">
            
            <!-- カテゴリでの検索 -->
            <select id="category_id" name="category_id" class="select select-bordered text-xs">
                <option value="">すべてのカテゴリ</option>
                @foreach ($customerRelationCategories as $category)
                    <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }} class="text-xs">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            
            <!-- 担当部署での検索 -->
            <select id="department" name="department" class="select select-bordered text-xs">
                <option value="">全ての部署</option>
                @foreach ($departments as $department)
                    <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }} class="text-xs">
                        {{ $department }}
                    </option>
                @endforeach
            </select>
            
            <!-- 完了フラグでの検索 -->
            <div>
                <select id="is_finished" name="is_finished" class="select select-bordered text-xs">
                    <option value="" {{ request('is_finished') === null ? 'selected' : '' }}>全て</option>
                    <option value="1" {{ request('is_finished') == '1' ? 'selected' : '' }}>完了</option>
                    <option value="0" {{ request('is_finished') == '0' ? 'selected' : '' }}>対応中</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-secondary text-xs">検索</button>
            <a href="{{ route('customerRelations.index') }}" class="btn btn-accent text-xs">クリア</a>
            <a href="{{ route('customerRelationCategories.index') }}" class="btn btn-secondary mr-2">カテゴリ管理</a>
            <a href="{{ route('customerRelations.create') }}" class="btn btn-primary">新規追加</a>
        </form>
        
    </div>
    <table class="table table-xs mt-4 mb-4">
        <thead>
            <tr class="bg-base-200">
                <th>ID</th>
                <th>受付日時</th>
                <th>ステータス</th>
                <th>受付担当者</th>
                <th>お客様名</th>
                <th>連絡先</th>
                <th>リンク</th>
                <th>受付場所</th>
                <th>分類</th>
                <th>初期受付内容</th>
                <th>対象の商品名</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customerRelations as $relation)
            <tr>
                <td>{{ $relation->id }}</td>
                <td>{{ $relation->received_at->format('Y年m月d日') }}</td>
                <td>
                    @if ($relation->is_finished)
                    完了
                    @else
                    対応中
                    @endif
                </td>
                <td>{{ $relation->user->name }}</td>
                <td>{{ $relation->customer_name }}</td>
                <td><a href="tel:{{ $relation->contact_number }}" class="link link-primary">{{ $relation->contact_number }}</a></td>
                <td>
                    @if ($relation->link)
                        <a href="{{ $relation->link }}">
                            <img src="{{ asset('images/icons/link.svg') }}" alt="link" width="16px">
                        </a>
                    @endif
                </td>
                <td>{{ $relation->reception_channel }}</td>
                <td>
                    @php
                        $categories = $relation->customerRelationCategories;
                        $categoriesCount = $categories->count();
                    @endphp
                    @foreach ($categories->take(2) as $category)
                        {{ $category->name }}@if (!$loop->last), @endif
                    @endforeach
                    
                    @if ($categoriesCount > 2)
                        , 他
                    @endif
                </td>
                <td>{{ mb_strimwidth($relation->initial_content, 0, 100, '...') }}</td>
                <td>{{ $relation->product_name }}</td>
                <td class="flex items-center space-x-2">
                    <a href="{{ route('customerRelations.edit', $relation->id) }}" class="btn btn-primary text-sm" target="_blank">編集</a>
                    <form action="{{ route('customerRelations.destroy', $relation->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('削除してよろしいですか？');">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
{{-- ページネーションのリンク --}}
{{ $customerRelations->links() }}
</div>
@endsection
