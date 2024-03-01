@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-4">
        <div></div>
        <h1 class="text-2xl font-semibold">資材備品管理</h1>
        <div></div>
    </div>
    
    <div class="mb-4">
        <label for="item_name" class="form-label">資材備品名</label>
        <input type="text" id="item_name" name="item_name" class="input input-bordered w-full" value="{{ $selectedItem->item_name }}" readonly>
    </div>
    
    <!--在庫管理-->
    <div class="join join-vertical w-full">
        <div class="collapse collapse-arrow join-item bg-base-100 border border-base-100">
            <input type="radio" name="my-accordion" class="peer" /> 
            <div class="collapse-title text-xl font-medium peer-checked:bg-secondary peer-checked:text-secondary-content">
                在庫入力
            </div>
            <div class="collapse-content bg-base-200">
                <div class="w-full mx-auto">
                    <form action="{{ route('supplyOrders.storeRequest') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                                <label for="actual_stock" class="form-label">実在庫数</label>
                                <input type="number" id="actual_stock" name="actual_stock" class="input input-bordered w-full" value="{{ $selectedItem->actual_stock }}">
                        </div>
                        <div class="form-control mt-6">
                            <input type="hidden" name="update_stock" value="1">
                            <input type="hidden" name="item_id" value="{{ $selectedItem->id }}">
                            <button type="submit" class="btn btn-primary">在庫更新を送信</button>    
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!--発注依頼-->
        <div class="collapse collapse-arrow join-item bg-base-100 border border-base-100">
            <input type="radio" name="my-accordion"  class="peer" /> 
            <div class="collapse-title text-xl font-medium peer-checked:bg-secondary peer-checked:text-secondary-content">
                発注依頼
            </div>
            <div class="collapse-content bg-base-200">
                <div class="w-full mx-auto">
                    <form action="{{ route('supplyOrders.storeRequest') }}" method="POST">
                        @csrf
            
                        @if ($selectedItem)
            
                        <div class="mb-4">
                            <p>次回発注予定日: {{ $selectedItem->order_schedule ? $selectedItem->order_schedule->format('Y年m月d日') : '未設定' }} 納期: {{ $selectedItem->delivery_period }} 日</p>
                            <p>ロケーション：{{ $selectedItem->location->location_code }} {{ $selectedItem->location->location_name }}　発注先：{{ $selectedItem->company->name }}</p>
                            <p>
                                （実在庫: {{ $selectedItem->actual_stock }} 個＋入荷待ち: {{ $pendingArrivalsQuantity }} 個）＝フリー在庫: {{ $selectedItem->actual_stock + $pendingArrivalsQuantity }} 個　発注点: {{ $selectedItem->order_point }} 個　＝＞
                                @if(($selectedItem->actual_stock + $pendingArrivalsQuantity) <= $selectedItem->order_point)
                                    <span class="badge badge-primary">要発注</span>
                                @else
                                    <span class="badge badge-secondary">発注不要</span>
                                @endif
                            </p>
                        </div>
            
                        <div>
                            <p></p>
                        </div>
                        <div class="mb-4">
                            <label for="actual_stock" class="form-label">実在庫に変更ある場合入力</label>
                            <input type="number" id="actual_stock" name="actual_stock" class="input input-bordered w-full" value="{{ $selectedItem->actual_stock }}">
                        </div>
                        
                        @else
                        <div class="mb-4">
                            <label for="item_id" class="form-label">資材備品</label>
                            <p>※資材備品コードが指定されませんでした。QRコードからスマートフォン読み込んで表示してください。</p>
                        </div>
            
                        @endif
            
                        <div class="mb-4">
                        @if ($selectedItem && $selectedItem->item_status !== '承認済み')
                            <p>現在のステータス: <strong>{{ $selectedItem->item_status ?? '未設定' }}</strong></p>
                            <p>この資材備品は発注を承認をされていません。</p>
                        @elseif($selectedItem)
                            <div class="grid grid-cols-2 gap-4">
                                <button type="button" class="templateBtn btn btn-warning" data-template="至急発注希望" {{ $selectedItem->item_status === '承認済み' ? '' : 'disabled'}}>至急発注</button>
                                <button type="button" class="templateBtn btn btn-accent" data-template="急ぎません" {{ $selectedItem->item_status === '承認済み' ? '' : 'disabled'}}>急ぎません</button>
                            </div>
                        @else
                            <p>資材備品が選択されていません。</p>
                        @endif
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="form-label">備考</label>
                            <textarea id="description" name="description" class="textarea textarea-bordered w-full" placeholder="備考" {{ ($selectedItem && $selectedItem->item_status === '承認済み') ? '' : 'disabled'}}></textarea>
                        </div>
            
            
                        <div class="form-control mt-6">
                            <input type="hidden" name="item_id" value="{{ $selectedItem->id }}">
                            <button type="submit" class="btn btn-primary" {{ ($selectedItem && $selectedItem->item_status === '承認済み') ? '' : 'disabled'}}>発注依頼</button>
                        </div>
                    </form>
                    <!-ー定型文入力-->
                    <script>
                    document.querySelectorAll('.templateBtn').forEach(button => {
                        button.addEventListener('click', function() {
                            var template = this.getAttribute('data-template');
                            var textarea = document.getElementById('description');
                            textarea.value += "【" +template +"】"; // ボタンに設定された定型文をテキストエリアに挿入
                        });
                    });
                    </script>
            
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection