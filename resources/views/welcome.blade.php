<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Rental PS</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="text-center p-8 bg-white rounded-lg shadow-md">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Selamat Datang di Rental PS!</h1>
        <p class="text-lg text-gray-600 mb-8">Penyedia konsol PlayStation terbaik untuk hiburan Anda.</p>
        <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-xl">
            Mulai Sekarang!
        </a>
    </div>
</body>
</html>