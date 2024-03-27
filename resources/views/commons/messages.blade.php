{{-- 成功メッセージ --}}
@if (session('success'))
    <div class="alert bg-secondary text-secondary-content mb-4 no-print">
        {{ session('success') }}
    </div>
@endif

{{-- エラーメッセージ --}}
@if (session('error'))
    <div class="alert bg-accent text-accent-content mb-4 no-print">
        {{ session('error') }}
    </div>
@endif

{{-- バリデーションエラー --}}
@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="alert bg-accent text-accent-content mb-4 no-print">
            <div>
                <!-- アイコンなど -->
                {{ $error }}
            </div>
        </div>
    @endforeach
@endif