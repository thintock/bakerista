@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-4">取引先情報の登録</h1>
    <div class="w-full lg:w-1/2 mx-auto bg-base-100 shadow-xl p-6">
        <form action="{{ route('companies.store') }}" id="uploadForm" method="POST">
            @csrf

            <div class="form-control">
                <label class="label" for="name">
                    <span class="label-text">企業名<span class="text-info">＊必須</span></span>
                </label>
                <input type="text" id="name" name="name" class="input input-bordered" placeholder="◯◯◯◯◯株式会社" required>
            </div>
            
            <div class="form-control">
                <label class="label" for="postal_code">
                    <span class="label-text">郵便番号(ハイフンなし)</span>
                </label>
                <input type="text" id="postal_code" name="postal_code" class="input input-bordered" pattern="\d{7}" placeholder="1234567" onInput="formatPostalCode()">
            </div>

            <div class="form-control">
                <label class="label" for="address">
                    <span class="label-text">住所</span>
                </label>
                <input type="text" id="address" name="address" class="input input-bordered" placeholder="北海道室蘭市・・・">
            </div>

            <div class="form-control">
                <label class="label" for="phone_number">
                    <span class="label-text">電話番号(ハイフンなし)</span>
                </label>
                <input type="text" id="phone_number" name="phone_number" class="input input-bordered" pattern="\d{10,11}" placeholder="0123456789">
            </div>

            <div class="form-control">
                <label class="label" for="fax_number">
                    <span class="label-text">FAX番号(ハイフンなし)</span>
                </label>
                <input type="text" id="fax_number" name="fax_number" class="input input-bordered" pattern="\d{10,11}" placeholder="0123456789">
            </div>

            <div class="form-control">
                <label class="label" for="email">
                    <span class="label-text">Eメールアドレス</span>
                </label>
                <input type="email" id="email" name="email" class="input input-bordered" placeholder="XXX@XXX.XX">
            </div>

            <div class="form-control">
                <label class="label" for="order_url">
                    <span class="label-text">注文URL</span>
                </label>
                <input type="url" id="order_url" name="order_url" class="input input-bordered" placeholder="http://example.com">
            </div>

            <div class="form-control">
                <label class="label" for="how_to_order">
                    <span class="label-text">注文方法</span>
                </label>
                <select id="how_to_order" name="how_to_order" class="select select-bordered">
                    <option value="">選択してください</option>
                    <option value="WEB">WEB</option>
                    <option value="FAX">FAX</option>
                    <option value="メール">メール</option>
                    <option value="電話">電話</option>
                </select>
            </div>

            <div class="form-control">
                <label class="label" for="order_condition">
                    <span class="label-text">発注条件</span>
                </label>
                <textarea id="order_condition" name="order_condition" class="textarea textarea-bordered" placeholder="１０本ロット、100枚ロット、１回の注文で◯万円以下の場合送料◯円など。"></textarea>
            </div>

            <div class="form-control">
                <label class="label" for="staff_name">
                    <span class="label-text">担当者名</span>
                </label>
                <input type="text" id="staff_name" name="staff_name" class="input input-bordered">
            </div>

            <div class="form-control">
                <label class="label" for="staff_phone">
                    <span class="label-text">担当者電話番号(ハイフンなし)</span>
                </label>
                <input type="text" id="staff_phone" name="staff_phone" class="input input-bordered" pattern="\d{10,11}" placeholder="0123456789">
            </div>

            <div class="form-control mt-6">
                <button type="submit" class="btn btn-primary">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection
