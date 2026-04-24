<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rental PS</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at top left, #1e3a8a, #020617);
            min-height: 100vh;
        }

        /* CARD */
        .login-card {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        /* INPUT */
        .input-modern {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.2s ease;
        }

        .input-modern:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59,130,246,0.3);
            background: rgba(255,255,255,0.05);
        }

        /* BUTTON */
        .btn-login {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            transition: all 0.25s ease;
            box-shadow: 0 10px 25px rgba(59,130,246,0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(59,130,246,0.5);
        }

        /* ICON BUTTON */
        .icon-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .icon-btn:hover {
            color: #fff;
        }
    </style>
</head>

<body class="flex items-center justify-center px-4">

<div class="login-card w-full max-w-md p-8 text-white">

    <!-- HEADER -->
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo-rentalps.png') }}"
             class="mx-auto h-14 mb-4">

        <h1 class="text-2xl font-semibold">Rental PS</h1>
        <p class="text-sm text-gray-400">Masuk untuk melanjutkan</p>
    </div>

    <!-- FORM -->
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <!-- USERNAME -->
        <div class="mb-5">
            <label class="text-sm text-gray-300">Username</label>
            <input type="text" name="username"
                value="{{ old('username') }}"
                class="input-modern w-full mt-1 px-4 py-3 rounded-lg text-white placeholder-gray-500"
                placeholder="Masukkan username"
                required autofocus>

            @error('username')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- PASSWORD -->
        <div class="mb-5 relative">
            <label class="text-sm text-gray-300">Password</label>

            <input type="password" id="password" name="password"
                class="input-modern w-full mt-1 px-4 py-3 pr-10 rounded-lg text-white placeholder-gray-500"
                placeholder="Masukkan password"
                required>

            <button type="button" id="togglePassword" class="icon-btn">
                <!-- eye -->
                <svg id="eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M15 12a3 3 0 11-6 0"/>
                    <path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7"/>
                </svg>

                <!-- eye closed -->
                <svg id="eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M3 3l18 18"/>
                </svg>
            </button>

            @error('password')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- REMEMBER -->
        <div class="flex items-center mb-6">
            <input type="checkbox" name="remember"
                class="mr-2 accent-blue-500">
            <span class="text-sm text-gray-400">Remember me</span>
        </div>

        <!-- BUTTON -->
        <button type="submit"
            class="btn-login w-full py-3 rounded-lg font-semibold">
            Login
        </button>

    </form>

</div>

<script>
const toggle = document.getElementById('togglePassword');
const password = document.getElementById('password');
const eyeOpen = document.getElementById('eye-open');
const eyeClosed = document.getElementById('eye-closed');

toggle.addEventListener('click', () => {
    const isPassword = password.type === 'password';

    password.type = isPassword ? 'text' : 'password';

    eyeOpen.classList.toggle('hidden');
    eyeClosed.classList.toggle('hidden');
});
</script>

</body>
</html>