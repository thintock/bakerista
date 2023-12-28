@extends('layouts.app')

@section('content')
    {{--ユーザ一覧--}}
    <div class="container mx-auto px-4 py-6">
    <div class="flex justify-end mb-4">
        <a href="{{ route('millPolishedMaterials.create') }}" class="btn btn-primary">精麦登録</a>
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
                        <p>精麦済み</p>
                        <p>投入量</p>
                    </th>
                    <th>原価</th>
                    <th>投入原料ロット</th>
                    <th>原料名</th>
                    <th>使用重量(kg)</th>
                    <th>使用原価</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($millPolishedMaterials as $millPolishedMaterial)
                <tr>
                    <td>
                        <p>{{ $millPolishedMaterial->polished_lot_number }}</p>
                        <p>{{ $millPolishedMaterial->polished_date }}</p>
                    </td>
                    <td>
                        <p>{{ $millPolishedMaterial->total_output_weight }} kg</p>
                        <p class="border-t">{{ $millPolishedMaterial->total_input_weight }} kg</p>
                    </td>
                    <td>{{ number_format($millPolishedMaterial->total_input_cost) }} 円</td>
                    <td class="border-l border-r border-base-200"colspan="4">
                      <table class="table table-xs">
                          <tbody>
                              @foreach ($millPolishedMaterial->millPurchaseMaterials as $millPurchaseMaterial)
                                <tr>
                                  <td><div>{{ $millPurchaseMaterial->lot_number }}</div></td>
                                  <td><div>{{ $millPurchaseMaterial->material->materials_name }}</div></td>
                                  <td><div>{{ $millPurchaseMaterial->pivot->input_weight }} kg</div></td>
                                  <td><div>{{ number_format($millPurchaseMaterial->pivot->input_cost) }} 円</div></td>
                                </tr>
                              @endforeach
                            </tbody>
                        </thead>
                      </table>
                    </td>
                    <td><a href="{{ route('millPolishedMaterials.edit', $millPolishedMaterial->id) }}" class="btn btn-primary"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="18" height="18" color="#000000"><defs><style>.cls-6374f8d9b67f094e4896c676-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs><path class="cls-6374f8d9b67f094e4896c676-1" d="M7.23,20.59l-4.78,1,1-4.78L17.89,2.29A2.69,2.69,0,0,1,19.8,1.5h0a2.7,2.7,0,0,1,2.7,2.7h0a2.69,2.69,0,0,1-.79,1.91Z"></path><line class="cls-6374f8d9b67f094e4896c676-1" x1="0.55" y1="22.5" x2="23.45" y2="22.5"></line><line class="cls-6374f8d9b67f094e4896c676-1" x1="19.64" y1="8.18" x2="15.82" y2="4.36"></line></svg></a></td>
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