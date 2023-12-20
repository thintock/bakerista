<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    </head>
    <body class="theme-light">
        <div class="flex h-screen">
            <!-- Sidebar -->
            <div class="flex flex-col w-64 shadow-lg">
                <div class="flex items-center justify-center h-20">
                    <h1><a href="{{ route('home') }}"><img src="{{ asset('images/bakerista_logo_200px.png') }}" alt="bakerista" width="175px"></a></h1>
                </div>
                @include('commons.leftnav')
            </div>
            {{--ライトコンテンツ--}}
            <div class="flex-1 flex flex-col overflow-hidden">
        
                {{--ナビゲーションバー--}}
                @include('commons.navbar')
                <div class="m-6">
                    {{--メインコンテンツ --}}
                    @include('commons.error_messages')
                
                    @yield('content')
                </div>
            </div>
        </div>
    </body>
</html>
