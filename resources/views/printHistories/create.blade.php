@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">ラベル印刷用データ作成</h1>

    <form action="{{ route('printHistories.store') }}" method="POST" class="form-control w-full max-w-xs">
        @csrf
        <label class="label" for="product_id">
            <span class="label-text">製品選択</span>
        </label>
        <select id="product_id" name="product_id" class="select select-bordered w-full max-w-xs">
            <option value="">選択してください</option>
            @foreach ($productItems as $productItem)
            <option value="{{ $productItem->id }}">{{ $productItem->name }}</option>
            @endforeach
        </select>
    
        <label class="label mt-4" for="count">
            <span class="label-text">印刷枚数</span>
        </label>
        <div class="flex items-center">
            <input type="number" id="count" name="count" value="0" min="1" class="input input-bordered" style="flex: 1;">
        </div>
        
        <div class="flex items-center mt-4">
            <!-- 加算ボタン -->
            <div class="flex gap-2 ml-4">
                <button type="button" onclick="addToCount(1)" class="btn btn-secondary">+1</button>
                <button type="button" onclick="addToCount(2)" class="btn btn-secondary">+2</button>
                <button type="button" onclick="addToCount(5)" class="btn btn-secondary">+5</button>
                <button type="button" onclick="addToCount(10)" class="btn btn-secondary">+10</button>
                <button type="button" onclick="addToCount(25)" class="btn btn-secondary">+25</button>
                <button type="button" onclick="addToCount(50)" class="btn btn-secondary">+50</button>
                <button type="button" onclick="addToCount(100)" class="btn btn-secondary">+100</button>
            </div>
        </div>
        
        <div class="flex items-center mt-4">
            <!-- 加算ボタン -->
            <div class="flex gap-2 ml-4">
                <button type="button" onclick="addToCount(-1)" class="btn btn-accent">-1</button>
                <button type="button" onclick="addToCount(-2)" class="btn btn-accent">-2</button>
                <button type="button" onclick="addToCount(-5)" class="btn btn-accent">-5</button>
                <button type="button" onclick="addToCount(-10)" class="btn btn-accent">-10</button>
                <button type="button" onclick="addToCount(-25)" class="btn btn-accent">-25</button>
                <button type="button" onclick="addToCount(-50)" class="btn btn-accent">-50</button>
                <button type="button" onclick="addToCount(-100)" class="btn btn-accent">-100</button>
                <button type="button" onclick="clearCount()" class="btn btn-error">クリア</button>
            </div>
        </div>
    
        <div class="mt-6">
            <button type="submit" class="btn btn-primary">作成</button>
        </div>
    </form>
    
    <script>
    function addToCount(number) {
        var countInput = document.getElementById('count');
        var currentValue = parseInt(countInput.value) || 0;
        countInput.value = currentValue + number;
    }
    
    // カウントをクリアする関数
    function clearCount() {
        document.getElementById('count').value = 0;
    }
    </script>


    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4">印刷履歴</h2>
        <div class="overflow-x-auto">
            <table class="table table-xs bg-base-100">
                <thead>
                    <tr>
                        <th>商品名</th>
                        <th>印刷枚数</th>
                        <th>印刷日時</th>
                        <th>ユーザー</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($printHistories as $history)
                    <tr>
                        <td>{{ $history->productItem->name }}</td>
                        <td>{{ $history->count }}</td>
                        <td>{{ $history->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $history->user->name }} {{ $history->user->first_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $printHistories->links() }}
        </div>
    </div>
</div>
@endsection
