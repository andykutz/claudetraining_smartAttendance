<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">{{ $location->name }} — QR code</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                @if (session('success'))
                    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded p-3 text-left">{{ session('success') }}</div>
                @endif

                <img src="{{ route('admin.locations.qr', $location) }}" alt="QR code" class="mx-auto mb-4" width="300" height="300">

                <p class="text-sm text-gray-500 break-all mb-6">{{ $location->scanUrl() }}</p>

                <div class="flex justify-center gap-3 mb-6">
                    <a href="{{ route('admin.locations.qr', $location) }}" download="{{ \Illuminate\Support\Str::slug($location->name) }}-qr.svg"
                        class="text-sm bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-4 py-2 rounded-md hover:from-blue-500 hover:to-indigo-600">Download SVG</a>
                    <a href="{{ route('admin.locations.edit', $location) }}"
                        class="text-sm bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">Edit location</a>
                </div>

                <form method="POST" action="{{ route('admin.locations.regenerate-token', $location) }}"
                    onsubmit="return confirm('Regenerating replaces this QR code — the printed one will stop working. Continue?');">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 underline">Regenerate QR code (invalidate old one)</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
