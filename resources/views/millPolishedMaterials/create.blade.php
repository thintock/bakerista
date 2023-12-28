@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">精麦登録</h1>
    <form action="{{ route('millPolishedMaterials.store') }}" method="POST" class="lg:flex lg:gap-10">
        @csrf

        <div class="lg:w-1/4"> <!-- 左側のセクション -->
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2" for="polished_date">
                    精麦日付
                </label>
                <input class="input input-bordered w-full" id="datePicker" name="polished_date" type="date" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2" for="total_output_weight">
                    精麦後の総重量(kg)
                </label>
                <input class="input input-bordered w-full" id="total_output_weight" name="total_output_weight" value="0" type="number" step="1" required>
            </div>
        </div>

        <!-- 右側のセクション -->
        <div class="lg:w-3/4">
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