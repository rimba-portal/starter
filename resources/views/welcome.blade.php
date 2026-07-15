<!doctype html>
<html lang="en" class="scroll-smooth dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />

    <title>Rimba</title>

    <meta name="description" content="Rimba - Resource Integration for Manufacturing & Business Alignment" />
    <meta name="author" content="Rimba" />

    <!-- Icons -->
    <link rel="icon" href="/pics/tapir.png" />

    <!-- Inter web font from bunny.net (GDPR compliant) -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />

    <!-- Tailwind CSS Play CDN (mostly for development/testing purposes) -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <!-- Tailwind CSS v4 Configuration -->
    <style type="text/tailwindcss">
        /* Class based dark mode */
        @custom-variant dark (&:where(.dark, .dark *));

        /* Theme configuration */
        @theme {
            /* Fonts */
            --default-font-family: "Inter";
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Alpine.js (x-cloak - https://alpinejs.dev/directives/cloak) -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <!-- GitHub Markdown CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/github-markdown-css/github-markdown.min.css">

    <!-- Markdown-it -->
    <script src="https://cdn.jsdelivr.net/npm/markdown-it/dist/markdown-it.min.js"></script>
</head>

<body class="antialiased">
    <!-- Page Container -->
    <div x-data="{
        darkMode: true,
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
    
            // Toggle dark class on html element
            if (this.darkMode) {
                document.body.parentNode.classList.add('dark');
            } else {
                document.body.parentNode.classList.remove('dark');
            }
        }
    }" class="min-h-dvh min-w-80 bg-white text-zinc-800 dark:bg-zinc-950 dark:text-zinc-100">
        <!-- Page Content -->
        <div class="w-full min-h-screen bg-white dark:bg-black">
            <div class="flex flex-col gap-4 items-center justify-center pt-6 pb-10 md:px-6 px-4">
                <!--  -->
                <img class="w-full h-[85vh] object-cover object-top rounded" src="/pics/rimba.png" alt="Photo of Rimba">
                <!--  -->
                <div class="w-full flex gap-2 items-center justify-between">
                    <div class="ld:w-1/3 flex flex-col">
                        <h2 class="text-4xl capitalize font-semibold dark:text-white">The Industrial Nervous System for Factory
                        </h2>
                        <p class="mt-2 dark:text-gray-400">
                            Designed for modern manufacturing and enterprise teams, RIMBA brings everything 
                            organization needs into one unified platform — enabling clarity, efficiency, and smarter
                            decisions at every level.
                        </p>
                        {{-- <button class="w-fit px-4 py-2 rounded mt-4 font-semibold bg-green-600 text-white">Learn
                            More</button> --}}
                    </div>
                    <!--  -->
                    <!-- Admin Panel: Sage Green Button -->
                    <a href="{{ route('filament.staff.pages.dashboard') }}"
                        class="lg:inline-flex hidden items-center justify-center w-[16rem] h-14 font-medium text-white bg-[#556B2F] hover:bg-[#415324] rounded-lg transition-colors shadow-sm">
                        Staff Panel
                    </a>

                    <!-- Staff Panel: Warm Terracotta/Clay Button -->
                    <a href="{{ route('filament.admin.pages.dashboard') }}"
                        class="xl:inline-flex hidden items-center justify-center w-[16rem] h-14 font-medium text-white bg-[#C25A3F] hover:bg-[#A3462F] rounded-lg transition-colors shadow-sm">
                        Admin Panel
                    </a>

                    <!-- General App: Deep Ochre/Mud Button -->
                    {{-- <a href="{{ route('about') }}"
                        class="sm:inline-flex hidden items-center justify-center w-[16rem] h-14 font-medium text-white bg-[#8B5A2B] hover:bg-[#6E441F] rounded-lg transition-colors shadow-sm">
                        About Rimba
                    </a> --}}
                </div>
            </div>
        </div>
        <!-- END Page Content -->
    </div>
    <!-- END Page Container -->
</body>

</html>
