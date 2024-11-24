<x-app-layout>
<div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    @if(session('success'))
        <div class="mb-4 text-green-500 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-6">Add Specialization</h2>

    <form action="{{ route('specialization.update', $specialization) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Specialization Name:</label>
            <input type="text" name="name" id="name" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                value="{{ old('name') }}" required
                placeholder="{{ $specialization->name }}">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg font-bold hover:bg-blue-600">
            Add Specialization
        </button>
    </form>
</div>
</x-app-layout>

