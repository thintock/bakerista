@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
      <div class="justify-center">
        <h1 class="text-2xl font-bold mb-4">取引先マスタ</h1>
        <p>原料や資材など取引のある会社情報を登録してください。</p>
      </div>
    <div class="flex justify-end mb-4">
        <a href="{{ route('companies.create') }}" class="btn btn-primary">新規取引先登録</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table table-xs bg-base-100">
            <thead>
              <tr>
                <th>会社名</th>
                <th>住所</th>
                <th>電話番号</th>
                <th>発注URL</th>
                <th>発注方法</th>
                <th>発注条件</th>
                <th></th>
              </tr>
            </thead>
    @if ($companies->count() > 0)
            <tbody>
              @foreach ($companies as $company)
              <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->address }}</td>
                <td><a href="TEL:{{ $company->phone_number }}">{{ $company->phone_number }}</a></td>
                <td><a href="{{ $company->order_url }}" target="_blank">{{ $company->order_url }}</a></td>
                <td>{{ $company->how_to_order }}</td>
                <td>{{ $company->order_condition }}</td>
                <td><a href="{{ route('companies.edit', $company->id) }}" class="btn btn-secondary">編集</a></td>
              </tr>
              @endforeach
            </tbody>
        </table>
    </div>
    {{ $companies->links() }} {{-- ページネーションリンク --}}
    @else
        </table>
    </div>
        <p>登録されている会社はありません。</p>    
    @endif
@endsection
