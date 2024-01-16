@extends('layouts.app')

@section('content')
    {{--ユーザ一覧--}}
    <div class="container mx-auto px-4 py-6">
    <div class="flex justify-end mb-4">
        <a href="{{ route('millPurchaseMaterials.create') }}" class="btn btn-primary mr-1">入荷登録</a>
        <form action="{{ route('millPurchaseMaterials.index') }}" method="GET" class="inline">
            @if(request('show_all') == 'true')
                <button type="submit" class="btn btn-secondary">在庫なしを表示しない</button>
                <input type="hidden" name="show_all" value="false">
            @else
                <button type="submit" class="btn btn-accent">在庫なしも含める</button>
                <input type="hidden" name="show_all" value="true">
            @endif
        </form>
    </div>

    @if ($millPurchaseMaterials->count() > 0)
    <div class="overflow-x-auto">
        <table class="table table-xs">
        <!-- head -->
            <thead>
              <tr class="bg-base-200">
                <th>ロットナンバー<br>入荷日</th>
                <th>生産者<br>原材料</th>
                <th>生産年度<br>フレコン番号</th>
                <th>入荷量<br>在庫量</th>
                <th>仕入れ先<br>仕入れ価格（総額）</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <!-- row 1 -->
              @foreach ($millPurchaseMaterials as $millPurchaseMaterial)
              <tr style="border-bottom-width:0px">
                <td>
                    <p>{{ $millPurchaseMaterial->lot_number }}</p>
                    <p>{{ $millPurchaseMaterial->arrival_date->format('Y年m月d日') }}</p>
                </td>
                <td>
                    <div class="badge badge-secondary badge-outline">{{ $millPurchaseMaterial->material->materials_producer_name }}</div>
                    <h2 class="text-lg">&nbsp;{{ $millPurchaseMaterial->material->materials_name }}</h2>
                </td>
                <td>
                    <p>{{ $millPurchaseMaterial->year_of_production }}年度</p>
                    <p>{{ $millPurchaseMaterial->flecon_number }}</p>
                </td>
                <td>
                    <p>{{ $millPurchaseMaterial->total_amount }} kg</p>
                    <p>{{ $millPurchaseMaterial->remaining_amount }} kg
                    @if($millPurchaseMaterial->is_finished)
                    <span class="text-accent">在庫なし</span>
                    @else
                    @endif</p>
                </td>
                <td>
                    <div class="badge badge-secondary badge-outline">{{ $millPurchaseMaterial->material->materials_purchaser }}</div>
                    <p>¥{{ number_format(floor($millPurchaseMaterial->cost), 0, '.', ',') }}</p>
                </td>
                <td><a href="{{ route('millPurchaseMaterials.edit', $millPurchaseMaterial->id) }}" class="btn btn-primary"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="18" height="18" color="#000000"><defs><style>.cls-6374f8d9b67f094e4896c676-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs><path class="cls-6374f8d9b67f094e4896c676-1" d="M7.23,20.59l-4.78,1,1-4.78L17.89,2.29A2.69,2.69,0,0,1,19.8,1.5h0a2.7,2.7,0,0,1,2.7,2.7h0a2.69,2.69,0,0,1-.79,1.91Z"></path><line class="cls-6374f8d9b67f094e4896c676-1" x1="0.55" y1="22.5" x2="23.45" y2="22.5"></line><line class="cls-6374f8d9b67f094e4896c676-1" x1="19.64" y1="8.18" x2="15.82" y2="4.36"></line></svg></a></td>
              </tr>
              <tr>
                <td colspan="7" class="text-right">
                    <p class="text-xs">作成者：{{ $millPurchaseMaterial->user->name }} {{ $millPurchaseMaterial->user->first_name }} 作成日：{{ $millPurchaseMaterial->created_at->format('Y年m月d日 H時i分') }} 最終更新日：{{ $millPurchaseMaterial->updated_at->format('Y年m月d日 H時i分') }}</p>
                </td>
              </tr>
            @endforeach
            </tbody>
        </table>
    </div>
            {{-- ページネーションのリンク --}}
            {{ $millPurchaseMaterials->links() }}
            @else
            <p>データがありません。</p>
            @endif
@endsection