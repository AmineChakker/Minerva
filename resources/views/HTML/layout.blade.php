<!DOCTYPE html>
<html lang="en" data-theme="light" data-sidenav-size="default" data-sidenav-color="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EduPulse') — School Management</title>

    <!-- Tailwick theme init: MUST run before CSS to prevent flash -->
    <script>
        (function () {
            const html = document.documentElement;
            const storageKey = "__TAILWICK_CONFIG__";
            const savedConfig = sessionStorage.getItem(storageKey);
            const defaultConfig = { dir: "ltr", theme: "light", sidenav: { color: "light", size: "default" } };
            function getSystemTheme() { return window.matchMedia('(prefers-color-scheme: dark)').matches ? "dark" : "light"; }
            const htmlConfig = {
                dir: html.getAttribute("dir") || defaultConfig.dir,
                theme: html.getAttribute("data-theme") === 'system' ? getSystemTheme() : (html.getAttribute("data-theme") || defaultConfig.theme),
                sidenav: {
                    color: html.getAttribute("data-sidenav-color") || defaultConfig.sidenav.color,
                    size: html.getAttribute("data-sidenav-size") || defaultConfig.sidenav.size,
                },
            };
            window.defaultConfig = structuredClone(htmlConfig);
            let config = savedConfig ? JSON.parse(savedConfig) : htmlConfig;
            window.config = config;
            html.setAttribute("dir", config.dir);
            html.setAttribute("data-theme", config.theme);
            html.setAttribute("data-sidenav-color", config.sidenav.color);
            if (config.sidenav.size) {
                let size = config.sidenav.size;
                if (window.innerWidth <= 1140) { size = "offcanvas"; }
                html.setAttribute("data-sidenav-size", size);
            }
        })();
    </script>

    <link rel="stylesheet" href="/assets/app-0ZOPNGSF.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.22.0/dist/tabler-icons.min.css">
    <style>
        .form-select {
            background-color: var(--color-card) !important;
            color: var(--color-default-800) !important;
            border-color: var(--color-default-200) !important;
            border-radius: 0.25rem !important;
            height: calc(var(--spacing) * 9.25) !important;
            font-size: var(--text-sm) !important;
        }
        .form-select option {
            background-color: var(--color-card);
            color: var(--color-default-800);
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- Sidebar -->
    <aside class="app-menu" id="app-menu">
        <!-- Logo -->
        <a class="logo-box sticky top-0 flex min-h-topbar-height items-center justify-start px-6 backdrop-blur-xs" href="{{ route('dashboard') }}">
            <div class="logo-light">
                <span class="logo-lg text-lg font-bold text-primary">EduPulse</span>
                <span class="logo-sm text-primary font-bold text-base">EP</span>
            </div>
            <div class="logo-dark">
                <span class="logo-lg text-lg font-bold text-primary">EduPulse</span>
                <span class="logo-sm text-primary font-bold text-base">EP</span>
            </div>
        </a>

        <!-- Hover toggle -->
        <div class="absolute top-0 end-5 flex h-topbar items-center justify">
            <button id="button-hover-toggle">
                <i class="ti ti-circle size-5"></i>
            </button>
        </div>

        <!-- Sidebar Menu -->
        <div class="relative min-h-0 flex-grow">
            <div class="size-full" data-simplebar="">
                <ul class="side-nav p-3 hs-accordion-group">

                    <li class="menu-title"><span>Overview</span></li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <span class="menu-icon"><i data-lucide="layout-dashboard"></i></span>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>

                    @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                    <li class="menu-title"><span>Management</span></li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                            <span class="menu-icon"><i data-lucide="graduation-cap"></i></span>
                            <span class="menu-text">Students</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">
                            <span class="menu-icon"><i data-lucide="user-check"></i></span>
                            <span class="menu-text">Teachers</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('parents.*') ? 'active' : '' }}" href="{{ route('parents.index') }}">
                            <span class="menu-icon"><i data-lucide="users"></i></span>
                            <span class="menu-text">Parents</span>
                        </a>
                    </li>

                    <li class="menu-title"><span>Academic</span></li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('classes.*') ? 'active' : '' }}" href="{{ route('classes.index') }}">
                            <span class="menu-icon"><i data-lucide="school"></i></span>
                            <span class="menu-text">Classes</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index') }}">
                            <span class="menu-icon"><i data-lucide="book-open"></i></span>
                            <span class="menu-text">Subjects</span>
                        </a>
                    </li>

                    <li class="menu-title"><span>Attendance &amp; Exams</span></li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                            <span class="menu-icon"><i class="ti ti-clipboard-check"></i></span>
                            <span class="menu-text">Attendance</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('exams.*') ? 'active' : '' }}" href="{{ route('exams.index') }}">
                            <span class="menu-icon"><i class="ti ti-writing"></i></span>
                            <span class="menu-text">Exams</span>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->isAdmin())
                    <li class="menu-title"><span>Administration</span></li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admins.*') ? 'active' : '' }}" href="{{ route('admins.index') }}">
                            <span class="menu-icon"><i class="ti ti-shield-check"></i></span>
                            <span class="menu-text">Admins</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('academic-years.*') ? 'active' : '' }}" href="{{ route('academic-years.index') }}">
                            <span class="menu-icon"><i class="ti ti-calendar-stats"></i></span>
                            <span class="menu-text">Academic Years</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}" href="{{ route('announcements.index') }}">
                            <span class="menu-icon"><i class="ti ti-speakerphone"></i></span>
                            <span class="menu-text">Announcements</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('fees.*') ? 'active' : '' }}" href="{{ route('fees.index') }}">
                            <span class="menu-icon"><i class="ti ti-credit-card"></i></span>
                            <span class="menu-text">Fee Management</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <span class="menu-icon"><i class="ti ti-users-group"></i></span>
                            <span class="menu-text">All Users</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <span class="menu-icon"><i class="ti ti-chart-bar"></i></span>
                            <span class="menu-text">Reports</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('school.settings') ? 'active' : '' }}" href="{{ route('school.settings') }}">
                            <span class="menu-icon"><i class="ti ti-settings"></i></span>
                            <span class="menu-text">School Settings</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
    </aside>
    <!-- End Sidebar -->

    <!-- Page Content -->
    <div class="page-content">

        <!-- Topbar -->
        <div class="app-header min-h-topbar-height flex items-center sticky top-0 z-30 bg-(--topbar-background) border-b border-default-200">
            <div class="w-full flex items-center justify-between px-6">
                <div class="flex items-center gap-5">
                    <button class="btn btn-icon size-9 bg-default-400/10 hover:bg-default-150 rounded" id="button-toggle-menu">
                        <i class="ti ti-align-left text-xl"></i>
                    </button>
                    <div class="lg:flex hidden items-center relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="ti ti-search text-base"></i>
                        </div>
                        <input class="form-input px-12 text-sm rounded border-transparent focus:border-transparent w-60" placeholder="Search something..." type="search"/>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle -->
                    <div class="topbar-item">
                        <button class="btn btn-icon size-8 hover:bg-default-150 transition-[scale] rounded-full" id="light-dark-mode" type="button">
                            <i class="ti ti-moon text-xl absolute dark:scale-0 dark:-rotate-90 scale-100 rotate-0 transition-all duration-200"></i>
                            <i class="ti ti-sun text-xl absolute dark:scale-100 dark:rotate-0 scale-0 rotate-90 transition-all duration-200"></i>
                        </button>
                    </div>

                    <!-- Settings -->
                    <div class="topbar-item">
                        <button aria-controls="theme-customization" aria-expanded="false" aria-haspopup="dialog" class="btn btn-icon size-8 hover:bg-default-150 rounded-full" data-hs-overlay="#theme-customization" type="button">
                            <i class="size-4.5" data-lucide="settings"></i>
                        </button>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="topbar-item hs-dropdown relative inline-flex">
                        <button aria-expanded="false" aria-haspopup="menu" aria-label="Dropdown" class="cursor-pointer bg-primary/10 rounded-full hs-dropdown-toggle">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ Storage::url(auth()->user()->profile_photo) }}" class="size-9.5 rounded-full object-cover ring-2 ring-primary/20" alt="{{ auth()->user()->full_name }}">
                            @else
                                <div class="size-9.5 rounded-full bg-primary/20 flex items-center justify-center text-sm font-bold text-primary">
                                    {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                                </div>
                            @endif
                        </button>
                        <div aria-orientation="vertical" class="hs-dropdown-menu min-w-48" role="menu">
                            <div class="p-2">
                                <h6 class="mb-2 text-default-500">{{ auth()->user()->school->name ?? 'EduPulse' }}</h6>
                                <div class="flex gap-3">
                                    @if(auth()->user()->profile_photo)
                                        <img src="{{ Storage::url(auth()->user()->profile_photo) }}" class="size-12 rounded object-cover" alt="{{ auth()->user()->full_name }}">
                                    @else
                                        <div class="size-12 rounded bg-primary/20 flex items-center justify-center text-lg font-bold text-primary">
                                            {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-1 text-sm font-semibold text-default-800">{{ auth()->user()->full_name }}</h6>
                                        <p class="text-default-500 capitalize">{{ auth()->user()->role }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-t-default-200 -mx-2 my-2"></div>
                            <div class="flex flex-col gap-y-1">
                                <div class="border-t border-default-200 -mx-2 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded">
                                        <i class="size-4" data-lucide="log-out"></i>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Topbar -->

        <!-- Main Content -->
        <main class="p-6">
            <!-- Page Header -->
            <div class="flex items-center md:justify-between flex-wrap gap-2 mb-6 print:hidden">
                <h4 class="text-default-900 text-lg font-semibold">@yield('page-title', 'Dashboard')</h4>
                <div class="md:flex hidden items-center gap-2 text-sm">
                    <a class="font-medium text-default-500 hover:text-default-700" href="{{ route('dashboard') }}">EduPulse</a>
                    @yield('breadcrumbs')
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="flex items-center gap-3 bg-success/10 border border-success/20 text-success px-4 py-3 rounded-lg text-sm mb-6">
                    <i class="ti ti-circle-check text-lg flex-shrink-0"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 bg-danger/10 border border-danger/20 text-danger px-4 py-3 rounded-lg text-sm mb-6">
                    <i class="ti ti-circle-x text-lg flex-shrink-0"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
        <!-- End Main Content -->

        <!-- Footer -->
        <footer class="mt-auto footer flex items-center py-5 border-t border-default-200">
            <div class="lg:px-8 px-6 w-full flex md:justify-between justify-center gap-4">
                <div>{{ date('Y') }} &copy; EduPulse School Management</div>
                <div class="md:flex hidden gap-2 items-center md:justify-end text-default-500 text-sm">
                    Built with <span class="text-danger">♥</span> for Education
                </div>
            </div>
        </footer>
        <!-- End Footer -->

    </div>
    <!-- End Page Content -->

</div>

<!-- Theme Customizer -->
<div class="hs-overlay hs-overlay-open:translate-x-0 hidden bg-card dark:bg-default-100 hs-overlay-open:flex flex-col translate-x-full rtl:-translate-x-full fixed inset-y-0 end-0 bottom-0 transition-all duration-300 transform max-w-sm w-full z-80 overflow-hidden" id="theme-customization">
    <div class="min-h-16 flex items-center text-default-600 border-b border-dashed border-default-900/10 px-6 gap-3">
        <h5 class="text-base grow">Theme Settings</h5>
        <button class="btn size-9 rounded-full btn-sm hover:bg-default-150" data-hs-overlay="#theme-customization" type="button">
            <i class="ti ti-x text-xl"></i>
        </button>
    </div>
    <div class="h-full flex-grow overflow-y-auto" data-simplebar="">
        <div class="divide-y divide-dashed divide-default-200 dark:divide-white/14">
            <div class="p-6">
                <h5 class="font-semibold text-sm mb-3">Theme Mode</h5>
                <div class="flex gap-2">
                    <div>
                        <input class="hidden" id="layout-color-light" name="data-theme" type="radio" value="light"/>
                        <label class="form-label btn bg-default-150" for="layout-color-light">Light</label>
                    </div>
                    <div>
                        <input class="hidden" id="layout-color-dark" name="data-theme" type="radio" value="dark"/>
                        <label class="form-label btn bg-default-150" for="layout-color-dark">Dark</label>
                    </div>
                    <div>
                        <input class="hidden" id="layout-color-system" name="data-theme" type="radio" value="system"/>
                        <label class="form-label btn bg-default-150" for="layout-color-system">System</label>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <h5 class="font-semibold text-sm mb-3">Sidenav Color</h5>
                <div class="flex gap-2">
                    <div>
                        <input class="hidden" id="menu-color-light" name="data-sidenav-color" type="radio" value="light"/>
                        <label class="form-label btn bg-default-150" for="menu-color-light">Light</label>
                    </div>
                    <div>
                        <input class="hidden" id="menu-color-dark" name="data-sidenav-color" type="radio" value="dark"/>
                        <label class="form-label btn bg-default-150" for="menu-color-dark">Dark</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4 flex border-t border-dashed border-default-900/10">
        <button class="btn bg-default-150 grow" id="reset-layout" type="button">Reset</button>
    </div>
</div>

<script type="module" src="/assets/app-BxTRRtUp.js"></script>
@stack('scripts')
</body>
</html>
