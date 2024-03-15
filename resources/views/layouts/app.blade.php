<!DOCTYPE html>
<html lang="ja">
    @include('commons.header')
    <body>
        <div class="md:grid md:grid-cols-12 overflow-hidden h-screen">
            <!--スマホナビゲーション-->
            <div class="md:hidden flex items-center justify-between bg-base-300 p-3">
                <div class="flex items-center justify-left no-print">
                    <h1><a href="{{ route('home') }}"><img src="{{ asset('images/bakerista_log_beige_200px.png') }}" alt="bakerista" width="120px"></a></h1>
                </div>
                <div class=" no-print">
                    <p class="flex text-base-100 text-xs">
                        @if (Auth::check())
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            &nbsp;{{ Auth::user()->name }} {{ Auth::user()->first_name }}
                        @else
                            Guest
                        @endif
                    </p>
                </div>
                <button class="text-base-100" id="menuButton">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
            <!-- PCレフトナビゲーション -->
            <aside class="hidden md:block md:col-span-3 lg:col-span-2 bg-base-300 no-print" id="menu">
                @include('commons.leftnav')
            </aside>
            {{--ライトコンテンツ--}}
            <div class="md:col-span-9 lg:col-span-10">
        
                {{--トップナビゲーションバースマホ非表示--}}
                <div class="hidden md:block no-print">
                    @include('commons.navbar')
                </div>
                {{--PCメインコンテンツ表示--}}
                <div class="content-height overflow-y-auto bg-base-200 p-6">
                    @include('commons.messages')
                    @yield('content')
                </div>
            </div>
        </div>
        
        @include('commons.common_tools')
    </body>
</html>
