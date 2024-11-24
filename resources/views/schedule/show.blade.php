
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Details</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h1 class="text-2xl font-bold mb-4 text-center">Schedule Details</h1>
        
        <div class="mb-4">
            <label for="date" class="block text-gray-700 font-medium">Date:</label>
            <p class="text-lg">{{ \Carbon\Carbon::parse($schedule->date)->format('l, F j, Y') }}</p>
        </div>

        <div class="mb-4">
            <label for="start_time" class="block text-gray-700 font-medium">Start Time:</label>
            <p class="text-lg">{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}</p>
        </div>

        <div class="mb-4">
            <label for="end_time" class="block text-gray-700 font-medium">End Time:</label>
            <p class="text-lg">{{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</p>
        </div>

        <div class="text-center">
            <a href="{{ route('schedule.edit', $schedule) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-500">
                Edit Schedule
            </a>
        </div>
    </div>
</body>
</html>
