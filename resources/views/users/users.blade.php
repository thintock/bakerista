@if (isset($users))
    <div class="overflow-x-auto">
  <table class="table">
    <!-- head -->
    <thead>
      <tr>
        <th>ID</th>
        <th></th>
        <th>名前</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <!-- row 1 -->
      @foreach ($users as $user)
      <tr>
        <th>{{ $user->id }}</th>
        <td><img src="{{ asset('images/bakema_gray.png') }}" alt="prof" width="50px"></td>
        <td>{{ $user->name }}</td>
        <td><a class="link link-hover text-info" href="{{ route('users.show', $user->id) }}"><button class="btn btn-primary">詳細</button></a></td>
      </tr>
        @endforeach
    </tbody>
  </table>
</div>
    {{-- ページネーションのリンク --}}
    {{ $users->links() }}
@endif