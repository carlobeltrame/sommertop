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
                    <a onclick="fetch('/f5').then(() => window.location.reload())"><svg class="h-5 pr-3 fill-current text-purple-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M0 2C0 .9.9 0 2 0h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm14 12h4V2H2v12h4c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2zM5 9l2-2 2 2 4-4 2 2-6 6-4-4z"/>
                    </svg></a>
                    <h1>
                        <a id="main-title" class="text-gray-900 dark:text-gray-100 no-underline hover:no-underline font-extrabold text-xl"  href="/">
                            Digitale Unterlagen Sommertopkurs
                        </a>
                    </h1>
                </div>
                <div class="block lg:hidden pl-4 pt-2">
                    <button id="nav-toggle" class="flex items-center px-3 py-2 border rounded text-gray-500 border-gray-600 hover:text-gray-900 hover:border-purple-500 appearance-none">
                        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <title>Menü</title>
                            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
                        </svg>
                    </button>
                    <div class="w-full flex-grow lg:flex  lg:content-center lg:items-center lg:w-auto hidden lg:block mt-2 lg:mt-0 z-20" id="nav-content">
                        <ul class="list-reset lg:flex justify-end items-center">
                            @foreach($menuEntries as $menuEntry)
                                <li class="mr-3 py-2 lg:py-0">
                                    <a class="inline-block py-2 px-4 no-underline {{ $activePage === $menuEntry['path'] ? 'text-gray-900 dark:text-gray-100 font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:underline' }}" href="{{ $menuEntry['path'] }}">{{ $menuEntry['displayName'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!--Container-->
        <div class="container w-full flex flex-wrap mx-auto px-2 pt-8 lg:pt-16 mt-16">
            <nav id="sidebar" title="Sidebar" class="w-full lg:w-1/5 lg:px-6 text-xl text-gray-800 dark:text-gray-200 leading-normal">
                <div class="w-full sticky inset-0 hidden h-64 lg:h-auto overflow-x-hidden overflow-y-auto lg:overflow-y-hidden lg:block mt-0 border border-gray-400 dark:border-gray-600 lg:border-transparent bg-white shadow lg:shadow-none lg:bg-transparent z-20" style="top:5em;" id="menu-content">
                    <ul class="list-reset">
                        @foreach($menuEntries as $menuEntry)
                            <li class="py-2 md:my-0 hover:bg-purple-100 dark:hover:bg-purple-900 lg:hover:bg-transparent">
                                <a href="{{ $menuEntry['path'] }}" class="block pl-4 align-middle text-gray-700 dark:text-gray-300 no-underline hover:text-purple-500 border-l-4 border-transparent lg:hover:border-gray-400 dark:lg:hover:border-gray-600 {{ $activePage === $menuEntry['path'] ? 'lg:border-purple-500 lg:hover:border-purple-500' : '' }}">
                                    <span class="pb-1 md:pb-0 text-sm {{ $activePage === $menuEntry['path'] ? 'text-gray-900 dark:text-gray-100 font-bold' : '' }}">{{ $menuEntry['displayName'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
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
