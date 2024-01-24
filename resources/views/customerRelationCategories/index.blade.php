@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">カテゴリ管理</h1>

    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <form action="{{ route('customerRelationCategories.store') }}" method="POST">
                            @csrf
                            <div class="form-control">
                                <input type="text" id="name" name="name" class="input input-bordered" placeholder="新規作成"  required autofocus>
                            </div>
                    </td>
                    <td>
                            <div class="form-control">
                                <button type="submit" class="btn btn-primary">登録</button>
                            </div>
                        </form>
                    </td>
                </tr>
                @foreach ($customerRelationCategories as $category)
                    <tr>
                        <td>
                            <form action="{{ route('customerRelationCategories.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-control">
                                    <input type="text" name="name" value="{{ $category->name }}" class="input input-bordered" required>
                                </div>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('customerRelationCategories.update', $category->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">更新</button>
                            </form>
                            <form action="{{ route('customerRelationCategories.destroy', $category->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error" onclick="return confirm('本当に削除しますか？');">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
