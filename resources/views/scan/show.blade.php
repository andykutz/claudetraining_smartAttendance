<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $location->name }} — Attendance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8 bg-gradient-to-br from-navy-950 via-navy-900 to-blue-950">
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center gap-2.5 mb-1">
                <span class="flex items-center justify-center h-9 w-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-700 shrink-0">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
                <h1 class="text-xl font-semibold text-slate-900">{{ $location->name }}</h1>
            </div>
            <p class="text-sm text-gray-500 mb-1 ml-[46px]">{{ $employee->name }} ({{ $employee->employee_code }})</p>

            <p class="text-sm mb-6">
                @if ($latest?->type === 'time_in')
                    <span class="text-green-700">Clocked in since {{ $latest->scanned_at->format('g:i A') }}</span>
                @else
                    <span class="text-gray-500">Not clocked in today.</span>
                @endif
            </p>

            @if (session('success'))
                <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded p-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded p-3">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-2 gap-3">
                <form method="POST" action="{{ route('scan.time-in', $location->qr_token) }}">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 rounded-md text-white text-lg font-semibold {{ $latest?->type === 'time_in' ? 'bg-green-300 cursor-not-allowed' : 'bg-green-600 hover:bg-green-500' }}"
                        @disabled($latest?->type === 'time_in')>
                        Time In
                    </button>
                </form>

                <form method="POST" action="{{ route('scan.time-out', $location->qr_token) }}">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 rounded-md text-white text-lg font-semibold {{ $latest?->type !== 'time_in' ? 'bg-red-300 cursor-not-allowed' : 'bg-red-600 hover:bg-red-500' }}"
                        @disabled($latest?->type !== 'time_in')>
                        Time Out
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('scan.logout', $location->qr_token) }}" class="mt-6 text-center">
                @csrf
                <button type="submit" class="text-sm text-gray-500 underline">Not you? Sign out</button>
            </form>
        </div>
    </div>
</body>
</html>
