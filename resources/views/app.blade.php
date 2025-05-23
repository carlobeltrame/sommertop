<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>Digitale Unterlagen Sommertopkurs</title>

        <style>
            @import url('https://fonts.googleapis.com/css?family=Lato');
            html { font-family: 'Lato', sans-serif; }
        </style>

        @routes
        @vite(['resources/js/app.ts'])
        @yield('head')
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased tracking-wider">

        <nav id="header" aria-labelledby="main-title" class="fixed w-full z-10 top-0 bg-white dark:bg-gray-800 border-b border-gray-400 dark:border-gray-200">
            <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 py-4">
                <div class="pl-4 flex items-center">
                    <svg class="h-5 pr-3 fill-current text-purple-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M0 2C0 .9.9 0 2 0h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm14 12h4V2H2v12h4c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2zM5 9l2-2 2 2 4-4 2 2-6 6-4-4z"/>
                    </svg>
                    <h1>
                        <a id="main-title" class="text-gray-900 dark:text-gray-100 no-underline hover:no-underline font-extrabold text-xl"  href="/">
                            Digitale Unterlagen Sommertopkurs
                        </a>
                    </h1>
                </div>
                $topMenu
            </div>
        </nav>
        <!--Container-->
        <div class="container w-full flex flex-wrap mx-auto px-2 pt-8 lg:pt-16 mt-16">
            <nav id="sidebar" title="Sidebar" class="w-full lg:w-1/5 lg:px-6 text-xl text-gray-800 dark:text-gray-200 leading-normal">
                <li class="py-2 md:my-0 hover:bg-purple-100 dark:hover:bg-purple-900 lg:hover:bg-transparent">
                    <a href="/" class="block pl-4 align-middle text-gray-700 dark:text-gray-300 no-underline hover:text-purple-500 border-l-4 border-transparent lg:hover:border-gray-400 dark:lg:hover:border-gray-600 {{ $activePage === '/' ? 'lg:border-purple-500 lg:hover:border-purple-500' : '' }}">
                        <span class="pb-1 md:pb-0 text-sm {{ $activePage === '/' ? ' text-gray-900 dark:text-gray-100 font-bold' : '' }}">Home</span>
                    </a>
                </li>
                @foreach($menuEntries as $menuEntry)
                    <li class="py-2 md:my-0 hover:bg-purple-100 dark:hover:bg-purple-900 lg:hover:bg-transparent">
                        <a href="{{ $menuEntry['path'] }}" class="block pl-4 align-middle text-gray-700 dark:text-gray-300 no-underline hover:text-purple-500 border-l-4 border-transparent lg:hover:border-gray-400 dark:lg:hover:border-gray-600 {{ $activePage === $menuEntry['path'] ? 'lg:border-purple-500 lg:hover:border-purple-500' : '' }}">
                            <span class="pb-1 md:pb-0 text-sm {{ $activePage === $menuEntry['path'] ? ' text-gray-900 dark:text-gray-100 font-bold' : '' }}">{{ $menuEntry['name'] }}</span>
                        </a>
                    </li>
                @endforeach
            </nav>
            <div class="w-full lg:w-4/5 p-8 mt-6 lg:mt-0 text-gray-900 dark:text-gray-100 leading-normal bg-white dark:bg-gray-800 border border-gray-400 dark:border-gray-600 border-rounded">
                <main class="prose dark:prose-invert">
                    @yield('content')
                </main>
            </div>
        </div>

        <script>
            var mobileNav = document.getElementById("nav-content");
            var mobileNavToggle = document.getElementById("nav-toggle");

            document.onclick = check;

            function check(e){
                var target = (e && e.target) || (event && event.srcElement);


                //Nav Menu
                if (!checkParent(target, mobileNav)) {
                    // click NOT on the menu
                    if (checkParent(target, mobileNavToggle)) {
                        // click on the link
                        if (mobileNav.classList.contains("hidden")) {
                            mobileNav.classList.remove("hidden");
                        } else {mobileNav.classList.add("hidden");}
                    } else {
                        // click both outside link and outside menu, hide menu
                        mobileNav.classList.add("hidden");
                    }
                }
            }

            function checkParent(t, elm) {
                while(t.parentNode) {
                    if( t == elm ) {return true;}
                    t = t.parentNode;
                }
                return false;
            }


        </script>
    </body>
</html>
