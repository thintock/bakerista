@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="w-full lg:w-1/2 mx-auto bg-base-100 shadow-xl p-6">
        <form action="{{ route('millPurchaseMaterials.store') }}" id="uploadForm" method="POST">
            @csrf

            <div class="form-control">
                <label class="label" for="arrival_date">
                    <span class="label-text">入荷日<span class="text-info">(YYYY MM DD)</span></span>
                </label>
                <input type="text" id="datePicker" name="arrival_date" value="{{ date("Ymd"); }}" class="input input-bordered" required>
            </div>
            
            {{-- 原材料の選択 --}}
            <div class="form-control">
                <label class="label" for="materials_id">
                    <span class="label-text">原材料</span>
                </label>
                <select id="materials_id" name="materials_id" class="select select-search select-bordered w-full max-w-xs">
                    @foreach ($millPurchaseMaterials as $millPurchaseMaterials)
                        <option value="{{ $millPurchaseMaterials->id }}">
                            {{ $millPurchaseMaterials->materials_code }} - {{ $millPurchaseMaterials->materials_name }} ({{ $millPurchaseMaterials->materials_purchaser }} - {{ $millPurchaseMaterials->materials_producer_name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-control">
                <label class="label" for="year_of_production">
                    <span class="label-text">生産年度（２桁）<span class="text-info">＊必須</span></span>
                </label>
                <input type="text" id="year_of_production" name="year_of_production" value="@include('commons.year_production_swich')" class="input input-bordered" required>
            </div>

            <div class="form-control">
                <label class="label" for="flecon_number">
                    <span class="label-text">フレコン番号（３桁）<span class="text-info">＊必須</span></span>
                </label>
                <input type="text" id="flecon_number" name="flecon_number" value="001" class="input input-bordered" required>
            </div>

            <div class="form-control">
                <label class="label" for="total_amount">
                    <span class="label-text">入荷量（kg）</span>
                </label>
                <input type="number" id="total_amount" name="total_amount" value="1000" class="input input-bordered">
            </div>
            <div class="form-control">
                <label class="label" for="cost">
                    <span class="label-text">仕入価格（総額　商品価格＋運賃＋他経費）</span>
                </label>
                <input type="number" id="cost" name="cost" value="0" class="input input-bordered">
            </div>

            <div class="form-control mt-6">
                <button type="submit" class="btn btn-primary">入荷登録</button>
            </div>
        </form>
    </div>
</div>
@endsection