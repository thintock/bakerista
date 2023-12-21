<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @include('commons.theme_controller')
    </head>
    <body>
        
        <div class="md:grid md:grid-cols-12 md:gap-4 h-screen">
            <!-- レフトナビゲーション -->
            <div class="md:col-span-3 lg:col-span-2 text-center shadow-lg bg-base-200">
                @include('commons.leftnav')
            </div>
            {{--ライトコンテンツ--}}
            <div class="md:col-span-9 lg:col-span-10 text-center">
        
                {{--トップナビゲーションバー--}}
                @include('commons.navbar')
                <div class="m-6">
                    {{--メインコンテンツ --}}
                    @include('commons.messages')
                
                    @yield('content')
                </div>
            </div>
        </div>
    </body>
</html>
