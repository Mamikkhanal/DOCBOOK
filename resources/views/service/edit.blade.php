<x-app-layout>
    <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        @if(session('success'))
            <div class="mb-4 text-green-500 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="text-2xl font-bold mb-6">Edit Service</h2>

        <form action="{{ route('service.update', $service) }}" method="POST">
            @csrf
            @method('PUT') {{-- Specify the HTTP method as PUT for updating --}}

            <!-- Service Name -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Service Name:</label>
                <input type="text" name="name" id="name" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('name', $service->name) }}" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Description:</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>{{ old('description', $service->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label for="category" class="block text-gray-700 font-bold mb-2">Category:</label>
                <select name="category" id="category"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled>Select a category</option>
                    <option value="a" {{ old('category', $service->category) == 'a' ? 'selected' : '' }}>Category A</option>
                    <option value="b" {{ old('category', $service->category) == 'b' ? 'selected' : '' }}>Category B</option>
                </select>
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label for="price" class="block text-gray-700 font-bold mb-2">Price:</label>
                <input type="number" name="price" id="price" step="0.01"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('price', $service->price) }}" required>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Availability -->
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_available" id="is_available"
                    class="mr-2 leading-tight"
                    {{ old('is_available', $service->is_available) ? 'checked' : '' }}>
                <label for="is_available" class="text-gray-700 font-bold">Available</label>
                @error('is_available')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg font-bold hover:bg-blue-600">
                Update Service
            </button>
        </form>
    </div>
</x-app-layout>
