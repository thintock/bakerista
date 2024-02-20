<!DOCTYPE html>
<html lang="ja">
    @include('commons.header')
    <body>
        <div class="md:grid md:grid-cols-12 overflow-hidden h-screen">
            <!-- レフトナビゲーション -->
            <aside class="md:col-span-3 lg:col-span-2 text-center bg-base-300">
                @include('commons.leftnav')
            </aside>
            {{--ライトコンテンツ--}}
            <div class="md:col-span-9 lg:col-span-10 text-center">
        
                {{--トップナビゲーションバー--}}
                @include('commons.navbar')
                <div class="overflow-y-auto bg-base-200 p-6" style="height: calc(100vh - 70px);">
                    
                    {{--メインコンテンツ --}}
                    @include('commons.messages')
                
                    @yield('content')
                </div>
            </div>
        </div>
        
        @include('commons.common_tools')
    </body>
</html>
