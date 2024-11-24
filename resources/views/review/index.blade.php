<x-app-layout>
    
    <div class="max-w-6xl mx-auto my-4 sm:px-6 lg:px-8 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-blue-800 text-center">
                <h1 class="bg-gradient-to-r from-stone-300 to-stone-400 px-8 py-6 w-full rounded-lg text-3xl font-bold mb-4">Reviews</h1>
                @if ($reviews->isEmpty())
                    <p class="text-lg">No reviews found.</p>
                @else
                <ul class="bg-gray-100 p-4 rounded-lg shadow-lg">
                    @foreach ($reviews as $review)
                        <li class="mb-6 p-4 bg-white rounded-lg shadow-md">
                            <p class="bg-gray-200 p-2 rounded-md text-lg font-semibold text-gray-800 mb-2">
                                Review of Appointment ID: {{ $review->appointment_id }}
                            </p>
                            <p class="text-md text-gray-700 min-h-20">{{ $review->review }}</p>
                            @if(Auth::user()->role == 'patient')
                            <form action="{{ route('review.edit', $review) }}" method="GET" class="inline">
                                <button type="submit" class="text-white hover:bg-blue-800 px-4 py-1 rounded bg-blue-700">Edit</button>
                            </form>
                            <form action="{{ route('review.destroy', $review) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white hover:bg-red-800 px-4 py-1 rounded bg-red-700">Delete</button>
                            </form>
                            @endif
                        </li>
                    @endforeach
                </ul>                
                @endif
            </div>
    </div>
</x-app-layout>