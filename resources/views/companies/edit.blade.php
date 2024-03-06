@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <!-- 戻るボタン -->
        <a href="{{ route('companies.index') }}" class="btn btn-secondary">
            ← 戻る
        </a>
        <h1 class="text-2xl font-bold">取引先情報編集</h1>
        <!-- 新規作成ボタン -->
        <a href="{{ route('companies.create') }}" class="btn btn-primary">
            新規作成
        </a>
    </div>
    <div class="w-full lg:w-1/2 mx-auto bg-base-100 shadow-xl p-6">
        <form action="{{ route('companies.update', $company->id) }}" id="uploadForm" method="POST">
            @csrf
            @method('PUT')

            <div class="form-control mb-4">
                <label for="name" class="label">企業名</label>
                <input type="text" id="name" name="name" value="{{ $company->name }}" class="input input-bordered" placeholder="◯◯◯◯◯株式会社" required>
            </div>

            <div class="form-control mb-4">
                <label for="postal_code" class="label">郵便番号(ハイフンなし)</label>
                <input type="text" id="postal_code" name="postal_code" value="{{ $company->postal_code }}" pattern="\d{7}" class="input input-bordered" placeholder="1234567">
            </div>

            <div class="form-control mb-4">
                <label for="address" class="label">住所</label>
                <input type="text" id="address" name="address" value="{{ $company->address }}" class="input input-bordered" placeholder="北海道室蘭市・・・">
            </div>

            <div class="form-control mb-4">
                <label for="phone_number" class="label">電話番号(ハイフンなし)</label>
                <input type="tel" id="phone_number" name="phone_number" value="{{ $company->phone_number }}" class="input input-bordered" pattern="\d{10,11}" placeholder="0123456789">
            </div>

            <div class="form-control mb-4">
                <label for="fax_number" class="label">FAX番号(ハイフンなし)</label>
                <input type="tel" id="fax_number" name="fax_number" value="{{ $company->fax_number }}" class="input input-bordered" pattern="\d{10,11}" placeholder="0123456789">
            </div>

            <div class="form-control mb-4">
                <label for="email" class="label">Eメールアドレス</label>
                <input type="email" id="email" name="email" value="{{ $company->email }}" class="input input-bordered" placeholder="XXX@XXX.XX">
            </div>

            <div class="form-control mb-4">
                <label for="order_url" class="label">注文URL</label>
                <input type="url" id="order_url" name="order_url" value="{{ $company->order_url }}" class="input input-bordered" placeholder="http://example.com">
            </div>

            <div class="form-control mb-4">
                <label for="how_to_order" class="label">注文方法</label>
                <select id="how_to_order" name="how_to_order" class="select select-bordered">
                    <option value="">選択してください</option>
                    <option value="WEB" @if ($company->how_to_order == 'WEB') selected @endif>WEB</option>
                    <option value="FAX" @if ($company->how_to_order == 'FAX') selected @endif>FAX</option>
                    <option value="メール" @if ($company->how_to_order == 'メール') selected @endif>メール</option>
                    <option value="電話" @if ($company->how_to_order == '電話') selected @endif>電話</option>
                </select>
            </div>


            <div class="form-control mb-4">
                <label for="order_condition" class="label">発注条件</label>
                <textarea id="order_condition" name="order_condition" class="textarea textarea-bordered" placeholder="１０本ロット、100枚ロット、１回の注文で◯万円以下の場合送料◯円など。">{{ $company->order_condition }}</textarea>
            </div>

            <div class="form-control mb-4">
                <label for="staff_name" class="label">担当者名</label>
                <input type="text" id="staff_name" name="staff_name" value="{{ $company->staff_name }}" class="input input-bordered">
            </div>

            <div class="form-control mb-4">
                <label for="staff_phone" class="label">担当者電話番号(ハイフンなし)</label>
                <input type="tel" id="staff_phone" name="staff_phone" value="{{ $company->staff_phone }}" class="input input-bordered" pattern="\d{10,11}" placeholder="0123456789">
            </div>
            <div class="flex mt-6">
                <div class="form-control w-1/2 mr-3">
                    <button type="submit" class="btn btn-primary">更新</button>
                </div>
            </form>
            <form action="{{ route('companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="w-1/2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-warning w-full">取引先情報を削除</button>
            </form>
        </div>
    </div>
</div>
@endsection
