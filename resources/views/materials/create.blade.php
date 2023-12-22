@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="w-full lg:w-1/2 mx-auto bg-base-100 shadow-xl p-6">
        <form action="{{ route('materials.store') }}" method="POST">
            @csrf

            <div class="form-control">
                <label class="label" for="materials_code">
                    <span class="label-text">原材料コード（６桁 ネクストエンジンと連動）<span class="text-accent">＊必須</span></span>
                </label>
                <input type="text" id="materials_code" name="materials_code" class="input input-bordered" required autofocus>
            </div>

            <div class="form-control">
                <label class="label" for="materials_name">
                    <span class="label-text">原材料名（栽培方法＋小麦品種名）<span class="text-accent">＊必須</span></span>
                </label>
                <input type="text" id="materials_name" name="materials_name" class="input input-bordered" required>
            </div>

            <div class="form-control">
                <label class="label" for="materials_purchaser">
                    <span class="label-text">仕入れ先名</span>
                </label>
                <input type="text" id="materials_purchaser" name="materials_purchaser" class="input input-bordered">
            </div>

            <div class="form-control">
                <label class="label" for="materials_producer_name">
                    <span class="label-text">生産者名</span>
                </label>
                <input type="text" id="materials_producer_name" name="materials_producer_name" class="input input-bordered">
            </div>

            <div class="form-control mt-6">
                <button type="submit" class="btn btn-primary">原材料登録</button>
            </div>
        </form>
    </div>
</div>
@endsection
