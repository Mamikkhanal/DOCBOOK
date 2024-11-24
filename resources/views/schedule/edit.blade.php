<!-- resources/views/schedule/edit.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule</title>
    @vite('resources/css/app.css') <!-- Include your CSS if needed -->
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h1 class=" bg-gradient-to-br from-stone-300 to-stone-400 p-4 text-blue-800 rounded-lg text-2xl font-bold mb-4 text-center">Edit Schedule</h1>

        <!-- Begin Form for Editing Schedule -->
        <form action="{{ route('schedule.update', $schedule->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Use PUT method for updating resources -->
            
            <div class="mb-4">
                <label for="date" class="block text-gray-700 font-medium">Date:</label>
                <input type="date" id="date" name="date" value="{{ \Carbon\Carbon::parse($schedule->date)->format('Y-m-d') }}" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="start_time" class="block text-gray-700 font-medium">Start Time:</label>
                <input type="time" id="start_time" name="start_time" value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="end_time" class="block text-gray-700 font-medium">End Time:</label>
                <input type="time" id="end_time" name="end_time" value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="text-center">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring focus:ring-blue-500">
                    Save Changes
                </button>
            </div>
        </form>
        <!-- End Form -->

    </div>
</body>
</html>
