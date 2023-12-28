@extends('layouts.app')

@section('content')
    {{--ユーザ一覧--}}
    <div class="container mx-auto px-4 py-6">
      <div class="justify-center">
        <h2>原材料マスター</h2>
        <p>原材料は品種名/仕入れ先/生産者/ごとに作成します。<br>
        価格や生産年度などは変動するのでここでは登録せずに入荷登録の際に登録します。</p>
      </div>
    <div class="flex justify-end mb-4">
        <a href="{{ route('materials.create') }}" class="btn btn-primary">新規原材料登録</a>
    </div>

    @if ($materials->count() > 0)
    <div class="overflow-x-auto">
        <table class="table table-xs">
        <!-- head -->
            <thead>
              <tr class="bg-base-200">
                <th>システムID</th>
                <th>原材料コード</th>
                <th>原材料名</th>
                <th>仕入れ先名</th>
                <th>生産者名</th>
                <th>作成者</th>
                <th>作成日</th>
                <th>最終更新日</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <!-- row 1 -->
              @foreach ($materials as $material)
              <tr>
                <th>{{ $material->id }}</th>
                <td>{{ $material->materials_code }}</td>
                <td>{{ $material->materials_name }}</td>
                <td>{{ $material->materials_purchaser }}</td>
                <td>{{ $material->materials_producer_name }}</td>
                <td>{{ $material->user->name }}&nbsp;{{ $material->user->first_name }}</td>
                <td>{{ $material->created_at->format('Y年m月d日 H時i分') }}</td>
                <td>{{ $material->updated_at->format('Y年m月d日 H時i分') }}</td>
                <td><a href="{{ route('materials.edit', $material->id) }}" class="btn btn-primary"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="18" height="18" color="#000000"><defs><style>.cls-6374f8d9b67f094e4896c676-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs><path class="cls-6374f8d9b67f094e4896c676-1" d="M7.23,20.59l-4.78,1,1-4.78L17.89,2.29A2.69,2.69,0,0,1,19.8,1.5h0a2.7,2.7,0,0,1,2.7,2.7h0a2.69,2.69,0,0,1-.79,1.91Z"></path><line class="cls-6374f8d9b67f094e4896c676-1" x1="0.55" y1="22.5" x2="23.45" y2="22.5"></line><line class="cls-6374f8d9b67f094e4896c676-1" x1="19.64" y1="8.18" x2="15.82" y2="4.36"></line></svg></a></td>
              </tr>
            @endforeach
            </tbody>
        </table>
    </div>
            {{-- ページネーションのリンク --}}
            {{ $materials->links() }}
            @else
            <p>データがありません。</p>
            @endif
@endsection