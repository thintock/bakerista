<!DOCTYPE html>
<html lang="ja">
    @include('commons.header')
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
