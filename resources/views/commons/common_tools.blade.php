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

<!-- ローディングアニメーションコンテナ -->
<div id="loading" class="hidden fixed top-0 left-0 z-50 w-full h-full flex items-center justify-center" style="background: rgba(0, 0, 0, 0.5);">
    <span class="loading loading-spinner text-base-100 loading-lg mr-3"></span>
    <h2 class="text-center text-white text-xl font-semibold">アップロード中...</h2>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function() {
            var loading = document.getElementById('loading');
            if (loading) {
                // ローディングアニメーションを表示
                loading.classList.remove('hidden');
            }
        });
    }
});
</script>