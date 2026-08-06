<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 bg-gradient-to-br from-navy-950 via-navy-900 to-blue-950 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.08] [background-image:radial-gradient(circle_at_15%_20%,white,transparent_38%),radial-gradient(circle_at_85%_0%,white,transparent_32%)]"></div>

        <div class="relative flex flex-col items-center text-center">
            <span class="flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-700 shadow-lg mb-5">
                <svg class="h-9 w-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
            <h1 class="text-3xl font-bold text-white tracking-tight mb-2">Smart Attendance</h1>
            <p class="text-blue-200 mb-8 max-w-sm">Scan a location's QR code to record your time in / time out.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-white text-navy-900 font-semibold shadow-sm hover:shadow-md hover:bg-blue-50 transition-all duration-200">Go to dashboard</a>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-white/10 border border-white/20 text-white font-semibold hover:bg-white/20 transition-all duration-200">Admin / manager login</a>
            @endauth
        </div>
    </div>
</body>
</html>
