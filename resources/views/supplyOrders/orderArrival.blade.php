@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col items-center">
        <h1 class="text-2xl font-bold mb-4">入荷登録</h1>
        <p class="mb-6">入荷検品を実施した上で登録してください。<br>発注数と入荷した数が異なる場合、これで入荷を完了とする場合には「発注修正」にチェックを入れて登録してください。</p>
        <!-- 検索フォーム -->
        <div class="w-full max-w-xl mb-6">
            <form action="{{ route('supplyOrders.orderArrival') }}" method="GET" class="flex flex-wrap gap-4 justify-center">
                <input type="text" name="search_term" placeholder="検索..." class="input input-bordered w-full max-w-xs" />
                <button type="submit" class="btn btn-primary">検索</button>
            </form>
        </div>
    </div>
    
    
    <!-- 商品リスト -->
    <div class="w-full">
        @forelse ($supplyOrders as $order)
        <form action="{{ route('supplyOrders.storeArrival') }}" id="uploadForm" method="POST" class="bg-base-100 mb-4">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <div class="container mx-auto p-2">
                <div class="flex flex-wrap">
                    <div class="xl:w-1/12 md:w-1/6 w-full p-2">
                        <div class="flex items-center justify-center h-14">
                            @if($order->supplyItem && $order->supplyItem->thumbnail)
                                    <img src="{{ Storage::url($order->supplyItem->thumbnail) }}" alt="サムネイル" class="top-0 left-0 w-14 aspect-square object-cover rounded-lg cursor-pointer" onclick="showModal('{{ Storage::url($order->supplyItem->thumbnail) }}')">
                            @endif
                        </div>
                    </div>
                    <div class="xl:w-3/12 md:w-5/6 w-full p-2">
                        <div class="items-center p-2">
                            <p>
                                {{ $order->supplyItem->item_name }}
                            </p>
                            <p class="text-xs break-words">
                                入荷予定日：{{ $order->delivery_date }}　備考：{{ $order->description }}
                            </p>
                            <p class="text-xs break-words">
                                保管場所：{{ $order->location->location_name }}
                            </p>
                        </div>
                    </div>
                    <div class="xl:w-1/6 md:w-1/2 w-full p-2">
                        <div class="flex items-center justify-center h-14 mb-2">
                            <input type="number" name="arrival_quantity" value="{{ $order->order_quantity - $order->arrival_quantity }}" class="input input-bordered w-24 mr-2" min="0">
                            <div class="btn btn-xs btn-secondary"><input type="checkbox" name="order_fix" class="checkbox checkbox-secondary" value="1" style="width: 1rem; height: 1rem;">発注修正</div>
                        </div>
                    </div>
                    <div class="xl:w-2/6 md:w-1/2 w-full p-2 px-4">
                        <div>
                            <input type="range" min="1" max="5" value="3" class="range" step="1" name="point_review"/>
                            <div class="w-full flex justify-between text-xs px-2">
                                <span>遅すぎた</span>
                                <span>遅い</span>
                                <span>丁度良い</span>
                                <span>早い</span>
                                <span>早すぎた</span>
                            </div>
                        </div>
                    </div>
                    <div class="xl:w-1/6 w-full p-2">
                        <div class="flex items-center justify-center h-14">
                            <button class="btn btn-primary w-full" type="submit">入荷登録</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
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
        @empty
        <div class="text-center">
            <p>入荷待ちはありません。</p>
        </div>
        @endforelse
    </div>
    <!-- ページネーション -->
    <div class="pagination">
        {{ $supplyOrders->links() }}
    </div>
</div>
@endsection
