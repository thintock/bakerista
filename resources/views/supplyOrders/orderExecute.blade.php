@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <!-- 戻るボタン -->
        <a href="{{ route('supplyOrders.orderEntry') }}" class="btn btn-secondary">
            自動発注入力
        </a>
        <h1 class="text-2xl font-bold">発注実行</h1>
        <!-- 新規作成ボタン -->
        <a href="{{ route('supplyOrders.create') }}" class="btn btn-primary">
            手動発注入力
        </a>
    </div>
    @if($ordersByCompany->isEmpty())
        <div class="mb-8">
            <div>対応が必要な発注依頼はありません。</div>
            <p>先に<a href="{{ route('supplyOrders.orderEntry') }}" class="link">発注入力</a>を実行してください。</p>
        </div>
    @else
        @foreach($ordersByCompany as $company_id => $orders)
            @php
                $company = $orders->first()->company;
                $selectAllCheckboxId = 'select-all-' . $company_id;
            @endphp
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold mb-2"><a href="{{ route('companies.edit',$company_id) }}" target="_blank" class="link">{{ $company->name ?? '未登録'; }}への発注</a>
                    <div class="badge badge-info">{{ $company->how_to_order ?? '未登録'; }}</div></h2>
                    <div>
                        @if($company && $company->how_to_order === '電話')
                            @if($company->phone_number)
                                <div>電話番号: <a href="tel:{{ $company->phone_number }}">{{ $company->phone_number }}</a></div>
                            @else
                                <div>電話番号: 設定されていません</div>
                            @endif
                        @endif
                    </div>
                </div>
                
                <form action="{{ route('supplyOrders.storeExecute') }}" id="uploadForm" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="table table-xs bg-base-100">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="{{ $selectAllCheckboxId }}" class="checkbox">
                                    </th>
                                    @if(!is_null($company->how_to_order))
                                        @if($company->how_to_order === 'WEB')
                                            <th>注文URL</th>
                                        @endif
                                    @endif
                                    <th>資材備品名</th>
                                    <th>
                                        ロケーション<br>
                                        備考
                                    </th>
                                    <th>発注数量</th>
                                    <th>入荷予定日</th>
                                    <th>備考</th>
                                    <th>取消</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_orders[]" value="{{ $order->id }}" class="checkbox" data-company="{{ $company_id }}">
                                        </td>
                                        @if($company->how_to_order === 'WEB')
                                        <th><a href="{{ $order->supplyItem->order_url }}" target="_blank" class="link"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg></a></th>
                                        @endif
                                        <td><a href="{{ route('supplyItems.edit', $order->supplyItem->id) }}" target="_blanc" class="link">{{ $order->supplyItem->item_name }}</a></td>
                                        <td>
                                            {{ $order->location->location_code }} {{ $order->location->location_name }}<br>
                                            {{ $order->description }}
                                        </td>
                                        <td>
                                            <input type="number" name="orders[{{ $order->id }}]" value="{{ $order->order_quantity }}" class="input input-bordered w-full">
                                        </td>
                                        <td>
                                            <input type="date" name="delivery_dates[{{ $order->id }}]" value="{{ old('delivery_dates.'.$order->id, $order->delivery_date) }}" class="input input-bordered w-full">
                                        </td>
                                        <td>
                                            <input type="text" name="descriptions[{{ $order->id }}]" class="input input-bordered w-full" placeholder="備考">
                                        </td>
                                        <td>
                                            <a href="{{ route('supplyOrders.cancel', $order->id) }}" class="btn btn-warning" onclick="return confirm('この発注依頼を取消しますか？');">取消</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="form-control mt-6">
                        @if($company->how_to_order === 'FAX' || $company->how_to_order === 'メール')
                            <input type="text" name="orderDescription" class="input input-bordered w-full mb-6" placeholder="発注書の備考欄に記載する内容を入力してください。">
                            <button type="submit" onclick="return confirm('注文を確定し注文書を作成しますか？');" class="btn btn-accent">注文書作成</button>
                        @else
                            <button type="submit" onclick="return confirm('発注作業は完了しましたか？');" class="btn btn-primary">発注完了</button>
                        @endif
                    </div>
                </form>
                    <!--チェックボックス全選択の処理-->
                <script>
                    document.getElementById('{{ $selectAllCheckboxId }}').addEventListener('click', function(event) {
                        var isChecked = event.target.checked;
                        document.querySelectorAll('input.checkbox[data-company="{{ $company_id }}"]').forEach(function(checkbox) {
                            checkbox.checked = isChecked;
                        });
                    });
                </script>
            </div>
        @endforeach
    @endif
</div>
@endsection
