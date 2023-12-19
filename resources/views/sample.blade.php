<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ベーカリスタ</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/daisyui/dist/full.js"></script>
</head>
<body>

<!-- Wrapper -->
<div class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <div class="flex flex-col w-64 bg-[#f5f2e9] shadow-lg">
        <div class="flex items-center justify-center h-20">
            <h1 class="text-3xl uppercase text-blue-500">bakerista</h1>
        </div>
        <ul class="flex flex-col py-4">
            <!-- Navigation Items -->
            <li>
                <a href="#" class="flex items-center text-gray-600 hover:bg-gray-200 p-3">
                    <span>原料管理</span>
                </a>
            </li>
            <li></li>
            <li>
                <a href="#" class="flex items-center text-gray-600 hover:bg-gray-200 p-3">
                    <span>精麦管理</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center text-gray-600 hover:bg-gray-200 p-3">
                    <span>製粉管理</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Top Navbar 
        <header class="flex justify-end items-center h-20 py-4 px-6 bg-white border-b-4 border-gray-200">
            <div class="flex items-center">
                <button class="text-gray-600 mr-4 hover:text-gray-700 focus:outline-none">
                    <span>ログイン</span>
                </button>
                <button class="text-gray-600 hover:text-gray-700 focus:outline-none">
                    <span>登録</span>
                </button>
            </div>
        </header>-->
        
        <header class="flex justify-end items-center h-20 py-4 px-6 bg-white border-b-4 border-gray-200">
    <div class="flex items-center">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                @endif
            @endauth
        @endif
    </div>
</header>


        <!-- Main Section -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-white">
            <div class="container mx-auto px-6 py-8">
                <h3 class="text-gray-700 text-3xl font-medium">Dashboard</h3>
                <!-- More content here -->
            </div>
        </main>
    </div>

</div>

</body>
</html>
