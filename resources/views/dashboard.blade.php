<x-app-layout>
    <div class="py-2 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- @if(Auth::user()->role == 'admin')
                    <div class="p-6 text-gray-900 flex justify-between space-x-[100px]">
                        <a href={{ route('review.index')}} class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                            Reviews
                        </a>
                        <a href={{ route('specialization.index')}} class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                            Specializations
                        </a>
                        <a href={{ route('service.index')}} class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                            Services
                        </a>
                    </div>
                @else
                    <div class="p-6 text-gray-900 flex justify-center space-x-[700px]">
                        @if(Auth::user()->role == 'patient')
                                <a href={{ route('review.index')}} class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                                    Reviews
                                </a>
                                <a href={{ route('appointment.create')}} class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                                    Take an Appointment
                                </a>
                        @elseif(Auth::user()->role == 'doctor')
                                <a href={{ route('review.index')}} class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                                    Reviews
                                </a>
                                <a href={{ route('schedule.index')}} class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                                    Schedules
                                </a>
                        @endif
                    </div>
                @endif --}}
            </div>
        </div>
    </div>
    @include('appointment.index')
</x-app-layout>
