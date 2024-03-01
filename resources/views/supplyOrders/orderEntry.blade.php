@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">発注候補一覧</h1>

    <form action="{{ route('supplyOrders.updateEntry') }}" method="POST">
    @csrf
    @if($pendingOrders->isEmpty())
    <div class="mb-8">
        <div>対応が必要な発注依頼はありません。</div>
    </div>
    @else
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
                                備考<br>
                                ロケーション　発注先
                            </th>
                            <th>実在庫</th>
                            <th>入荷待ち</th>
                            <th>発注点</th>
                            <th>ロット</th>
                            <th>在庫定数</th>
                            <th>発注数量</th>
                            <th>備考</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingOrders as $order)
                            <tr>
                                <td><input type="checkbox" name="selected_orders[]" value="{{ $order->id }}" class="checkbox"></td>
                                <td>
                                    {{ $order->request_date }}　{{ $order->requestUser->name }} {{ $order->requestUser->first_name }}<br>
                                    {{ $order->supplyItem->item_name }}
                                </td>
                                <td>
                                    {{ $order->description }}<br>
                                    {{ $order->supplyItem->location->location_code }} {{ $order->supplyItem->location->location_name }}<br>
                                    {{ $order->supplyItem->company->name }}
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
    @endif
</form>


    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">発注が必要な資材備品</h2>
        <form action="{{ route('supplyOrders.storeEntry') }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="table table-xs bg-base-100">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all-store" class="checkbox"></th>
                            <th>資材備品名</th>
                            <th>
                                ロケーション<br>
                                発注先
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
                                    <td>{{ $item->item_name }}</td>
                                    <td>
                                        {{ $item->location->location_code }} {{ $item->location->location_name }}<br>
                                        {{ $item->company->name }}
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
