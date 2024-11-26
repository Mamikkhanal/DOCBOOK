<x-app-layout>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        @if($errors->any())
            @foreach($errors as $error)
            <p class="text-sm text-red-500 mt-2">{{$error}}</p>
            @endforeach
        @endif
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
            <h1 class="bg-gradient-to-br from-stone-300 to-stone-400 p-4 text-blue-800 rounded-lg text-2xl font-bold mb-4 text-center">Create Schedule</h1>
        <form action="{{ route('schedule.store') }}" method="POST">
        @csrf <!-- Include CSRF token for Laravel -->
            <!-- Date -->
            <div class="mb-4">
                <label for="date" class="block text-gray-700 font-medium">Date:</label>
                <input type="date" name="date" id="date" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-indigo-500 focus:outline-none">
            </div>

            <!-- Start Time -->
            <div class="mb-4">
                <label for="start_time" class="block text-gray-700 font-medium">Start Time:</label>
                <input type="time" name="start_time" id="start_time" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-indigo-500 focus:outline-none">
            </div>

            <!-- End Time -->
            <div class="mb-4">
                <label for="end_time" class="block text-gray-700 font-medium">End Time:</label>
                <input type="time" name="end_time" id="end_time" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-indigo-500 focus:outline-none">
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring focus:ring-indigo-500">
                    Create Schedule
                </button>
            </div
        </form>
    </div>
</div>
</x-app-layout>
