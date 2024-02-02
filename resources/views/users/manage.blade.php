@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">ユーザー管理</h1>

    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名前</th>
                    <th>メール</th>
                    <th>電話番号</th>
                    <th>作成日</th>
                    <th>更新日</th>
                    <th>承認ステータス</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }} {{ $user->first_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->updated_at }}</td>
                    <td>
                        <form action="{{ route('users.updateStatus', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="checkbox" name="is_approved" class="toggle toggle-accent" {{ $user->is_approved ? 'checked' : '' }}
                                onchange="confirmStatusChange(this.form)">
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-error" onclick="return confirmDelete()">
                                削除
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="my-4">
        {{ $users->links() }}
    </div>
</div>

<script>
    function confirmStatusChange(form) {
        if (confirm('承認ステータスを変更しますか？')) {
            form.submit();
        }
    }

    function confirmDelete() {
        return confirm('本当に削除しますか？');
    }
</script>
@endsection
