@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">製粉生産編集</h1>
        <form action="{{ route('millFlourProductions.update', $millFlourProduction->id) }}" method="POST">
            <div class="lg:flex lg:gap-10">
                @csrf
                @method('PUT')
                <!-- 左側のセクション -->
                <div class="lg:w-2/5">
                    <!-- 製粉日 -->
                    <div class="mb-4">
                        <label for="production_date" class="block text-sm font-bold mb-2">製粉日</label>
                        <input type="date" id="production_date" name="production_date" value="{{ $millFlourProduction->production_date->format('Y-m-d') }}" class="input input-bordered w-full bg-base-200" required readonly>
                    </div>
            
                    <!-- 製粉機の選択 -->
                    <div class="mb-4">
                        <label for="mill_machine_id" class="block text-sm font-bold mb-2">製粉機</label>
                        <div class="w-full">No.{{ $millFlourProduction->millMachine->machine_number}}-{{ $millFlourProduction->millMachine->machine_name }}</div>
                    </div>
                    
                    <!-- 開始時間 -->
                    <div class="mb-4">
                        <label for="start_time" class="block text-sm font-bold mb-2">開始時間</label>
                        <input type="time" id="start_time" name="start_time" value="{{ $millFlourProduction->start_time }}" class="input input-bordered w-full">
                    </div>
                    
                    <!-- 終了時間 -->
                    <div class="mb-4">
                        <label for="end_time" class="block text-sm font-bold mb-2">終了時間</label>
                        <input type="time" id="end_time" name="end_time" value="{{ $millFlourProduction->end_time }}" class="input input-bordered w-full">
                    </div>
                    <!-- 製品小麦粉量 -->
                    <div class="mb-4">
                        <label for="flour_weight" class="block text-sm font-bold mb-2">製品小麦粉量（kg）</label>
                        <input type="number" id="flour_weight" name="flour_weight" value="{{ $millFlourProduction->flour_weight }}" step="0.01" class="input input-bordered w-full">
                    </div>
                    <!-- 製品ふすま量 -->
                    <div class="mb-4">
                        <label for="bran_weight" class="block text-sm font-bold mb-2">製品ふすま量（kg）</label>
                        <input type="number" id="bran_weight" name="bran_weight" value="{{ $millFlourProduction->bran_weight }}" step="0.01" class="input input-bordered w-full">
                    </div>
                    
                    <!-- 備考 -->
                    <div class="mb-4">
                        <label for="remarks" class="block text-sm font-bold mb-2">備考</label>
                        <textarea class="textarea textarea-bordered w-full" id="remarks" name="remarks" rows="3">{{ $millFlourProduction->remarks }}</textarea>
                    </div>
        
                </div>
                <!-- 右側のセクション -->
                <div class="lg:w-3/5">
                    <div id="DetailsContainer" class="mb-4">
                        <label class="block text-xm font-bold mb-2" for="polished_date">
                            使用精麦原料
                        </label>
                        @foreach ($millFlourProduction->millPolishedMaterials as $material)
                        <div class="flex items-center gap-2 mb-4">
                            <select class="select select-bordered flex-1" name="mill_polished_material_ids[]">
                                <option value="{{ $material->id }}" selected>
                                    {{ $material->polished_lot_number }}
                                    @if ($material->millPurchaseMaterials->first() && $material->millPurchaseMaterials->first()->material) - {{ $material->millPurchaseMaterials->first()->material->materials_name }}</option>
                                    @endif
                            </select>
                        <input class="input input-bordered flex-1" value="{{ $material->pivot->input_weight }}" name="input_weights[]" type="number" step="0.01" placeholder="投入量(kg)" required>
                        <span>残:{{ $material->remaining_polished_amount }}kg</span>
                        <button type="button" class="removeDetail btn btn-error" data-materialid="{{ $material->id }}"><svg version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="width: 16px; height: 16px; opacity: 1;" xml:space="preserve"><style type="text/css">.st0{fill:#4B4B4B;}</style><g><path class="st0" d="M439.114,69.747c0,0,2.977,2.1-43.339-11.966c-41.52-12.604-80.795-15.309-80.795-15.309l-2.722-19.297C310.387,9.857,299.484,0,286.642,0h-30.651h-30.651c-12.825,0-23.729,9.857-25.616,23.175l-2.722,19.297c0,0-39.258,2.705-80.778,15.309C69.891,71.848,72.868,69.747,72.868,69.747c-10.324,2.849-17.536,12.655-17.536,23.864v16.695h200.66h200.677V93.611C456.669,82.402,449.456,72.596,439.114,69.747z" style="fill: rgb(75, 75, 75);"></path><path class="st0" d="M88.593,464.731C90.957,491.486,113.367,512,140.234,512h231.524c26.857,0,49.276-20.514,51.64-47.269l25.642-327.21H62.952L88.593,464.731z M342.016,209.904c0.51-8.402,7.731-14.807,16.134-14.296c8.402,0.51,14.798,7.731,14.296,16.134l-14.492,239.493c-0.51,8.402-7.731,14.798-16.133,14.288c-8.403-0.51-14.806-7.722-14.296-16.125L342.016,209.904z M240.751,210.823c0-8.42,6.821-15.241,15.24-15.241c8.42,0,15.24,6.821,15.24,15.241v239.492c0,8.42-6.821,15.24-15.24,15.24c-8.42,0-15.24-6.821-15.24-15.24V210.823z M153.833,195.608c8.403-0.51,15.624,5.894,16.134,14.296l14.509,239.492c0.51,8.403-5.894,15.615-14.296,16.125c-8.403,0.51-15.624-5.886-16.134-14.288l-14.509-239.493C139.026,203.339,145.43,196.118,153.833,195.608z" style="fill: rgb(75, 75, 75);"></path></g></svg></button>
                        </div>
                        @endforeach
                    </div>
                    <button id="addDetails" type="button" class="btn btn-primary mb-4">原料を追加</button>
                </div>
            </div>
            <div class="flex mt-6">
                <button class="btn btn-success w-1/2 mr-3" type="submit">登録</button>
            </form>
        <!-- 削除ボタン -->
            <form action="{{ route('millFlourProductions.destroy', $millFlourProduction->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class=" w-1/2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error w-full">製粉生産記録を削除</button>
            </form>
        </div>
    </div>
@include('commons.detailInputs')
@endsection