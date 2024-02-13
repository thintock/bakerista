<h2 class="mb-4">ユーザー詳細</h2>
<div class="card w-96 bg-base-100 shadow-xl">
  <figure><img src="{{ asset('images/bakema_gray.png') }}" alt="prof" /></figure>
  <div class="card-body">
    <h2 class="card-title">{{ $user->name }}&nbsp;{{ $user->first_name }}</h2>
    <p>電話番号：{{ $user->phone }}</p>
    <p>Eメール：{{ $user->email }}</p>
    <p>
      @if($user->is_approved)
          <span class="badge badge-secondary">承認済み</span>
      @else
          <span class="badge badge-warning badge-outline">未承認</span><span>※管理者に連絡してください。</span>
      @endif
    </p>
    <div class="card-actions justify-end">
      @include('commons.user_edit_del_button')
    </div>
  </div>
</div>