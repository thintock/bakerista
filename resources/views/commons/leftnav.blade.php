
<div class="flex items-center justify-left h-20 py-4 px-5">
    <h1><a href="{{ route('home') }}"><img src="{{ asset('images/bakerista_log_beige_200px.png') }}" alt="bakerista" width="150px"></a></h1>
</div>
<div style="height:90vh;" class="overflow-y-auto">
   
    <!--製粉管理-->
    <ul class="flex flex-col py-4 mt-5 px-5 text-sm text-base-200">
        <li>
            <h3 class="flex items-center text-xs p-3 bg-base-300">製粉管理</h3>
        </li>
        <li>
            <a href="{{ route('materials.index') }}" class="flex items-center p-3 hover:bg-neutral">
                <span>原材料マスタ</span>
            </a>
        </li>
        <li>
            <a href="{{ route('millMachines.index') }}" class="flex items-center p-3 hover:bg-neutral">
                <span>製粉機マスタ</span>
            </a>
        </li>
        <li>
            <a href="{{ route('millPurchaseMaterials.index') }}" class="flex items-center p-3 hover:bg-neutral">
                <span>原料入荷管理</span>
            </a>
        </li>
        <li>
            <a href="{{ route('millPolishedMaterials.index') }}" class="flex items-center p-3 hover:bg-neutral">
                <span>精麦管理</span>
            </a>
        </li>
        <li>
            <a href={{ route('millFlourProductions.index') }} class="flex items-center p-3 hover:bg-neutral">
                <span>製粉管理</span>
            </a>
        </li>
    </ul>
    <!--顧客管理-->
    <ul class="flex flex-col py-4 mt-5 px-5 text-sm text-base-200">
        <li>
            <h3 class="flex items-center text-xs p-3 bg-base-300">顧客管理</h3>
        </li>
        
        <li>
            <a href="{{ route('customerRelationCategories.index') }}" class="flex items-center p-3 hover:bg-neutral">
                <span>分類マスタ</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customerRelations.index') }}" class="flex items-center p-3 hover:bg-neutral">
                <span>お客様対応管理</span>
            </a>
        </li>
    </ul>
    <!--資材発注管理-->
    <ul class="flex flex-col py-4 mt-5 px-5 text-sm text-base-200">
        <li>
            <h3 class="flex items-center text-xs p-3">管理マスタ</h3>
        </li>
        <!-- Navigation Items -->
        <li>
            <a href="{{ route('supplyItems.index') }}" class="flex items-center p-3 hover:bg-neutral">
                <span>資材備品マスタ</span>
            </a>
        </li>
        <li>
            <a href="{{ route('locations.index') }}" class="flex items-center p-3 hover:bg-neutral">
                <span>ロケーションマスタ</span>
            </a>
        </li>
        <li>
            <a href="{{ route('companies.index') }}" class="flex items-center p-3 hover:bg-neutral">
                <span>取引先マスタ</span>
            </a>
        </li>
    </ul>
</div>