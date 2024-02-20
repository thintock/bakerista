@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="justify-center">
        <h1 class="text-2xl font-bold mb-4">資材・備品マスタ</h1>
        <p>社内で使用される資材と備品の一覧と管理を行います。</p>
    </div>
    <div class="flex justify-end mb-4">
        <a href="{{ route('supplyItems.create') }}" class="btn btn-primary">新規資材・備品登録</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table table-xs bg-base-100">
            <thead>
                <tr>
                    <th>コード</th>
                    <th>ステータス</th>
                    <th>備品名</th>
                    <th>規格</th>
                    <th>ブランド名</th>
                    <th>分類</th>
                    <th>価格</th>
                    <th>納期</th>
                    <th>発注点</th>
                    <th>在庫数</th>
                    <th>次回発注予定日</th>
                    <th></th>
                </tr>
            </thead>
            @if ($supplyItems->count() > 0)
            <tbody>
                @foreach ($supplyItems as $item)
                <tr>
                    <td>{{ $item->item_code }}</td>
                    <td><!-- 現在のステータスに応じたバッジ表示 -->
                        @if($item->item_status === '承認申請中')
                            <span class="badge badge-secondary ml-2">承認申請中</span>
                        @elseif($item->item_status === '未承認')
                            <span class="badge badge-accent ml-2">未承認</span>
                        @elseif($item->item_status === '承認済み')
                            <span class="badge badge-primary ml-2">承認済み</span>
                        @elseif($item->item_status === '使用終了')
                            <span class="badge badge-warning ml-2">使用終了</span>
                        @endif
                    </td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->standard }}</td>
                    <td>{{ $item->brand_name }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->delivery_period }}</td>
                    <td>{{ $item->order_point }}</td>
                    <td>{{ $item->actual_stock }}</td>
                    <td>{{ $item->order_schedule ? $item->order_schedule->format('Y-m-d') : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('supplyItems.edit', $item->id) }}" class="btn btn-secondary">編集</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            @else
            <tbody>
                <tr>
                    <td colspan="11" class="text-center">登録されている備品はありません。</td>
                </tr>
            </tbody>
            @endif
        </table>
        {{ $supplyItems->links() }} {{-- ページネーションリンク --}}
    </div>
</div>
@endsection
