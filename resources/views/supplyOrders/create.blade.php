@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('supplyOrders.index') }}" class="btn btn-secondary">
            ← 戻る
        </a>
        <h1 class="text-2xl font-semibold">新規発注登録</h1>
        <div></div>
    </div>
    <div class="w-full mx-auto">
        <form action="{{ route('supplyOrders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label for="item_id" class="form-label">資材備品</label>
                <select id="item_id" name="item_id" class="select select-bordered w-full" required>
                    <option value="">選択してください</option>
                    @foreach ($supplyItems as $item)
                        <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label for="order_quantity" class="form-label">発注数量</label>
                <input type="number" id="order_quantity" name="order_quantity" class="input input-bordered w-full" placeholder="発注数量を入力">
            </div>

            <div class="mb-4">
                <label for="description" class="form-label">備考</label>
                <textarea id="description" name="description" class="textarea textarea-bordered w-full" placeholder="備考"></textarea>
            </div>

            <div class="form-control mt-6">
                <button type="submit" class="btn btn-primary">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection
