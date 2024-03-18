@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-4">
        <!-- 戻るボタン -->
        <a href="{{ route('millFlourProductions.index') }}" class="btn btn-secondary">
            ← 戻る
        </a>
        <h1 class="text-2xl font-semibold">製粉登録</h1>
        <div></div>
    </div>
    <form action="{{ route('millFlourProductions.store') }}" id="uploadForm" method="POST">
        <div class="lg:flex lg:gap-10 text-left">
            @csrf
            <!-- 左側のセクション -->
            <div class="lg:w-2/5">
                <!-- 製粉日付 -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="production_date">
                        製粉日付
                    </label>
                    <input class="input input-bordered w-full" id="production_date" name="production_date" type="date" value="{{ date('Y-m-d') }}" required>
                </div>
                <!-- 開始時間 -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="start_time">
                        開始時間
                    </label>
                    <input class="input input-bordered w-full" id="start_time" name="start_time" type="time">
                </div>
                
                <!-- 終了時間 -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="end_time">
                        終了時間
                    </label>
                    <input class="input input-bordered w-full" id="end_time" name="end_time" type="time">
                </div>
    
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="mill_machine_id">
                        製粉機
                    </label>
                    <select class="select select-bordered w-full" id="mill_machine_id" name="mill_machine_id">
                        <!-- 製粉機のデータがある場合、ここに<option>タグを挿入 -->
                        <option disabled selected>製粉機を選択してください</option>
                        @foreach ($millMachines as $millMachine)
                            <option value="{{ $millMachine->id }}">No.{{ $millMachine->machine_number }}-{{ $millMachine->machine_name }}</option>
                        @endforeach
                    </select>
                </div>
    
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="flour_weight">
                        製品小麦粉量 (kg)
                    </label>
                    <input class="input input-bordered w-full" id="flour_weight" name="flour_weight" value="0" type="number" step="0.01" required>
                </div>
    
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="bran_weight">
                        製品ふすま量 (kg)
                    </label>
                    <input class="input input-bordered w-full" id="bran_weight" name="bran_weight" value="0" type="number" step="0.01" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="remarks">
                        備考
                    </label>
                    <textarea class="textarea textarea-bordered w-full" id="remarks" name="remarks" rows="3"></textarea>
                </div>
    
            </div>
    
            <!-- 右側のセクション（使用原料登録など） -->
            <div class="lg:w-3/5">
                <div id="DetailsContainer" class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="polished_date">
                        使用精麦原料登録
                    </label>
                </div>
                <button id="addDetails" type="button" class="btn btn-primary mb-4">原料を追加</button>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">製粉情報を登録</button>
        @include('commons.detailInputs')
        <script>
            // 初期状態で1セットの原料選択セクションを追加
            addDetailsInputGroup();
        </script>
    </form>
</div>
@endsection
