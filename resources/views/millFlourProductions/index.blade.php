@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('millFlourProductions.create') }}" class="btn btn-primary">新規製粉登録</a>
        </div>

        @if ($productions->count() > 0)
            <div class="overflow-x-auto">
                <table class="table table-xs">
                    <thead>
                        <tr class="bg-base-200">
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
                            <th>製粉歩留</th>
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
                                    <div class="badge badge-primary mb-1">No.{{ $production->millMachine->machine_number }}</div>
                                    <p>{{ $production->millMachine->machine_name }}</p>
                                </td>
                                <td>{{ $production->milling_retention }} %</td>
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
                                                <tr>
                                                    <td>{{ $polishedMaterial->polished_lot_number }}</td>
                                                    <td>{{ $polishedMaterial->pivot->input_weight }} kg</td>
                                                    <td>{{ number_format($polishedMaterial->pivot->input_cost) }} 円</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <a href="{{ route('millFlourProductions.edit', $production->id) }}" class="btn btn-primary"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="18" height="18" color="#000000"><defs><style>.cls-6374f8d9b67f094e4896c676-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs><path class="cls-6374f8d9b67f094e4896c676-1" d="M7.23,20.59l-4.78,1,1-4.78L17.89,2.29A2.69,2.69,0,0,1,19.8,1.5h0a2.7,2.7,0,0,1,2.7,2.7h0a2.69,2.69,0,0,1-.79,1.91Z"></path><line class="cls-6374f8d9b67f094e4896c676-1" x1="0.55" y1="22.5" x2="23.45" y2="22.5"></line><line class="cls-6374f8d9b67f094e4896c676-1" x1="19.64" y1="8.18" x2="15.82" y2="4.36"></line></svg></a>
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
