<x-app-layout>

    <div class="max-w-5xl mx-auto my-4">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="text-right ">
            <a href="{{ route('schedule.create') }}" class="text-white bg-blue-800 hover:bg-blue-700 px-4 py-1 rounded ">
                Create Schedule
            </a>
            </div>
            <!-- Header -->
            <div class="bg-gradient-to-r from-stone-300 to-stone-400 px-8 py-6">
                <h1 class="text-2xl font-bold text-blue-800 text-center">
                    My Schedules
                </h1>
                <p class="mt-2 text-center text-sm">
                    View your schedules
                </p>
            </div>

                    <div class="space-y-6">
                        @foreach($schedules as $schedule)
                            <div class="border rounded-lg p-6 shadow-sm bg-gray-50">
                                <h2 class="text-lg font-bold text-gray-800">
                                    Schedule ID: {{ $schedule->id }}
                                </h2>
                                <p class="text-sm text-gray-600">
                                    Date: {{ \Carbon\Carbon::parse($schedule->date)->format('F j, Y') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Time: {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} 
                                    - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                </p>

                                <div class="mt-4 flex space-x-4">
                                    
                                    <a href="{{ route('schedule.show', $schedule->id) }}" class="text-indigo-600 hover:text-indigo-500">
                                        View
                                    </a>

                                    @if(Auth::user()->role == 'doctor')
                                    <a href="{{ route('schedule.edit',  $schedule->id) }}" class="text-indigo-600 hover:text-indigo-500">
                                        Edit
                                    </a>
                                    @endif
                            
                                    <form method="POST" action="{{ route('schedule.destroy', $schedule->id) }}" onsubmit="return confirm('Are you sure you want to cancel this schedule?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-500">
                                            Cancel
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

            </div>
        </div>
    </div>
</x-app-layout>
