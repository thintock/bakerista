{{-- commons.detailInputs.blade.php --}}
<script>
    // 在庫残数データをJavaScriptオブジェクトとして保持する
    const remainingAmounts = {
        @foreach ($millPolishedMaterials as $millPolishedMaterial)
            "{{ $millPolishedMaterial->id }}": "{{ $millPolishedMaterial->remaining_polished_amount }}",
        @endforeach
    };

    const detailsContainer = document.getElementById('DetailsContainer');
    const addDetailsButton = document.getElementById('addDetails');
    
    // 新規製粉生産詳細セクションを追加する関数
    function addDetailsInputGroup() {
        const html = `
            <div class="flex items-center gap-2 mb-4">
                <select class="select select-bordered flex-1 polished-material-select" name="mill_polished_material_ids[]">
                    <!-- ここに動的な精麦済み原料の選択肢が追加される -->
                    @foreach ($millPolishedMaterials as $millPolishedMaterial)
                        <option value="{{ $millPolishedMaterial->id }}">
                        {{ $millPolishedMaterial->polished_lot_number }}
                        @if ($millPolishedMaterial->millPurchaseMaterials->first() && $millPolishedMaterial->millPurchaseMaterials->first()->material) - {{ $millPolishedMaterial->millPurchaseMaterials->first()->material->materials_name }}
                        @endif
                        </option>
                    @endforeach
                </select>
                <input class="input input-bordered flex-1" name="input_weights[]" type="number" step="0.01" placeholder="投入量(kg)" required>
                <span class="remaining-amount"></span>
                <button type="button" class="removeDetail btn btn-warning"><svg version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="width: 16px; height: 16px; opacity: 1;" xml:space="preserve"><style type="text/css">.st0{fill:#4B4B4B;}</style><g><path class="st0" d="M439.114,69.747c0,0,2.977,2.1-43.339-11.966c-41.52-12.604-80.795-15.309-80.795-15.309l-2.722-19.297C310.387,9.857,299.484,0,286.642,0h-30.651h-30.651c-12.825,0-23.729,9.857-25.616,23.175l-2.722,19.297c0,0-39.258,2.705-80.778,15.309C69.891,71.848,72.868,69.747,72.868,69.747c-10.324,2.849-17.536,12.655-17.536,23.864v16.695h200.66h200.677V93.611C456.669,82.402,449.456,72.596,439.114,69.747z" style="fill: rgb(75, 75, 75);"></path><path class="st0" d="M88.593,464.731C90.957,491.486,113.367,512,140.234,512h231.524c26.857,0,49.276-20.514,51.64-47.269l25.642-327.21H62.952L88.593,464.731z M342.016,209.904c0.51-8.402,7.731-14.807,16.134-14.296c8.402,0.51,14.798,7.731,14.296,16.134l-14.492,239.493c-0.51,8.402-7.731,14.798-16.133,14.288c-8.403-0.51-14.806-7.722-14.296-16.125L342.016,209.904z M240.751,210.823c0-8.42,6.821-15.241,15.24-15.241c8.42,0,15.24,6.821,15.24,15.241v239.492c0,8.42-6.821,15.24-15.24,15.24c-8.42,0-15.24-6.821-15.24-15.24V210.823z M153.833,195.608c8.403-0.51,15.624,5.894,16.134,14.296l14.509,239.492c0.51,8.403-5.894,15.615-14.296,16.125c-8.403,0.51-15.624-5.886-16.134-14.288l-14.509-239.493C139.026,203.339,145.43,196.118,153.833,195.608z" style="fill: rgb(75, 75, 75);"></path></g></svg></button>
            </div>
        `;
        detailsContainer.insertAdjacentHTML('beforeend', html);
        updateRemainingAmount(); // 在庫残数の表示を更新
    }

    // 在庫残数を更新する関数
    function updateRemainingAmount() {
        document.querySelectorAll('.polished-material-select').forEach((select) => {
            const remainingAmountSpan = select.parentElement.querySelector('.remaining-amount');
            const inputElement = select.parentElement.querySelector('input[name="input_weights[]"]'); // 投入量のmax値を在庫以内に設定
            const polishedMaterialId = select.value; // 選択された原料のID
            const remainingAmount = remainingAmounts[polishedMaterialId] || '不明'; //　在庫残数を取得、ない場合は不明と表示
            
            remainingAmountSpan.textContent = `残:${remainingAmount} kg`; // 在庫残数を表示
            
            // 在庫残数に基づいてmax値を更新
            if (remainingAmount !== '不明') {
                inputElement.max = remainingAmount;
            } else {
                inputElement.removeAttribute('max'); // 在庫数が不明の場合はmax属性を削除
            }
        });
    }
    // 在庫残数更新のイベントリスナー
    detailsContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('polished-material-select')) {
        updateRemainingAmount();
        }
    });

    // 詳細セクションの削除ボタンが押されたときのイベントリスナー
    detailsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeDetail')) {
            e.target.parentElement.remove();
        }
    });
    
    // 原料を追加するボタンが押された時にも在庫残数を更新
    addDetailsButton.addEventListener('click', () => {
        addDetailsInputGroup();
        updateRemainingAmount();
    });
</script>
