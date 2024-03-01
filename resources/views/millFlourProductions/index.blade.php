@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        
        <div class="flex flex-col flex-row justify-between items-center mb-4">
            
            <div class="flex mb-4">
                <h1 class="text-xl font-semibold">製粉記録一覧</h1>
            </div>
            
            <div class="stats shadow">
                <div class="stat">
                    <div class="stat-title">累計製粉量</div>
                    <div class="stat-value text-primary">{{ number_format(round($totalFlourAmount)) }} kg</div>
                    <div class="stat-desc"></div>
                </div>
                
                <div class="stat">
                    <div class="stat-title">小麦粉在庫量</div>
                    <div class="stat-value text-primary">{{ number_format(round($currentFlourAmount)) }} kg</div>
                    <div class="stat-desc"></div>
                </div>
                
                <div class="stat">
                    <div class="stat-title">ふすま在庫量</div>
                    <div class="stat-value text-primary">{{ number_format(round($currentBranAmount)) }} kg</div>
                    <div class="stat-desc"></div>
                </div>
                
                <div class="stat">
                    <div class="stat-title">在庫金額</div>
                    <div class="stat-value text-info">{{ number_format(round($currentStockValue)) }} 円</div>
                    <div class="stat-desc"></div>
                </div>
                
            </div>
        
            <div class="flex mt-4">
                <form action="{{ route('millFlourProductions.index') }}" method="GET" class="mb-4">
                    <div class="flex space-x-2">
                        <div class="form-control">
                            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="input input-bordered">
                        </div>
                        <div class="mt-3">〜</div>
                        <div class="form-control">
                            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="input input-bordered">
                        </div>
                        <div class="form-control">
                            <button type="submit" class="btn btn-info mr-2">表示期間設定</button>
                        </div>
                        <!--絞り込み条件の保持-->
                        <input type="hidden" name="show_all" value="{{ request('show_all') }}">
                    </div>
                </form>
                <a href="{{ route('millFlourProductions.create') }}" class="btn btn-primary mr-1">新規製粉登録</a>
                <form action="{{ route('millFlourProductions.index') }}" method="GET" class="inline">
                    @if(request('show_all') == 'true')
                        <button type="submit" class="btn btn-secondary">在庫を表示</button>
                        <input type="hidden" name="show_all" value="false">
                    @else
                        <button type="submit" class="btn btn-success">在庫なしを表示</button>
                        <input type="hidden" name="show_all" value="true">
                    @endif
                    <!--絞り込み条件の保持-->
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                </form>
            </div>
        </div>
        
        @if ($productions->count() > 0)
            <div class="overflow-x-auto">
                <table class="table table-xs bg-base-100">
                    <thead>
                        <tr>
                            <th>
                                <p>製造ロット番号</p>
                                <p>製粉日</p>
                            </th>
                            <th>
                                <p>総投入量</p>
                                <p>投入原価</p>
                            </th>
                            <th>
                                <p>小麦粉</p>
                                <p>ふすま</p>
                            </th>
                            <td>
                                <p>製粉機</p>
                            </td>
                            <th>
                                製品歩留<br>
                                製粉歩留
                            </th>
                            <td>備考</td>
                            <td>使用原料情報</td>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productions as $production)
                            <tr class="border-b-0">
                                <td>
                                    <p>{{ $production->production_lot_number }}</p>
                                    <p>{{ $production->production_date->format('Y年m月d日') }}</p>
                                </td>
                                <td>
                                    <p>{{ round($production->total_input_weight, 1) }} kg</p>
                                    <p>{{ number_format($production->total_input_cost) }} 円</p>
                                </td>
                                <td>
                                    <p>小麦粉: {{ round($production->flour_weight, 1) }} kg</p>
                                    <p>ふすま: {{ round($production->bran_weight, 1) }} kg</p>
                                </td>
                                <td>
                                    <div class="badge badge-info mb-1">No.{{ $production->millMachine->machine_number }}</div>
                                    <p>{{ $production->millMachine->machine_name }}</p>
                                </td>
                                <td>
                                    <p>{{ $production->milling_retention }} %</p>
                                    <p>{{ $production->productRetention }} %</p>
                                    @if ($production->is_finished)
                                    <p class="text-info">在庫なし</p>
                                    @else
                                    @endif
                                </td>
                                <td>{{ $production->remarks }}</td>
                                <td class="border-l border-r border-base-200">
                                    <table class="table table-xs w-full">
                                        @if ($loop->first)
                                            <thead>
                                                <tr>
                                                    <th>精麦済みロット番号</th>
                                                    <th>投入重量</th>
                                                    <th>投入原価</th>
                                                </tr>
                                            </thead>
                                        @endif
                                        <tbody>
                                            @foreach ($production->millPolishedMaterials as $polishedMaterial)
                                                @php
                                                    $purchaseMaterial = $polishedMaterial->millPurchaseMaterials->first(); // 最初の購入材料を取得
                                                @endphp
                                                @if ($purchaseMaterial && $purchaseMaterial->material)
                                                    <tr>
                                                        <td>{{ $polishedMaterial->polished_lot_number }} - {{ $purchaseMaterial->material->materials_name }}</td>
                                                        <td>{{ $polishedMaterial->pivot->input_weight }} kg</td>
                                                        <td>{{ number_format($polishedMaterial->pivot->input_cost) }} 円</td>
                                                    </tr>
                                                @endif
                                            @endforeach

                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <a href="{{ route('millFlourProductions.edit', $production->id) }}" class="btn btn-secondary"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="18" height="18" color="#000000"><defs><style>.cls-6374f8d9b67f094e4896c676-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs><path class="cls-6374f8d9b67f094e4896c676-1" d="M7.23,20.59l-4.78,1,1-4.78L17.89,2.29A2.69,2.69,0,0,1,19.8,1.5h0a2.7,2.7,0,0,1,2.7,2.7h0a2.69,2.69,0,0,1-.79,1.91Z"></path><line class="cls-6374f8d9b67f094e4896c676-1" x1="0.55" y1="22.5" x2="23.45" y2="22.5"></line><line class="cls-6374f8d9b67f094e4896c676-1" x1="19.64" y1="8.18" x2="15.82" y2="4.36"></line></svg></a>
                                </td>
                            </tr>
                          <tr>
                            <td colspan="8" class="text-right">
                                <p class="text-xs">作成者：{{ $production->user->name }} {{ $production->user->first_name }} 作成日：{{ $production->created_at->format('Y年m月d日 H時i分') }} 最終更新日：{{ $production->updated_at->format('Y年m月d日 H時i分') }}</p>
                            </td>
                          </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- ページネーションのリンク --}}
            {{ $productions->links() }}
        @else
            <p>データがありません。</p>
        @endif
    </div>
@endsection
