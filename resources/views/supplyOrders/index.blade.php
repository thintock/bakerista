@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="justify-center">
        <h1 class="text-2xl font-bold mb-4">資材・備品発注データ保守</h1>
        <p>社内で使用される資材と備品の発注業務を一覧表示します。<br>この画面はデータ保守に使用し、日常業務には使用しないでください。</p>
    </div>
    <div class="flex mb-4">
        <form action="{{ route('supplyOrders.index') }}" method="GET" class="flex flex-wrap gap-4">
            <!--発注番号での検索-->
            <input type="text" name="id" value="{{ request('id') }}" placeholder="発注番号"  class="input input-bordered text-xs">
            <!-- ステータスでの検索 -->
            <select name="status" class="select select-bordered text-xs">
                <option value="">全てのステータス</option>
                <option value="発注依頼中" {{ request('status') == '発注依頼中' ? 'selected' : '' }}>発注依頼中</option>
                <option value="発注待ち" {{ request('status') == '発注待ち' ? 'selected' : '' }}>発注待ち</option>
                <option value="入荷待ち" {{ request('status') == '入荷待ち' ? 'selected' : '' }}>入荷待ち</option>
                <option value="保留" {{ request('status') == '保留' ? 'selected' : '' }}>保留</option>
                <option value="取消" {{ request('status') == '取消' ? 'selected' : '' }}>取消</option>
                <option value="完了" {{ request('status') == '完了' ? 'selected' : '' }}>完了</option>
                </select>
            <!-- 発注先での検索 -->
            <select name="company_id" class="select select-bordered text-xs">
                <option value="">全ての発注先</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
            <!-- 資材備品での検索 -->
            <select name="item_id" class="select select-bordered text-xs">
                <option value="">全ての資材備品</option>
                @foreach ($supplyItems as $supplyItem)
                    <option value="{{ $supplyItem->id }}" {{ request('item_id') == $supplyItem->id ? 'selected' : '' }}>{{ $supplyItem->item_name }}</option>
                @endforeach
            </select>
            <!-- ロケーションでの検索 -->
            <select name="location_code" class="select select-bordered text-xs">
                <option value="">全てのロケーション</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}" {{ request('location_code') == $location->id ? 'selected' : '' }}>{{ $location->location_code }}-{{ $location->location_name }}</option>
                @endforeach
            </select>
            <!-- 検索ボタン -->
            <button type="submit" class="btn btn-secondary text-xs">検索</button>
            <a href="{{ route('supplyOrders.index') }}" class="btn btn-info text-xs">クリア</a>
            <a href="{{ route('supplyOrders.create') }}" class="btn btn-primary">手動発注入力</a>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="table table-xs bg-base-100">
            <thead>
                <tr>
                    <th>コード<br>ステータス</th>
                    <td></td>
                    <th>資材備品名</th>
                    <th>備考</th>
                    <th>発注依頼日</th>
                    <th>発注日</th>
                    <th>納品予定日</th>
                    <th>入荷日</th>
                    <th>発注数</th>
                    <th>入荷数</th>
                    <th>
                        発注先<br>
                        納品ロケーション
                    </th>
                </tr>
            </thead>
            @if ($supplyOrders->count() > 0)
            <tbody>
                @foreach ($supplyOrders as $order)
                <tr>
                    <td width="110px">{{ $order->id }}<br>
                    <!-- 現在のステータスに応じたバッジ表示 -->
                        @if($order->status === '発注依頼中')
                            <span class="badge badge-secondary ml-2">発注依頼中</span>
                        @elseif($order->status === '発注待ち')
                            <span class="badge badge-accent ml-2">発注待ち</span>
                        @elseif($order->status === '入荷待ち')
                            <span class="badge badge-primary ml-2">入荷待ち</span>
                        @elseif($order->status === '保留')
                            <span class="badge badge-info ml-2">保留</span>
                        @elseif($order->status === '取消')
                            <span class="badge badge-warning ml-2">取消</span>
                        @elseif($order->status === '完了')
                            <span class="badge badge-warning ml-2">完了</span>
                        @endif
                    </td>
                    <td>
                        @if($order->supplyItem && $order->supplyItem->thumbnail)
                            <img src="{{ Storage::url($order->supplyItem->thumbnail) }}" alt="サムネイル" class="top-0 left-0 w-8 aspect-square object-cover rounded-lg cursor-pointer" onclick="showModal('{{ Storage::url($order->supplyItem->thumbnail) }}')">
                        @endif
                        <!-- モーダル -->
                        <div id="imageModal" class="modal flex items-center justify-center">
                            <div class="modal-box flex flex-col items-center justify-center">
                                <img id="fullSizeImage" src="" alt="サムネイル" class="max-w-full h-auto mx-auto">
                                <div class="modal-action w-full flex justify-center">
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
                    </td>
                    <td>{{ $order->supplyItem->item_name }}</td>
                    <td>{{ $order->description }}</td>
                    <td>{{ $order->request_date }}</td>
                    <td>{{ $order->order_date }}</td>
                    <td>{{ $order->delivery_date }}</td>
                    <td>{{ $order->arrival_date }}</td>
                    <td>{{ $order->order_quantity }}</td>
                    <td>{{ $order->arrival_quantity }}</td>
                    <td>
                        {{ $order->company ? $order->company->name : '未登録' }}<br>
                        {{ $order->location ? $order->location->location_name : '未登録' }} <br>
                    </td>
                    <td>
                        <a href="{{ route('supplyOrders.edit', $order->id) }}" class="btn btn-secondary">編集</a>
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
        {{ $supplyOrders->links() }} {{-- ページネーションリンク --}}
    </div>
</div>
@endsection
