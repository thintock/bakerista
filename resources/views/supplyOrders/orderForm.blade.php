@extends('layouts.app')

@section('content')
    
    <div class="alert bg-secondary text-secondary-content mb-4 no-print">
        {{ $message }}
    </div>
    
    <div class="bg-white w-full lg:w-2/3 mx-auto bg-base-100 p-6">
        @if(count($orders) > 0)
            <header class="mb-4">
                <div class="grid grid-cols-6 gap-4">
                    <div class="col-span-3">{{ $orders[0]->company->name }} 御中</div>
                    <div class="col-span-3 text-right">発注日: {{ now()->format('Y年m月d日') }}</div>
                    <div class=" col-start-2 col-span-4 text-center">
                        <h1 class="text-xl">発注書</h1>
                    </div>
                    <div class="col-span-2 text-xs">
                        <p>いつもお世話になっております。</p>
                        <p>以下の通り発注させていただきますのでご対応よろしくお願いいたします。</p>
                    </div>
                    <div class="col-span-4 flex flex-col items-end">
                        <img src="{{ asset('images/bakerista_logo_200px.png') }}" alt="bakerista" width="120px">
                        <p>ベーカリスタ株式会社</p>
                        <p>発注者：{{ $user->name }} {{ $user->first_name }}</p>
                        <p class="text-xs">北海道室蘭市母恋南町二丁目４番１５号</p>
                        <p class="text-xs">電話: 050-1808-4227　FAX：0143-25-6851</p>
                    </div>
                </div>
            </header>
        
            <table class="order-table table table-xs border w-full mb-4">
                <thead>
                    <tr>
                        <th class="border border-black">資材備品コード</th>
                        <th class="border border-black">名称</th>
                        <th class="border border-black">発注数</th>
                        <th class="border border-black">納期目安</th>
                        <th class="border border-black">金額</th>
                        <th class="border border-black">納品場所</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <td class="border border-black">{{ $order->supplyItem->item_code }}</td>
                        <td class="border border-black">{{ $order->supplyItem->item_name }}</td>
                        <td class="border border-black">{{ $order->order_quantity }}</td>
                        <td class="border border-black">{{ $order->delivery_date }}</td>
                        <td class="border border-black">{{ number_format($order->supplyItem->price) }}円</td>
                        <td class="border border-black">{{ mb_substr($order->location->location_name, 0, 4) }}</td>
                    </tr>
                    @endforeach
                    {{-- $ordersの要素数が5未満の場合は2行の空行を追加 --}}
                    @php $remainingRows = 5 - count($orders); @endphp
                    @if ($remainingRows > 0)
                        @for ($i = 0; $i < $remainingRows + 2; $i++)
                            <tr>
                                <td class="border border-black">&nbsp;</td>
                                <td class="border border-black">&nbsp;</td>
                                <td class="border border-black">&nbsp;</td>
                                <td class="border border-black">&nbsp;</td>
                                <td class="border border-black">&nbsp;</td>
                                <td class="border border-black">&nbsp;</td>
                            </tr>
                        @endfor
                    @endif
                </tbody>
            </table>
        
            <footer>
                <table class="table table-xs border w-full">
                    <th class="border border-black">連絡事項</th>
                    <th class="border border-black">発注先：{{ $order->company->how_to_order }}</th>
                    <tr>
                        <td class="border border-black">
                            <p>{{ $orderDescription }}</p>
                        </td>
                        <td class="border border-black">
                            @if($order->company->how_to_order === 'FAX')
                            <p>{{ $order->company->fax_number }}</p>
                            @elseif($order->company->how_to_order === 'メール')
                            <p>{{ $order->company->email }}</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black">
                            <p>
                                母恋倉庫
                            </p>
                            <p>
                                〒051-0003 北海道室蘭市母恋南町二丁目４番１５号
                            </p>
                            <p>
                                TEL:050-1808-4227
                            </p>
                        </td>
                        <td class="border border-black">
                            <p>
                                製粉工場
                            </p>
                            <p>
                                〒051-0025 北海道室蘭市常盤町６番１７号
                            </p>
                            <p>
                                TEL:050-1808-4227（共通）
                            </p>
                        </td>
                    </tr>
                </table>
            </footer>
         @else
            <div class="mb-4">
                <p>注文はありません。</p>
            </div>
        @endif
    </div>
    <div class="flex mt-6 no-print">
        <div class="form-control w-1/2 mr-3">
            <button onclick="window.print()" class="btn btn-primary">印刷する</button>
        </div>
        <div class="w-1/2">
            <a href="{{ route('supplyOrders.orderExecute') }}" class="btn btn-secondary w-full">発注完了</a>
        </div>
    </div>

@endsection