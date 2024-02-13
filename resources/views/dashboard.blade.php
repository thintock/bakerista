@extends('layouts.app')

@section('content')
    <div class="hero min-h-screen" style="background-image: url({{ asset('images/background.jpg') }});">
      <div class="hero-overlay bg-opacity-60"></div>
      <div class="hero-content text-center text-neutral-content">
        <div class="max-w-md">
          <h1 class="mb-5 text-5xl font-bold">Hello there</h1>
          <p class="mb-5">ベーカリスタ業務システムへようこそ</p>
          <p class="mb-5">ログインしているユーザーによって操作履歴をとっています。システムを操作する際は自分のユーザーでログインし直すことを心がけてください。</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="link link-hover" href="#" onclick="event.preventDefault();this.closest('form').submit();" class="btn btn-accent">ログアウト</a>
            </font>
        </div>
      </div>
    </div>
@endsection