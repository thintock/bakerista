<script>
// 在庫残数データをJavaScriptオブジェクトとして保持する
const remainingAmounts = {
    @foreach ($millPurchaseMaterials as $millPurchaseMaterial)
        "{{ $millPurchaseMaterial->id }}": "{{ $millPurchaseMaterial->remaining_amount }}",
    @endforeach
};

const materialsContainer = document.getElementById('materialsContainer');
const addMaterialButton = document.getElementById('addMaterial');

// 新規原料選択セクションを追加する関数
function addMaterialInputGroup() {
    const html = `
        <div class="flex items-center gap-2 mb-4">
            <select class="select select-bordered flex-1 material-select" name="selectMaterials[]">
                @foreach ($millPurchaseMaterials as $millPurchaseMaterial)
                    <option value="{{ $millPurchaseMaterial->id }}">{{ $millPurchaseMaterial->lot_number }} - {{ $millPurchaseMaterial->material->materials_name }}</option>
                @endforeach
            </select>
            <input class="input input-bordered flex-1" name="input_weights[]" type="number" step="0.01" placeholder="投入量(kg)" required>
            <span class="remaining-amount"></span> <!-- 在庫残数を表示する -->
            <button type="button" class="removeMaterial btn btn-error">削除</button>
        </div>
    `;
    materialsContainer.insertAdjacentHTML('beforeend', html);
    updateRemainingAmount(); // 在庫残数の表示を更新
}

// 削除される原料のIDを特定し隠しフィールドとして追加する処理
materialsContainer.addEventListener('click', function(e) {
    if (e.target.classList.contains('removeMaterial')) {
        const materialId = e.target.getAttribute('data-materialid');
        if (materialId) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'removeMaterials[]';
            input.value = materialId;
            document.getElementById('editForm').appendChild(input);
        }
        e.target.parentElement.remove();
        updateRemainingAmount(); // 在庫残数の表示を更新
    }
});

// 在庫残数を更新する関数
function updateRemainingAmount() {
    document.querySelectorAll('.material-select').forEach((select) => {
        const remainingAmountSpan = select.parentElement.querySelector('.remaining-amount');
        const materialId = select.value; // 選択された原料のID
        const remainingAmount = remainingAmounts[materialId] || '不明'; // 在庫残数を取得、ない場合は'不明'と表示
        remainingAmountSpan.textContent = `残:${remainingAmount} kg`; // 在庫残数を表示
    });
}

// 原料を追加するボタンが押されたときの処理
addMaterialButton.addEventListener('click', () => {
    addMaterialInputGroup();
});

// 原料選択が変更されたときに在庫残数を更新する
materialsContainer.addEventListener('change', function(e) {
    if (e.target.classList.contains('material-select')) {
        updateRemainingAmount();
    }
});

// 初期状態での原料選択セクションのセットアップを削除する
// addMaterialInputGroup(); // この行を削除する

</script>