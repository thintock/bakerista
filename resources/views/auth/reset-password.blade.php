@extends('layouts.auth')

@section('content')
    <section style="background-image: url('{{ asset('images/background.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 100vh;">
      <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
          <a href="{{ route('dashboard') }}" class="flex items-center mb-9 text-4xl text-white mb-5"><img src="{{ asset('images/bakerista_log_beige_200px.png') }}" alt="bakerista" width="175px"></a>
          <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
              <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                  <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                      パスワードリセット
                  </h1>
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                  <form method="POST" class="space-y-4 md:space-y-6" action="{{ route('password.store') }}">
                    @csrf
                      <div>
                          <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">登録済みのEメールアドレス</label>
                          <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required autofocus autocomplete="username">
                          <x-input-error :messages="$errors->get('email')" class="mt-2" />
                      </div>
                      <div>
                          <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">新しいパスワード</label>
                          <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required autocomplete="new-password" />
                          <x-input-error :messages="$errors->get('password')" class="mt-2" />
                      </div>
                      <div>
                          <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">新しいパスワード（確認用）</label>
                          <input type="password" id="password_confirmation" name="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required autocomplete="new-password" />
                          <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                      </div>
                      <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                      <button type="submit" class="btn btn-secondary w-full">パスワードをリセット</button>
                  </form>
              </div>
          </div>
      </div>
    </section>
@endsection
