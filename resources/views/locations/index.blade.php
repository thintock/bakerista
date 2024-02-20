@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">ロケーション管理</h1>

    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <form action="{{ route('locations.store') }}" method="POST" id="uploadForm">
                            @csrf
                            <div class="form-control">
                                <input type="number" id="location_code" name="location_code" value="100" min="100" max="999" step="1" class="input input-bordered" placeholder="ロケーションコード(3桁)"  required autofocus>
                            </div>
                        </td>
                        <td>
                            <div class="form-control">
                                <input type="text" id="location_name" name="location_name" class="input input-bordered" placeholder="ロケーション名称" required>
                            </div>
                        </td>
                        <td>
                            <div class="form-control">
                                <button type="submit" class="btn btn-primary">登録</button>
                            </div>
                        </form>
                    </td>
                </tr>
                @foreach ($locations as $location)
                <tr>
                    <form action="{{ route('locations.update', $location->id) }}" id="uploadForm" method="POST">
                        @csrf
                        @method('PUT')
                        <td>
                            <div class="form-control">
                                <input type="number" name="location_code"  name="location_code" value="{{ $location->location_code }}" min="100" max="999" step="1" class="input input-bordered"  required>
                            </div>
                        </td>
                        <td>
                            <div class="form-control">
                                <input type="text" id="location_name" name="location_name" class="input input-bordered" value="{{ $location->location_name }}" required>
                            </div>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-secondary">更新</button>
                        
                    </form>
                    
                        <form action="{{ route('locations.destroy', $location->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning" onclick="return confirm('本当に削除しますか？');">削除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
