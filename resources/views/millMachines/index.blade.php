@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
      <div class="justify-center">
        <h1 class="text-2xl font-bold mb-4">製粉機マスター</h1>
        <p>製粉機は各機器ごとに管理されます。各製粉機には固有の名称や詳細な説明があります。</p>
      </div>
    <div class="flex justify-end mb-4">
        <a href="{{ route('millMachines.create') }}" class="btn btn-primary">新規製粉機登録</a>
    </div>

    @if ($millMachines->count() > 0)
    <div class="overflow-x-auto">
        <table class="table table-xs bg-base-100">
            <thead>
              <tr>
                <th>番号</th>
                <th>製粉機名</th>
                <th>説明</th>
                <th>作成日</th>
                <th>最終更新日</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($millMachines as $millMachine)
              <tr>
                <th>{{ $millMachine->machine_number }}</th>
                <td>{{ $millMachine->machine_name }}</td>
                <td>{{ $millMachine->description }}</td>
                <td>{{ $millMachine->created_at->format('Y年m月d日 H時i分') }}</td>
                <td>{{ $millMachine->updated_at->format('Y年m月d日 H時i分') }}</td>
                <td><a href="{{ route('millMachines.edit', $millMachine->id) }}" class="btn btn-secondary"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="18" height="18" color="#000000"><defs><style>.cls-6374f8d9b67f094e4896c676-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs><path class="cls-6374f8d9b67f094e4896c676-1" d="M7.23,20.59l-4.78,1,1-4.78L17.89,2.29A2.69,2.69,0,0,1,19.8,1.5h0a2.7,2.7,0,0,1,2.7,2.7h0a2.69,2.69,0,0,1-.79,1.91Z"></path><line class="cls-6374f8d9b67f094e4896c676-1" x1="0.55" y1="22.5" x2="23.45" y2="22.5"></line><line class="cls-6374f8d9b67f094e4896c676-1" x1="19.64" y1="8.18" x2="15.82" y2="4.36"></line></svg></a></td>
              </tr>
              @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p>データがありません。</p>
    @endif
@endsection
