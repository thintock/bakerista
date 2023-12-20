@if (Auth::id() ==$user->id)
  <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">修正</a>
  <form action="{{ route('users.destroy', $user->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？');">ユーザー削除</button>
  </form>
@endif