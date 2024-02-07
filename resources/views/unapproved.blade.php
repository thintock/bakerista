@extends('layouts.auth')

@section('content')
    <section style="background-image: url('{{ asset('images/background.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 100vh;">
      <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
          <a href="{{ route('dashboard') }}" class="flex items-center mb-4 text-4xl text-white mb-5" style="font-family: 'Bodoni Moda Semi-Bold', serif;">
              bakerista  
          </a>
          <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
              <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                  <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-xl dark:text-white">
                      アカウントを登録しました。
                  </h1>
                  <p class="leading-tight tracking-tight text-gray-900 md:text-xl dark:text-white">システム使用開始のために管理者の承認を受けてください。</p>
                  
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                        <a class="link link-hover" href="#" onclick="event.preventDefault();this.closest('form').submit();" class="btn btn-accent">ログアウト</a>
                    </font>
              </div>
          </div>
      </div>
    </section>
@endsection
