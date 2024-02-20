@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="w-full lg:w-1/2 mx-auto bg-base-100 shadow-xl p-6">
        <form action="{{ route('materials.update', $material->id) }}" id="uploadForm" class="mb-4" method="POST">
            @csrf
            @method('PUT')

            <div class="form-control">
                <label class="label" for="materials_code">
                    <span class="label-text">原材料コード<span class="text-info">（変更不可）</span></span>
                </label>
                <input type="text" id="materials_code" name="materials_code" value="{{ $material->materials_code }}" class="input input-bordered bg-base-200" required readonly>
            </div>

            <div class="form-control">
                <label class="label" for="materials_name">
                    <span class="label-text">原材料名<span class="text-info">＊必須</span></span>
                </label>
                <input type="text" id="materials_name" name="materials_name" value="{{ $material->materials_name }}" class="input input-bordered" required>
            </div>

            <div class="form-control">
                <label class="label" for="materials_purchaser">
                    <span class="label-text">仕入れ先名<span class="text-info">（変更不可）</span></span>
                </label>
                <input type="text" id="materials_purchaser" name="materials_purchaser" value="{{ $material->materials_purchaser }}" class="input input-bordered bg-base-200" required readonly>
            </div>

            <div class="form-control">
                <label class="label" for="materials_producer_name">
                    <span class="label-text">生産者名<span class="text-info">（変更不可）</span></span>
                </label>
                <input type="text" id="materials_producer_name" name="materials_producer_name" value="{{ $material->materials_producer_name }}" class="input input-bordered bg-base-200" readonly>
            </div>
            <div class="flex mt-6">
                <div class="form-control w-1/2 mr-3">
                    <button type="submit" class="btn btn-secondary">更新</button>
                    
                </div>
                </form>
                <form action="{{ route('materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="w-1/2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning w-full">削除</button>
                </form>
            </div>
        </div>
    </div>
@endsection
