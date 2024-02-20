@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="w-full lg:w-1/2 mx-auto bg-base-100 shadow-xl p-6">
        <form action="{{ route('materials.store') }}" id="uploadForm" method="POST">
            @csrf

            <div class="form-control">
                <label class="label" for="materials_code">
                    <span class="label-text">原材料コード（６桁 ネクストエンジンと連動）<span class="text-accent">＊必須</span></span>
                </label>
                <div class="flex items-center">
                    <input type="text" id="materials_code" name="materials_code" class="w-full input input-bordered" placeholder="123456" oninput="updateLengthDisplay()" required autofocus>
                    <span id="checkmark" style="display:none; margin-left: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                </div>
                <small>現在の入力桁数: <span id="input_length">0</span>桁</small>
            </div>
            <script>
                        function updateLengthDisplay() {
                            var input = document.getElementById('materials_code');
                            var inputLength = input.value.length;
                            var checkmark = document.getElementById('checkmark');
                        
                            document.getElementById('input_length').textContent = inputLength;
                        
                            // 入力桁数が13桁の場合にチェックマークを表示
                            if(inputLength === 6) {
                                checkmark.style.display = "inline";
                            } else {
                                checkmark.style.display = "none";
                            }
                        }
                    </script>

            <div class="form-control">
                <label class="label" for="materials_name">
                    <span class="label-text">原材料名（栽培方法＋小麦品種名）<span class="text-accent">＊必須</span></span>
                </label>
                <input type="text" id="materials_name" name="materials_name" class="input input-bordered" required>
            </div>

            <div class="form-control">
                <label class="label" for="materials_purchaser">
                    <span class="label-text">仕入れ先名<span class="text-accent">＊必須</span></span>
                </label>
                <input type="text" id="materials_purchaser" name="materials_purchaser" class="input input-bordered" required>
            </div>

            <div class="form-control">
                <label class="label" for="materials_producer_name">
                    <span class="label-text">生産者名<span class="text-accent">＊必須</span></span>
                </label>
                <input type="text" id="materials_producer_name" name="materials_producer_name" class="input input-bordered" required>
            </div>

            <div class="form-control mt-6">
                <button type="submit" class="btn btn-primary">原材料登録</button>
            </div>
        </form>
    </div>
</div>
@endsection