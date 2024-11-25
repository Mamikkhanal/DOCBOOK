<x-app-layout>
    
    <div class="max-w-6xl mx-auto my-4 sm:px-6 lg:px-8 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-blue-800 text-center">
                <h1 class="bg-gradient-to-r from-stone-300 to-stone-400 px-8 py-3 w-full rounded-lg text-2xl font-semibold mb-4">Reviews</h1>
                @if ($reviews->isEmpty())
                    <p class="text-lg">No reviews found.</p>
                @else
                <ul class="bg-gray-100 p-4 rounded-lg shadow-lg">
                    @foreach ($reviews as $review)
                        <li class="mb-6 p-4 bg-white flex flex-row justify-between rounded-lg shadow-md">
                            <p class="bg-gray-200 p-2 rounded-md text-right text-md font-semibold text-black mb-2">
                                Review of Appointment ID: {{ $review->appointment_id }} 
                                <br/>
                                By: {{ $review->appointment->patient->user->name }}
                                <br/>
                                To: {{ $review->appointment->doctor->user->name }}
                                <br/>
                            </p>
                            <p class="text-md mx-16 my-6 text-gray-700 min-h-20">{{ $review->review }}</p>
                            <div class="justify-end">
                            @if(Auth::user()->role == 'patient')
                            <form action="{{ route('review.edit', $review) }}" method="GET" class="inline">
                                <button type="submit" class="text-white mx-4 my-4 hover:bg-blue-800 px-4 py-1 rounded bg-blue-700">Edit</button>
                            </form>
                            @endif
                                <form action="{{ route('review.destroy', $review) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-white my-4 hover:bg-red-800 px-4 py-1 rounded bg-red-700">Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>                
                @endif
            </div>
    </div>
</x-app-layout>