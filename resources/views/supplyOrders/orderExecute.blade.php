@foreach ($ordersByCompany as $company_id => $orders)
    <h3>{{ $orders->first()->company->name }}への発注</h3>
    <table>
        <thead>
            <tr>
                <th>資材備品名</th>
                <th>発注数量</th>
                <th>納期</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->supplyItem->item_name }}</td>
                    <td>{{ $order->order_quantity }}</td>
                    <td>{{ $order->delivery_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- ここに発注実行のためのフォームやボタンを配置 --}}
@endforeach
