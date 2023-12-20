<h2 class="mb-4">ユーザー詳細</h2>
<div class="card w-96 bg-base-100 shadow-xl">
  <figure><img src="{{ asset('images/bakema_gray.png') }}" alt="prof" /></figure>
  <div class="card-body">
    <h2 class="card-title">{{ $user->name }}&nbsp;{{ $user->first_name }}</h2>
    <p>電話番号：{{ $user->phone }}</p>
    <p>Eメール：{{ $user->email }}</p>
    <div class="card-actions justify-end">
      <button class="btn btn-primary">修正</button>
      <button class="btn btn-danger">ユーザー削除</button>
    </div>
  </div>
</div>