@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <!-- 戻るボタン -->
        <a href="{{ route('supplyOrders.orderRequest') }}" class="btn btn-secondary">
            発注依頼
        </a>
        <h1 class="text-2xl font-bold">自動発注入力</h1>
        
        <!-- 新規作成ボタン -->
        <a href="{{ route('supplyOrders.orderExecute') }}" class="btn btn-primary">
            発注実行
        </a>
    </div>
    @if($pendingOrders->isEmpty())
    <div class="mb-8">
        <div>対応が必要な発注依頼はありません。</div>
    </div>
    @else
        <form action="{{ route('supplyOrders.updateEntry') }}" id="uploadForm" method="POST">
        @csrf
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-2">発注依頼中の資材備品</h2>
                <div class="overflow-x-auto">
                    <table class="table table-xs bg-base-100">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-update" class="checkbox"></th> <!-- 全選択用チェックボックス -->
                                <th>
                                    依頼日　依頼者<br>
                                    資材備品
                                </th>
                                <th>
                                    ロケーション　発注先<br>
                                    備考
                                </th>
                                <th>実在庫</th>
                                <th>入荷待ち</th>
                                <th>発注点</th>
                                <th>ロット</th>
                                <th>在庫定数</th>
                                <th>発注数量</th>
                                <th>備考</th>
                                <th>取消</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingOrders as $order)
                                <tr>
                                    <td><input type="checkbox" name="selected_orders[]" value="{{ $order->id }}" class="checkbox"></td>
                                    <td>
                                        {{ $order->request_date }}　{{ $order->requestUser->name }} {{ $order->requestUser->first_name }}<br>
                                        <a href="{{ route('supplyItems.edit', $order->supplyItem->id) }}" class="link" target="_blank">{{ $order->supplyItem->item_name }}</a>
                                    </td>
                                    <td>
                                        {{ $order->supplyItem->location->location_code }} {{ $order->supplyItem->location->location_name }}<br>
                                        {{ $order->supplyItem->company->name }}<br>
                                        {{ $order->description }}
                                    </td>
                                    <td>{{ $order->supplyItem->actual_stock }}</td>
                                    <td>{{ $order->pendingArrivals }}</td>
                                    <td>{{ $order->supplyItem->order_point }}</td>
                                    <td>{{ $order->supplyItem->order_lot }}</td>
                                    <td>{{ $order->supplyItem->constant_stock }}</td>
                                    <td>
                                        <input type="number" name="order_quantities[{{ $order->id }}]" class="input input-bordered w-full" value="{{ $order->calculatedOrderQuantity }}">
                                    </td>
                                    <td>
                                        <input type="text" name="descriptions[{{ $order->id }}]" class="input input-bordered w-full" placeholder="備考"></textarea>
                                    </td>
                                    <td>
                                        <a href="{{ route('supplyOrders.cancel', $order->id) }}" class="btn btn-warning" onclick="return confirm('この発注依頼を取消しますか？');">取消</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-control mt-6 mb-8">
                <button type="submit" class="btn btn-primary">発注入力</button>
            </div>
            <!--チェックボックス全選択の処理-->
            <script>
                document.getElementById('select-all-update').addEventListener('click', function(event) {
                    var isChecked = event.target.checked;
                    document.querySelectorAll('input[name="selected_orders[]"]').forEach(function(checkbox) {
                        checkbox.checked = isChecked;
                    });
                });
            </script>
        </form>
    @endif


    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <div></div>
            <h1 class="text-2xl font-bold">発注が必要な資材備品</h1>
            
            <a href="{{ route('supplyOrders.create') }}" class="btn btn-primary">
                手動発注入力
            </a>
        </div>
        @if($itemsCount == 0)
            <div>
                発注が必要な資材備品はありません。
            </div>
        @else
            <form action="{{ route('supplyOrders.storeEntry') }}" id="uploadForm" method="POST">
                @csrf
                <div class="overflow-x-auto">
                    <table class="table table-xs bg-base-100">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-store" class="checkbox"></th>
                                <th>
                                    資材備品名<br>
                                    発注先</th>
                                <th>
                                    ロケーション<br>
                                    次回発注予定日
                                </th>
                                <th>実在庫数</th>
                                <th>入荷待ち</th>
                                <th>発注点</th>
                                <th>ロット</th>
                                <th>在庫定数</th>
                                <th>発注数量</th>
                                <th>備考</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($itemsForOrdering as $item)
                                @if($item->order_quantity > 0)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_stores[]" value="{{ $item->id }}" class="checkbox">
                                        </td>
                                        <td>
                                            <p>
                                                @if($item->order_schedule)
                                                    {{ $item->order_schedule->format('Y年m月d日') }}
                                                @else
                                                    未設定
                                                @endif
                                                自動発注
                                            </p>
                                                <a href="{{ route('supplyItems.edit', $item->id) }}" class="link" target="_blank">{{ $item->item_name }}</a>
                                            </td>
                                        <td>
                                            <p>
                                                {{ $item->location->location_code }} {{ $item->location->location_name }}
                                            </p>
                                            <p>
                                                {{ $item->company->name }}
                                            </p>
                                        </td>

                                        <td>{{ $item->actual_stock }}</td>
                                        <td>{{ $item->pendingArrivals }}</td>
                                        <td>{{ $item->order_point }}</td>
                                        <td>{{ $item->order_lot }}</td>
                                        <td>{{ $item->constant_stock }}</td>
                                        <td>
                                            <input type="number" name="orders[{{ $item->id }}]" value="{{ $item->order_quantity }}" class="input input-bordered w-full">
                                        </td>
                                        <td>
                                            <input type="text" name="descriptions[{{ $item->id }}]" class="input input-bordered w-full" placeholder="備考"></textarea>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">発注入力</button>
                </div>
                </div>
            </form>
        @endif
    </div>
</div>

<!--チェックボックス全選択の処理-->
<script>
    document.getElementById('select-all-store').addEventListener('click', function(event) {
        var isChecked = event.target.checked;
        document.querySelectorAll('input[name="selected_stores[]"]').forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });
</script>
@endsection
