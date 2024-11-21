<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-2 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-center">
                   @if(Auth::user()->role == 'patient')
                        <a href={{ route('appointment.create')}} class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                            Take an Appointment
                        </a>
                   @endif
                </div>
            </div>
        </div>
    </div>
    @include('appointment.index')
</x-app-layout>
