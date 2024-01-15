@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">精麦登録</h1>
    <form action="{{ route('millPolishedMaterials.store') }}" method="POST" class="lg:flex lg:gap-10">
        @csrf
         <!-- 左側のセクション -->
        <div class="lg:w-2/5">
                <!-- 精麦日付-->
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2" for="polished_date">
                    精麦日付
                </label>
                <input class="input input-bordered w-full" id="datePicker" name="polished_date" type="date" required>
            </div>

            <div class="flex flex-wrap mb-4">
                <div class="w-1/2 p-1">
                    <label class="block text-sm font-bold mb-2" for="total_output_weight">
                        精麦後重量(kg)
                    </label>
                    <input class="input input-bordered w-full" id="total_output_weight" name="total_output_weight" value="0" type="number" step="0.01" required>
                </div>
                <div class="w-1/2 p-1">
                    <label class="block text-sm font-bold mb-2" for="polished_retention">
                        精麦歩留(%)
                    </label>
                    <input class="input input-bordered w-full" id="polished_retention" name="polished_retention" value="0" type="number" step="0.1" nullable disabled>
                </div>
            </div>
            
            <div class="flex flex-wrap mb-4 border border-primary">
                
                <div class="w-1/3 p-1 bg-primary flex items-center justify-center">
                    <p class="text-base-200">
                        白度
                    </p>
                </div>
                
                <div class="w-1/3 p-1">
                    <label class="block text-sm font-bold mb-1" for="mill_whiteness_1">
                        1p
                    </label>
                    <input class="input input-bordered w-full" id="mill_whiteness_1" name="mill_whiteness_1" value="" type="number" step="0.1" nullable>
                </div>
                
                <div class="w-1/3 p-1">
                    <label class="block text-sm font-bold mb-1" for="mill_whiteness_2">
                        2p
                    </label>
                    <input class="input input-bordered w-full" id="mill_whiteness_2" name="mill_whiteness_2" value="" type="number" step="0.1" nullable>
                </div>
                
                <div class="w-1/3 p-1">
                    <label class="block text-sm font-bold mb-1" for="mill_whiteness_3">
                        3p
                    </label>
                    <input class="input input-bordered w-full" id="mill_whiteness_3" name="mill_whiteness_3" value="" type="number" step="0.1" nullable>
                </div>
                
                <div class="w-1/3 p-1">
                    <label class="block text-sm font-bold mb-1" for="mill_whiteness_4">
                        4p
                    </label>
                    <input class="input input-bordered w-full" id="mill_whiteness_4" name="mill_whiteness_4" value="" type="number" step="0.1" nullable>
                </div>
                
                <div class="w-1/3 p-1">
                    <label class="block text-sm font-bold mb-1" for="mill_whiteness_5">
                        5p
                    </label>
                    <input class="input input-bordered w-full" id="mill_whiteness_5" name="mill_whiteness_5" value="" type="number" step="0.1" nullable>
                </div>
                
                <div class="w-1/3 p-1">
                    <label class="block text-sm font-bold mb-1" for="mill_whiteness_6">
                        6p
                    </label>
                    <input class="input input-bordered w-full" id="mill_whiteness_6" name="mill_whiteness_6" value="" type="number" step="0.1" nullable>
                </div>
                
                <div class="w-1/3 p-1">
                    <label class="block text-sm font-bold mb-1" for="mill_whiteness_7">
                        7p
                    </label>
                    <input class="input input-bordered w-full" id="mill_whiteness_7" name="mill_whiteness_7" value="" type="number" step="0.1" nullable>
                </div>
                
                <div class="w-1/3 p-1">
                    <label class="block text-sm font-bold mb-1" for="mill_whiteness_8">
                        8p
                    </label>
                    <input class="input input-bordered w-full" id="mill_whiteness_8" name="mill_whiteness_8" value="" type="number" step="0.1" nullable>
                </div>
            </div>
            
        </div>

        <!-- 右側のセクション -->
        <div class="lg:w-3/5">
            <div id="materialsContainer" class="mb-4">
                <label class="block text-sm font-bold mb-2" for="polished_date">
                    使用原料登録
                </label>
                <!-- 初期のMaterial input templateがここに入る -->
            </div>
            <button id="addMaterial" type="button" class="btn btn-primary mb-4">原料を追加</button>
            <button class="btn btn-success" type="submit">登録</button>
        </div>

        @include('commons.materialInputs')
        <script>
        // 初期状態で1セットの原料選択セクションを追加
        addMaterialInputGroup();

        </script>
    </form>
</div>
@endsection