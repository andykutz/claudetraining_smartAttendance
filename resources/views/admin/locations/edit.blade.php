<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Edit {{ $location->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.locations.update', $location) }}">
                    @method('PUT')
                    @include('admin.locations._form')
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-2">Danger zone</h3>
                <form method="POST" action="{{ route('admin.locations.destroy', $location) }}"
                    onsubmit="return confirm('Delete this location? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <x-danger-button>Delete location</x-danger-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
