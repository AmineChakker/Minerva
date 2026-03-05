<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — EduPulse</title>
    <script>
        (function(){const h=document.documentElement,k="__TAILWICK_CONFIG__",s=sessionStorage.getItem(k);if(s){const c=JSON.parse(s);h.setAttribute("data-theme",c.theme||"light");}})();
    </script>
    <link rel="stylesheet" href="/assets/app-0ZOPNGSF.css">
</head>
<body>

<div class="bg-default-100 min-h-screen flex justify-center items-center">
    <div class="relative w-full max-w-4xl mx-4">
        <div class="bg-card/70 rounded-lg">
            <div class="grid lg:grid-cols-12 grid-cols-1 items-center gap-0">

                <!-- Left: Form -->
                <div class="lg:col-span-5">
                    <div class="text-center px-10 py-12">
                        <div class="text-center mb-8">
                            <h4 class="mb-3 text-xl font-semibold text-secondary">Welcome Back!</h4>
                            <p class="text-base text-default-500">Sign in to continue to EduPulse</p>
                        </div>

                        @if($errors->any())
                            <div class="flex items-center gap-3 bg-danger/10 border border-danger/20 text-danger px-4 py-3 rounded-lg text-sm mb-6 text-left">
                                <i class="iconify tabler--circle-x text-lg flex-shrink-0"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST" class="text-left w-full">
                            @csrf
                            <div class="mb-4">
                                <label class="block font-medium text-default-900 text-sm mb-2" for="email">Email Address</label>
                                <input class="form-input @error('email') border-danger @enderror"
                                       id="email" name="email" type="email"
                                       placeholder="Enter your email"
                                       value="{{ old('email') }}" required autofocus/>
                            </div>
                            <div class="mb-4">
                                <label class="block font-medium text-default-900 text-sm mb-2" for="password">Password</label>
                                <input class="form-input @error('password') border-danger @enderror"
                                       id="password" name="password" type="password"
                                       placeholder="Enter your password" required/>
                            </div>
                            <div class="flex items-center gap-2">
                                <input class="form-checkbox" id="remember" name="remember" type="checkbox"/>
                                <label class="text-default-900 text-sm font-medium" for="remember">Remember Me</label>
                            </div>
                            <div class="mt-10 text-center">
                                <button class="btn bg-primary text-white w-full" type="submit">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right: Branding -->
                <div class="lg:col-span-7 bg-card/60 mx-2 my-2 shadow-[0_14px_15px_-3px_#f1f5f9,0_4px_6px_-4px_#f1f5f9] dark:shadow-none rounded-lg">
                    <div class="p-10 h-full flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="size-12 rounded-lg bg-primary flex items-center justify-center">
                                <i class="size-6 text-white" data-lucide="graduation-cap"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-default-900">EduPulse</h3>
                                <p class="text-default-500 text-sm">School Management Platform</p>
                            </div>
                        </div>

                        <h4 class="text-2xl font-semibold text-default-800 mb-4">
                            Manage your school with ease
                        </h4>
                        <p class="text-default-500 mb-8">
                            EduPulse provides a complete solution for managing students, teachers, parents, classes, and subjects — all in one place.
                        </p>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center gap-3 p-3 bg-default-50 rounded-lg">
                                <div class="size-8 rounded bg-primary/10 flex items-center justify-center">
                                    <i class="size-4 text-primary" data-lucide="users"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-default-700">Students</p>
                                    <p class="text-xs text-default-500">Manage enrollment</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-default-50 rounded-lg">
                                <div class="size-8 rounded bg-success/10 flex items-center justify-center">
                                    <i class="size-4 text-success" data-lucide="user-check"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-default-700">Teachers</p>
                                    <p class="text-xs text-default-500">Staff management</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-default-50 rounded-lg">
                                <div class="size-8 rounded bg-warning/10 flex items-center justify-center">
                                    <i class="size-4 text-warning" data-lucide="school"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-default-700">Classes</p>
                                    <p class="text-xs text-default-500">Academic structure</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-default-50 rounded-lg">
                                <div class="size-8 rounded bg-info/10 flex items-center justify-center">
                                    <i class="size-4 text-info" data-lucide="book-open"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-default-700">Subjects</p>
                                    <p class="text-xs text-default-500">Curriculum tracking</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="module" src="/assets/app-BxTRRtUp.js"></script>
</body>
</html>
