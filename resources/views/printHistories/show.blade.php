@extends('layouts.app')

@section('content')

<div class="container mx-auto p-4 print:shadow-none">
    
    <h1 class="text-2xl font-bold mb-6 no-print">印刷枚数{{ $printHistory->count }}枚</h1>
    <p class="no-print")>印刷するボタンを押したら、印刷画面であらためて印刷数を入力してください。</p>
    <div class="label-container bg-white mx-auto print:border-none print:p-0 print:m-0">
        <table class="table-fixed w-full h-full">
            <tbody class="text-sm">
                <tr>
                    <td class="p-0 text-center align-top" height="200px">
                        <p class="whitespace-nowrap overflow-hidden m-0 font-bold" style="font-size:28px;">{{ $printHistory->productItem->label_name }}</p>
                        <p class="whitespace-nowrap overflow-hidden m-0" style="font-size:10px;">（{{ $printHistory->productItem->label_kana }}）</p>
                        <span class="whitespace-nowrap overflow-hidden m-0">{{ $printHistory->productItem->label_sub_name }}</span>
                        <span>{{ $printHistory->productItem->label_standard }}</span>
                        <p class="pt-1 mt-1 border-t border-black break-words" style="font-size:10px;line-height:1.4;">{{ $printHistory->productItem->label_description }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="p-0" height="1px">
                        <!--内容表示-->
                        <table class="w-full h-full">
                            <tbody class="text-sm">
                                <tr>
                                    <td class="p-0" style="border: 0.5px solid black;" width="60px" height="10px">
                                       <p class="m-0 ml-1 wf-mplus1p" style="font-size:10px;line-height:1.5;">名称</p> 
                                    </td>
                                    <td colspan="3" class="p-0" style="border: 0.5px solid black;">
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">{{ $printHistory->productItem->food_content_name }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0" style="border: 0.5px solid black;"height="40px">
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">原材料名</p>
                                    </td>
                                    <td colspan="3" class="p-0" style="border: 0.5px solid black;">
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">{{ $printHistory->productItem->food_content_ingredients }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0" style="border: 0.5px solid black;" height="8px">
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">内容量</p>
                                    </td>
                                    <td class="p-0" style="border: 0.5px solid black;">
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">{{ $printHistory->productItem->food_content_volume }}</p>
                                    </td>
                                    <td class="p-0" style="border: 0.5px solid black;">
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">賞味期限</p>
                                    </td>
                                    <td class="p-0" style="border: 0.5px solid black;">
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">{{ $printHistory->productItem->shelf_life }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0" style="border: 0.5px solid black;" height="10px">
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">保存方法</p>
                                    </td>
                                    <td class="p-0" style="border: 0.5px solid black;" colspan="3">
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">{{ $printHistory->productItem->storage_method }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0" style="border: 0.5px solid black;" height="10px">
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">製造者</p>
                                    </td>
                                    <td class="p-0" style="border: 0.5px solid black;" colspan="3">
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">{{ $printHistory->productItem->company->name }}</p>
                                        <p class="m-0 ml-1" style="font-size:10px;line-height:1.5;">{{ $printHistory->productItem->company->address }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="1px">
                        <p style="font-size:8px;line-height:1.2;">※{{ $printHistory->productItem->allergen_display }}</p>
                    </td>
                </tr>
                <tr>
                    <td height="1px">
                        <table class="w-full m-0 p-0">
                            <tr>
                                <td width="20px" style="position: relative;">
                                    <div style="position: absolute; top: -28px; left: -7px; transform: rotate(90deg); transform-origin: bottom left;">
                                        <svg id="itemCode"></svg>
                                        <script>
                                          JsBarcode("#itemCode", "{{ $printHistory->productItem->item_code }}", {
                                            format: "CODE39",
                                            lineColor: "#000",
                                            width: 0.6,
                                            height: 8,
                                            displayValue: false
                                          });
                                        </script>
                                      </div>
                                </td>
                                <td width="120px" class="align-top">
                                    <table style="border: 0.5px solid black;">
                                        <tbody>
                                            <tr>
                                                <td style="border: 0.5px solid black;" colspan="2">
                                                    <p style="font-size:10px;">栄養成分表示100gあたり</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="m-0 ml-1" style="font-size:10px;line-height:1.2;">エネルギー</p>
                                                </td>
                                                <td>
                                                    <p class="m-0 ml-1" style="font-size:10px;line-height:1.2;">{{ $printHistory->productItem->nutritional_energy }}kcal</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="m-0 ml-1" style="font-size:10px;line-height:1.2;">たんぱく質</p>
                                                </td>
                                                <td>
                                                    <p class="m-0 ml-1" style="font-size:10px;line-height:1.2;">{{ $printHistory->productItem->nutritional_protein }}g</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="m-0 ml-1" style="font-size:10px;line-height:1.2;">脂質</p>
                                                </td>
                                                <td>
                                                    <p class="m-0 ml-1" style="font-size:10px;line-height:1.2;">{{ $printHistory->productItem->nutritional_fat }}g</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="m-0 ml-1" style="font-size:10px;line-height:1.2;">炭水化物</p>
                                                </td>
                                                <td>
                                                    <p class="m-0 ml-1" style="font-size:10px;line-height:1.2;">{{ $printHistory->productItem->nutritional_carbohydrate }}g</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="m-0 ml-1" style="font-size:10px;line-height:1.2;">食塩相当量</p>
                                                </td>
                                                <td>
                                                    <p class="m-0 ml-1" style="font-size:10px;line-height:1.2;">{{ $printHistory->productItem->nutritional_salt_equivalent }}g</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td colspan="2" style="border: 0.5px solid black;" class="text-center">
                                                    <p class="m-0 ml-1" style="font-size:10px;">灰分：{{ $printHistory->productItem->nutritional_ash }}% 蛋白：{{ $printHistory->productItem->nutritional_protein }}%</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <p class="font-bold border-b border-black">サポート専用窓口</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p style="font-size:8px;line-height:1.2;">ご不明な点がございましたらこちらをご覧ください。</p>
                                                </td>
                                                <td width="60px">
                                                    <img src="{{ asset('images/supportQr.png') }}" alt="recycleMark-paper" width="60px">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                        <img src="{{ asset('images/recycleMark-paper.png') }}" alt="recycleMark-paper" width="25px">
                                        <p class="mt-1" style="font-size:7px;line-height:1;">ラベル</p>
                                    </td>
                                    <td class="text-center">
                                        <img src="{{ asset('images/recycleMark-plastic.png') }}" alt="recycleMark-paper" width="25px">
                                        <p class="mt-1" style="font-size:7px;line-height:1;">外装</p>
                                    </td>
                                    <td width="20px" style="position: relative;">
                                        <div style="position: absolute; top: 5px; left: 0px;">
                                            <svg id="janCode"></svg>
                                            <script>
                                              JsBarcode("#janCode", "{{ $printHistory->productItem->jan_code }}", {
                                                format: "CODE39",
                                                lineColor: "#000",
                                                width: 0.9,
                                                height: 20,
                                                displayValue: false
                                              });
                                            </script>
                                        </div>
                                        
                                        <div style="position: absolute; top: -5px; left: 10px;">
                                            {{ $printHistory->productItem->jan_code }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex mt-6 print:hidden">
        <button onclick="window.print()" class="btn btn-primary">印刷する</button>
        <a href="{{ route('printHistories.create') }}" class="btn btn-secondary ml-2">戻る</a>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* 画面表示と印刷時の両方で適用されるスタイル */
    .label-container {
        box-sizing: border-box;
        width: 76mm; /* 80mm幅 */
        height: 130mm; /* 130mm高さ */
        border: 0.5px solid #000;
        padding: 0mm; /* 内部の余白 */
        margin: auto; /* 中央揃え */
        overflow: hidden; /* オーバーフローした内容は隠す */
    }
    @media print {
        @page {
            size: 76mm 130mm;
            margin: 0;
        }
        body {
            margin: 0;
        }
        .label-container {
            border: none; /* 印刷時には境界線を非表示にする */
            padding: 0; /* 印刷時のパディングをリセット */
        }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
@endpush
