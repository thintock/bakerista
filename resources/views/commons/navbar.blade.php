<header>
    <nav class="navbar flex flex-row-reverse">
        <div class="flex-none">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <ul tabindex="0" class="menu hidden lg:menu-horizontal">
                    @include('commons.link_items')
                </ul>
                <div class="dropdown dropdown-end">
                    <button type="button" tabindex="0" class="btn btn-ghost normal-case font-normal lg:hidden">
                        @if (Auth::check())
                            {{ Auth::user()->name }}
                        @else
                            Guest
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <ul tabindex="0" class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52 text-info">
                        @include('commons.link_items')
                    </ul>
                </div>
            </form>
        </div>
        <div class="flex-none">
            <div>
                <input type="radio" name="theme" class="theme-controller radio radio-primary" aria-label="エメラルド" value="emerald"/>
                <input type="radio" name="theme" class="theme-controller radio radio-primary" aria-label="カップケーキ" value="cupcake"/>
                <input type="radio" name="theme" class="theme-controller radio radio-primary" aria-label="レトロ" value="retro"/>
                <input type="radio" name="theme" class="theme-controller radio radio-primary" aria-label="バレンタイン" value="valentine"/>
                <input type="radio" name="theme" class="theme-controller radio radio-primary" aria-label="ダーク" value="dark"/>
            </div>
        </div>
    </nav>
</header>