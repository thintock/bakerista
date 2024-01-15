@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
      <div class="justify-center">
        <h2>製粉機マスター</h2>
        <p>製粉機は各機器ごとに管理されます。各製粉機には固有の名称や詳細な説明があります。</p>
      </div>
    <div class="flex justify-end mb-4">
        <a href="{{ route('millMachines.create') }}" class="btn btn-primary">新規製粉機登録</a>
    </div>

    @if ($millMachines->count() > 0)
    <div class="overflow-x-auto">
        <table class="table table-xs">
            <thead>
              <tr class="bg-base-200">
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
                <td><a href="{{ route('millMachines.edit', $millMachine->id) }}" class="btn btn-primary">編集</a></td>
              </tr>
              @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p>データがありません。</p>
    @endif
@endsection
