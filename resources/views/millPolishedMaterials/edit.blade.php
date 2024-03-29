@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-4">
        <!-- 戻るボタン -->
        <a href="{{ route('millPolishedMaterials.index') }}" class="btn btn-secondary">
            ← 戻る
        </a>
    
        <h1 class="text-2xl font-bold">精麦済み原料の編集</h1>
    
        <!-- 新規作成ボタン -->
        <a href="{{ route('millPolishedMaterials.create') }}" class="btn btn-primary">
            新規作成
        </a>
    </div>
        <form action="{{ route('millPolishedMaterials.update', $polishedMaterial->id) }}" id="uploadForm" method="POST" id="editForm">
            <div class="lg:flex lg:gap-10 text-left">
                @csrf
                @method('PUT') 
                <!-- 左側のセクション -->
                <div class="lg:w-2/5">
                    <!-- 精麦日付（表示のみ） -->
                    <div class="mb-4">
                        <label for="polished_date" class="block text-sm font-bold mb-2">精麦日付（変更不可）:</label>
                        <input type="text" name="polished_date" class="input input-bordered w-full" value="{{ $polishedMaterial->polished_date->format('Y年m月d日') }}" disabled>
                    </div>
        
                    <!-- 総重量の編集 -->
                    <div class="flex flex-wrap mb-4">
                        
                        <div class="w-1/2 p-1">
                            <label for="total_output_weight" class="block text-sm font-bold mb-2">
                                精麦後重量(kg):
                            </label>
                            <input type="number" name="total_output_weight" class="input input-bordered w-full" value="{{ $polishedMaterial->total_output_weight }}" step="0.01">
                        </div>
                        
                        <div class="w-1/2 p-1">
                            <label for="polished_retention" class="block text-sm font-bold mb-2">
                                精麦歩留(%):
                            </label>
                            <input type="number" name="polished_retention" class="input input-bordered w-full" value="{{ $polishedMaterial->polished_retention }}" disabled>
                        </div>
                        
                    </div>
                    <div class="flex flex-wrap mb-4 border border-secondary text-center">
                    
                        <div class="w-1/3 p-1 bg-secondary flex items-center justify-center">
                            <p class="text-base-200">
                                白度
                            </p>
                        </div>
                        
                        <div class="w-1/3 p-1">
                            <label class="block text-sm font-bold mb-1" for="mill_whiteness_1">
                                1p
                            </label>
                            <input class="input input-bordered w-full" id="mill_whiteness_1" name="mill_whiteness_1" value="{{ $polishedMaterial->mill_whiteness_1 }}" type="number" step="0.1" nullable>
                        </div>
                        
                        <div class="w-1/3 p-1">
                            <label class="block text-sm font-bold mb-1" for="mill_whiteness_2">
                                2p
                            </label>
                            <input class="input input-bordered w-full" id="mill_whiteness_2" name="mill_whiteness_2" value="{{ $polishedMaterial->mill_whiteness_2 }}" type="number" step="0.1" nullable>
                        </div>
                        
                        <div class="w-1/3 p-1">
                            <label class="block text-sm font-bold mb-1" for="mill_whiteness_3">
                                3p
                            </label>
                            <input class="input input-bordered w-full" id="mill_whiteness_3" name="mill_whiteness_3" value="{{ $polishedMaterial->mill_whiteness_3 }}" type="number" step="0.1" nullable>
                        </div>
                        
                        <div class="w-1/3 p-1">
                            <label class="block text-sm font-bold mb-1" for="mill_whiteness_4">
                                4p
                            </label>
                            <input class="input input-bordered w-full" id="mill_whiteness_4" name="mill_whiteness_4" value="{{ $polishedMaterial->mill_whiteness_4 }}" type="number" step="0.1" nullable>
                        </div>
                        
                        <div class="w-1/3 p-1">
                            <label class="block text-sm font-bold mb-1" for="mill_whiteness_5">
                                5p
                            </label>
                            <input class="input input-bordered w-full" id="mill_whiteness_5" name="mill_whiteness_5" value="{{ $polishedMaterial->mill_whiteness_5 }}" type="number" step="0.1" nullable>
                        </div>
                        
                        <div class="w-1/3 p-1">
                            <label class="block text-sm font-bold mb-1" for="mill_whiteness_6">
                                6p
                            </label>
                            <input class="input input-bordered w-full" id="mill_whiteness_6" name="mill_whiteness_6" value="{{ $polishedMaterial->mill_whiteness_6 }}" type="number" step="0.1" nullable>
                        </div>
                        
                        <div class="w-1/3 p-1">
                            <label class="block text-sm font-bold mb-1" for="mill_whiteness_7">
                                7p
                            </label>
                            <input class="input input-bordered w-full" id="mill_whiteness_7" name="mill_whiteness_7" value="{{ $polishedMaterial->mill_whiteness_7 }}" type="number" step="0.1" nullable>
                        </div>
                        
                        <div class="w-1/3 p-1">
                            <label class="block text-sm font-bold mb-1" for="mill_whiteness_8">
                                8p
                            </label>
                            <input class="input input-bordered w-full" id="mill_whiteness_8" name="mill_whiteness_8" value="{{ $polishedMaterial->mill_whiteness_8 }}" type="number" step="0.1" nullable>
                        </div>
                        
                    </div>
                </div>
                
                <!--右側のセクション-->
                <div class="lg:w-3/5">
                    <!-- 使用原料の編集 -->
                    <div class="mb-4" id="materialsContainer">
                        <label for="materials" class="block text-sm font-bold mb-2">使用原料:</label>
                        <!-- 既存の原料を表示・編集 -->
                        @foreach ($polishedMaterial->millPurchaseMaterials as $material)
                        <div class="flex items-center gap-2 mb-4">
                            <select class="select select-bordered flex-1" name="selectMaterials[]">
                                <option value="{{ $material->id }}" selected>{{ $material->lot_number }} - {{ $material->material->materials_name }}</option>
                                <!-- 他の選択可能な原料をここにリストする -->
                            </select>
                            <input class="input input-bordered flex-1" name="input_weights[]" type="number" value="{{ $material->pivot->input_weight }}" step="0.01">
                            <span>残:{{ $material->remaining_amount }} kg</span> <!-- 在庫残数を表示する -->
                            <button type="button" class="removeMaterial btn btn-warning" data-materialid="{{ $material->id }}"><svg version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="width: 16px; height: 16px; opacity: 1;" xml:space="preserve"><style type="text/css">.st0{fill:#4B4B4B;}</style><g><path class="st0" d="M439.114,69.747c0,0,2.977,2.1-43.339-11.966c-41.52-12.604-80.795-15.309-80.795-15.309l-2.722-19.297C310.387,9.857,299.484,0,286.642,0h-30.651h-30.651c-12.825,0-23.729,9.857-25.616,23.175l-2.722,19.297c0,0-39.258,2.705-80.778,15.309C69.891,71.848,72.868,69.747,72.868,69.747c-10.324,2.849-17.536,12.655-17.536,23.864v16.695h200.66h200.677V93.611C456.669,82.402,449.456,72.596,439.114,69.747z" style="fill: rgb(75, 75, 75);"></path><path class="st0" d="M88.593,464.731C90.957,491.486,113.367,512,140.234,512h231.524c26.857,0,49.276-20.514,51.64-47.269l25.642-327.21H62.952L88.593,464.731z M342.016,209.904c0.51-8.402,7.731-14.807,16.134-14.296c8.402,0.51,14.798,7.731,14.296,16.134l-14.492,239.493c-0.51,8.402-7.731,14.798-16.133,14.288c-8.403-0.51-14.806-7.722-14.296-16.125L342.016,209.904z M240.751,210.823c0-8.42,6.821-15.241,15.24-15.241c8.42,0,15.24,6.821,15.24,15.241v239.492c0,8.42-6.821,15.24-15.24,15.24c-8.42,0-15.24-6.821-15.24-15.24V210.823z M153.833,195.608c8.403-0.51,15.624,5.894,16.134,14.296l14.509,239.492c0.51,8.403-5.894,15.615-14.296,16.125c-8.403,0.51-15.624-5.886-16.134-14.288l-14.509-239.493C139.026,203.339,145.43,196.118,153.833,195.608z" style="fill: rgb(75, 75, 75);"></path></g></svg></button>
                        </div>
                        @endforeach
                    </div>
                    <!-- 新規原料追加ボタン -->
                    <button id="addMaterial" type="button" class="btn btn-primary mb-4">原料を追加</button>
                <!-- 更新ボタン -->
                </div>
            </div>
            <div class="flex mt-6">
                <button type="submit" class="btn btn-secondary w-1/2 mr-3">更新</button>
            </form>
        <!-- 削除ボタン -->
            <form action="{{ route('millPolishedMaterials.destroy', $polishedMaterial->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="w-1/2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-warning w-full">精麦記録を削除</button>
            </form>
        </div>
    </div>
@include('commons.materialInputs')
@endsection