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
                        <form action="{{ route('customerRelationCategories.store') }}" id="uploadForm" method="POST">
                            @csrf
                            <div class="form-control">
                                <input type="text" id="name" name="name" class="input input-bordered" placeholder="新規作成"  required autofocus>
                            </div>
                        </td>
                        <td>
                            <div class="form-control">
                                <select id="department" name="department" class="select select-bordered">
                                    <option value="">担当部署を選択</option>
                                    <option value="業務部">業務部</option>
                                    <option value="出荷部">出荷部</option>
                                    <option value="製造部">製造部</option>
                                    <option value="その他">その他</option>
                                </select>
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
                            <form action="{{ route('customerRelationCategories.update', $category->id) }}" id="uploadForm" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-control">
                                    <input type="text" name="name" value="{{ $category->name }}" class="input input-bordered" required>
                                </div>
                            </td>
                            <td>
                                <div class="form-control">
                                    <select id="department" name="department" class="select select-bordered">
                                        <option value="">担当部署を選択</option>
                                        <option value="業務部" {{ $category->department == '業務部' ? 'selected' : '' }}>業務部</option>
                                        <option value="出荷部" {{ $category->department == '出荷部' ? 'selected' : '' }}>出荷部</option>
                                        <option value="製造部" {{ $category->department == '製造部' ? 'selected' : '' }}>製造部</option>
                                        <option value="その他" {{ $category->department == 'その他' ? 'selected' : '' }}>その他</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-secondary">更新</button>
                            </form>
                            <form action="{{ route('customerRelationCategories.destroy', $category->id) }}" method="POST" class="inline">
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
