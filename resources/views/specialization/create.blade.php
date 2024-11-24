<div class="max-w-lg mx-auto mt-10 p-6 rounded-lg ">
    @if(session('success'))
        <div class="mb-4 text-green-500 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="text-2xl text-blue-800 text-center w-full bg-gradient-to-br from-stone-300 to-stone-400 p-4 rounded-lg font-bold mb-6">Specialization</h2>

    <form action="{{ route('specialization.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Specialization Name:</label>
            <input type="text" name="name" id="name" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                value="{{ old('name') }}" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-blue-800 text-white py-2 px-4 rounded-lg font-bold hover:bg-blue-600">
            Add Specialization
        </button>
    </form>
</div>


