@extends('layouts.app')

@section('content')
<div class="py-6 sm:py-8 lg:py-12">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- text - start -->
    <div class="mb-10 md:mb-16">
      <h2 class="mb-4 text-center text-2xl font-bold md:mb-6 lg:text-3xl">ユーザー一覧</h2>

      <p class="mx-auto max-w-screen-md text-center md:text-lg">必ず自分のアカウントを作成して、使用する際は自身のユーザーアカウントでログインしていることを確認して使用してください。</p>
      @if(Auth::id() === 1)
          <!--管理者のみ表示-->
          <a href="{{ route('users.manage') }}" class="btn btn-primary">ユーザー管理</a>
      @endif
    </div>
    <!-- text - end -->
    
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4 lg:gap-8">
      <!-- person - start -->
    @if ($users->count() > 0)
              @foreach ($users as $user)
      <div class="flex flex-col items-center rounded-lg bg-base-200 p-4 lg:p-8">
        <div class="mb-2 h-24 w-24 overflow-hidden rounded-full bg-base-200 shadow-lg md:mb-4 md:h-32 md:w-32">
          <img src="{{ asset('images/bakema_gray.png') }}" loading="lazy" alt="prof" class="h-full w-full object-cover object-center" />
        </div>

        <div>
          <div class="text-center font-bold md:text-lg">{{ $user->name }}&nbsp;{{ $user->first_name }}</div>
          <p class="mb-3 text-center text-sm md:mb-4">{{ $user->email }}</p>
          <p class="mb-3 text-center text-sm md:mb-4">{{ $user->phone }}</p>
          <p class="mb-3 text-center text-sm md:mb-4">
            @if($user->is_approved)
                <span class="badge badge-primary">承認済み</span>
            @else
                <span class="badge badge-accent badge-outline">未承認</span>
            @endif
          </p>

          <!-- social - start -->
          <div class="flex justify-center">
            <div class="flex gap-4">
            @include('commons.user_edit_del_button')
            </div>
          </div>
          <!-- social - end -->
        </div>
      </div>
    @endforeach
      <!-- person - end -->
    </div>
  </div>
</div>
{{-- ページネーションのリンク --}}
{{ $users->links() }}
@else
<p>データがありません。</p>
@endif
@endsection