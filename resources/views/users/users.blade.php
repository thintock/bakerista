@if (isset($users))
    <div class="overflow-x-auto">
  <table class="table">
    <!-- head -->
    <thead>
      <tr>
        <th>ID</th>
        <th></th>
        <th>姓</th>
        <th>名</th>
        <th>電話番号</th>
        <th>Eメールアドレス</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <!-- row 1 -->
      @foreach ($users as $user)
      <tr @class(['bg-base-200' => Auth::id() == $user->id])>
        <th>{{ $user->id }}</th>
        <td><img src="{{ asset('images/bakema_gray.png') }}" alt="prof" width="50px"></td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->first_name }}</td>
        <td>{{ $user->phone }}</td>
        <td>{{ $user->email }}</td>
        <td><a class="link link-hover text-info" href="{{ route('users.show', $user->id) }}"><button class="btn btn-primary">詳細</button></a></td>
      </tr>
        @endforeach
    </tbody>
  </table>
</div>
    {{-- ページネーションのリンク --}}
    {{ $users->links() }}
@endif