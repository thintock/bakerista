
<div class="flex items-center justify-center h-20">
    <h1><a href="{{ route('home') }}"><img src="{{ asset('images/bakerista_logo_200px.png') }}" alt="bakerista" width="175px"></a></h1>
</div>
<ul class="flex flex-col py-4">
    <!-- Navigation Items -->
    <li>
        <a href="{{ route('materials.index') }}" class="flex items-center p-3 hover:bg-base-300">
            <span>原材料マスター</span>
        </a>
    </li>
    <li>
        <a href="{{ route('millMachines.index') }}" class="flex items-center p-3 hover:bg-base-300">
            <span>製粉機マスター</span>
        </a>
    </li>
    <li>
        <a href="{{ route('millPurchaseMaterials.index') }}" class="flex items-center p-3 hover:bg-base-300">
            <span>原料入荷管理</span>
        </a>
    </li>
    <li>
        <a href="{{ route('millPolishedMaterials.index') }}" class="flex items-center p-3 hover:bg-base-300">
            <span>精麦管理</span>
        </a>
    </li>
    <li>
        <a href={{ route('millFlourProductions.index') }} class="flex items-center p-3 hover:bg-base-300">
            <span>製粉管理</span>
        </a>
    </li>
</ul>