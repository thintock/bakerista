@extends('layouts.app')

@section('content')
    {{--ユーザ一覧--}}
    <div class="container mx-auto px-4 py-6">
    
    <div class="flex flex-col 2xl:flex-row justify-between items-center mb-4">
        
        <div class="flex mb-4">
            <h1 class="text-xl font-semibold">精麦記録一覧</h1>
        </div>
        
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-title">累計精麦量</div>
                <div class="stat-value text-secondary">{{ round($totalPolishedAmount) }} kg</div>
                <div class="stat-desc"></div>
            </div>
            
            <div class="stat">
                <div class="stat-title">精麦済み在庫量</div>
                <div class="stat-value text-secondary">{{ round($currentPolishedAmount) }} kg</div>
                <div class="stat-desc"></div>
            </div>
            
            <div class="stat">
                <div class="stat-title">精麦済み在庫金額</div>
                <div class="stat-value text-accent">{{ round($currentPolishedValue) }} 円</div>
                <div class="stat-desc"></div>
            </div>
            
        </div>
        
        <div class="flex mt-4">
            <form action="{{ route('millPolishedMaterials.index') }}" method="GET" class="mb-4">
                <div class="flex space-x-2">
                    <div class="form-control">
                        <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="input input-bordered input-secondary">
                    </div>
                    <div class="mt-3">〜</div>
                    <div class="form-control">
                        <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="input input-bordered input-secondary">
                    </div>
                    <div class="form-control">
                        <button type="submit" class="btn btn-secondary mr-2">表示期間設定</button>
                    </div>
                </div>
            </form>
            <a href="{{ route('millPolishedMaterials.create') }}" class="btn btn-primary mr-1">精麦登録</a>
            <form action="{{ route('millPolishedMaterials.index') }}" method="GET" class="inline">
                @if(request('show_all') == 'true')
                    <button type="submit" class="btn btn-secondary">在庫を表示</button>
                    <input type="hidden" name="show_all" value="false">
                @else
                    <button type="submit" class="btn btn-accent">在庫なしを表示</button>
                    <input type="hidden" name="show_all" value="true">
                @endif
            </form>
        </div>
    </div>

    @if ($millPolishedMaterials->count() > 0)
    <div class="overflow-x-auto">
        <table class="table table-xs">
        <!-- head -->
           <thead>
                <tr class="bg-base-200">
                    <th>
                        <p>精麦済みロット番号</p>
                        <p>精麦日付</p>
                    </th>
                    <th>
                        <p>投入量</p>
                        <p>投入原価</p>
                    </th>
                    <th>
                        <p>精麦済量（歩留）</p>
                        <p>在庫残量</p>
                    </th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($millPolishedMaterials as $millPolishedMaterial)
                <tr class="border-b-0">
                    <td>
                        <p>{{ $millPolishedMaterial->polished_lot_number }}</p>
                        <p>{{ $millPolishedMaterial->polished_date->format('Y年m月d日') }}</p>
                    </td>
                    <td>
                        <p class="mb-1">{{ round($millPolishedMaterial->total_input_weight, 1) }} kg</p>
                        <p class="border-t border-base-200 pt-1">{{ number_format($millPolishedMaterial->total_input_cost) }} 円</p>
                    </td>
                    <td>
                        <p class="mb-1">{{ round($millPolishedMaterial->total_output_weight, 1) }} kg ({{ $millPolishedMaterial->polished_retention }} %)</p>
                        <p class="border-t border-base-200 pt-1">{{ round($millPolishedMaterial->remaining_polished_amount, 1) }}kg
                        @if ($millPolishedMaterial->is_finished)
                        <span class="text-accent">在庫なし</span>
                        @else
                        @endif</p>
                    </td>
                    <td class="border-l border-r border-base-200">
                        <table class="table table-xs w-full">
                            @if ($loop->first)
                                <thead>
                                    <tr>
                                        <th class="w-1/5">投入原料ロット</th>
                                        <th class="w-2/5">原料名</th>
                                        <th class="w-1/5">投入重量</th>
                                        <th class="w-1/5">投入原価</th>
                                    </tr>
                                </thead>
                            @endif
                          <tbody>
                              @foreach ($millPolishedMaterial->millPurchaseMaterials as $millPurchaseMaterial)
                                <tr>
                                  <td class="w-1/5"><div>{{ $millPurchaseMaterial->lot_number }}</div></td>
                                  <td class="w-2/5"><div>{{ $millPurchaseMaterial->material->materials_name }}</div></td>
                                  <td class="w-1/5"><div>{{ $millPurchaseMaterial->pivot->input_weight }} kg</div></td>
                                  <td class="w-1/5"><div>{{ number_format($millPurchaseMaterial->pivot->input_cost) }} 円</div></td>
                                </tr>
                              @endforeach
                            </tbody>
                        </thead>
                      </table>
                    </td>
                    <td><a href="{{ route('millPolishedMaterials.edit', $millPolishedMaterial->id) }}" class="btn btn-primary"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="18" height="18" color="#000000"><defs><style>.cls-6374f8d9b67f094e4896c676-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs><path class="cls-6374f8d9b67f094e4896c676-1" d="M7.23,20.59l-4.78,1,1-4.78L17.89,2.29A2.69,2.69,0,0,1,19.8,1.5h0a2.7,2.7,0,0,1,2.7,2.7h0a2.69,2.69,0,0,1-.79,1.91Z"></path><line class="cls-6374f8d9b67f094e4896c676-1" x1="0.55" y1="22.5" x2="23.45" y2="22.5"></line><line class="cls-6374f8d9b67f094e4896c676-1" x1="19.64" y1="8.18" x2="15.82" y2="4.36"></line></svg></a></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">
                        <p class="text-xs">作成者：{{ $millPolishedMaterial->user->name }} {{ $millPolishedMaterial->user->first_name }} 作成日：{{ $millPolishedMaterial->created_at->format('Y年m月d日 H時i分') }} 最終更新日：{{ $millPolishedMaterial->updated_at->format('Y年m月d日 H時i分') }}</p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
{{-- ページネーションのリンク --}}
{{ $millPolishedMaterials->links() }}
@else
<p>データがありません。</p>
@endif
@endsection