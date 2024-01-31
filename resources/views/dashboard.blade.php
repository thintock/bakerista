@extends('layouts.app')

@section('content')
    <div class="prose hero bg-base-200 mx-auto max-w-full rounded">
        <div class="hero-content text-center my-10">
            <div class="max-w-md mb-10">
                <h2></h2>
                {{-- ユーザ登録ページへのリンク --}}
                <a class="btn btn-primary btn-lg normal-case" href="{{ route('register') }}">新規登録</a>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4">
        <div class="stats shadow">

            {{-- 統計情報の表示 --}}
            <div class="stat">
                <div class="stat-title">今日の注文数</div>
                <div class="stat-value">123</div>
                <div class="stat-desc">昨日より20%増加</div>
            </div>

            <div class="stat">
                <div class="stat-title">在庫状況</div>
                <div class="stat-value">80%</div>
                <div class="stat-desc">20%は予備在庫</div>
            </div>

            <div class="stat">
                <div class="stat-title">今月の売上</div>
                <div class="stat-value">¥1,200,000</div>
                <div class="stat-desc">昨月と比較して5%増</div>
            </div>

        </div>

        {{-- その他の重要情報や機能へのリンク --}}
        <div class="grid grid-cols-3 gap-4 mt-4">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">製造計画</h2>
                    <p>最新の製造計画を確認し、計画を調整する。</p>
                    <div class="card-actions justify-end">
                        <button class="btn btn-primary">詳細</button>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">顧客対応</h2>
                    <p>顧客からの問い合わせやフィードバックをチェックする。</p>
                    <div class="card-actions justify-end">
                        <button class="btn btn-primary">詳細</button>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">在庫管理</h2>
                    <p>在庫状況を確認し、必要に応じて調達を計画する。</p>
                    <div class="card-actions justify-end">
                        <button class="btn btn-primary">詳細</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
