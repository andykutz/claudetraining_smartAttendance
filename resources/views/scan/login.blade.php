<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $location->name }} — Sign in</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8 bg-gradient-to-br from-navy-950 via-navy-900 to-blue-950">
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center gap-2.5 mb-4">
                <span class="flex items-center justify-center h-9 w-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-700 shrink-0">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
                <h1 class="text-lg font-semibold text-slate-900">{{ $location->name }}</h1>
            </div>
            <p class="text-sm text-slate-500 mb-6">Enter your employee code and PIN to clock in or out.</p>

            @if ($errors->any())
                <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg p-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('scan.login', $location->qr_token) }}" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="employee_code" value="Employee code" />
                    <x-text-input id="employee_code" name="employee_code" type="text"
                        inputmode="numeric" autofocus autocomplete="off"
                        class="mt-1 block w-full text-lg" required />
                </div>

                <div>
                    <x-input-label for="pin" value="PIN" />
                    <x-text-input id="pin" name="pin" type="password"
                        inputmode="numeric" autocomplete="off"
                        class="mt-1 block w-full text-lg" required />
                </div>

                <button type="submit"
                    class="w-full inline-flex justify-center py-3 px-4 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-lg font-medium shadow-sm hover:from-blue-500 hover:to-indigo-600 hover:shadow-md transition-all duration-200">
                    Continue
                </button>
            </form>
        </div>
    </div>
</body>
</html>
