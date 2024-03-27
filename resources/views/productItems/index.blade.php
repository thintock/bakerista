@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">商品一覧</h1>
    
            <a href="{{ route('productItems.index') }}" class="btn btn-info text-xs">クリア</a>
            <a href="{{ route('productItems.create') }}" class="btn btn-primary">新規商品登録</a>
    <div class="overflow-x-auto">
        <table class="table table-xs bg-base-100 w-full">
            <thead>
                <tr>
                    <th>商品コード</th>
                    <th>商品名</th>
                    <th>ブランド名</th>
                    <th>商品ステータス</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productItems as $productItem)
                <tr>
                    <td>{{ $productItem->item_code }}</td>
                    <td>{{ $productItem->name }}</td>
                    <td>{{ $productItem->brand_name }}</td>
                    <td>{{ $productItem->item_status }}</td>
                    <td>
                        <a href="{{ route('productItems.edit', $productItem->id) }}" class="btn btn-info">編集</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
