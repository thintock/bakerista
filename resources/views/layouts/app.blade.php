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
        <!--検索可能なセレクトボックス-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2/dist/css/select2.min.css" rel="stylesheet" />
        <!--日付ピッカー用-->
        <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    </head>
    <body>
        <div class="md:grid md:grid-cols-12 md:gap-4 h-screen">
            <!-- レフトナビゲーション -->
            <aside class="md:col-span-3 lg:col-span-2 text-center shadow-lg bg-base-200">
                @include('commons.leftnav')
            </aside>
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
        <!--検索可能なセレクトボックス-->
        <script src="https://cdn.jsdelivr.net/npm/select2/dist/js/select2.min.js"></script>
        <script>
        $(document).ready(function() {
            $('.select-search').select2({width:'100%'});
        });
        //日付ピッカー
        flatpickr("#datePicker", {
            altInput: true,
            altFormat: "Y年m月d日", // 表示上の日付形式
            dateFormat : 'Y-m-d', // 20210524の形式で表示
            defaultDate: "{{ $millPurchaseMaterial->arrival_date ?? 'today' }}"
        });
        </script>
    </body>
</html>
