<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                            <div class="p-6 text-blue-900">
                                <h1 class="text-2xl font-semibold mb-6">My Appointments</h1>
                                
                                @if($appointments->isEmpty())
                                    <p class="text-gray-500">You have no appointments.</p>
                                @else
                                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                                        <thead>
                                            <tr class="bg-blue-700 text-left text-sm font-medium text-white">
                                                <th class="py-2 px-4">Doctor</th>
                                                <th class="py-2 px-4">Service</th>
                                                <th class="py-2 px-4">Date</th>
                                                <th class="py-2 px-4">Time</th>
                                                <th class="py-2 px-4">Reason</th>
                                                <th class="py-2 px-4">Actions</th>
                                                <th class="py-2 px-4">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments as $appointment)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $appointment->doctor->user->name }}</td>
                                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $appointment->service->name }}</td>
                                                    <td class="py-3 px-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}</td>
                                                    <td class="py-3 px-4 text-sm text-gray-700">
                                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - 
                                                        {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                                                    </td>
                                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $appointment->description }}</td>
                                                    <td class="py-3 px-4">
                                                        @if (Auth::user()->role == 'doctor')
                                                            <a href="{{ route('appointment.edit', $appointment) }}" class="text-indigo-600 hover:text-indigo-500 text-sm">Edit</a>
                                                        @endif

                                                        <form method="POST" action="{{ route('appointment.destroy', $appointment->id) }}" onsubmit="return confirm('Are you sure you want to cancel this appointment?');" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-500 text-sm">Cancel</button>
                                                        </form>
                                                    </td>
                                                    <td class="py-3 px-4 text-sm">
                                                        <span class="px-2 py-1 text-xs font-semibold inline-block rounded-full 
                                                            {{ $appointment->status == 'completed' ? 'bg-green-100 text-green-600' : 
                                                            ($appointment->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : 
                                                            ($appointment->status == 'cancelled' ? 'bg-red-100 text-red-600' : 
                                                            ($appointment->status == 'booked' ? 'bg-blue-100 text-blue-600' : 
                                                            'bg-gray-100 text-gray-600'))) }}">
                                                            {{ ucfirst($appointment->status) }}
                                                        </span>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
