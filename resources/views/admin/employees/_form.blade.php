@csrf

<div class="space-y-4">
    <div>
        <x-input-label for="employee_code" value="Employee code" />
        <x-text-input id="employee_code" name="employee_code" class="mt-1 block w-full" value="{{ old('employee_code', $employee->employee_code ?? '') }}" required />
        <x-input-error :messages="$errors->get('employee_code')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="name" value="Name" />
        <x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $employee->name ?? '') }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Email (optional)" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $employee->email ?? '') }}" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="home_location_id" value="Location" />
        <select id="home_location_id" name="home_location_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            @foreach ($locations as $location)
                <option value="{{ $location->id }}" @selected(old('home_location_id', $employee->home_location_id ?? null) == $location->id)>
                    {{ $location->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('home_location_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="pin" :value="isset($employee) ? 'Reset PIN (leave blank to keep current)' : 'PIN'" />
        <x-text-input id="pin" name="pin" type="text" inputmode="numeric" class="mt-1 block w-full" autocomplete="off" :required="! isset($employee)" />
        <x-input-error :messages="$errors->get('pin')" class="mt-2" />
    </div>

    @isset($employee)
        <div class="flex items-center">
            <input id="active" name="active" type="checkbox" value="1" {{ old('active', $employee->active) ? 'checked' : '' }}
                class="rounded border-gray-300 text-blue-600 shadow-sm">
            <label for="active" class="ms-2 text-sm text-gray-600">Active</label>
        </div>
    @endisset

    <div class="flex gap-3">
        <x-primary-button>Save</x-primary-button>
        <a href="{{ route('admin.employees.index') }}" class="text-sm text-gray-500 self-center">Cancel</a>
    </div>
</div>
