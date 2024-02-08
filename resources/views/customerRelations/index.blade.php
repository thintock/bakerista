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
                    <a href="{{ route('customerRelations.edit', $relation->id) }}" class="btn btn-primary text-sm" target="_blank"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="18" height="18" color="#000000"><defs><style>.cls-6374f8d9b67f094e4896c676-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs><path class="cls-6374f8d9b67f094e4896c676-1" d="M7.23,20.59l-4.78,1,1-4.78L17.89,2.29A2.69,2.69,0,0,1,19.8,1.5h0a2.7,2.7,0,0,1,2.7,2.7h0a2.69,2.69,0,0,1-.79,1.91Z"></path><line class="cls-6374f8d9b67f094e4896c676-1" x1="0.55" y1="22.5" x2="23.45" y2="22.5"></line><line class="cls-6374f8d9b67f094e4896c676-1" x1="19.64" y1="8.18" x2="15.82" y2="4.36"></line></svg></a>
                    <form action="{{ route('customerRelations.destroy', $relation->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-accent" onclick="return confirm('削除してよろしいですか？');"><svg version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="width: 16px; height: 16px; opacity: 1;" xml:space="preserve"><style type="text/css">.st0{fill:#4B4B4B;}</style><g><path class="st0" d="M439.114,69.747c0,0,2.977,2.1-43.339-11.966c-41.52-12.604-80.795-15.309-80.795-15.309l-2.722-19.297C310.387,9.857,299.484,0,286.642,0h-30.651h-30.651c-12.825,0-23.729,9.857-25.616,23.175l-2.722,19.297c0,0-39.258,2.705-80.778,15.309C69.891,71.848,72.868,69.747,72.868,69.747c-10.324,2.849-17.536,12.655-17.536,23.864v16.695h200.66h200.677V93.611C456.669,82.402,449.456,72.596,439.114,69.747z" style="fill: rgb(75, 75, 75);"></path><path class="st0" d="M88.593,464.731C90.957,491.486,113.367,512,140.234,512h231.524c26.857,0,49.276-20.514,51.64-47.269l25.642-327.21H62.952L88.593,464.731z M342.016,209.904c0.51-8.402,7.731-14.807,16.134-14.296c8.402,0.51,14.798,7.731,14.296,16.134l-14.492,239.493c-0.51,8.402-7.731,14.798-16.133,14.288c-8.403-0.51-14.806-7.722-14.296-16.125L342.016,209.904z M240.751,210.823c0-8.42,6.821-15.241,15.24-15.241c8.42,0,15.24,6.821,15.24,15.241v239.492c0,8.42-6.821,15.24-15.24,15.24c-8.42,0-15.24-6.821-15.24-15.24V210.823z M153.833,195.608c8.403-0.51,15.624,5.894,16.134,14.296l14.509,239.492c0.51,8.403-5.894,15.615-14.296,16.125c-8.403,0.51-15.624-5.886-16.134-14.288l-14.509-239.493C139.026,203.339,145.43,196.118,153.833,195.608z" style="fill: rgb(75, 75, 75);"></path></g></svg></button>
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
