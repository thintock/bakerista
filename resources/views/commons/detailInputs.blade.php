{{-- commons.detailInputs.blade.php --}}
<script>
    // 仮の在庫残数データをJavaScriptオブジェクトとして保持する
    // 実際の在庫残数を反映する場合は、サーバーからのデータを使用します。
    const remainingAmounts = {
        // "原料ID": "在庫残数",
        // 各原料の在庫残数を設定...
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
                        <option value="{{ $millPolishedMaterial->id }}">{{ $millPolishedMaterial->polished_lot_number }}</option>
                    @endforeach
                </select>
                <input class="input input-bordered flex-1" name="input_weights[]" type="number" step="0.01" placeholder="投入量(kg)" required>
                <button type="button" class="removeDetail btn btn-error">削除</button>
            </div>
        `;
        detailsContainer.insertAdjacentHTML('beforeend', html);
    }

    // 詳細セクションの削除ボタンが押されたときのイベントリスナー
    detailsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeDetail')) {
            e.target.parentElement.remove();
        }
    });

    // 原料を追加するボタンが押されたときの処理
    addDetailsButton.addEventListener('click', () => {
        addDetailsInputGroup();
    });
</script>
