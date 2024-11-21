<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Appointments</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-6">
                <h1 class="text-2xl font-bold text-white text-center">
                    My Appointments
                </h1>
                <p class="mt-2 text-indigo-100 text-center text-sm">
                    View your scheduled consultations
                </p>
            </div>

            <!-- Appointment List -->
            <div class="px-8 py-6">
                @if($appointments->isEmpty())
                    <p class="text-center text-gray-600">
                        You have no appointments scheduled. 
                    </p>
                @else
                    <div class="space-y-6">
                        @foreach($appointments as $appointment)
                            <div class="border rounded-lg p-6 shadow-sm bg-gray-50">
                                <h2 class="text-lg font-bold text-gray-800">
                                    Appointment with Dr. {{ $appointment->doctor->user->name }}
                                </h2>
                                <p class="text-sm text-gray-600">
                                    Specialization: {{ $appointment->doctor->specialization }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Service: {{ $appointment->service->name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Date: {{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Time: {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Reason: {{ $appointment->description }}
                                </p>
                                
                                <div class="mt-4 flex space-x-4">
                                    
                                    <a href="{{ route('appointment.show', $appointment->id) }}" class="text-indigo-600 hover:text-indigo-500">
                                        View
                                    </a>

                                    @if(Auth::user()->role == 'doctor')
                                    <a href="{{ route('appointment.edit', $appointment) }}" class="text-indigo-600 hover:text-indigo-500">
                                        Edit
                                    </a>
                                    @endif
                            
                                    <form method="POST" action="{{ route('appointment.destroy', $appointment->id) }}" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
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
                @endif
            </div>
        </div>
    </div>
</body>
</html>
