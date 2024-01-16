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
                <button type="button" class="removeDetail btn btn-error">削除</button>
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
