<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave a Review</title>
    @vite('resources/css/app.css')
</head>
<body
<div class="min-h-screen flex justify-center items-center bg-gray-100">
    <div class=" rounded-lg shadow-xl p-6 w-full max-w-lg">
        <h2 class="text-2xl font-semibold text-center mb-6 bg-gradient-to-br from-stone-300 to-stone-400 p-4 rounded-lg">Leave a Review</h2>

        <form action="{{ route('review.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                <label for="review" class="block text-sm font-medium text-gray-700">Your Review</label>
                <textarea name="review" id="review" rows="5" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('review') border-red-500 @enderror" placeholder="Write your review...">{{ old('review') }}</textarea>
                @error('review')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Submit Review</button>
        </form>
    </div>
</div>
</body>
</html>